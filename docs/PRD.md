# Ilmora — Product Requirements Document (PRD)

**Version:** 4.2.0 — Spatie RBAC + Full 7-Day Calendar Sprint  
**Date:** 2026-04-18  
**Auditor:** Claude Code (Senior Technical PM)  
**Classification:** Internal  
**Branch audited:** `docs/add-project-documentation`

> This PRD is produced by reading every file in the actual codebase. Every status marker is backed by specific file evidence. Nothing is assumed or guessed.

---

## Section 1: Infrastructure Status

| Component | Expected | Actual (from code) | Status |
|-----------|----------|--------------------|--------|
| **PHP Version** | ^8.2 | `"php": "^8.2"` in `composer.json` | ✅ Configured |
| **Laravel Version** | 11.x | `"laravel/framework": "^11.31"` in `composer.json` | ✅ v11.31 |
| **Database** | MySQL 8 | `DB_CONNECTION=mysql` in `.env.example` | ✅ MySQL 8 |
| **Migrations** | Full ERD covered | 15 custom + 3 Laravel default migrations | 🔧 Partial (see Section 3) |
| **Authentication** | Laravel Breeze | `laravel/breeze ^2.0` in `require-dev` only — **never installed/scaffolded**; custom `Livewire\Auth\Login` hand-rolled instead; `StudentRegister` Livewire component added (`GET /register/{school:slug}`) | 🔧 Partial — login + student self-reg; no teacher register/reset |
| **Session Auth** | Cookie-based | `Auth::attempt()` in `Login.php`, session regenerate, logout route | ✅ Functional |
| **RBAC — Spatie** | `spatie/laravel-permission` | Migrations run (`2026_04_18_113224`); `HasRoles` trait on `User`; `RoleSeeder` seeds 4 roles + 20 permissions; all user-creation paths call `assignRole()`; existing users backfilled via `tinker` | ✅ Active |
| **Role System (Custom)** | — | `role` string column retained for scoping queries; Spatie `RoleMiddleware` replaces old `CheckRole` alias in `bootstrap/app.php`; routes `/lessons`, `/groups`, `/students` require `role:school_admin\|teacher`; `/teachers` requires `role:school_admin` | ✅ Routes protected |
| **Livewire** | ^3.0 | `"livewire/livewire": "^3.0"` in `composer.json` | ✅ v3 |
| **Alpine.js** | ^3.x | `"alpinejs": "^3.15.11"` in `package.json` | ✅ v3.15 |
| **Alpine plugins** | collapse/focus/sort | `@alpinejs/collapse`, `@alpinejs/focus`, `@alpinejs/sort` in `package.json` | ✅ Installed (unused in views) |
| **Tailwind CSS** | ^3.4 | `"tailwindcss": "^3.4.19"` in `package.json` | ✅ v3.4 |
| **@tailwindcss/forms** | Required | Present in `package.json` + `tailwind.config.js` | ✅ Configured |
| **@tailwindcss/typography** | Required | Present in `package.json` + `tailwind.config.js` | ✅ Configured |
| **Vite** | ^6 | `"vite": "^6.0.11"` + `laravel-vite-plugin` in `package.json` | ✅ v6 |
| **Chart.js** | ^4 | `"chart.js": "^4.4.6"` in `package.json` | 📦 Installed, unused |
| **i18n — lang/ directory** | 7 locales | `lang/ar.json` and `lang/en.json` fully populated (~120 keys each). `de.json`, `tr.json`, `ur.json`, `ms.json`, `fr.json` still empty `{}` | 🔧 Partial (ar+en done, 5 locales empty) |
| **SetLocale Middleware** | Registered | `SetLocale.php` exists and is **registered** in `bootstrap/app.php` via `->web(append:[SetLocale::class])` | ✅ Active |
| **Queue Driver** | database | `QUEUE_CONNECTION=database` in `.env.example`; `jobs` table migration exists | ✅ Configured |
| **Cache Driver** | file | `CACHE_STORE=file` in `.env.example` | ✅ Configured |
| **Mail Config** | SMTP | `MAIL_MAILER=smtp` + all mail vars in `.env.example` | ✅ Configured |
| **Spatie Activity Log** | `^4.9` | `"spatie/laravel-activitylog": "^4.9"` in `composer.json` — no migrations run, no `LogsActivity` trait on models | 📦 Package only |
| **Spatie Backup** | `^9.0` | `"spatie/laravel-backup": "^9.0"` in `composer.json` — no config published | 📦 Package only |
| **Spatie CSP** | `^2.9` | `"spatie/laravel-csp": "^2.9"` in `composer.json` — no config published | 📦 Package only |
| **Laravel Sanctum** | `^4.0` | `"laravel/sanctum": "^4.0"` in `composer.json` | 📦 Installed, unused |
| **Laravel Scout** | `^10.0` | `"laravel/scout": "^10.0"` in `composer.json` | 📦 Installed, unconfigured |
| **DomPDF** | `^3.0` | `"barryvdh/laravel-dompdf": "^3.0"` in `composer.json` | 📦 Installed, unused |
| **PHPStan / Larastan** | Level 5 | `nunomaduro/larastan ^2.9`; `phpstan.neon` at level 5 | ✅ Configured |
| **Pest** | `^3.0` | `pestphp/pest ^3.0` + `pestphp/pest-plugin-laravel ^3.0` in `composer.json` | ✅ Installed |
| **Laravel Pint** | Code style | `laravel/pint ^1.13` in `composer.json` | ✅ Installed |
| **CI/CD — GitHub Actions** | Tests + code style | `.github/workflows/tests.yml` (PHP 8.2/8.3/8.4 matrix, PHPUnit), `pull-requests.yml`, `issues.yml`, `update-changelog.yml` | 🔧 Exists, runs PHPUnit not Pest; targets `master` not `main` |
| **Docker / Sail** | Dev environment | `docker-compose.yml`, `Dockerfile`, `docker/` dir, `laravel/sail ^1.26` | ✅ Present |
| **BelongsToTenant Trait** | On all tenant models | `app/Models/Concerns/BelongsToTenant.php` — updated to use `school_id`; **applied to `Group` and `Lesson`** | ✅ Applied (Group, Lesson) |
| **Form Requests** | Required on all actions | `app/Http/Requests/` — 5 classes: `StoreGroupRequest`, `StoreLessonRequest`, `StoreAssignmentRequest`, `StoreStudentRequest`, `StoreTeacherRequest` | ✅ Created |
| **RTL / dir attribute** | `<html dir="rtl">` for Arabic | `components/layouts/app.blade.php` now has dynamic `dir="{{ in_array(app()->getLocale(), ['ar','ur']) ? 'rtl' : 'ltr' }}" lang="{{ app()->getLocale() }}"` | ✅ Done |

---

## Section 2: Feature Audit

### 1. Multi-tenancy & Organization

| Feature | Status | Evidence |
|---------|--------|----------|
| Tenant/School model | 🔧 Partial | `School` model exists (`app/Models/School.php`), `schools` table migration exists (`2024_01_01_000001_create_schools_table.php`). However the model has only `name` + `slug` — no `plan`, `settings`, `is_active`, `logo_url` columns that the ERD specifies. Called "School" not "Tenant". |
| School setup wizard | ✅ Done | `Setup` Livewire component (`app/Livewire/Setup.php`) + view (`livewire/setup.blade.php`); creates school + first admin; route `/setup` in `web.php`. Full stack. |
| `EnsureSchoolExists` guard | ✅ Done | `app/Http/Middleware/EnsureSchoolExists.php` registered in `bootstrap/app.php` as aliased middleware `'school'`; applied to route group in `web.php`. |
| BelongsToTenant scoping | ✅ Done | `app/Models/Concerns/BelongsToTenant.php` — updated to use `school_id`; `use BelongsToTenant` added to `app/Models/Group.php` and `app/Models/Lesson.php`; global scope auto-filters all queries by `school_id`; auto-sets `school_id` on create. |
| Multi-school / SaaS tenancy | ❌ Not Started | Schema only supports a single school (no `tenant_id` on users/groups/lessons). `School::exists()` is a boolean check — not per-user tenant routing. |
| School settings / config | ❌ Not Started | No settings column, no settings UI. |

---

### 2. Authentication & Authorization

| Feature | Status | Evidence |
|---------|--------|----------|
| Login | ✅ Done | `app/Livewire/Auth/Login.php` + `resources/views/livewire/auth/login.blade.php`; route `GET /login` in `web.php`; `Auth::attempt()`, session regenerate. |
| Logout | ✅ Done | `POST /logout` route in `web.php`; invalidates session, regenerates token. |
| User registration | 🔧 Partial | **Student self-registration added:** `app/Livewire/Auth/StudentRegister.php` + `resources/views/livewire/auth/student-register.blade.php`; route `GET /register/{school:slug}` (school resolved by slug); collects student name/age + guardian name/phone/email; no password required; admin students page shows a shareable registration link with Copy button. Teacher/admin self-registration still not implemented. |
| Password reset | ❌ Not Started | No forgot-password route, no reset-password flow. `password_reset_tokens` table exists from default migration but nothing uses it. |
| Email verification | ❌ Not Started | `email_verified_at` column exists in users migration but `MustVerifyEmail` interface not implemented. |
| Role-based access control (Spatie) | ✅ Done | Migrations run; `HasRoles` on `User`; `RoleSeeder` creates `super_admin`, `school_admin`, `teacher`, `student` roles with 20 permissions; `assignRole()` called in `Setup`, `Students`, `Teachers`, `StudentRegister`; existing users backfilled; `$user->hasRole()`, `$user->can()`, `@role`/`@can` Blade directives all functional. |
| Custom role system | ✅ Done | Spatie `RoleMiddleware` replaces `CheckRole` as `role` alias in `bootstrap/app.php`. Routes protected: `/lessons`, `/groups`, `/students` → `role:school_admin\|teacher`; `/teachers` → `role:school_admin`. Helper methods `isTeacher()` etc. retained for query scoping. |
| Laravel Policies | ❌ Not Started | `app/Policies/` directory exists but contains only `.gitkeep`. No policy files. |
| Student invite system | 🔧 Partial | No `invites` table or invite codes. However a **shareable self-registration link** (`/register/{school:slug}`) is displayed and copyable in the admin students page, achieving similar onboarding flow without token-based invites. |
| Teacher management (add/edit teachers) | ✅ Done | `app/Livewire/Teachers.php` + `resources/views/livewire/teachers.blade.php`; full CRUD (create, edit, delete); shows school_admin and teacher roles; prevents self-delete; route `GET /teachers` in `web.php`. Wired to `StoreTeacherRequest`. |

---

### 3. Teacher Dashboard & Halaqah Management

> **Architecture note:** The codebase uses `Group` (not `Halaqah`) as the primary grouping entity, and `Lesson` (not `Session`) as the scheduling entity. This diverges from the ERD naming convention.

| Feature | Status | Evidence |
|---------|--------|----------|
| Weekly timetable / schedule view | ✅ Done | `Dashboard` Livewire component + `livewire/dashboard.blade.php`; **7-column Sun–Sat grid** (Sunday-first per Islamic/Arab convention); `startOfWeek(Carbon::SUNDAY)`; prev/next week navigation; lessons grouped by `day_of_week` (ISO 1–7). Route `GET /dashboard`. |
| Lesson detail modal (from dashboard) | ✅ Done | `LessonModal` Livewire component (`app/Livewire/LessonModal.php`) + `livewire/lesson-modal.blade.php`; embedded in dashboard via `@livewire('lesson-modal', ...)`. Tabs: Attendance, Assignments. |
| Create/Edit Group (Halaqah) | ✅ Done | `Groups` Livewire component (`app/Livewire/Groups.php`) + `livewire/groups.blade.php`; full CRUD (create, edit, delete); teacher assignment; student management panel with checkbox sync. Route `GET /groups`. Wired to `StoreGroupRequest`. |
| Delete Group | ✅ Done | `delete()` method in `Groups.php` with `wire:confirm` in view. |
| Manage students in group | ✅ Done | `manageStudents()` + `syncStudents()` in `Groups.php`; checkbox UI with `group_student` pivot sync. |
| Create/Edit Lesson (scheduling) | ✅ Done | `app/Livewire/Schedule/LessonForm.php` + `resources/views/livewire/schedule/lesson-form.blade.php`; full CRUD with group/teacher/day/time/room/status fields; auto-fills teacher from selected group; route `GET /lessons` in `web.php`. Wired to `StoreLessonRequest`. |
| Archive/soft-delete Group | ❌ Not Started | `Group` model has no `SoftDeletes` trait. No archive logic. |
| Recurring schedule rules | ❌ Not Started | No `recurring_rules` table, no `RecurringRule` model. Lessons have `day_of_week` but no recurring instance generation. |
| Halaqah type (hifz/murajaah/tilawah) | ❌ Not Started | `Group` model has no `type` column. ERD `halaqat.type` not implemented. |
| Halaqah color coding | ❌ Not Started | No `color_hex` column on `groups`. Dashboard cards hardcoded to indigo. |
| Teacher dashboard stats/summary cards | ❌ Not Started | Dashboard shows schedule only. No student count, assignment count, or attendance summary cards. |

---

### 4. Student Management

| Feature | Status | Evidence |
|---------|--------|----------|
| List students | ✅ Done | `Students` Livewire component (`app/Livewire/Students.php`) + `livewire/students.blade.php`; lists students for current school. Route `GET /students`. |
| Add student | ✅ Done | `save()` method in `Students.php` creates `User` with `role=student`; form with name, email, password. Wired to `StoreStudentRequest`. |
| Edit student | ✅ Done | `edit()` + `save()` with `editingId` in `Students.php`; email unique rule respects editing context via `StoreStudentRequest::rulesFor($this->editingId)`. |
| Delete student | ✅ Done | `delete()` in `Students.php` with `wire:confirm` in view. |
| Student profile (avatar, phone, locale) | 🔧 Partial | **Added:** `phone` (guardian mobile), `guardian_name`, `age` columns on `users` via migrations `2026_04_18_000001–000004`. Admin form and self-registration form both collect these. Still missing: `avatar_url`, `locale`, `timezone`, `is_active`, `last_login_at`. |
| Student sibling management | ✅ Done | `student_siblings` pivot table (`2026_04_18_000002`); `User::siblings()` BelongsToMany self-referencing relationship; admin student form shows checkbox grid of other students when editing; `syncSiblings()` maintains symmetric links (A↔B); sibling badge pills shown in students table. |
| Student portal (read-only view) | ❌ Not Started | `app/Livewire/Student/` directory exists but contains only `.gitkeep`. No student-facing layout or views. |
| Student progress view | ❌ Not Started | No progress tracking UI for students. |
| Student self-registration | ✅ Done | `StudentRegister` Livewire component; public route `/register/{school:slug}`; collects student name + age and guardian name + phone + email; no password required; success confirmation screen; bilingual (AR/EN). |
| Student search | 🔧 Partial | Client-side Alpine.js search in students table (filters by name and phone). No server-side pagination. |

---

### 5. Session / Class Scheduling

| Feature | Status | Evidence |
|---------|--------|----------|
| Weekly calendar view | ✅ Done | `Dashboard.php` renders a **7-day Sun–Sat grid**. `Lesson` model has `day_of_week` (ISO tinyInt 1–7), `start_time`, `end_time`, `room`, `title`. Day dropdown in `LessonForm` also ordered Sun-first. |
| Create lesson (add to schedule) | ✅ Done | `LessonForm::save()` with `Lesson::create()`; form includes all scheduling fields. (`app/Livewire/Schedule/LessonForm.php`, route `GET /lessons`) |
| Edit lesson | ✅ Done | `LessonForm::edit()` + `save()` with `editingId`; pre-fills all fields including status. |
| Delete / cancel lesson | ✅ Done | `LessonForm::delete()` calls `Lesson::findOrFail($id)->delete()`; auto-scoped by `BelongsToTenant`; `wire:confirm` in view. |
| Drag-and-drop reschedule | ❌ Not Started | Alpine.js sort plugin installed but no drag-and-drop calendar implemented. |
| Session status (scheduled/completed/cancelled) | ✅ Done | `status` string column (default `'scheduled'`) added via `2026_04_17_000001_add_status_to_lessons_table.php`; in `Lesson::$fillable`; displayed and editable in `LessonForm` view. |
| Recurring session generation | ❌ Not Started | No `RecurringRule` model or generator. |
| Session timezone handling | ❌ Not Started | `Lesson` stores no timezone. ERD `sessions.timezone` not implemented. |

---

### 6. Assignment & Memorization Tracking

| Feature | Status | Evidence |
|---------|--------|----------|
| Create assignment (from lesson modal) | ✅ Done | `createAssignment()` in `LessonModal.php`; creates `Assignment` record + attaches all group students with `status=pending` via `assignment_student` pivot. Validated via `StoreAssignmentRequest`. |
| List assignments per lesson | ✅ Done | `lesson-modal.blade.php` Assignments tab; lists assignments with title, type badge, surah reference, status badge, due date, and done-count. |
| Assignment due date | ✅ Done | `due_date` column on `assignments` table; shown in lesson modal view. |
| Mark individual assignment done (student-side) | ❌ Not Started | `assignment_student` pivot has `status` and `note` columns, but there is no UI for a student to update their own status. |
| Grade assignment (1–5 scale) | 🔧 Partial | `grade` tinyint column added to `assignments` table via `2026_04_17_000003_add_quran_fields_to_assignments_table.php` and in `Assignment::$fillable`. However no UI exposes the grade field — grading panel uses `assignment_student` pivot `status`/`note` only. |
| Quran-specific assignment fields | ✅ Done | `type`, `surah_number`, `start_ayah`, `end_ayah`, `juz_number` columns added via migration; `Surah` model + `surahs` table seeded with all 114 Surahs; `Assignment::surah()` BelongsTo; `LessonModal` shows type radio, surah dropdown, ayah range inputs. |
| Assignment status update by teacher | ✅ Done | Grading panel in `LessonModal.php`: `openGrading()`, `saveGrades()`, `closeGrading()`; per-student status (pending/done/needs_repeat) + note via `updateExistingPivot()`; auto-marks assignment `completed` when all students are done. |
| Delete assignment | ❌ Not Started | No delete assignment UI. |
| Form Request validation on assignment creation | ✅ Done | `app/Http/Requests/StoreAssignmentRequest.php`; wired in `LessonModal::createAssignment()` via `$this->validate((new StoreAssignmentRequest())->rules())`. |

---

### 7. Progress & Reporting

| Feature | Status | Evidence |
|---------|--------|----------|
| Attendance tracking | ✅ Done | `saveAttendance()` in `LessonModal.php`; `Attendance::updateOrCreate()` with date + student + lesson key; status: present/absent/late; date picker in view. |
| Attendance list per lesson | ✅ Done | Attendance tab in `lesson-modal.blade.php`; loads students and existing records for selected date. |
| Progress snapshots | ❌ Not Started | No `progress_snapshots` table, no `ProgressSnapshot` model, no snapshot command. |
| Student progress history | ❌ Not Started | No history view. No `ComputeSnapshots` artisan command. |
| Memorization tracking (ayahs/juz/pages) | ❌ Not Started | Quran fields exist on assignments but no progress rollup or per-student memorization ledger. |
| Attendance summary / report | ❌ Not Started | No aggregate attendance report. |
| PDF export | ❌ Not Started | `barryvdh/laravel-dompdf` installed but no PDF generation logic. |
| Chart.js dashboards | ❌ Not Started | `chart.js ^4.4.6` installed but no chart views implemented. |
| Progress percentage calculations | ❌ Not Started | No service or computed property for progress. |

---

### 8. Notifications & Alerts

| Feature | Status | Evidence |
|---------|--------|----------|
| Notification infrastructure | ❌ Not Started | `app/Notifications/` exists with `.gitkeep` only. No notification classes. |
| Session reminder | ❌ Not Started | No `SessionReminder` notification class. |
| Assignment due reminder | ❌ Not Started | No `AssignmentDue` notification class. |
| In-app notification bell | ❌ Not Started | `app/Livewire/Notification/` exists with `.gitkeep` only. |
| Email notifications | ❌ Not Started | `resources/views/emails/` directory exists but empty. No mail templates. |
| Notification preferences | ❌ Not Started | No `notification_preferences` table. |
| Laravel notifications table | ❌ Not Started | `php artisan notifications:table` not yet run. |
| Scheduled reminders (artisan commands) | ❌ Not Started | `app/Console/Commands/` contains only `.gitkeep`. |

---

### 9. Localization (Arabic / English)

| Feature | Status | Evidence |
|---------|--------|----------|
| Lang files created (7 locales) | 🔧 Partial | `lang/ar.json` and `lang/en.json` fully populated (~120 keys each covering nav.*, setup.*, auth.*, dashboard.*, lessons.*, groups.*, teachers.*, students.*, modal.*). `de.json`, `tr.json`, `ur.json`, `ms.json`, `fr.json` still contain empty `{}`. |
| SetLocale middleware logic | ✅ Done | `app/Http/Middleware/SetLocale.php` registered in `bootstrap/app.php` via `->web(append:[\App\Http\Middleware\SetLocale::class])`; resolves locale from user pref → cookie → Accept-Language → fallback `'ar'`. |
| Default locale set to Arabic | ✅ Done | `APP_LOCALE=ar` in `.env.example`; `'locale' => env('APP_LOCALE', 'ar')` in `config/app.php`. |
| RTL layout (`dir="rtl"`) | ✅ Done | `resources/views/components/layouts/app.blade.php` — `<html dir="{{ in_array(app()->getLocale(), ['ar','ur']) ? 'rtl' : 'ltr' }}" lang="{{ app()->getLocale() }}">`. |
| `__()` wrappers on UI strings | ✅ Done | All 9 view files use `__()` (140 occurrences): `app.blade.php`, `login.blade.php`, `setup.blade.php`, `dashboard.blade.php`, `lesson-modal.blade.php`, `schedule/lesson-form.blade.php`, `groups.blade.php`, `students.blade.php`, `teachers.blade.php`. |
| Logical CSS properties (ms-/me-) | ✅ Done | All app views updated: `text-start`/`text-end` replace `text-left`/`text-right`; `me-2` replaces `mr-2`; 53 logical property occurrences across 8 files (confirmed via grep). |
| Arabic-Indic numerals | ❌ Not Started | No `NumberFormatter` or Arabic numeral rendering. |
| Hijri calendar display | ❌ Not Started | Not implemented. No Hijri library installed. |
| Quran Uthmani font | ❌ Not Started | No font files, no `.quran-text` CSS class, no font-face declarations. |

---

### 10. Admin Panel

| Feature | Status | Evidence |
|---------|--------|----------|
| School setup (initial) | ✅ Done | `Setup` Livewire component creates school + first admin. |
| Teacher management | ✅ Done | `app/Livewire/Teachers.php` + `resources/views/livewire/teachers.blade.php`; add/edit/delete teachers; role badge (Admin/Teacher); "(you)" marker prevents self-delete; `GET /teachers` route. |
| Multi-teacher school management | ❌ Not Started | No admin panel beyond initial setup. |
| Spatie Roles/Permissions management UI | ❌ Not Started | Spatie permissions package installed but not configured. No admin UI for roles. |
| Audit log viewer | ❌ Not Started | `spatie/laravel-activitylog` installed but unused. No audit log UI. |
| Backup management | ❌ Not Started | `spatie/laravel-backup` installed but unconfigured. |
| Super admin panel | ❌ Not Started | `isSuperAdmin()` helper exists on User model but no super admin views or routes. |
| School settings page | ❌ Not Started | No settings view or form. |

---

## Section 3: Migration Checklist

Cross-referencing the ERD entities against actual migration files:

| ERD Entity | Migration File | Status | Notes |
|------------|---------------|--------|-------|
| `users` | `0001_01_01_000000_create_users_table.php` + `2024_01_01_000002_add_school_id_role_to_users_table.php` + `2026_04_18_000001_add_phone_to_users_table.php` + `2026_04_18_000003_add_guardian_name_to_users_table.php` + `2026_04_18_000004_add_age_to_users_table.php` | 🔧 Partial | Has `school_id`, `role`, `phone`, `guardian_name`, `age`. Missing: `locale`, `timezone`, `avatar_url`, `is_active`, `last_login_at`, `auth_provider`. |
| `schools` (tenants) | `2024_01_01_000001_create_schools_table.php` | 🔧 Partial | Has `name`, `slug`. Missing: `plan`, `settings`, `is_active`, `address`, `city`, `country`, `logo_url`, `deleted_at`. |
| `groups` (halaqat) | `2024_01_01_000003_create_groups_table.php` | 🔧 Partial | Has core fields. Missing: `type`, `color_hex`, `max_students`, `recurring_schedule`, `is_active`, `is_archived`, `deleted_at`. |
| `group_student` (halaqah_student) | `2024_01_01_000004_create_group_student_table.php` | 🔧 Partial | Has `group_id`, `user_id`. Missing: `status`, `sort_order`, `enrolled_at`, `withdrawn_at`. |
| `lessons` (sessions) | `2024_01_01_000005_create_lessons_table.php` + `2026_04_17_000001_add_status_to_lessons_table.php` | 🔧 Partial | Has core schedule fields + `status` (default `'scheduled'`). Missing: `teacher_notes`, `is_recurring_instance`, `recurring_rule_id`, `deleted_at`, `timezone`. |
| `assignments` | `2024_01_01_000006_create_assignments_table.php` + `2026_04_17_000003_add_quran_fields_to_assignments_table.php` | 🔧 Partial | Has `lesson_id`, `title`, `description`, `due_date`, `type`, `surah_number`, `start_ayah`, `end_ayah`, `juz_number`, `status`, `grade`. Missing: `teacher_notes`, `student_visible_notes`, `completed_at`, `deleted_at`. |
| `assignment_student` | `2024_01_01_000007_create_assignment_student_table.php` | ✅ Done | Has `assignment_id`, `student_id`, `status`, `note`. Fully matches needs. |
| `attendances` | `2024_01_01_000008_create_attendances_table.php` | 🔧 Partial | Has `lesson_id`, `student_id`, `date`, `status`. Unique constraint present. Missing: `note`, `marked_at` (uses `created_at`). |
| `tenants` (separate) | — | ❌ Not Started | No separate `tenants` table. School doubles as tenant. |
| `roles` | `2026_04_18_113224_create_permission_tables.php` | ✅ Done | Spatie permission tables created via `vendor:publish` + `migrate`. |
| `permissions` | `2026_04_18_113224_create_permission_tables.php` | ✅ Done | 20 permissions seeded via `RoleSeeder`. |
| `model_has_roles` | `2026_04_18_113224_create_permission_tables.php` | ✅ Done | Pivot for user↔role; all existing users backfilled. |
| `role_has_permissions` | `2026_04_18_113224_create_permission_tables.php` | ✅ Done | Pivot for role↔permission; populated by `RoleSeeder`. |
| `sessions` (Laravel built-in) | `0001_01_01_000000_create_users_table.php` | ✅ Done | Laravel session table (not Halaqah sessions). |
| `cache` | `0001_01_01_000001_create_cache_table.php` | ✅ Done | Standard Laravel cache table. |
| `jobs` / `job_batches` / `failed_jobs` | `0001_01_01_000002_create_jobs_table.php` | ✅ Done | Queue infrastructure. |
| `password_reset_tokens` | `0001_01_01_000000_create_users_table.php` | ✅ Done | In users migration. |
| `recurring_rules` | — | ❌ Not Started | No model, no migration. |
| `progress_snapshots` | — | ❌ Not Started | No model, no migration. |
| `surahs` | `2026_04_17_000002_create_surahs_table.php` | ✅ Done | `app/Models/Surah.php` with `$incrementing=false`; `database/seeders/SurahSeeder.php` inserts all 114 Surahs (Arabic/English names, ayah count, juz, page, revelation type). |
| `notifications` | — | ❌ Not Started | `php artisan notifications:table` not run. |
| `notification_preferences` | — | ❌ Not Started | |
| `student_siblings` | `2026_04_18_000002_create_student_siblings_table.php` | ✅ Done | Self-referencing pivot: `student_id`, `sibling_id`, both FK to `users` with cascade delete. Composite PK. |
| `invites` | — | ❌ Not Started | No `Invite` model, no `invites` table. Self-registration link used instead. |
| `audit_logs` | — | ❌ Not Started | `spatie/laravel-activitylog` installed but no migrations run. |

---

## Section 4: Progress Summary

### Counts Per Category

| Category | ✅ Done | 🔧 Partial | ❌ Not Started | 📦 Pkg Only |
|----------|---------|-----------|--------------|------------|
| 1. Multi-tenancy & Organization | 3 | 1 | 2 | 0 |
| 2. Authentication & Authorization | 5 | 2 | 3 | 0 |
| 3. Teacher Dashboard & Halaqah Mgmt | 6 | 0 | 5 | 0 |
| 4. Student Management | 6 | 2 | 2 | 0 |
| 5. Session / Class Scheduling | 5 | 0 | 3 | 0 |
| 6. Assignment & Memorization Tracking | 6 | 1 | 2 | 0 |
| 7. Progress & Reporting | 2 | 0 | 7 | 0 |
| 8. Notifications & Alerts | 0 | 0 | 8 | 0 |
| 9. Localization (Arabic/English) | 5 | 1 | 3 | 0 |
| 10. Admin Panel | 2 | 0 | 6 | 0 |
| **TOTAL** | **40** | **7** | **41** | **0** |

### Overall Completion Estimate

| Metric | Count | % |
|--------|-------|---|
| Fully Done (✅) | 40 | 45% |
| Partial (🔧) | 7 | 8% |
| Not Started (❌) | 41 | 47% |
| Pkg Only (📦) | 0 | 0% |
| **Total features tracked** | **88** | — |

> **Overall project completion: ~50%** (counting Partial as 50% credit: 40 + 3.5 = 43.5 / 88)

---

### Top Remaining Priority Actions

These are ordered by impact-to-effort ratio:

#### ~~Priority 1 — Run Spatie Permission Migrations + Apply HasRoles to User~~ ✅ DONE
Migrations run, `HasRoles` on `User`, `RoleSeeder` with 4 roles + 20 permissions, routes protected, all users backfilled.

#### Priority 2 — Implement User Registration + Password Reset
Currently no teacher can self-register. `laravel/breeze` is in `require-dev` but **Breeze was never scaffolded**.
- Evidence of gap: No `GET /register` route, no register view, no password reset flow.
- Acceptance: Teacher can register with name/email/password and log in.

#### Priority 3 — Populate Remaining 5 Locale Files
Arabic and English are fully translated. German, Turkish, Urdu, Malay, and French `lang/*.json` files still contain empty `{}`.
- Files to update: `lang/de.json`, `lang/tr.json`, `lang/ur.json`, `lang/ms.json`, `lang/fr.json` — copy all ~120 keys from `en.json` and translate.
- Acceptance: Switching locale to `de` renders German UI strings.

#### Priority 4 — Add Laravel Policies
`app/Policies/` exists but is empty. Without policies, any authenticated user can edit any resource (no ownership checks).
- Files to create: `HalaqahPolicy`, `LessonPolicy`, `AssignmentPolicy`; register in `AuthServiceProvider`.
- Acceptance: A teacher cannot edit another teacher's group.

#### Priority 5 — Progress & Reporting Foundation
The platform collects attendance and assignment data but provides zero reporting. Add at minimum an attendance summary view and per-student memorization progress.
- Files to create: `app/Services/ProgressService.php`; a reporting Livewire component; Chart.js integration.
- Acceptance: Teacher can see attendance rate per student per group.

---

## Appendix: File Inventory Summary

### Models (7 actual, 2 with BelongsToTenant applied)

| Model | Migration | BelongsToTenant | Notes |
|-------|-----------|-----------------|-------|
| `School` | ✅ | ❌ | Acts as tenant; missing ERD columns |
| `User` | ✅ | ❌ | Missing locale, timezone, etc. |
| `Group` | ✅ | ✅ | Represents Halaqah; missing type, color, archived |
| `Lesson` | ✅ | ✅ | Represents Session; has status; missing timezone |
| `Assignment` | ✅ | ❌ | Has all Quran fields; missing teacher_notes, completed_at |
| `Attendance` | ✅ | ❌ | Functional for basic use |
| `Surah` | ✅ | ❌ | Reference data, 114 rows seeded; not tenant-scoped (correct) |

### Active Livewire Components (8 active, 4 empty placeholder directories)

| Component | Route | View | Status |
|-----------|-------|------|--------|
| `Auth\Login` | `GET /login` | ✅ | ✅ Functional |
| `Auth\StudentRegister` | `GET /register/{school:slug}` | ✅ | ✅ Functional |
| `Setup` | `GET /setup` | ✅ | ✅ Functional |
| `Dashboard` | `GET /dashboard` | ✅ | ✅ Functional |
| `Groups` | `GET /groups` | ✅ | ✅ Functional |
| `Students` | `GET /students` | ✅ | ✅ Functional |
| `Teachers` | `GET /teachers` | ✅ | ✅ Functional |
| `Schedule\LessonForm` | `GET /lessons` | ✅ | ✅ Functional |
| `LessonModal` | embedded in Dashboard | ✅ | ✅ Functional |
| `Assignment/*` | — | ❌ | Empty `.gitkeep` dir |
| `Student/*` | — | ❌ | Empty `.gitkeep` dir |
| `Notification/*` | — | ❌ | Empty `.gitkeep` dir |
| `Halaqah/*` | — | ❌ | Empty `.gitkeep` dir |

### Form Request Classes (5 created)

| Class | Used By | Notes |
|-------|---------|-------|
| `StoreGroupRequest` | `Groups.php` `rules()` | Group CRUD validation |
| `StoreLessonRequest` | `Schedule\LessonForm.php` `rules()` | Lesson CRUD validation |
| `StoreAssignmentRequest` | `LessonModal.php` `createAssignment()` | Assignment creation validation |
| `StoreStudentRequest` | `Students.php` `rules()` | Student CRUD; `rulesFor(?int)` for dynamic unique email |
| `StoreTeacherRequest` | `Teachers.php` `rules()` | Teacher CRUD; `rulesFor(?int)` for dynamic unique email |

### Still Missing (Referenced in ADR but Not Yet Created)

- `app/Livewire/Assignment/` — assignment management outside lesson modal
- `app/Livewire/Student/` — student-facing dashboard + progress
- `app/Livewire/Notification/` — bell component
- `app/Services/` — QuranService, ScheduleService, ProgressService (only `.gitkeep`)
- `app/Notifications/` — SessionReminder, AssignmentDue (only `.gitkeep`)
- `app/Console/Commands/` — ComputeSnapshots, SendReminders (only `.gitkeep`)
- `app/Policies/` — HalaqahPolicy, AssignmentPolicy (only `.gitkeep`)
- `resources/views/emails/` — mail templates (directory exists, empty)
- `database/factories/` — model factories
- ~~`database/seeders/RoleSeeder.php` — Spatie role seeder~~ ✅ Created
- `lang/de.json`, `lang/tr.json`, `lang/ur.json`, `lang/ms.json`, `lang/fr.json` — still empty `{}`

---

*End of PRD — Updated 2026-04-18. Previous version: 4.1.0 (48% completion). Current version: 4.2.0 (50% completion). Sprint delivered: Spatie RBAC fully activated (migrations, HasRoles, RoleSeeder, route protection, user backfill); 7-day Sun–Sat calendar grid with Sunday-first ordering per Islamic/Arab convention.*
