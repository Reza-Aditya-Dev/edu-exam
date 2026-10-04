<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Kartu Peserta Ujian CBT — {{ $schoolName }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f1f5f9;
            color: #0f172a;
            padding: 20px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .no-print {
            max-width: 900px;
            margin: 0 auto 20px auto;
            background: #ffffff;
            padding: 14px 20px;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 8px;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }
        .btn-primary {
            background-color: #2563eb;
            color: #ffffff;
        }
        .btn-primary:hover {
            background-color: #1d4ed8;
        }
        .btn-secondary {
            background-color: #e2e8f0;
            color: #334155;
        }
        .btn-secondary:hover {
            background-color: #cbd5e1;
        }
        .cards-container {
            max-width: 900px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        .exam-card {
            background: #ffffff;
            border: 2px solid #1e293b;
            border-radius: 10px;
            padding: 14px;
            position: relative;
            page-break-inside: avoid;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }
        .school-emblem {
            width: 44px;
            height: 44px;
            background: #eff6ff;
            border: 1.5px solid #2563eb;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }
        .header-title {
            flex: 1;
            min-width: 0;
            line-height: 1.25;
        }
        .header-title h3 {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0f172a;
        }
        .header-title h4 {
            font-size: 13px;
            font-weight: 800;
            color: #1e40af;
            margin: 2px 0 1px 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .header-title p {
            font-size: 9.5px;
            color: #64748b;
            font-weight: 500;
        }
        .card-body {
            display: flex;
            gap: 12px;
            margin-bottom: 10px;
        }
        .student-photo-box {
            width: 72px;
            height: 94px;
            background: #f8fafc;
            border: 1.5px dashed #94a3b8;
            border-radius: 6px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            text-align: center;
            padding: 4px;
            overflow: hidden;
        }
        .student-photo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 4px;
        }
        .student-photo-placeholder {
            font-size: 8.5px;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            line-height: 1.2;
        }
        .student-info-table {
            flex: 1;
            font-size: 11px;
            border-collapse: collapse;
        }
        .student-info-table tr td {
            padding: 2.5px 0;
            vertical-align: top;
        }
        .student-info-table td.label {
            width: 86px;
            color: #475569;
            font-weight: 600;
        }
        .student-info-table td.colon {
            width: 10px;
            color: #475569;
            text-align: center;
        }
        .student-info-table td.val {
            color: #0f172a;
            font-weight: 600;
        }
        .mono-highlight {
            font-family: 'JetBrains Mono', monospace;
            background: #f1f5f9;
            padding: 1px 5px;
            border-radius: 4px;
            font-weight: 700;
            color: #0f172a;
            border: 1px solid #e2e8f0;
            font-size: 10.5px;
        }
        .card-footer {
            border-top: 1px dashed #cbd5e1;
            padding-top: 8px;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            font-size: 9px;
            color: #64748b;
        }
        .stamp-box {
            border: 1.5px solid #059669;
            color: #059669;
            padding: 3px 8px;
            border-radius: 4px;
            font-weight: 800;
            font-size: 9px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            transform: rotate(-3deg);
        }
        .signature-box {
            text-align: center;
            font-size: 9.5px;
        }
        .signature-space {
            height: 24px;
        }
        .signature-name {
            font-weight: 700;
            color: #0f172a;
            text-decoration: underline;
        }
        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            .cards-container {
                max-width: 100%;
                gap: 12px;
            }
            .exam-card {
                box-shadow: none;
                border-color: #000000;
            }
            @page {
                size: A4 portrait;
                margin: 8mm;
            }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <div>
            <h2 style="font-size: 16px; font-weight: 800; color: #0f172a;">Pratinjau Cetak Kartu Peserta Ujian CBT</h2>
            <p style="font-size: 12px; color: #64748b; margin-top: 2px;">
                Ditemukan <strong>{{ $students->count() }}</strong> data kartu siswa siap cetak (Ukuran standar: A4, 2 kolom).
            </p>
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('admin.students') }}" class="btn btn-secondary">
                Kembali ke Data Siswa
            </a>
            <button onclick="window.print()" class="btn btn-primary">
                Cetak Sekarang (Ctrl + P)
            </button>
        </div>
    </div>

    <div class="cards-container">
        @forelse($students as $student)
            @php
                $primaryClass = $student->classrooms->first();
                $academicYearName = $primaryClass && $primaryClass->academicYear ? $primaryClass->academicYear->name : ($academicYear ? $academicYear->name : '2026/2027');
                $semesterName = ($academicYear && $academicYear->semester == 2) ? 'Genap' : 'Ganjil';
            @endphp
            <div class="exam-card">
                <div>
                    <!-- Header Kartu -->
                    <div class="card-header">
                        <div class="school-emblem">🎓</div>
                        <div class="header-title">
                            <h3>KARTU PESERTA UJIAN BERBASIS CBT</h3>
                            <h4>{{ $schoolName }}</h4>
                            <p>NPSN: {{ $schoolNpsn }} • T.A {{ $academicYearName }} ({{ $semesterName }})</p>
                        </div>
                    </div>

                    <!-- Body Kartu -->
                    <div class="card-body">
                        <!-- Foto Siswa -->
                        <div class="student-photo-box">
                            @if($student->avatar)
                                <img src="{{ $student->avatar_url }}" alt="Foto">
                            @else
                                <div class="student-photo-placeholder">
                                    PAS FOTO<br>3 X 4<br><br>
                                    <span style="font-size: 16px;">👤</span>
                                </div>
                            @endif
                        </div>

                        <!-- Info Siswa -->
                        <table class="student-info-table">
                            <tr>
                                <td class="label">Nama Lengkap</td>
                                <td class="colon">:</td>
                                <td class="val" style="font-size: 11.5px; text-transform: uppercase;">{{ $student->name }}</td>
                            </tr>
                            <tr>
                                <td class="label">NISN / NIS</td>
                                <td class="colon">:</td>
                                <td class="val"><span class="mono-highlight">{{ $student->nis ?: '-' }}</span></td>
                            </tr>
                            <tr>
                                <td class="label">Kelas / Rombel</td>
                                <td class="colon">:</td>
                                <td class="val">{{ $primaryClass ? $primaryClass->name : 'Reguler' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Username CBT</td>
                                <td class="colon">:</td>
                                <td class="val"><span class="mono-highlight">{{ $student->username }}</span></td>
                            </tr>
                            <tr>
                                <td class="label">Password Akun</td>
                                <td class="colon">:</td>
                                <td class="val"><span class="mono-highlight">******</span> <span style="font-size: 9px; color: #64748b;">(Terdaftar)</span></td>
                            </tr>
                            <tr>
                                <td class="label">Ruang / Server</td>
                                <td class="colon">:</td>
                                <td class="val">LAB-CBT-01</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Footer Kartu -->
                <div class="card-footer">
                    <div>
                        <div class="stamp-box">TERVERIFIKASI CBT</div>
                        <div style="margin-top: 4px; font-size: 8px;">Cetak: {{ date('d/m/Y H:i') }} WIB</div>
                    </div>
                    <div class="signature-box">
                        <div>Ketua Panitia CBT,</div>
                        <div class="signature-space"></div>
                        <div class="signature-name">Panitia Ujian Sekolah</div>
                        <div>NIP. 19850314 201001 1 012</div>
                    </div>
                </div>
            </div>
        @empty
            <div style="grid-column: span 2; text-align: center; padding: 60px 20px; background: #fff; border-radius: 12px;">
                <h3 style="color: #64748b;">Tidak ada data siswa untuk dicetak.</h3>
                <p style="font-size: 12px; color: #94a3b8; margin-top: 6px;">Silakan pilih siswa pada tabel data siswa terlebih dahulu.</p>
                <a href="{{ route('admin.students') }}" class="btn btn-primary" style="margin-top: 14px;">Kembali ke Data Siswa</a>
            </div>
        @endforelse
    </div>

</body>
</html>
