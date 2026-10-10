<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Bagikan nama sekolah ke seluruh view secara global
        View::composer('*', function ($view) {
            try {
                $schoolName = \App\Models\SchoolSetting::get('school_name', 'SMA Nusantara');
                $view->with('schoolName', $schoolName);
            } catch (\Throwable $e) {
                $view->with('schoolName', 'SMA Nusantara');
            }
        });

        // Bagikan data notifikasi pengguna aktif ke seluruh layout
        View::composer(['layouts.admin', 'layouts.teacher', 'layouts.student', 'student.dashboard'], function ($view) {
            if (Auth::check()) {
                /** @var \App\Models\User $user */
                $user = Auth::user();
                $unreadNotificationCount = $user->notifications()->where('is_read', false)->count();
                $latestNotifications = $user->notifications()->latest()->limit(8)->get();
                $view->with([
                    'unreadNotificationCount' => $unreadNotificationCount,
                    'latestNotifications'     => $latestNotifications,
                ]);
            }
        });
    }
}
