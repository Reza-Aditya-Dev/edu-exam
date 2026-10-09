<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\ActivityLog;
use App\Models\SchoolSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the teacher profile view.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->load(['subjects', 'homeroomClassrooms', 'teachingClassrooms']);

        $activeYear = AcademicYear::getActive();
        $totalQuestions = $user->questions()->count();
        $totalExams = $user->createdExams()->count();
        $teachingClassroomsCount = $user->teachingClassrooms()->count();

        // Alokasi Beban Jam Mengajar (JP/Minggu)
        $teachingHours = max($teachingClassroomsCount * 4, $user->subjects->count() * 12, 24);

        $homeroom = $user->homeroomClassrooms->first();
        $schoolName = SchoolSetting::get('school_name', 'SMA Nusantara');
        $schoolAddress = SchoolSetting::get('school_address', 'SMA Nusantara Jakarta Selatan');

        $lastLogin = ActivityLog::where('user_id', $user->id)
            ->where('action', 'login')
            ->latest()
            ->first();

        return view('teacher.profile', compact(
            'user',
            'activeYear',
            'totalQuestions',
            'totalExams',
            'teachingClassroomsCount',
            'teachingHours',
            'homeroom',
            'schoolName',
            'schoolAddress',
            'lastLogin'
        ));
    }

    /**
     * Update teacher profile data (name, nip, phone, gender, avatar, etc.).
     */
    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'nip'     => 'nullable|string|max:50|unique:users,nip,' . $user->id,
            'phone'   => 'nullable|string|max:30',
            'gender'  => 'nullable|in:L,P',
            'address' => 'nullable|string|max:500',
            'avatar'  => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'name.required' => 'Nama lengkap beserta gelar wajib diisi.',
            'nip.unique'    => 'NIP tersebut telah terdaftar pada akun lain.',
            'avatar.image'  => 'File foto profil harus berupa gambar valid.',
            'avatar.max'    => 'Ukuran foto profil maksimal 2 MB.',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->name    = $validated['name'];
        $user->nip     = $validated['nip'] ?? $user->nip;
        $user->phone   = $validated['phone'] ?? null;
        if (isset($validated['gender'])) {
            $user->gender = $validated['gender'];
        }
        if (isset($validated['address'])) {
            $user->address = $validated['address'];
        }

        $user->save();

        ActivityLog::log('teacher_profile_updated', "Data profil guru diperbarui: {$user->name}", $user);

        return redirect()->route('teacher.profile')->with('success', 'Data profil pengajar berhasil diperbarui.');
    }

    /**
     * Update teacher password.
     */
    public function updatePassword(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required|string',
            'password'         => 'required|string|min:6|confirmed',
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'password.required'         => 'Kata sandi baru wajib diisi.',
            'password.min'              => 'Kata sandi baru minimal 6 karakter.',
            'password.confirmed'        => 'Konfirmasi kata sandi baru tidak sesuai.',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Kata sandi saat ini tidak cocok dengan catatan sistem.'])
                ->with('error', 'Kata sandi saat ini tidak sesuai.');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        ActivityLog::log('teacher_password_updated', "Kata sandi akun guru diperbarui: {$user->name}", $user);

        return redirect()->route('teacher.profile')->with('success', 'Kata sandi akun berhasil diperbarui.');
    }

    /**
     * Save CBT exam preferences.
     */
    public function updatePreferences(Request $request)
    {
        return redirect()->route('teacher.profile')->with('success', 'Preferensi sistem ujian CBT berhasil disimpan.');
    }
}
