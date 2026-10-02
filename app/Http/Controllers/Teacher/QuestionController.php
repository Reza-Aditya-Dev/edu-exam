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
        $query = Question::where('created_by', $teacher->id)
            ->with(['subject', 'options']);

        if ($request->subject_id) {
            $query->where('subject_id', $request->subject_id);
        }
        if ($request->type) {
            $query->where('type', $request->type);
        }
        if ($request->difficulty) {
            $query->where('difficulty', $request->difficulty);
        }
        if ($request->search) {
            $query->where('question_text', 'like', '%' . $request->search . '%');
        }

        $questions = $query->latest()->paginate(15);
        $subjects  = Subject::orderBy('name')->get();

        return view('teacher.question.index', compact('questions', 'subjects'));
    }

    public function create()
    {
        $teacher  = Auth::user();
        $subjects = Subject::orderBy('name')->get();
        return view('teacher.question.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject_id'     => 'required|exists:subjects,id',
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

        if ($request->has('save_and_add_another')) {
            return redirect()->route('teacher.questions.create')
                ->withInput($request->only(['subject_id', 'topic', 'difficulty', 'score', 'type']))
                ->with('success', 'Soal berhasil disimpan. Lanjut buat soal berikutnya.');
        }

        return redirect()->route('teacher.questions.index')->with('success', 'Soal berhasil disimpan.');
    }

    public function edit(Question $question)
    {
        $this->authorizeQuestion($question);
        $subjects = Subject::orderBy('name')->get();
        $question->load('options');
        return view('teacher.question.edit', compact('question', 'subjects'));
    }

    public function update(Request $request, Question $question)
    {
        $this->authorizeQuestion($question);
        $request->validate([
            'subject_id'    => 'required|exists:subjects,id',
            'type'          => 'required|in:multiple_choice,true_false,short_answer,essay',
            'question_text' => 'required|string',
            'difficulty'    => 'required|in:easy,medium,hard',
            'score'         => 'required|numeric|min:0|max:100',
        ]);

        DB::transaction(function () use ($request, $question) {
            $imagePath = $question->question_image;
            if ($request->hasFile('question_image')) {
                if ($imagePath) Storage::disk('public')->delete($imagePath);
                $imagePath = $request->file('question_image')->store('questions', 'public');
            }

            $question->update([
                'subject_id'    => $request->subject_id,
                'type'          => $request->type,
                'question_text' => $request->question_text,
                'question_image'=> $imagePath,
                'topic'         => $request->topic,
                'difficulty'    => $request->difficulty,
                'score'         => $request->score,
                'explanation'   => $request->explanation,
            ]);

            if (in_array($request->type, ['multiple_choice', 'true_false']) && $request->options) {
                $question->options()->delete();
                foreach ($request->options as $index => $option) {
                    if (empty($option['text'])) continue;
                    QuestionOption::create([
                        'question_id' => $question->id,
                        'label'       => $option['label'] ?? chr(65 + $index),
                        'option_text' => $option['text'],
                        'is_correct'  => ($request->correct_option == $index),
                        'sort_order'  => $index,
                    ]);
                }
            }

            ActivityLog::log('question_updated', "Soal diperbarui: {$question->question_text}", $question);
        });

        return redirect()->route('teacher.questions.index')->with('success', 'Soal berhasil diperbarui.');
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
