<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Nilai - {{ $exam->title }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 12mm 15mm 12mm 15mm;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10.5px;
            color: #1e293b;
            line-height: 1.35;
            margin: 0;
            padding: 0;
        }

        /* ═══ HEADER DOKUMEN ═══ */
        .header-container {
            width: 100%;
            margin-bottom: 12px;
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 8px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table td {
            vertical-align: middle;
            padding: 0;
        }
        .badge {
            display: inline-block;
            padding: 2px 7px;
            font-size: 9px;
            font-weight: bold;
            border-radius: 4px;
            margin-right: 4px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .badge-primary {
            background-color: #e0e7ff;
            color: #3730a3;
        }
        .badge-gray {
            background-color: #f1f5f9;
            color: #475569;
        }
        .title {
            font-size: 16px;
            font-weight: bold;
            color: #0f172a;
            margin: 4px 0 2px 0;
            text-transform: uppercase;
            letter-spacing: 0.2px;
        }
        .meta-info {
            font-size: 10px;
            color: #64748b;
        }

        /* ═══ STATS SUMMARY ═══ */
        .stats-grid {
            width: 100%;
            margin-bottom: 12px;
            border-collapse: separate;
            border-spacing: 8px 0;
        }
        .stats-card {
            width: 25%;
            padding: 6px 10px;
            text-align: center;
            border-radius: 6px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
        }
        .stats-card.pass {
            background-color: #ecfdf5;
            border-color: #a7f3d0;
        }
        .stats-card.fail {
            background-color: #fef2f2;
            border-color: #fecaca;
        }
        .stats-label {
            font-size: 8.5px;
            font-weight: bold;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 1px;
            letter-spacing: 0.3px;
        }
        .stats-card.pass .stats-label {
            color: #047857;
        }
        .stats-card.fail .stats-label {
            color: #b91c1c;
        }
        .stats-value {
            font-size: 15px;
            font-weight: bold;
            color: #0f172a;
        }
        .stats-value.primary { color: #4f46e5; }
        .stats-value.pass { color: #059669; }
        .stats-value.fail { color: #dc2626; }
        .stats-sub {
            font-size: 8px;
            color: #94a3b8;
            margin-top: 1px;
        }
        .stats-card.pass .stats-sub { color: #059669; }
        .stats-card.fail .stats-sub { color: #dc2626; }

        .table-heading {
            font-size: 11px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 6px;
        }

        /* ═══ TABEL REKAP HASIL UJIAN ═══ */
        table.results-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            font-size: 10px;
        }
        table.results-table thead {
            display: table-header-group;
        }
        table.results-table tr {
            page-break-inside: avoid;
        }
        table.results-table th {
            background-color: #f1f5f9;
            color: #334155;
            font-weight: bold;
            text-align: left;
            padding: 6px 8px;
            border: 1px solid #94a3b8;
            font-size: 9.5px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            vertical-align: middle;
        }
        table.results-table td {
            padding: 5px 8px;
            border: 1px solid #cbd5e1;
            vertical-align: middle;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        table.results-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        /* Column Specifics */
        .col-no { width: 4%; text-align: center; }
        .col-nama { width: 28%; }
        .col-nis { width: 12%; text-align: center; }
        .col-waktu { width: 20%; }
        .col-bsk { width: 16%; text-align: center; }
        .col-nilai { width: 9%; text-align: center; }
        .col-status { width: 11%; text-align: center; }

        .text-center { text-align: center !important; }
        .text-right { text-align: right !important; }
        .font-mono { font-family: monospace, Courier, sans-serif; }
        .student-name {
            font-weight: bold;
            color: #0f172a;
            font-size: 10px;
            line-height: 1.25;
        }
        .waktu-main {
            font-weight: 600;
            color: #1e293b;
            font-size: 9.5px;
        }
        .waktu-sub {
            font-size: 8.5px;
            color: #64748b;
            line-height: 1.2;
        }
        .score {
            font-size: 12px;
            font-weight: bold;
        }
        .score-pass { color: #059669; }
        .score-fail { color: #dc2626; }

        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8.5px;
            font-weight: bold;
            text-align: center;
            white-space: nowrap;
        }
        .status-lulus {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        .status-tidak-lulus {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        .status-menunggu {
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        /* ═══ TANDA TANGAN / FOOTER ═══ */
        .footer-section {
            margin-top: 14px;
            width: 100%;
            page-break-inside: avoid;
        }
        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }
        .footer-table td {
            border: none;
            padding: 0;
            vertical-align: top;
        }
        .signature-box {
            text-align: center;
            width: 240px;
            float: right;
        }
        .sig-date {
            font-size: 9.5px;
            color: #475569;
            margin-bottom: 4px;
        }
        .sig-role {
            font-size: 9.5px;
            color: #1e293b;
            margin-bottom: 38px; /* Ruang tanda tangan yang proporsional */
        }
        .sig-name {
            font-weight: bold;
            text-decoration: underline;
            font-size: 10.5px;
            color: #0f172a;
            line-height: 1.2;
        }
        .sig-nip {
            font-size: 9px;
            color: #64748b;
            margin-top: 2px;
        }
    </style>
</head>
<body>

    <!-- Header Rekap Sesuai Halaman Hasil -->
    <div class="header-container">
        <table class="header-table">
            <tr>
                <td>
                    <div>
                        <span class="badge badge-primary">{{ $exam->subject->name ?? '-' }}</span>
                        <span class="badge badge-gray">{{ $exam->classroom->name ?? '-' }}</span>
                    </div>
                    <div class="title">{{ $exam->title }}</div>
                    <div class="meta-info">
                        Pelaksanaan: <strong>{{ $exam->exam_date ? $exam->exam_date->format('d M Y') : '-' }}</strong>
                        &nbsp;•&nbsp;
                        KKM: <strong>{{ $exam->passing_grade }}</strong>
                        &nbsp;•&nbsp;
                        Guru Pengampu: <strong>{{ $exam->teacher->name ?? '-' }}</strong>
                    </div>
                </td>
                <td style="text-align: right; vertical-align: top; width: 180px;">
                    <div style="font-size: 9px; color: #64748b;">EduExam CBT Platform</div>
                    <div style="font-size: 8.5px; color: #94a3b8;">Laporan Resmi Hasil Ujian</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Stats Ringkasan Nilai Sesuai Halaman Hasil -->
    <table class="stats-grid">
        <tr>
            <td class="stats-card">
                <div class="stats-label">Total Peserta</div>
                <div class="stats-value">{{ $stats['total'] }}</div>
                <div class="stats-sub">Siswa Mengumpulkan</div>
            </td>
            <td class="stats-card">
                <div class="stats-label">Rata-rata Nilai</div>
                <div class="stats-value primary">{{ $stats['avg'] }}</div>
                <div class="stats-sub">Skor Rerata Kelas</div>
            </td>
            <td class="stats-card pass">
                <div class="stats-label">Lulus (&gt;= {{ $exam->passing_grade }})</div>
                <div class="stats-value pass">{{ $stats['pass'] }}</div>
                <div class="stats-sub">Siswa Tuntas</div>
            </td>
            <td class="stats-card fail">
                <div class="stats-label">Remedial (&lt; {{ $exam->passing_grade }})</div>
                <div class="stats-value fail">{{ $stats['fail'] }}</div>
                <div class="stats-sub">Belum Tuntas</div>
            </td>
        </tr>
    </table>

    <div class="table-heading">Daftar Rekap Nilai Siswa ({{ count($results) }} Peserta Ujian)</div>

    <!-- Tabel Rekap Nilai: Kolom dan Format Sama Persis dengan Halaman Web -->
    <table class="results-table">
        <thead>
            <tr>
                <th class="col-no">No</th>
                <th class="col-nama">Nama Siswa</th>
                <th class="col-nis">NIS</th>
                <th class="col-waktu">Waktu Pengerjaan</th>
                <th class="col-bsk">Benar / Salah / Kosong</th>
                <th class="col-nilai">Nilai Akhir</th>
                <th class="col-status">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($results as $index => $result)
            <tr>
                <td class="col-no font-mono">{{ $index + 1 }}</td>
                <td class="col-nama">
                    <div class="student-name">{{ $result->student->name ?? '-' }}</div>
                </td>
                <td class="col-nis font-mono">{{ $result->student->nis ?? '-' }}</td>
                <td class="col-waktu">
                    <div class="waktu-main">{{ $result->time_spent_minutes }} Menit</div>
                    <div class="waktu-sub">Dikumpulkan: {{ $result->created_at ? $result->created_at->format('H:i') . ' WIB' : '-' }}</div>
                </td>
                <td class="col-bsk font-mono">
                    <span style="color: #059669; font-weight: bold;">{{ $result->correct_answers }} B</span>
                    <span style="color: #94a3b8;">/</span>
                    <span style="color: #dc2626; font-weight: bold;">{{ $result->wrong_answers }} S</span>
                    <span style="color: #94a3b8;">/</span>
                    <span style="color: #64748b;">{{ $result->unanswered }} K</span>
                </td>
                <td class="col-nilai">
                    <span class="score {{ $result->pass_status === 'pass' ? 'score-pass' : 'score-fail' }}">
                        {{ round($result->total_score) }}
                    </span>
                </td>
                <td class="col-status">
                    @if($result->is_pending_review)
                        <span class="status-badge status-menunggu">Menunggu Koreksi</span>
                    @else
                        <span class="status-badge {{ $result->pass_status === 'pass' ? 'status-lulus' : 'status-tidak-lulus' }}">
                            {{ $result->pass_label }}
                        </span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center" style="padding: 16px; color: #94a3b8;">
                    Belum ada siswa yang mengumpulkan ujian ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Tanda Tangan Guru Pengampu yang Proporsional -->
    <div class="footer-section">
        <table class="footer-table">
            <tr>
                <td style="width: 65%;"></td>
                <td style="width: 35%;">
                    <div class="signature-box">
                        <div class="sig-date">Dicetak pada: {{ now()->translatedFormat('d F Y') }}</div>
                        <div class="sig-role">Guru Pengampu,</div>
                        <div class="sig-name">{{ $exam->teacher->name ?? 'Guru Pengampu' }}</div>
                        <div class="sig-nip">NIP. {{ $exam->teacher->nip ?? '-' }}</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
