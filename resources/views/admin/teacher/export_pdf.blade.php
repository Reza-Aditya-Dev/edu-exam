<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Guru - {{ $schoolName }}</title>
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
            margin-bottom: 8px;
        }
        .kop-table td {
            vertical-align: middle;
            padding: 0;
        }
        .logo-img {
            max-height: 46px;
            max-width: 46px;
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

        /* ═══ TABEL DATA GURU ═══ */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7.5pt;
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
            font-size: 7pt;
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
            font-size: 6.5pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-active {
            background-color: #dcfce7;
            color: #15803d;
            border: 0.5pt solid #86efac;
        }
        .status-suspended {
            background-color: #fee2e2;
            color: #b91c1c;
            border: 0.5pt solid #fca5a5;
        }

        .badge-wali {
            display: inline-block;
            padding: 1.5px 5px;
            border-radius: 3px;
            font-size: 7pt;
            font-weight: bold;
            background-color: #d1fae5;
            color: #065f46;
            border: 0.5pt solid #a7f3d0;
        }

        .badge-exam {
            display: inline-block;
            padding: 1.5px 5px;
            border-radius: 3px;
            font-size: 7pt;
            font-weight: bold;
            background-color: #e0e7ff;
            color: #3730a3;
            border: 0.5pt solid #c7d2fe;
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
                <td style="width: 50%;">EduExam &bull; {{ $schoolName }}</td>
                <td style="width: 50%; text-align: right;" class="page-number"></td>
            </tr>
        </table>
    </div>

    <!-- Header / Kop Surat Sekolah -->
    <table class="kop-table">
        <tr>
            @if(!empty($schoolLogoBase64))
                <td style="width: 50px; padding-right: 10px; text-align: center;">
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
                <div class="report-badge">DATABASE GURU CBT</div>
            </td>
        </tr>
    </table>

    <!-- Bar Judul Laporan & Metadata -->
    <table class="meta-bar">
        <tr>
            <td style="width: 60%;">
                <div class="doc-title">LAPORAN DATA GURU</div>
                <div class="doc-subtitle">Daftar profil tenaga pendidik, penugasan mata pelajaran, wali kelas, dan statistik ujian CBT</div>
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
                <div><strong>Total Guru:</strong> {{ count($teachers) }} Tenaga Pendidik</div>
            </td>
        </tr>
    </table>

    <!-- Tabel Data Guru (Landscape A4: 9 Kolom) -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 3%;">No</th>
                <th style="width: 13%;">NIP / NUPTK</th>
                <th style="width: 18%;">Nama Guru</th>
                <th style="width: 4%;">L/P</th>
                <th style="width: 18%;">Mata Pelajaran Diampu</th>
                <th style="width: 12%;">Wali Kelas</th>
                <th style="width: 8%;">Ujian Dibuat</th>
                <th style="width: 16%;">Email &amp; Username</th>
                <th style="width: 8%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($teachers as $index => $t)
                @php
                    $gender = match($t->gender) {
                        'L', 'laki-laki' => 'L',
                        'P', 'perempuan' => 'P',
                        default => '-'
                    };

                    $subjects = $t->subjects->pluck('name')->join(', ') ?: 'Belum ditentukan';
                    $statusText = $t->is_active ? 'Aktif' : 'Nonaktif';
                    $statusClass = $t->is_active ? 'status-active' : 'status-suspended';
                    $examCount = $t->created_exams_count ?? 0;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center font-mono" style="font-size: 7pt;">{{ $t->nip ?: '-' }}</td>
                    <td class="text-left font-bold">{{ $t->name }}</td>
                    <td class="text-center">{{ $gender }}</td>
                    <td class="text-left">{{ $subjects }}</td>
                    <td class="text-center">
                        @if($t->homeroomClassrooms && $t->homeroomClassrooms->count() > 0)
                            @foreach($t->homeroomClassrooms as $hrClass)
                                <span class="badge-wali">{{ $hrClass->name }} (Wali)</span>
                            @endforeach
                        @else
                            <span style="color: #94a3b8; font-weight: bold;">—</span>
                        @endif
                    </td>
                    <td class="text-center font-bold">
                        @if($examCount > 0)
                            <span class="badge-exam">{{ $examCount }} Ujian</span>
                        @else
                            <span style="color: #64748b;">0 Ujian</span>
                        @endif
                    </td>
                    <td class="text-left font-mono" style="font-size: 7pt;">
                        {{ $t->email }}
                        @if($t->username)
                            <br><span style="color: #64748b; font-size: 6.5pt;">({{ '@' . $t->username }})</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center" style="padding: 16px; color: #94a3b8;">
                        Tidak ada data guru yang sesuai dengan kriteria yang dipilih.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
