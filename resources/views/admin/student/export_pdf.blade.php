<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Siswa - {{ $schoolName }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 12mm 14mm 12mm;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 8.5pt;
            color: #0f172a;
            line-height: 1.35;
            margin: 0;
            padding: 0;
        }

        /* ═══ KOP SURAT / IDENTITAS RESMI SEKOLAH ═══ */
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
        .logo-img {
            max-height: 48px;
            max-width: 48px;
            object-fit: contain;
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
            font-size: 12pt;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .doc-subtitle {
            font-size: 8pt;
            color: #64748b;
            margin-top: 2px;
        }
        .doc-info-right {
            text-align: right;
            font-size: 8pt;
            color: #475569;
        }

        /* Filter Pills */
        .filter-tags {
            margin-top: 4px;
            font-size: 7.5pt;
            color: #475569;
        }
        .filter-tag {
            display: inline-block;
            background-color: #f1f5f9;
            border: 0.5pt solid #cbd5e1;
            padding: 1px 5px;
            border-radius: 3px;
            margin-right: 4px;
        }

        /* ═══ TABEL DATA SISWA ═══ */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8pt;
            margin-top: 4px;
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
            padding: 6px 4px;
            border: 0.5pt solid #0f172a;
            font-size: 7.5pt;
            text-transform: uppercase;
            letter-spacing: 0.2px;
        }
        table.data-table td {
            padding: 5px 4px;
            vertical-align: middle;
            border: 0.5pt solid #cbd5e1;
            color: #1e293b;
            word-wrap: break-word;
        }
        table.data-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        /* Text Alignments */
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }

        /* Typography */
        .font-mono {
            font-family: 'Courier New', Courier, monospace;
            font-weight: bold;
        }
        .font-bold { font-weight: bold; }
        .text-muted { color: #64748b; font-size: 7pt; }

        /* Badges */
        .status-badge {
            display: inline-block;
            padding: 1.5px 5px;
            border-radius: 3px;
            font-size: 7pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-active {
            background-color: #dcfce7;
            color: #15803d;
            border: 0.5pt solid #86efac;
        }
        .status-remedial {
            background-color: #fef3c7;
            color: #b45309;
            border: 0.5pt solid #fde68a;
        }
        .status-suspended {
            background-color: #fee2e2;
            color: #b91c1c;
            border: 0.5pt solid #fca5a5;
        }

        /* ═══ FOOTER & NOMOR HALAMAN ═══ */
        .footer {
            position: fixed;
            bottom: -9mm;
            left: 0;
            right: 0;
            height: 15px;
            border-top: 0.5pt solid #cbd5e1;
            padding-top: 4px;
            font-size: 7.5pt;
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
                <td style="width: 50%;">EduExam &bull; {{ $schoolName }}</td>
                <td style="width: 50%; text-align: right;" class="page-number"></td>
            </tr>
        </table>
    </div>

    <!-- Header / Kop Surat Sekolah -->
    <table class="kop-table">
        <tr>
            @if(!empty($schoolLogoBase64))
                <td style="width: 55px; padding-right: 10px; text-align: center;">
                    <img src="{{ $schoolLogoBase64 }}" class="logo-img" alt="Logo">
                </td>
            @endif
            <td style="width: auto;">
                <div class="school-name">{{ $schoolName }}</div>
                <div class="school-info">
                    @if(!empty($schoolNpsn))<strong>NPSN:</strong> {{ $schoolNpsn }} &bull; @endif
                    @if(!empty($schoolAddress)){{ $schoolAddress }}@endif
                </div>
            </td>
            <td style="width: 160px; text-align: right;">
                <div class="report-badge">DATABASE SISWA CBT</div>
            </td>
        </tr>
    </table>

    <!-- Bar Judul Laporan & Metadata -->
    <table class="meta-bar">
        <tr>
            <td style="width: 60%;">
                <div class="doc-title">LAPORAN DATA SISWA</div>
                <div class="doc-subtitle">Daftar induk identitas peserta didik, penempatan kelas rombel, dan status akun</div>
                @if(!empty($filterLabels))
                    <div class="filter-tags">
                        <strong>Filter Aktif:</strong>
                        @foreach($filterLabels as $tag)
                            <span class="filter-tag">{{ $tag }}</span>
                        @endforeach
                    </div>
                @endif
            </td>
            <td style="width: 40%;" class="doc-info-right">
                <div><strong>Waktu Cetak:</strong> {{ $printDate }} WIB</div>
                <div><strong>Total Siswa:</strong> {{ count($students) }} Peserta Didik</div>
            </td>
        </tr>
    </table>

    <!-- Tabel Data Siswa -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 11%;">NIS</th>
                <th style="width: 11%;">NISN</th>
                <th style="width: 25%;">Nama Siswa</th>
                <th style="width: 6%;">L/P</th>
                <th style="width: 15%;">Kelas Rombel</th>
                <th style="width: 16%;">Username / Akun</th>
                <th style="width: 12%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $index => $s)
                @php
                    $gender = match($s->gender) {
                        'L' => 'L',
                        'P' => 'P',
                        default => '-'
                    };

                    $className = $s->classrooms->pluck('name')->join(', ') ?: 'Tanpa Kelas';
                    if (!$s->is_active) {
                        $statusText = 'Nonaktif';
                        $statusClass = 'status-suspended';
                    } else {
                        $statusText = 'Aktif';
                        $statusClass = 'status-active';
                    }
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center font-mono" style="font-size: 7.5pt;">{{ $s->nis ?: '-' }}</td>
                    <td class="text-center font-mono" style="font-size: 7.5pt;">{{ $s->nisn ?: '-' }}</td>
                    <td class="text-left font-bold">{{ $s->name }}</td>
                    <td class="text-center">{{ $gender }}</td>
                    <td class="text-center">{{ $className }}</td>
                    <td class="text-left font-mono" style="font-size: 7.5pt;">{{ $s->username ?: $s->email }}</td>
                    <td class="text-center">
                        <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 16px; color: #94a3b8;">
                        Tidak ada data siswa yang sesuai dengan filter yang dipilih.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
