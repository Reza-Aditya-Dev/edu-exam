<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Ujian Siswa - {{ $schoolName }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 10mm 12mm 12mm 12mm;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 8pt;
            color: #0f172a;
            line-height: 1.3;
            margin: 0;
            padding: 0;
        }

        /* ═══ KOP SURAT / IDENTITAS SEKOLAH ═══ */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2px solid #1e3a8a;
            padding-bottom: 6px;
            margin-bottom: 10px;
        }
        .kop-table td {
            vertical-align: middle;
            padding: 0;
        }
        .school-name {
            font-size: 14pt;
            font-weight: bold;
            color: #1e3a8a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0 0 2px 0;
        }
        .school-info {
            font-size: 8pt;
            color: #475569;
            line-height: 1.35;
        }
        .report-badge {
            display: inline-block;
            background-color: #eff6ff;
            color: #1d4ed8;
            font-weight: bold;
            font-size: 8pt;
            padding: 4px 8px;
            border-radius: 4px;
            border: 1px solid #bfdbfe;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ═══ TITLE & METADATA BAR ═══ */
        .meta-bar {
            width: 100%;
            margin-bottom: 8px;
            border-collapse: collapse;
        }
        .meta-bar td {
            padding: 0;
            vertical-align: bottom;
        }
        .doc-title {
            font-size: 11pt;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .doc-subtitle {
            font-size: 7.5pt;
            color: #64748b;
            margin-top: 2px;
        }
        .doc-info-right {
            text-align: right;
            font-size: 7.5pt;
            color: #475569;
        }

        /* ═══ TABEL DATA UJIAN ═══ */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7.5pt;
        }
        table.data-table thead {
            display: table-header-group;
        }
        table.data-table tfoot {
            display: table-footer-group;
        }
        table.data-table tr {
            page-break-inside: avoid;
        }
        table.data-table th {
            background-color: #1e3a8a;
            color: #ffffff;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
            padding: 6px 3px;
            border: 0.5pt solid #0f172a;
            font-size: 7pt;
            text-transform: uppercase;
            letter-spacing: 0.2px;
        }
        table.data-table td {
            padding: 5px 3px;
            vertical-align: middle;
            border: 0.5pt solid #cbd5e1;
            color: #1e293b;
            word-wrap: break-word;
        }
        table.data-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        /* Alignments */
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }

        /* Typography accents */
        .font-mono {
            font-family: 'Courier New', Courier, monospace;
            font-weight: bold;
        }
        .font-bold { font-weight: bold; }
        .text-muted { color: #64748b; font-size: 7pt; }

        /* Status Badges */
        .status-badge {
            display: inline-block;
            padding: 1.5px 4px;
            border-radius: 3px;
            font-size: 6.5pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-active {
            background-color: #dcfce7;
            color: #15803d;
            border: 0.5pt solid #86efac;
        }
        .status-scheduled {
            background-color: #dbeafe;
            color: #1d4ed8;
            border: 0.5pt solid #93c5fd;
        }
        .status-completed {
            background-color: #f1f5f9;
            color: #334155;
            border: 0.5pt solid #cbd5e1;
        }
        .status-draft {
            background-color: #fef3c7;
            color: #b45309;
            border: 0.5pt solid #fde68a;
        }
        .status-archived {
            background-color: #f3f4f6;
            color: #6b7280;
            border: 0.5pt solid #d1d5db;
        }

        /* ═══ FOOTER & NOMOR HALAMAN ═══ */
        .footer {
            position: fixed;
            bottom: -8mm;
            left: 0;
            right: 0;
            height: 15px;
            border-top: 0.5pt solid #cbd5e1;
            padding-top: 4px;
            font-size: 7pt;
            color: #64748b;
        }
        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }
        .footer-table td {
            padding: 0;
            vertical-align: middle;
        }
        .page-number:before {
            content: "Halaman " counter(page);
        }
    </style>
</head>
<body>

    <!-- Fixed Footer -->
    <div class="footer">
        <table class="footer-table">
            <tr>
                <td style="width: 50%;">EduExam CBT Engine &bull; {{ $schoolName }}</td>
                <td style="width: 50%; text-align: right;" class="page-number"></td>
            </tr>
        </table>
    </div>

    <!-- Header / Kop Sekolah -->
    <table class="kop-table">
        <tr>
            <td style="width: 75%;">
                <div class="school-name">{{ $schoolName }}</div>
                <div class="school-info">
                    @if(!empty($schoolNpsn))<strong>NPSN:</strong> {{ $schoolNpsn }} &bull; @endif
                    @if(!empty($schoolAddress)){{ $schoolAddress }}@endif
                </div>
            </td>
            <td style="width: 25%; text-align: right;">
                <div class="report-badge">SISTEM CBT EDUEXAM</div>
            </td>
        </tr>
    </table>

    <!-- Bar Informasi Laporan -->
    <table class="meta-bar">
        <tr>
            <td style="width: 65%;">
                <div class="doc-title">Laporan Data Ujian Siswa</div>
                <div class="doc-subtitle">Rekapitulasi jadwal, parameter CBT, target rombel, dan status operasional ujian</div>
            </td>
            <td style="width: 35%;" class="doc-info-right">
                <div><strong>Tanggal Cetak:</strong> {{ $printDate }}</div>
                <div><strong>Total Data:</strong> {{ count($exams) }} Sesi Ujian</div>
            </td>
        </tr>
    </table>

    <!-- Tabel Data Ujian (17 Kolom Resmi) -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 3%;">No</th>
                <th style="width: 6%;">ID Sistem</th>
                <th style="width: 14%;">Judul Ujian</th>
                <th style="width: 6%;">Sesi</th>
                <th style="width: 4%;">Jenis</th>
                <th style="width: 9%;">Mata Pelajaran</th>
                <th style="width: 6%;">Target Kelas</th>
                <th style="width: 10%;">Guru Pengampu</th>
                <th style="width: 8%;">NIP Guru</th>
                <th style="width: 6%;">Tgl Pelaksanaan</th>
                <th style="width: 4%;">Mulai</th>
                <th style="width: 4%;">Selesai</th>
                <th style="width: 4%;">Durasi</th>
                <th style="width: 4%;">Soal</th>
                <th style="width: 3%;">KKM</th>
                <th style="width: 5%;">Token</th>
                <th style="width: 6%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($exams as $index => $exam)
                @php
                    $systemId = 'CBT-' . $exam->id;
                    $examDate = $exam->exam_date ? $exam->exam_date->format('d-m-Y') : '-';
                    $startTime = $exam->start_time ? substr($exam->start_time, 0, 5) : '-';
                    $endTime = $exam->end_time ? substr($exam->end_time, 0, 5) : '-';
                    $token = $exam->token;
                    $nip = $exam->teacher?->nip ?? '-';
                    $status = $exam->status;
                    $statusBadgeClass = match($status) {
                        'active'    => 'status-active',
                        'scheduled' => 'status-scheduled',
                        'completed' => 'status-completed',
                        'draft'     => 'status-draft',
                        'archived'  => 'status-archived',
                        default     => 'status-completed',
                    };
                    $statusLabel = $exam->status_label ?? ucfirst($status);
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center font-mono">{{ $systemId }}</td>
                    <td class="text-left font-bold">{{ $exam->title }}</td>
                    <td class="text-center">{{ $exam->session_name ?? 'Sesi 1' }}</td>
                    <td class="text-center">{{ $exam->exam_type }}</td>
                    <td class="text-left">{{ $exam->subject->name ?? '-' }}</td>
                    <td class="text-center">{{ $exam->classroom->name ?? '-' }}</td>
                    <td class="text-left">{{ $exam->teacher->name ?? '-' }}</td>
                    <td class="text-center font-mono" style="font-size: 6.8pt;">{{ $nip }}</td>
                    <td class="text-center">{{ $examDate }}</td>
                    <td class="text-center">{{ $startTime }}</td>
                    <td class="text-center">{{ $endTime }}</td>
                    <td class="text-center">{{ $exam->duration_minutes }}'</td>
                    <td class="text-center">{{ $exam->total_questions }}</td>
                    <td class="text-center font-bold">{{ $exam->passing_grade }}</td>
                    <td class="text-center font-mono font-bold" style="letter-spacing: 0.5px;">{{ $token }}</td>
                    <td class="text-center">
                        <span class="status-badge {{ $statusBadgeClass }}">{{ $statusLabel }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="17" class="text-center" style="padding: 15px; color: #94a3b8;">
                        Tidak ada data ujian yang sesuai dengan kriteria filter.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
