<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduExam — Platform Ujian Digital Sekolah</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --primary: #3155D9;
            --primary-dark: #233e9f;
            --primary-light: #eef2ff;
            --success: #10B981;
            --success-light: #ecfdf5;
            --warning: #F59E0B;
            --warning-light: #fffbeb;
            --danger: #EF4444;
            --danger-light: #fef2f2;
            
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --gray-900: #0f172a;
            
            --radius-md: 8px;
            --radius-lg: 12px;
            --radius-xl: 16px;
            --radius-2xl: 24px;
            
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--gray-50);
            color: var(--gray-800);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* NAVBAR */
        .navbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            z-index: 100;
            padding: 16px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
        }
        
        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }
        
        .brand-logo {
            width: 40px; height: 40px;
            background: var(--primary);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 800; font-size: 1.25rem;
        }
        
        .brand-text {
            font-size: 1.5rem; font-weight: 800;
            color: var(--gray-900); letter-spacing: -0.5px;
        }

        .nav-links { display: flex; gap: 32px; align-items: center; }
        .nav-link { text-decoration: none; color: var(--gray-600); font-weight: 600; transition: color 0.2s; }
        .nav-link:hover { color: var(--primary); }

        .btn {
            display: inline-flex; align-items: center; justify-content: center;
            padding: 12px 24px; border-radius: 100px; font-weight: 700;
            text-decoration: none; transition: all 0.3s; cursor: pointer; border: none;
        }
        .btn-primary { background: var(--primary); color: white; box-shadow: 0 4px 14px rgba(49, 85, 217, 0.3); }
        .btn-primary:hover { background: var(--primary-dark); transform: translateY(-2px); box-shadow: 0 6px 20px rgba(49, 85, 217, 0.4); }
        .btn-outline { background: transparent; color: var(--gray-700); border: 2px solid var(--gray-200); }
        .btn-outline:hover { border-color: var(--primary); color: var(--primary); }

        /* HERO SECTION */
        .hero {
            padding: 160px 5% 100px;
            min-height: 100vh;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            text-align: center;
            background: linear-gradient(180deg, var(--primary-light) 0%, rgba(255,255,255,0) 100%);
            position: relative;
        }

        .hero::before {
            content: ''; position: absolute;
            top: -100px; left: -100px; width: 400px; height: 400px;
            background: var(--primary); filter: blur(150px); opacity: 0.1; border-radius: 50%;
        }

        .hero-badge {
            background: white; border: 1px solid var(--gray-200); padding: 8px 16px;
            border-radius: 100px; font-size: 0.875rem; font-weight: 600; color: var(--primary);
            margin-bottom: 24px; box-shadow: var(--shadow-sm); display: inline-flex; align-items: center; gap: 8px;
        }

        .hero-title {
            font-size: 4.5rem; font-weight: 800; color: var(--gray-900);
            line-height: 1.1; letter-spacing: -1.5px; margin-bottom: 24px; max-width: 900px;
        }
        
        .hero-title span { color: var(--primary); position: relative; }

        .hero-desc {
            font-size: 1.25rem; color: var(--gray-600); max-width: 700px;
            margin-bottom: 48px; line-height: 1.6;
        }

        .hero-actions { display: flex; gap: 16px; margin-bottom: 80px; }
        .hero-actions .btn { font-size: 1.125rem; padding: 16px 36px; }

        /* PORTALS GRID */
        .portals { padding: 40px 5% 100px; background: white; }
        .section-header { text-align: center; margin-bottom: 64px; }
        .section-title { font-size: 2.5rem; font-weight: 800; color: var(--gray-900); margin-bottom: 16px; }
        .section-desc { font-size: 1.125rem; color: var(--gray-600); max-width: 600px; margin: 0 auto; }

        .portals-grid {
            display: grid; grid-template-columns: repeat(3, 1fr); gap: 32px; max-width: 1200px; margin: 0 auto;
        }

        .portal-card {
            background: white; border-radius: var(--radius-2xl); border: 1px solid var(--gray-200);
            padding: 40px 32px; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-align: center; position: relative; overflow: hidden; z-index: 1; text-decoration: none; color: inherit;
        }

        .portal-card::before {
            content: ''; position: absolute; inset: 0; background: linear-gradient(180deg, transparent 0%, rgba(0,0,0,0.02) 100%);
            z-index: -1;
        }

        .portal-card:hover {
            transform: translateY(-10px); border-color: var(--primary); box-shadow: var(--shadow-xl);
        }

        .portal-icon {
            width: 80px; height: 80px; border-radius: var(--radius-xl);
            display: flex; align-items: center; justify-content: center;
            font-size: 2.5rem; margin: 0 auto 24px; transition: all 0.3s;
        }

        /* Student Portal */
        .portal-student .portal-icon { background: var(--primary-light); color: var(--primary); }
        .portal-student:hover .portal-icon { background: var(--primary); color: white; transform: scale(1.1); }
        .portal-student:hover { border-color: var(--primary); }

        /* Teacher Portal */
        .portal-teacher .portal-icon { background: var(--success-light); color: var(--success); }
        .portal-teacher:hover .portal-icon { background: var(--success); color: white; transform: scale(1.1); }
        .portal-teacher:hover { border-color: var(--success); }

        /* Admin Portal */
        .portal-admin .portal-icon { background: var(--gray-100); color: var(--gray-900); }
        .portal-admin:hover .portal-icon { background: var(--gray-900); color: white; transform: scale(1.1); }
        .portal-admin:hover { border-color: var(--gray-900); }

        .portal-title { font-size: 1.5rem; font-weight: 800; color: var(--gray-900); margin-bottom: 12px; }
        .portal-desc { font-size: 1rem; color: var(--gray-600); margin-bottom: 32px; }
        
        .portal-btn {
            display: inline-flex; align-items: center; gap: 8px; font-weight: 700; color: var(--primary);
            background: transparent; border: none; font-size: 1rem; transition: gap 0.2s;
        }
        .portal-student .portal-btn { color: var(--primary); }
        .portal-teacher .portal-btn { color: var(--success); }
        .portal-admin .portal-btn { color: var(--gray-900); }
        
        .portal-card:hover .portal-btn { gap: 12px; }

        /* FEATURES */
        .features { padding: 100px 5%; background: var(--gray-50); }
        .features-grid {
            display: grid; grid-template-columns: repeat(2, 1fr); gap: 64px; align-items: center; max-width: 1200px; margin: 0 auto;
        }
        
        .feature-img {
            background: white; border-radius: var(--radius-2xl); border: 1px solid var(--gray-200);
            box-shadow: var(--shadow-lg); overflow: hidden; position: relative; padding: 24px;
        }
        .feature-img-inner {
            background: var(--gray-50); border-radius: var(--radius-lg); height: 400px; border: 1px dashed var(--gray-300);
            display: flex; align-items: center; justify-content: center; flex-direction: column; gap: 16px;
        }
        .mockup-item { width: 80%; height: 40px; background: white; border-radius: 8px; box-shadow: var(--shadow-sm); }

        .feature-content h2 { font-size: 2.5rem; font-weight: 800; color: var(--gray-900); margin-bottom: 24px; line-height: 1.2; }
        .feature-list { list-style: none; display: flex; flex-direction: column; gap: 20px; }
        .feature-item { display: flex; gap: 16px; align-items: flex-start; }
        .feature-check {
            width: 28px; height: 28px; border-radius: 50%; background: var(--success-light);
            color: var(--success); display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-weight: bold;
        }
        .feature-text h4 { font-size: 1.125rem; font-weight: 700; color: var(--gray-900); margin-bottom: 4px; }
        .feature-text p { font-size: 0.9375rem; color: var(--gray-600); }

        /* FOOTER */
        .footer { background: var(--gray-900); color: white; padding: 64px 5% 32px; }
        .footer-content { max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 64px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 48px; margin-bottom: 32px; }
        .footer-brand { margin-bottom: 24px; }
        .footer-logo { width: 32px; height: 32px; background: var(--primary); border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; font-weight: bold; margin-right: 12px; }
        .footer-desc { color: var(--gray-400); max-width: 300px; }
        .footer-title { font-size: 1.125rem; font-weight: 700; margin-bottom: 24px; }
        .footer-links { list-style: none; display: flex; flex-direction: column; gap: 12px; }
        .footer-links a { color: var(--gray-400); text-decoration: none; transition: color 0.2s; }
        .footer-links a:hover { color: white; }
        .copyright { text-align: center; color: var(--gray-500); font-size: 0.875rem; }

        @media (max-width: 1024px) {
            .hero-title { font-size: 3.5rem; }
            .portals-grid { grid-template-columns: 1fr; max-width: 500px; }
            .features-grid { grid-template-columns: 1fr; }
            .feature-img { order: 2; }
            .feature-content { order: 1; }
            .footer-content { grid-template-columns: 1fr; gap: 32px; }
        }
        @media (max-width: 768px) {
            .nav-links { display: none; }
            .hero-title { font-size: 2.5rem; }
            .hero-desc { font-size: 1.125rem; }
            .hero-actions { flex-direction: column; width: 100%; max-width: 300px; }
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar">
        <a href="#" class="brand">
            <div class="brand-logo">E</div>
            <div class="brand-text">EduExam</div>
        </a>
        <div class="nav-links">
            <a href="#fitur" class="nav-link">Fitur</a>
            <a href="#akses" class="nav-link">Portal Akses</a>
            @if (Route::has('login'))
                @auth
                    @if(auth()->user()->role === 'student')
                        <a href="{{ route('student.dashboard') }}" class="btn btn-primary">Dashboard Siswa</a>
                    @elseif(auth()->user()->role === 'teacher')
                        <a href="{{ route('teacher.dashboard') }}" class="btn btn-primary">Dashboard Guru</a>
                    @else
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Admin Panel</a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary">Masuk ke Sistem</a>
                @endauth
            @endif
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section class="hero">
        <div class="hero-badge">
            <span style="display:inline-block;width:8px;height:8px;background:var(--success);border-radius:50%;box-shadow:0 0 8px var(--success);"></span>
            Sistem Aktif & Berjalan
        </div>
        <h1 class="hero-title">Platform <span>Ujian Digital</span> Sekolah Terintegrasi</h1>
        <p class="hero-desc">
            Satu platform untuk seluruh kebutuhan evaluasi akademik. Mulai dari pembuatan soal oleh guru, pelaksanaan ujian untuk siswa, hingga analisis nilai untuk manajemen sekolah.
        </p>
        <div class="hero-actions">
            <a href="#akses" class="btn btn-primary">Mulai Akses EduExam</a>
            <a href="#fitur" class="btn btn-outline">Pelajari Fitur</a>
        </div>
    </section>

    <!-- PORTALS -->
    <section id="akses" class="portals">
        <div class="section-header">
            <h2 class="section-title">Portal Akses</h2>
            <p class="section-desc">Silakan pilih portal masuk yang sesuai dengan peran Anda di sekolah untuk mengakses fitur yang relevan.</p>
        </div>
        
        <div class="portals-grid">
            <!-- Student -->
            <a href="{{ route('login') }}?role=student" class="portal-card portal-student">
                <div class="portal-icon"><i class="bi bi-mortarboard-fill"></i></div>
                <h3 class="portal-title">Portal Siswa</h3>
                <p class="portal-desc">Ikuti ujian, lihat riwayat nilai, dan pantau progres belajar Anda di satu tempat yang bebas distraksi.</p>
                <div class="portal-btn">Masuk sebagai Siswa <i class="bi bi-arrow-right ms-1"></i></div>
            </a>
            
            <!-- Teacher -->
            <a href="{{ route('login') }}?role=teacher" class="portal-card portal-teacher">
                <div class="portal-icon"><i class="bi bi-person-video3"></i></div>
                <h3 class="portal-title">Portal Guru</h3>
                <p class="portal-desc">Kelola bank soal, jadwalkan ujian, dan dapatkan analisis daya serap siswa secara otomatis.</p>
                <div class="portal-btn">Masuk sebagai Guru <i class="bi bi-arrow-right ms-1"></i></div>
            </a>
            
            <!-- Admin -->
            <a href="{{ route('login') }}?role=admin" class="portal-card portal-admin">
                <div class="portal-icon"><i class="bi bi-shield-lock-fill"></i></div>
                <h3 class="portal-title">Administrator</h3>
                <p class="portal-desc">Kelola data master (Siswa, Guru, Kelas) dan pantau seluruh aktivitas sistem secara menyeluruh.</p>
                <div class="portal-btn">Masuk sebagai Admin <i class="bi bi-arrow-right ms-1"></i></div>
            </a>
        </div>
    </section>

    <!-- FEATURES -->
    <section id="fitur" class="features">
        <div class="features-grid">
            <div class="feature-content">
                <h2>Dirancang untuk Memudahkan Seluruh Ekosistem Sekolah</h2>
                <ul class="feature-list">
                    <li class="feature-item">
                        <div class="feature-check"><i class="bi bi-check-lg"></i></div>
                        <div class="feature-text">
                            <h4>Antarmuka Mobile-First untuk Siswa</h4>
                            <p>Desain ujian yang responsif, bersih, dan bebas distraksi. Sangat optimal diakses menggunakan smartphone.</p>
                        </div>
                    </li>
                    <li class="feature-item">
                        <div class="feature-check"><i class="bi bi-check-lg"></i></div>
                        <div class="feature-text">
                            <h4>Analisis Nilai Otomatis</h4>
                            <p>Sistem langsung mengoreksi soal pilihan ganda dan menyajikan grafik analisis daya serap untuk evaluasi guru.</p>
                        </div>
                    </li>
                    <li class="feature-item">
                        <div class="feature-check"><i class="bi bi-check-lg"></i></div>
                        <div class="feature-text">
                            <h4>Manajemen Data Terpusat</h4>
                            <p>Admin dapat dengan mudah mengatur data siswa, guru, kelas, dan mata pelajaran dalam satu dashboard komprehensif.</p>
                        </div>
                    </li>
                    <li class="feature-item">
                        <div class="feature-check"><i class="bi bi-check-lg"></i></div>
                        <div class="feature-text">
                            <h4>Bank Soal Terstruktur</h4>
                            <p>Guru dapat membuat, menyimpan, dan menggunakan kembali butir soal dari bank soal yang terorganisir per mata pelajaran.</p>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="feature-img">
                <div class="feature-img-inner">
                    <div class="mockup-item" style="width:60%;"></div>
                    <div class="mockup-item" style="width:80%;"></div>
                    <div class="mockup-item" style="width:70%;"></div>
                    <div class="mockup-item" style="width:85%;"></div>
                    <div class="mockup-item" style="width:50%;"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="footer-content">
            <div>
                <div class="footer-brand">
                    <span class="footer-logo">E</span>
                    <span style="font-size: 1.5rem; font-weight: 800;">EduExam</span>
                </div>
                <p class="footer-desc">Platform digitalisasi sistem evaluasi pembelajaran untuk Sekolah Menengah Atas di Indonesia.</p>
            </div>
            <div>
                <h4 class="footer-title">Portal</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('login') }}">Login Siswa</a></li>
                    <li><a href="{{ route('login') }}">Login Guru</a></li>
                    <li><a href="{{ route('login') }}">Login Administrator</a></li>
                </ul>
            </div>
            <div>
                <h4 class="footer-title">Bantuan</h4>
                <ul class="footer-links">
                    <li><a href="#">Panduan Penggunaan Siswa</a></li>
                    <li><a href="#">Panduan Penggunaan Guru</a></li>
                    <li><a href="#">Hubungi Admin Sekolah</a></li>
                </ul>
            </div>
        </div>
        <div class="copyright">
            &copy; {{ date('Y') }} EduExam - Platform Ujian Digital Sekolah. All rights reserved.
        </div>
    </footer>

</body>
</html>