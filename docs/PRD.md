# Ilmora — Product Requirements Document (PRD)

**Version:** 2.0.0-draft
**Date:** April 15, 2026
**Authors:** Architecture Team
**Classification:** Internal — Confidential
**Previous Name:** Hifz Hub → renamed to **Ilmora** in repository

---

## 1. Product Vision & Mission

**Vision:** Become the global standard for Quran education management — the tool every Mu'allim and Mu'allimah reaches for to run their Halaqat with excellence.

**Mission:** Empower Quran teachers worldwide with a beautiful, purpose-built platform that eliminates administrative friction so they can focus entirely on teaching the Book of Allah.

**Product Name:** **Ilmora** (عِلْمُورَا)

**Positioning Statement:** Ilmora is the first production-grade SaaS platform purpose-built for Quran education management. Where generic school management tools force Quran teachers into awkward workarounds, Ilmora speaks their language — literally and conceptually. It understands Halaqat, Hifz portions, Muraaja'ah (revision) schedules, and the rhythms of Quran teaching.

**Technical Foundation:** Built on Laravel 11 with Livewire 3, Alpine.js, and Tailwind CSS (the TALL stack), Ilmora leverages server-rendered, reactive components to deliver a fast, accessible experience even on low-bandwidth connections common in developing regions. Blade templating with Livewire reactivity provides the interactivity of an SPA without the complexity of a separate frontend framework.

## 2. Target Users & Personas

### Persona 1: Ustadha Fatimah (Teacher — Primary User)
- **Age:** 28–55
- **Context:** Teaches 3–5 Halaqat with 5–15 students each, either at a mosque, Islamic center, or online
- **Pain points:** Tracks everything in WhatsApp groups and paper notebooks; loses track of who memorized what; spends 30+ minutes daily on admin instead of teaching
- **Tech comfort:** Uses smartphone daily, comfortable with WhatsApp and basic apps; may not be comfortable with complex software
- **Goal:** See her entire week at a glance, assign work in seconds, track progress without notebooks

### Persona 2: Student Ahmad (Student — Secondary User)
- **Age:** 8–45 (wide range: children, university students, adults)
- **Context:** Attends 2–3 Halaqah sessions per week
- **Pain points:** Forgets what was assigned; doesn't know exactly what to prepare for next session
- **Tech comfort:** Varies wildly — may be a child using a parent's phone or an adult with full smartphone fluency
- **Goal:** Know exactly what to prepare, see upcoming sessions, view their own progress

### Persona 3: Sheikh Abdullah (School Administrator)
- **Age:** 35–60
- **Context:** Manages a Quran school with 10–50 teachers and hundreds of students
- **Pain points:** No visibility into teacher performance or student progress across the institution; relies on verbal reports
- **Goal:** Institutional-level dashboards, teacher management, aggregate reporting

### Persona 4: Parent Khadijah (Future — v2.0)
- **Age:** 30–50
- **Context:** Has children enrolled in Halaqat
- **Goal:** Track her child's Quran memorization progress, receive notifications

## 3. Feature Breakdown

### P0 — MVP (Must Ship)

#### 3.1 Teacher Dashboard & Halaqah Management
- Create, edit, and archive Halaqat
- Add/remove students to Halaqat
- Set Halaqah schedule (recurring weekly time slots)
- Halaqah settings: name, description, type (Hifz, Muraaja'ah, Tilawah, Tafsir)
- **Implementation:** Livewire components with full-page Blade layouts; Eloquent models with soft deletes

#### 3.2 Weekly Schedule View (Core UI)
- **This is the heart of the product.** A clean, responsive weekly calendar showing all sessions across all Halaqat
- Day columns (Saturday–Friday for Islamic week, configurable)
- Time slots with color-coded Halaqat
- Click into any session to see/edit student assignments
- Drag-and-drop session rescheduling via Alpine.js + Livewire wire:sortable or custom Alpine component
- Quick-add session from any empty slot
- Mobile: swipeable day view with expandable session cards
- **Implementation:** Livewire component for calendar state, Alpine.js for drag-and-drop interactions, Blade partial for session cards

#### 3.3 Student Assignment & Tracking System
- Per-student, per-session assignment creation:
  - **Hifz (New Memorization):** Specify Surah, start Ayah, end Ayah
  - **Muraaja'ah (Revision):** Specify Juz or Surah range
  - **Tilawah (Recitation):** Specify page range or Surah
- Quran-aware input: autocomplete Surah names (Arabic + transliteration), validate Ayah ranges
- Grading per assignment: scale of 1–5 or custom (Excellent/Good/Needs Work/Repeat)
- Teacher notes per student per session (private, not visible to student)
- Completion status: Not Started → In Progress → Completed → Needs Repeat
- Bulk assignment: assign the same portion to all students in a Halaqah
- **Implementation:** Livewire forms with Zod-like validation via Laravel Form Requests; Eloquent relationships for student-session-assignment chain

#### 3.4 Student Read-Only Portal
- Login with code or link (no complex registration for younger students)
- View upcoming sessions with date/time
- View assignments for each session
- View personal progress history (what they've memorized, revision log)
- Cannot edit, delete, or add anything
- **Implementation:** Separate Blade layout with read-only Livewire components; Laravel middleware enforcing read-only access for student role

#### 3.5 Authentication & User Management
- Teacher registration and login (email + password)
- Role-based access: Teacher, Student, Admin
- Invite students via link or code
- Profile management (name, avatar, preferred language)
- **Implementation:** Laravel's built-in authentication (to be decided: Breeze, Fortify, or custom — see ADR-004). Middleware-based role guards. Laravel Gates and Policies for authorization.

### P1 — Post-MVP (Ship Within 2 Months of Launch)

#### 3.6 Notification System
- Email notifications for session reminders (configurable: 1 hour, 1 day before)
- Assignment due reminders
- Teacher notes shared with student (opt-in)
- In-app notification center
- **Implementation:** Laravel Notifications (mail channel initially, database channel for in-app). Scheduled commands via `php artisan schedule:run` for timed reminders. Queue workers (upgrade from sync to database or Redis driver) for async dispatch.

#### 3.7 Multi-Language Support
- Arabic (primary — RTL)
- English
- German
- Turkish
- Urdu
- Malay
- French
- Language switcher in UI; user preference saved
- **Implementation:** Laravel's `lang/` directory with JSON translation files per locale. `__()` and `@lang()` helpers in Blade templates. Livewire components respect current locale via middleware.

#### 3.8 RTL/LTR Layout Support
- Full bidirectional layout support
- Arabic-first design: RTL is the default, LTR is the adaptation
- Mixed-direction content handling (Arabic Quran text within English UI)
- **Implementation:** Tailwind CSS logical properties (`ms-*`, `me-*`, `ps-*`, `pe-*`). `dir` attribute set on `<html>` tag via Blade layout based on current locale. Quran text components always render with `dir="rtl"` regardless of UI locale.

#### 3.9 Reporting & Analytics for Teachers
- Per-student progress dashboard:
  - Total Ayahs memorized over time
  - Revision frequency and coverage
  - Attendance rate
  - Grade trends
- Per-Halaqah summary: average progress, attendance patterns
- Exportable reports (PDF)
- **Implementation:** Eloquent aggregation queries. Chart rendering via a lightweight JS charting library (Chart.js) embedded in Blade/Livewire. PDF export via Laravel DomPDF or Browsershot.

#### 3.10 Attendance Tracking
- Mark attendance per session: Present, Absent, Excused, Late
- Attendance history per student
- Attendance patterns in analytics
- **Implementation:** Livewire component for quick attendance marking; Eloquent model with session/student pivot.

### P2 — v1.5+ (Future Enhancements)

#### 3.11 Admin Panel for Institutions
- Multi-teacher management under one school
- School-level dashboards and aggregate reporting
- Teacher assignment to Halaqat by admin
- Student transfers between Halaqat

#### 3.12 Parent Portal
- Linked to student account
- View child's progress, attendance, assignments
- Receive notifications

#### 3.13 AI-Assisted Features
- Smart revision scheduling (spaced repetition algorithm for Muraaja'ah)
- Progress predictions ("At this pace, student will complete Juz 30 by...")
- Suggested assignment portions based on student capacity

#### 3.14 Gamification for Students
- Memorization streaks
- Milestone badges (completed first Juz, completed Surah Al-Baqarah, etc.)
- Progress visualization (Quran map showing what's been memorized)

#### 3.15 Mobile App
- Native mobile experience (Flutter or React Native)
- Offline mode with sync

#### 3.16 PWA Capability
- Service worker for offline caching of schedule and assignments
- Push notifications via web push
- **Implementation:** Laravel PWA package or custom service worker integrated with Vite build

## 4. Non-Functional Requirements

### Performance
- Initial page load: < 2 seconds on 3G networks (critical for developing regions)
- Time to Interactive: < 3 seconds
- Livewire round-trip updates: < 300ms p95
- Weekly schedule view must render smoothly with 50+ sessions
- Leverage Livewire's lazy loading and wire:init for progressive rendering

### Scalability
- Support 10,000 concurrent teachers at launch architecture
- Design for 1M+ student accounts within 2 years
- Multi-tenancy from day one
- Laravel's queue system for background processing at scale (upgrade to Redis/Horizon when needed)

### Accessibility
- WCAG 2.1 AA compliance
- Screen reader support (critical for visually impaired Quran students)
- Keyboard navigation throughout
- High contrast mode
- Minimum touch target: 44x44px on mobile

### Security
- SOC 2 Type I readiness
- GDPR compliance (German users)
- PDPL compliance (Saudi users)
- Data encryption at rest (MySQL transparent data encryption) and in transit (TLS 1.3)
- Laravel's built-in CSRF protection, SQL injection prevention via Eloquent, XSS prevention via Blade escaping
- Regular penetration testing

### Reliability
- 99.9% uptime SLA
- Automated backups every 6 hours (MySQL dumps + storage backups)
- Disaster recovery plan with < 4 hour RTO

## 5. Success Metrics & KPIs

| Metric | Target (6 months post-launch) |
|--------|-------------------------------|
| Registered teachers | 500 |
| Monthly active teachers | 200 |
| Students per teacher (avg) | 12 |
| Weekly schedule views per teacher | 10+ |
| Session completion rate | > 80% |
| Teacher retention (monthly) | > 85% |
| NPS score | > 50 |
| Average page load time | < 1.5s |

## 6. Monetization Strategy (Recommendation)

- **Free Tier:** 1 teacher, up to 2 Halaqat, 15 students — forever free
- **Pro Tier ($7/month):** Unlimited Halaqat, unlimited students, analytics, notifications
- **Institution Tier ($49/month):** Multi-teacher admin, aggregate reporting, priority support
- **Open Core Model:** Core platform is open source (AGPL); premium features (analytics, admin panel, AI features) are proprietary SaaS add-ons

---

## Implementation Gaps

The following items are referenced in this PRD but are **not yet present** in the repository:

1. **Authentication system** — No auth package installed yet (no Breeze, Fortify, Sanctum, or Jetstream in composer.json). Must be added before any user-facing feature.
2. **Database migrations** — No migration files uploaded/visible. All entities from the ERD need migrations.
3. **Queue infrastructure** — Currently set to `sync`. Must upgrade to `database` or `redis` driver before notifications can work asynchronously.
4. **Caching** — Currently `file` driver. Acceptable for MVP, but Redis recommended for production.
5. **No Quran reference data** — Surah/Ayah seed data not yet created.
6. **No i18n setup** — No `lang/` files visible. Arabic translation files needed as source of truth.
7. **No OAuth/Social login** — Only email/password auth path available with current setup.