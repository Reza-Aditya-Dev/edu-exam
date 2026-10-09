<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profil siswa.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $classroom = $user->currentClassroom();

        // Hitung statistik performa siswa
        $results = $user->examResults;
        $totalExams = $results->count();
        $passedExams = $results->where('pass_status', 'pass')->count();
        $avgScore = $totalExams > 0 ? round($results->avg('total_score'), 1) : 0;
        $highestScore = $totalExams > 0 ? round($results->max('total_score'), 1) : 0;

        return view('student.profile', compact(
            'user', 'classroom', 'totalExams', 'passedExams', 'avgScore', 'highestScore'
        ));
    }

    /**
     * Upload dan perbarui foto profil (avatar) siswa.
     */
    public function updateAvatar(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'avatar.required' => 'Silakan pilih berkas foto yang ingin diunggah.',
            'avatar.image'    => 'Berkas harus berupa gambar valid.',
            'avatar.mimes'    => 'Format gambar yang didukung adalah JPEG, PNG, JPG, atau WEBP.',
            'avatar.max'      => 'Ukuran foto profil maksimal 2 MB.',
        ]);

        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada di penyimpanan publik
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
            $user->save();

            ActivityLog::log('student_avatar_updated', "Siswa {$user->name} memperbarui foto profil.");
        }

        return back()->with('success', 'Foto profil berhasil diperbarui!');
    }

    /**
     * Perbarui kontak atau biodata pribadi siswa.
     */
    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'phone'   => 'nullable|string|max:30',
            'address' => 'nullable|string|max:500',
        ], [
            'phone.max'   => 'Nomor telepon maksimal 30 karakter.',
            'address.max' => 'Alamat maksimal 500 karakter.',
        ]);

        $user->phone = $validated['phone'] ?? null;
        $user->address = $validated['address'] ?? null;
        $user->save();

        ActivityLog::log('student_profile_updated', "Siswa {$user->name} memperbarui informasi kontak profil.");

        return back()->with('success', 'Informasi profil berhasil disimpan.');
    }

    /**
     * Perbarui kata sandi akun siswa.
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

        ActivityLog::log('student_password_updated', "Siswa {$user->name} memperbarui kata sandi akun.");

        return back()->with('success', 'Kata sandi akun Anda berhasil diperbarui.');
    }
}
