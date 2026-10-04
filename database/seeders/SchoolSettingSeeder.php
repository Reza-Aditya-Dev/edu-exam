<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SchoolSetting;

class SchoolSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'school_name'           => 'SMA Nusantara',
            'school_npsn'           => '201034824',
            'school_accreditation'  => 'A (Unggul)',
            'school_address'        => 'Jl. Garuda No. 45, Kebayoran Baru, Jakarta Selatan, DKI Jakarta 12120',
            'school_contact'        => 'info@smanusantara.sch.id / +62 21-7201928',
            'exam_default_duration' => '60 Menit',
            'exam_default_kkm'      => '75 Poin',
            'score_policy'          => 'instant',
            'exam_auto_submit'      => '1',
            'exam_safe_browser'     => '1',
            'session_timeout'       => '30',
            'password_min_length'   => '1',
            'password_require_mixed'=> '1',
            'two_factor_auth'       => '1',
            'lab_ip_restriction'    => '1',
            'lab_wifi_ssid'         => 'SMA_NUSANTARA_CBT',
            'notif_exam_reminder'   => '1',
            'notif_auto_report'     => '1',
            'school_logo'           => 'https://lh3.googleusercontent.com/aida/AEtjO1XMS8OoDyx9qMlALXP9TtuM5s4zpW0D5N3ytWGvYxbdvqUjZZ5FXm2GvesC7yV2dU_lvY8FcAuG-5l5zfbCYVYiRhRo55DRaszwwEcl1t-hRyIUSC1qj57X-7KPTuO2jIxLwO0QOx2pwe5uCFxzwEZ64h0x3c47pmb9kutcuJN19Lnn9rvWvQ2-pRKw_gJ_L86A-M39djczoe3xgAMLsdz92TG3iB8KaZq0KAl32qfFeToww6Vho-FjAM8',
            'kurikulum_last_sync'   => 'Hari ini, 02:23 WIB',
        ];

        foreach ($settings as $key => $val) {
            SchoolSetting::updateOrCreate(['key' => $key], ['value' => $val]);
        }
    }
}