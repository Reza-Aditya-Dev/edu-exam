<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita Acara - {{ $exam->title }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            color: #1e293b;
            background: #fff;
            margin: 0;
            padding: 24px;
            font-size: 13px;
            line-height: 1.5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #0f172a;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .school-name {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 20px;
            font-weight: 700;
            text-transform: uppercase;
            margin: 0;
            color: #0f172a;
        }
        .school-info {
            font-size: 12px;
            color: #475569;
            margin-top: 4px;
        }
        .doc-title {
            text-align: center;
            margin-bottom: 20px;
        }
        .doc-title h2 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 16px;
            font-weight: 700;
            text-decoration: underline;
            margin: 0;
            text-transform: uppercase;
        }
        .doc-title p {
            margin: 4px 0 0 0;
            font-size: 11px;
            color: #64748b;
        }
        table.meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        table.meta-table td {
            padding: 4px 8px;
            vertical-align: top;
        }
        table.meta-table td.label {
            width: 28%;
            font-weight: 600;
            color: #334155;
        }
        table.meta-table td.colon {
            width: 2%;
        }
        table.meta-table td.value {
            width: 70%;
        }
        .section-box {
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 16px;
            background: #f8fafc;
        }
        .section-title {
            font-weight: 700;
            margin-bottom: 8px;
            font-size: 13px;
            color: #0f172a;
        }
        .signature-row {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }
        .sign-col {
            width: 30%;
            text-align: center;
        }
        .sign-space {
            height: 70px;
        }
        .sign-name {
            font-weight: 700;
            text-decoration: underline;
        }
        .sign-title {
            font-size: 11px;
            color: #64748b;
        }
        @media print {
            body { padding: 0; }
            .no-print { display: none !important; }
        }
        .print-bar {
            background: #4f46e5;
            color: white;
            padding: 12px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 8px;
            margin-bottom: 24px;
        }
        .btn-print {
            background: #ffffff;
            color: #4f46e5;
            border: none;
            padding: 8px 16px;
            font-weight: 700;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="print-bar no-print">
        <div>
            <strong>Berita Acara Ujian CBT</strong> &bull; Siap Dicetak / Disimpan PDF
        </div>
        <button onclick="window.print()" class="btn-print">Cetak Dokumen (Ctrl+P)</button>
    </div>

    <!-- Header Kop Surat -->
    <div class="header">
        <h1 class="school-name">{{ $schoolName }}</h1>
        <div class="school-info">
            NPSN: {{ $schoolNpsn }} &bull; {{ $schoolAddress }} &bull; Portal CBT EduExam
        </div>
    </div>

    <!-- Judul Dokumen -->
    <div class="doc-title">
        <h2>BERITA ACARA PELAKSANAAN UJIAN CBT</h2>
        <p>Nomor: BA-CBT/{{ date('Y') }}/{{ str_pad($exam->id, 4, '0', STR_PAD_LEFT) }}</p>
    </div>

    <p>Pada hari ini, <strong>{{ $exam->exam_date ? $exam->exam_date->translatedFormat('l, d F Y') : date('d F Y') }}</strong>, telah diselenggarakan kegiatan asesmen berbasis komputer (CBT) dengan rincian pelaksanaan sebagai berikut:</p>

    <table class="meta-table">
        <tr>
            <td class="label">Nama Agenda Ujian</td>
            <td class="colon">:</td>
            <td class="value"><strong>{{ $exam->title }}</strong> ({{ $exam->session_name ?? 'Sesi 1' }})</td>
        </tr>
        <tr>
            <td class="label">Mata Pelajaran</td>
            <td class="colon">:</td>
            <td class="value">{{ $exam->subject->name ?? '-' }} (Kode: {{ $exam->subject->code ?? '-' }})</td>
        </tr>
        <tr>
            <td class="label">Target Rombel / Kelas</td>
            <td class="colon">:</td>
            <td class="value">{{ $exam->classroom->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Guru Pengampu / Pembuat</td>
            <td class="colon">:</td>
            <td class="value">{{ $exam->teacher->name ?? '-' }} (NIP: {{ $exam->teacher->nip ?? '-' }})</td>
        </tr>
        <tr>
            <td class="label">Waktu & Durasi</td>
            <td class="colon">:</td>
            <td class="value">{{ substr($exam->start_time, 0, 5) }} - {{ substr($exam->end_time, 0, 5) }} WIB ({{ $exam->duration_minutes }} Menit)</td>
        </tr>
        <tr>
            <td class="label">Token Akses CBT</td>
            <td class="colon">:</td>
            <td class="value"><strong style="font-family: monospace; letter-spacing: 1px;">{{ $exam->token }}</strong></td>
        </tr>
        <tr>
            <td class="label">Standar Kelulusan (KKM)</td>
            <td class="colon">:</td>
            <td class="value">{{ number_format($exam->passing_grade, 0) }} Poin</td>
        </tr>
    </table>

    <!-- Statistik Kehadiran -->
    @php
        $totalStudents = $exam->classroom?->students()->count() ?: ($exam->classroom?->capacity ?: 36);
        $presentStudents = $exam->participants()->count();
        $absentStudents = max(0, $totalStudents - $presentStudents);
        $percentage = min(100, round(($presentStudents / max(1, $totalStudents)) * 100, 1));
    @endphp
    <div class="section-box">
        <div class="section-title">I. REKAPITULASI KEHADIRAN PESERTA UJIAN</div>
        <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
            <tr>
                <td style="width: 25%; padding: 4px 0;">Total Peserta Terdaftar</td>
                <td style="width: 25%; font-weight: 700;">: {{ $totalStudents }} Orang</td>
                <td style="width: 25%; padding: 4px 0;">Tingkat Kehadiran</td>
                <td style="width: 25%; font-weight: 700; color: #006c49;">: {{ $percentage }}%</td>
            </tr>
            <tr>
                <td style="padding: 4px 0;">Jumlah Peserta Hadir</td>
                <td style="font-weight: 700;">: {{ $presentStudents }} Orang</td>
                <td style="padding: 4px 0;">Jumlah Tidak Hadir</td>
                <td style="font-weight: 700; color: #ba1a1a;">: {{ $absentStudents }} Orang</td>
            </tr>
        </table>
    </div>

    <!-- Catatan Kejadian Khusus -->
    <div class="section-box" style="background: #fff;">
        <div class="section-title">II. CATATAN KHUSUS SELAMA SESI BERLANGSUNG</div>
        <p style="margin: 0; color: #475569; font-style: italic;">
            Ujian berlangsung dengan tertib, lancar, dan aman terkendali di bawah pengawasan sistem perlindungan Safe Exam Browser dan auto-submit integritas CBT EduExam. Tidak ditemukan indikasi kecurangan massal.
        </p>
    </div>

    <p style="margin-top: 16px;">Demikian Berita Acara ini dibuat dengan sebenarnya sesuai dengan kondisi pelaksanaan di laboratorium/ruang asesmen digital sekolah.</p>

    <!-- Tanda Tangan -->
    <div class="signature-row">
        <div class="sign-col">
            <div>Pengawas Ruang,</div>
            <div class="sign-space"></div>
            <div class="sign-name">{{ $exam->teacher->name ?? 'Pengawas Ujian' }}</div>
            <div class="sign-title">NIP. {{ $exam->teacher->nip ?? '........................' }}</div>
        </div>
        <div class="sign-col">
            <div>Proktor CBT,</div>
            <div class="sign-space"></div>
            <div class="sign-name">{{ auth()->user()->name ?? 'Administrator' }}</div>
            <div class="sign-title">NIP. {{ auth()->user()->nip ?? '........................' }}</div>
        </div>
        <div class="sign-col">
            <div>Mengetahui,<br>Kepala Sekolah</div>
            <div class="sign-space"></div>
            <div class="sign-name">Drs. H. Mulyadi, M.Pd.</div>
            <div class="sign-title">NIP. 196805121994031004</div>
        </div>
    </div>
</div>

</body>
</html>
