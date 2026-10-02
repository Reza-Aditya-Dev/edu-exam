# 📐 Rancangan Database EduExam

> **Platform Ujian Digital Sekolah**
> Mencakup Stage 1 (Siswa), Stage 2 (Guru), Stage 3 (Administrator)

---

## 📊 Entity Relationship Diagram

```mermaid
erDiagram
    users ||--o{ classroom_student : "siswa mendaftar"
    users ||--o{ teacher_subject : "guru mengajar"
    users ||--o{ teacher_classroom : "guru mengajar kelas"
    users ||--o{ exams : "guru membuat"
    users ||--o{ exam_participants : "siswa ikut ujian"
    users ||--o{ student_answers : "siswa menjawab"
    users ||--o{ exam_results : "siswa mendapat hasil"
    users ||--o{ notifications : "menerima notifikasi"
    users ||--o{ activity_logs : "melakukan aktivitas"
    users ||--o{ questions : "guru membuat soal"

    academic_years ||--o{ classrooms : "memiliki kelas"
    academic_years ||--o{ exams : "memiliki ujian"

    subjects ||--o{ questions : "memiliki soal"
    subjects ||--o{ exams : "memiliki ujian"
    subjects ||--o{ teacher_subject : "diajar guru"

    classrooms ||--o{ classroom_student : "memiliki siswa"
    classrooms ||--o{ teacher_classroom : "diajar guru"
    classrooms ||--o{ exams : "memiliki ujian"

    questions ||--o{ question_options : "memiliki pilihan"
    questions ||--o{ exam_questions : "dipakai di ujian"

    exams ||--o{ exam_questions : "memiliki soal"
    exams ||--o{ exam_participants : "memiliki peserta"
    exams ||--o{ exam_results : "memiliki hasil"
    exams ||--|| exam_settings : "memiliki pengaturan"

    exam_questions ||--o{ student_answers : "dijawab siswa"
    question_options ||--o{ student_answers : "dipilih siswa"

    users {
        bigint id PK
        string name
        string email UK
        string username UK
        string password
        enum role "admin|teacher|student"
        string avatar
        string nip "guru"
        string nis "siswa"
        enum gender "L|P"
        boolean is_active
        timestamp email_verified_at
        timestamps created_at
    }

    academic_years {
        bigint id PK
        string name "2026/2027"
        int start_year
        int end_year
        enum semester "1|2"
        boolean is_active
        timestamps created_at
    }

    subjects {
        bigint id PK
        string name
        string code "MAT|IND|ENG"
        string description
        boolean is_active
        timestamps created_at
    }

    classrooms {
        bigint id PK
        bigint academic_year_id FK
        bigint homeroom_teacher_id FK
        string name "X IPA 1"
        int grade "10|11|12"
        string major "IPA|IPS"
        int capacity
        boolean is_active
        timestamps created_at
    }

    questions {
        bigint id PK
        bigint subject_id FK
        bigint created_by FK
        enum type "multiple_choice|true_false|short_answer|essay"
        text question_text
        string question_image
        string topic
        enum difficulty "easy|medium|hard"
        float score
        text explanation
        timestamps created_at
    }

    question_options {
        bigint id PK
        bigint question_id FK
        string label "A|B|C|D|E"
        text option_text
        string option_image
        boolean is_correct
        int sort_order
    }

    exams {
        bigint id PK
        bigint subject_id FK
        bigint classroom_id FK
        bigint teacher_id FK
        bigint academic_year_id FK
        string title
        string exam_type "UTS|UAS|UH|Quiz"
        text description
        text instructions
        date exam_date
        time start_time
        time end_time
        int duration_minutes
        int total_questions
        float passing_grade
        enum status "draft|scheduled|active|completed|archived"
        timestamps created_at
    }

    exam_settings {
        bigint id PK
        bigint exam_id FK
        boolean shuffle_questions
        boolean shuffle_options
        boolean auto_save
        boolean auto_submit
        boolean show_result_immediately
        boolean allow_review
        boolean show_correct_answers
        int max_attempts
    }

    exam_questions {
        bigint id PK
        bigint exam_id FK
        bigint question_id FK
        int question_order
    }

    exam_participants {
        bigint id PK
        bigint exam_id FK
        bigint student_id FK
        enum status "not_started|in_progress|submitted|timed_out"
        timestamp started_at
        timestamp submitted_at
        boolean is_late_submission
    }

    student_answers {
        bigint id PK
        bigint exam_participant_id FK
        bigint exam_question_id FK
        bigint selected_option_id FK
        text answer_text "essay/short"
        boolean is_correct
        float score_obtained
        boolean is_marked
        timestamp answered_at
    }

    exam_results {
        bigint id PK
        bigint exam_id FK
        bigint student_id FK
        bigint exam_participant_id FK
        float total_score
        int correct_answers
        int wrong_answers
        int unanswered
        int time_spent_minutes
        enum pass_status "pass|fail"
        timestamps created_at
    }

    notifications {
        bigint id PK
        bigint user_id FK
        string title
        text message
        enum type "info|exam|result|system"
        string related_type
        bigint related_id
        boolean is_read
        timestamp read_at
        timestamps created_at
    }

    activity_logs {
        bigint id PK
        bigint user_id FK
        string action
        text description
        string loggable_type
        bigint loggable_id
        string ip_address
        string user_agent
        timestamps created_at
    }

    school_settings {
        bigint id PK
        string key UK
        text value
        string group
        timestamps created_at
    }
```

---

## 📋 Daftar Tabel & Penjelasan

### Kelompok 1: Pengguna & Autentikasi

| Tabel | Fungsi |
|-------|--------|
| `users` | Tabel utama semua pengguna (admin, guru, siswa) dengan role-based access |
| `password_reset_tokens` | Token reset password (bawaan Laravel) |
| `sessions` | Sesi login aktif (bawaan Laravel) |

### Kelompok 2: Struktur Sekolah

| Tabel | Fungsi |
|-------|--------|
| `academic_years` | Tahun ajaran & semester |
| `subjects` | Daftar mata pelajaran |
| `classrooms` | Daftar kelas per tahun ajaran |
| `classroom_student` | Pivot: siswa ↔ kelas |
| `teacher_subject` | Pivot: guru ↔ mata pelajaran |
| `teacher_classroom` | Pivot: guru ↔ kelas |

### Kelompok 3: Bank Soal

| Tabel | Fungsi |
|-------|--------|
| `questions` | Soal-soal (PG, B/S, Isian, Essay) |
| `question_options` | Pilihan jawaban untuk soal pilihan ganda |

### Kelompok 4: Ujian

| Tabel | Fungsi |
|-------|--------|
| `exams` | Ujian yang dibuat guru |
| `exam_settings` | Pengaturan per ujian (acak soal, auto-submit, dll) |
| `exam_questions` | Pivot: soal yang dipakai di ujian |
| `exam_participants` | Peserta ujian & status pengerjaan |
| `student_answers` | Jawaban per soal per siswa |
| `exam_results` | Ringkasan hasil ujian per siswa |

### Kelompok 5: Sistem

| Tabel | Fungsi |
|-------|--------|
| `notifications` | Notifikasi untuk semua pengguna |
| `activity_logs` | Log aktivitas sistem (audit trail) |
| `school_settings` | Pengaturan sekolah (key-value) |

---

## 🔗 Relasi Antar Tabel

### Relasi Utama

| Dari | Ke | Tipe | Keterangan |
|------|-----|------|-----------|
| `users` (student) | `classrooms` | Many-to-Many | via `classroom_student` |
| `users` (teacher) | `subjects` | Many-to-Many | via `teacher_subject` |
| `users` (teacher) | `classrooms` | Many-to-Many | via `teacher_classroom` |
| `classrooms` | `academic_years` | Many-to-One | Kelas punya 1 tahun ajaran |
| `classrooms` | `users` (teacher) | Many-to-One | Wali kelas |
| `questions` | `subjects` | Many-to-One | Soal milik 1 mapel |
| `questions` | `users` (teacher) | Many-to-One | Dibuat oleh guru |
| `questions` | `question_options` | One-to-Many | 1 soal punya banyak pilihan |
| `exams` | `subjects` | Many-to-One | Ujian untuk 1 mapel |
| `exams` | `classrooms` | Many-to-One | Ujian untuk 1 kelas |
| `exams` | `users` (teacher) | Many-to-One | Dibuat oleh guru |
| `exams` | `questions` | Many-to-Many | via `exam_questions` |
| `exams` | `users` (student) | Many-to-Many | via `exam_participants` |
| `exam_participants` | `student_answers` | One-to-Many | 1 partisipasi punya banyak jawaban |
| `student_answers` | `exam_questions` | Many-to-One | Jawaban untuk soal spesifik |
| `student_answers` | `question_options` | Many-to-One | Pilihan yang dipilih |
| `exam_results` | `exams` | Many-to-One | Hasil untuk 1 ujian |
| `exam_results` | `users` | Many-to-One | Hasil milik 1 siswa |

---

## 🛡️ Indeks & Constraint

### Indeks Penting

| Tabel | Kolom | Tipe |
|-------|-------|------|
| `users` | `email` | UNIQUE |
| `users` | `username` | UNIQUE |
| `users` | `role` | INDEX |
| `users` | `nis` | INDEX |
| `users` | `nip` | INDEX |
| `questions` | `subject_id` | INDEX |
| `questions` | `created_by` | INDEX |
| `questions` | `type` | INDEX |
| `questions` | `difficulty` | INDEX |
| `exams` | `status` | INDEX |
| `exams` | `exam_date` | INDEX |
| `exams` | `teacher_id, subject_id` | COMPOSITE |
| `exam_participants` | `exam_id, student_id` | UNIQUE COMPOSITE |
| `student_answers` | `exam_participant_id, exam_question_id` | UNIQUE COMPOSITE |
| `exam_results` | `exam_id, student_id` | UNIQUE COMPOSITE |
| `notifications` | `user_id, is_read` | COMPOSITE |
| `activity_logs` | `user_id, created_at` | COMPOSITE |

### Foreign Key Cascades

| FK | ON DELETE |
|----|-----------|
| `classroom_student.student_id → users.id` | CASCADE |
| `classroom_student.classroom_id → classrooms.id` | CASCADE |
| `question_options.question_id → questions.id` | CASCADE |
| `exam_questions.exam_id → exams.id` | CASCADE |
| `exam_questions.question_id → questions.id` | CASCADE |
| `student_answers.exam_participant_id → exam_participants.id` | CASCADE |
| `exam_results.exam_id → exams.id` | CASCADE |
| `notifications.user_id → users.id` | CASCADE |
| `activity_logs.user_id → users.id` | SET NULL |

---

## 📌 Alur Data per Stage

### Stage 1 — Siswa

```
users (role=student)
  → classroom_student → classrooms
  → exam_participants → exams
  → student_answers → exam_questions → questions → question_options
  → exam_results
  → notifications
```

### Stage 2 — Guru

```
users (role=teacher)
  → teacher_subject → subjects
  → teacher_classroom → classrooms
  → questions → question_options
  → exams → exam_questions → questions
  → exams → exam_participants → student_answers
  → exams → exam_results (view results, analytics)
  → activity_logs
```

### Stage 3 — Admin

```
users (role=admin)
  → users (CRUD semua user)
  → classrooms (CRUD)
  → subjects (CRUD)
  → academic_years (CRUD)
  → exams (view all, archive)
  → school_settings (configure)
  → activity_logs (audit)
  → notifications (system-wide)
```

---

> [!IMPORTANT]
> Klik **Proceed** untuk melanjutkan pembuatan semua file Laravel Migration berdasarkan rancangan ini.
