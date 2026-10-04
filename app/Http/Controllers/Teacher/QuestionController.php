<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Subject;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $teacher = Auth::user();
        $baseQuery = Question::where('created_by', $teacher->id);

        $stats = [
            'total'           => (clone $baseQuery)->count(),
            'multiple_choice' => (clone $baseQuery)->where('type', 'multiple_choice')->count(),
            'short_essay'     => (clone $baseQuery)->whereIn('type', ['short_answer', 'essay'])->count(),
            'active_in_exams' => DB::table('exam_questions')
                                    ->join('questions', 'questions.id', '=', 'exam_questions.question_id')
                                    ->where('questions.created_by', $teacher->id)
                                    ->distinct('exam_questions.exam_id')
                                    ->count('exam_questions.exam_id'),
        ];

        $query = (clone $baseQuery)->with(['subject', 'classroom', 'options', 'exams']);

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }
        if ($request->filled('classroom_id')) {
            if ($request->classroom_id === 'all') {
                $query->whereNull('classroom_id');
            } else {
                $query->where('classroom_id', $request->classroom_id);
            }
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('question_text', 'like', '%' . $search . '%')
                  ->orWhere('topic', 'like', '%' . $search . '%')
                  ->orWhereHas('subject', function ($sq) use ($search) {
                      $sq->where('name', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('classroom', function ($cq) use ($search) {
                      $cq->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        $allQuestions = $query->latest('id')->get();

        // Kelompokkan butir soal menjadi Paket Soal berdasarkan Mapel dan Target Kelas
        $grouped = $allQuestions->groupBy(function ($q) {
            $subId = $q->subject_id ?? 0;
            $classId = $q->classroom_id ?? 'all';
            return "{$subId}_{$classId}";
        });

        $packages = $grouped->map(function ($items) {
            $first = $items->first();
            $subjectName = $first->subject?->name ?? 'Mata Pelajaran';
            $classroomName = $first->classroom ? ('Kelas ' . $first->classroom->name) : 'Semua Kelas (Umum)';
            $topic = $items->pluck('topic')->filter()->first() ?? '';
            $title = !empty($topic) ? $topic : "Bank Soal {$subjectName}";
            $key = 'pkg_' . ($first->subject_id ?? 0) . '_' . ($first->classroom_id ?? 'all');

            $exams = $items->flatMap->exams->unique('id');

            return (object) [
                'key'                   => $key,
                'title'                 => $title,
                'topic'                 => $topic,
                'subject'               => $first->subject,
                'subject_id'            => $first->subject_id,
                'subject_name'          => $subjectName,
                'classroom'             => $first->classroom,
                'classroom_id'          => $first->classroom_id,
                'classroom_name'        => $classroomName,
                'grade'                 => $first->classroom?->grade,
                'questions'             => $items,
                'total_questions'       => $items->count(),
                'multiple_choice_count' => $items->where('type', 'multiple_choice')->count(),
                'true_false_count'      => $items->where('type', 'true_false')->count(),
                'short_answer_count'    => $items->where('type', 'short_answer')->count(),
                'essay_count'           => $items->where('type', 'essay')->count(),
                'total_score'           => $items->sum('score'),
                'difficulty_counts'     => [
                    'easy'   => $items->where('difficulty', 'easy')->count(),
                    'medium' => $items->where('difficulty', 'medium')->count(),
                    'hard'   => $items->where('difficulty', 'hard')->count(),
                ],
                'exams'                 => $exams,
                'exams_count'           => $exams->count(),
                'updated_at'            => $items->max('updated_at'),
            ];
        })->values();

        $subjects   = Subject::orderBy('name')->get();
        $classrooms = \App\Models\Classroom::where('is_active', true)->orderBy('grade')->orderBy('name')->get();
        $openPackageKey = $request->query('package');

        return view('teacher.question.index', compact('packages', 'allQuestions', 'subjects', 'classrooms', 'stats', 'openPackageKey'));
    }

    public function create()
    {
        $teacher    = Auth::user();
        $subjects   = Subject::orderBy('name')->get();
        $classrooms = \App\Models\Classroom::where('is_active', true)->orderBy('grade')->orderBy('name')->get();
        return view('teacher.question.create', compact('subjects', 'classrooms'));
    }

    public function store(Request $request)
    {
        // 1. Dukungan Mode Massal (Bulk Creation 40+ Soal Sekaligus)
        if ($request->has('questions') && is_array($request->questions)) {
            $request->validate([
                'subject_id'   => 'required|exists:subjects,id',
                'classroom_id' => 'nullable|exists:classrooms,id',
                'topic'        => 'nullable|string|max:255',
                'questions'    => 'required|array|min:1',
            ]);

            $savedCount = 0;

            DB::transaction(function () use ($request, &$savedCount) {
                foreach ($request->questions as $index => $qData) {
                    $questionText = trim($qData['question_text'] ?? '');
                    if (empty($questionText)) {
                        continue; // Lewati kartu soal yang kosong
                    }

                    $type        = $qData['type'] ?? 'multiple_choice';
                    $difficulty  = $qData['difficulty'] ?? ($request->default_difficulty ?? 'medium');
                    $score       = isset($qData['score']) && is_numeric($qData['score']) ? floatval($qData['score']) : floatval($request->default_score ?? 2.5);
                    $topic       = !empty($qData['topic']) ? trim($qData['topic']) : ($request->topic ?: null);
                    $explanation = !empty($qData['explanation']) ? trim($qData['explanation']) : null;

                    // Gambar butir soal jika ada upload spesifik per index atau base64
                    $imagePath = null;
                    if ($request->hasFile("questions.{$index}.question_image")) {
                        $imagePath = $request->file("questions.{$index}.question_image")->store('questions', 'public');
                    } elseif (!empty($qData['question_image_base64']) && str_starts_with($qData['question_image_base64'], 'data:image')) {
                        $dataParts = explode(',', $qData['question_image_base64']);
                        if (count($dataParts) === 2) {
                            $decoded = base64_decode($dataParts[1]);
                            $ext = 'png';
                            if (preg_match('/data:image\/([a-zA-Z0-9]+);/', $dataParts[0], $m)) {
                                $ext = strtolower($m[1]) === 'jpeg' ? 'jpg' : strtolower($m[1]);
                            }
                            $fileName = 'questions/img_' . time() . '_' . $index . '_' . \Illuminate\Support\Str::random(6) . '.' . $ext;
                            \Illuminate\Support\Facades\Storage::disk('public')->put($fileName, $decoded);
                            $imagePath = $fileName;
                        }
                    }

                    $question = Question::create([
                        'subject_id'    => $request->subject_id,
                        'classroom_id'  => $request->classroom_id ?: null,
                        'created_by'    => Auth::id(),
                        'type'          => $type,
                        'question_text' => $questionText,
                        'question_image'=> $imagePath,
                        'topic'         => $topic,
                        'difficulty'    => $difficulty,
                        'score'         => $score,
                        'explanation'   => $explanation,
                        'is_active'     => true,
                    ]);

                    // Simpan pilihan jawaban jika tipe multiple_choice atau true_false
                    if (in_array($type, ['multiple_choice', 'true_false']) && !empty($qData['options']) && is_array($qData['options'])) {
                        $correctIndex = $qData['correct_option'] ?? 0;
                        foreach ($qData['options'] as $optIndex => $opt) {
                            $optText = is_array($opt) ? trim($opt['text'] ?? '') : trim($opt ?? '');
                            if ($optText === '') continue;

                            $label = is_array($opt) && !empty($opt['label']) ? $opt['label'] : chr(65 + $optIndex);
                            $isCorrect = (string)$correctIndex === (string)$optIndex || (string)$correctIndex === (string)$label;

                            QuestionOption::create([
                                'question_id' => $question->id,
                                'label'       => $label,
                                'option_text' => $optText,
                                'is_correct'  => $isCorrect,
                                'sort_order'  => $optIndex,
                            ]);
                        }
                    }

                    $savedCount++;
                }

                $subjectName = Subject::find($request->subject_id)?->name ?? 'Mata Pelajaran';
                ActivityLog::log('questions_bulk_created', "{$savedCount} butir soal dibuat secara massal untuk {$subjectName}");
            });

            if ($savedCount === 0) {
                return back()->withInput()->with('error', 'Tidak ada butir soal yang berhasil disimpan. Pastikan Anda mengisi teks soal.');
            }

            return redirect()->route('teacher.questions.index')
                ->with('success', "Alhamdulillah! Berhasil menyimpan {$savedCount} butir soal ke Bank Soal Pembelajaran.");
        }

        // 2. Dukungan Mode Tunggal (Single Question Creation)
        $request->validate([
            'subject_id'     => 'required|exists:subjects,id',
            'classroom_id'   => 'nullable|exists:classrooms,id',
            'type'           => 'required|in:multiple_choice,true_false,short_answer,essay',
            'question_text'  => 'required|string',
            'question_image' => 'nullable|image|max:2048',
            'topic'          => 'nullable|string|max:255',
            'difficulty'     => 'required|in:easy,medium,hard',
            'score'          => 'required|numeric|min:0|max:100',
            'explanation'    => 'nullable|string',
            'options'        => 'required_if:type,multiple_choice,true_false|array',
            'options.*.text' => 'required_if:type,multiple_choice,true_false|string',
            'correct_option' => 'required_if:type,multiple_choice,true_false',
        ]);

        DB::transaction(function () use ($request) {
            $imagePath = null;
            if ($request->hasFile('question_image')) {
                $imagePath = $request->file('question_image')->store('questions', 'public');
            }

            $question = Question::create([
                'subject_id'    => $request->subject_id,
                'classroom_id'  => $request->classroom_id ?: null,
                'created_by'    => Auth::id(),
                'type'          => $request->type,
                'question_text' => $request->question_text,
                'question_image'=> $imagePath,
                'topic'         => $request->topic,
                'difficulty'    => $request->difficulty,
                'score'         => $request->score,
                'explanation'   => $request->explanation,
            ]);

            if (in_array($request->type, ['multiple_choice', 'true_false']) && $request->options) {
                foreach ($request->options as $index => $option) {
                    if (empty($option['text'])) continue;
                    $optImagePath = null;
                    if (isset($option['image']) && $option['image'] instanceof \Illuminate\Http\UploadedFile) {
                        $optImagePath = $option['image']->store('options', 'public');
                    }
                    QuestionOption::create([
                        'question_id' => $question->id,
                        'label'       => $option['label'] ?? chr(65 + $index),
                        'option_text' => $option['text'],
                        'option_image'=> $optImagePath,
                        'is_correct'  => ($request->correct_option == $index),
                        'sort_order'  => $index,
                    ]);
                }
            }

            ActivityLog::log('question_created', "Soal baru dibuat: {$question->question_text}", $question);
        });

        $pkgKey = 'pkg_' . ($request->subject_id ?? 0) . '_' . ($request->classroom_id ?? 'all');
        if ($request->has('save_and_add_another')) {
            return redirect()->route('teacher.questions.create', [
                'subject_id'   => $request->subject_id,
                'classroom_id' => $request->classroom_id,
                'topic'        => $request->topic,
                'difficulty'   => $request->difficulty,
                'score'        => $request->score,
                'type'         => $request->type,
            ])->with('success', 'Soal berhasil disimpan. Lanjut buat soal berikutnya.');
        }

        return redirect()->route('teacher.questions.index', ['package' => $pkgKey])->with('success', 'Soal berhasil disimpan ke Bank Soal.');
    }

    public function edit(Question $question)
    {
        $this->authorizeQuestion($question);
        $subjects   = Subject::orderBy('name')->get();
        $classrooms = \App\Models\Classroom::where('is_active', true)->orderBy('grade')->orderBy('name')->get();
        $question->load(['options', 'classroom']);
        return view('teacher.question.edit', compact('question', 'subjects', 'classrooms'));
    }

    public function update(Request $request, Question $question)
    {
        $this->authorizeQuestion($question);
        $request->validate([
            'subject_id'    => 'required|exists:subjects,id',
            'classroom_id'  => 'nullable|exists:classrooms,id',
            'type'          => 'required|in:multiple_choice,true_false,short_answer,essay',
            'question_text' => 'required|string',
            'topic'         => 'nullable|string|max:255',
            'difficulty'    => 'required|in:easy,medium,hard',
            'score'         => 'required|numeric|min:0|max:100',
        ]);

        DB::transaction(function () use ($request, $question) {
            $imagePath = $question->question_image;
            if ($request->hasFile('question_image')) {
                if ($imagePath) Storage::disk('public')->delete($imagePath);
                $imagePath = $request->file('question_image')->store('questions', 'public');
            } elseif ($request->boolean('remove_image')) {
                if ($imagePath) Storage::disk('public')->delete($imagePath);
                $imagePath = null;
            }

            $question->update([
                'subject_id'    => $request->subject_id,
                'classroom_id'  => $request->classroom_id ?: null,
                'type'          => $request->type,
                'question_text' => $request->question_text,
                'question_image'=> $imagePath,
                'topic'         => $request->filled('topic') ? $request->topic : $question->topic,
                'difficulty'    => $request->difficulty,
                'score'         => $request->score,
                'explanation'   => $request->explanation,
            ]);

            if (in_array($request->type, ['multiple_choice', 'true_false']) && $request->has('options')) {
                $question->options()->delete();
                $correctIndex = $request->input('correct_option');
                foreach ($request->options as $index => $option) {
                    $optText = is_array($option) ? trim($option['text'] ?? '') : trim($option ?? '');
                    if ($optText === '') continue;

                    $label = is_array($option) && !empty($option['label']) ? $option['label'] : chr(65 + $index);
                    $isCorrect = ((string)$correctIndex === (string)$index || (string)$correctIndex === (string)$label);

                    QuestionOption::create([
                        'question_id' => $question->id,
                        'label'       => $label,
                        'option_text' => $optText,
                        'is_correct'  => $isCorrect,
                        'sort_order'  => $index,
                    ]);
                }
            }

            ActivityLog::log('question_updated', "Soal diperbarui: {$question->question_text}", $question);
        });

        $pkgKey = 'pkg_' . ($question->subject_id ?? 0) . '_' . ($question->classroom_id ?? 'all');
        return redirect()->route('teacher.questions.index', ['package' => $pkgKey])->with('success', 'Soal berhasil diperbarui.');
    }

    public function destroy(Question $question)
    {
        $this->authorizeQuestion($question);
        ActivityLog::log('question_deleted', "Soal dihapus: {$question->question_text}");
        $question->delete();
        return back()->with('success', 'Soal berhasil dihapus.');
    }

    public function duplicate(Question $question)
    {
        $this->authorizeQuestion($question);
        DB::transaction(function () use ($question) {
            $newQ = $question->replicate();
            $newQ->question_text = '[Salinan] ' . $question->question_text;
            $newQ->save();
            foreach ($question->options as $option) {
                $newOpt = $option->replicate();
                $newOpt->question_id = $newQ->id;
                $newOpt->save();
            }
        });
        return back()->with('success', 'Soal berhasil diduplikasi.');
    }

    private function authorizeQuestion(Question $question)
    {
        if ($question->created_by !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke soal ini.');
        }
    }
}
