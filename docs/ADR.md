# Ilmora — Architecture Decision Records (ADRs)

**Version:** 2.0.0-draft
**Date:** April 15, 2026
**Authors:** Architecture Team
**Classification:** Internal — Confidential

---

## ADR-001: Frontend Framework Selection

**Status:** Accepted — *Supersedes original decision (Next.js 15 with React)*

**Context:**
We need a frontend approach for a global, multilingual, RTL-first web application. The core UI is a weekly calendar view with drag-and-drop, reactive updates, and session/assignment management. Our team is two full-stack developers. The original documentation proposed Next.js 15 (React) as the frontend framework, but the repository was initialized with a different approach.

**Decision: Livewire 3 + Alpine.js + Tailwind CSS 3.4 (TALL Stack)**

The repository's `composer.json` includes `livewire/livewire: ^3.0`, and `package-lock.json` confirms `alpinejs: ^3.15.11` with `tailwindcss: ^3.4.19`. This is the TALL stack — a Laravel-native full-stack approach.

**Rationale:**

1. **Zero Frontend Build Complexity:** Livewire components are PHP classes that render Blade templates with reactive state. There is no separate frontend application to build, deploy, or maintain. For a 2-person team, eliminating the frontend/backend split means one less deployment target, one less CI pipeline, and one less mental model.

2. **Server-Rendered by Default:** Every Livewire page is server-rendered HTML, which means excellent performance on low-bandwidth networks (critical for users in developing regions). No JavaScript bundle must download and hydrate before the page becomes interactive — the HTML arrives ready. This is superior to client-side React for our target demographic.

3. **RTL Support:** Tailwind CSS provides logical property utilities (`ms-*`, `me-*`, `ps-*`, `pe-*`) that work bidirectionally with a single `dir="rtl"` attribute on `<html>`. The `@tailwindcss/forms` plugin (present in devDependencies) provides consistent form styling across RTL/LTR.

4. **Alpine.js for Client-Side Interactivity:** Alpine handles the parts Livewire cannot — drag-and-drop calendar interactions, dropdown menus, modal transitions, and keyboard shortcuts. It's 15KB (vs React's 140KB+), loads instantly, and integrates natively with Livewire via `wire:` and `x-` directives on the same elements.

5. **Developer Productivity:** Both developers write PHP for both "backend" and "frontend" logic. No TypeScript, no JSX, no build errors from version mismatches between React and Next.js. Livewire components are testable with Laravel's test suite (`$this->livewire(ScheduleView::class)->assertSee(...)`).

6. **Progressive Enhancement:** Livewire forms work without JavaScript (graceful degradation). This matters for accessibility and for the rare user on an extremely limited device.

**Trade-offs vs. the Original Next.js Decision:**

| Aspect | Next.js (Original) | Livewire (Actual) |
|--------|--------------------|--------------------|
| Client interactivity | Rich (React ecosystem) | Good (Alpine.js, simpler) |
| Calendar drag-and-drop | @dnd-kit (battle-tested) | Custom Alpine.js (more work) |
| Bundle size | Large (React + Next.js) | Minimal (Alpine.js only) |
| SSR performance | Excellent (RSC) | Excellent (server-rendered HTML) |
| Team velocity | Slower (separate frontend) | Faster (single codebase) |
| Ecosystem for charts | Recharts, D3, etc. | Chart.js (sufficient) |
| Complexity | High (monorepo, two runtimes) | Low (single Laravel app) |

**Consequences:**
- Positive: Dramatically simpler architecture — one codebase, one deployment, one language
- Positive: Faster time-to-MVP (estimated 30% reduction in development time)
- Positive: No hydration mismatch bugs, no client/server state synchronization issues
- Positive: Smaller client payload, better performance on low-end devices
- Negative: Drag-and-drop calendar must be custom-built with Alpine.js (no off-the-shelf React DnD library)
- Negative: Limited to Livewire's polling/morphing for reactivity (no true client-side SPA navigation)
- Negative: Less ecosystem for complex data visualization (mitigated: Chart.js covers our needs)
- Mitigation: If calendar interactivity proves insufficient, a targeted Alpine.js plugin or Livewire's `wire:navigate` for SPA-like page transitions can address specific gaps without rewriting the entire frontend

**Frontend Stack (from repo):**
- **Reactivity:** Livewire 3
- **Client JS:** Alpine.js 3.15
- **Styling:** Tailwind CSS 3.4 + @tailwindcss/forms
- **Build:** Vite 6 via laravel-vite-plugin
- **HTTP:** Axios (available but primarily for any custom AJAX calls outside Livewire)

---

## ADR-002: Backend Framework Selection

**Status:** Accepted — *Supersedes original decision (NestJS / TypeScript)*

**Context:**
The original documentation proposed NestJS (TypeScript/Node.js) for the backend, citing full-stack TypeScript benefits and modular architecture. The repository was instead initialized with Laravel, a PHP framework.

**Decision: Laravel 11.31 (PHP 8.2+)**

**Rationale:**

1. **Battle-Tested for SaaS:** Laravel is the most mature full-stack framework in the PHP ecosystem, used by production SaaS products serving millions of users. It provides out-of-the-box solutions for every feature we need: authentication, authorization, queues, notifications, scheduling, caching, mail, file storage, and database migrations.

2. **Unified Codebase:** With Livewire as the frontend, Laravel becomes the entire application — not just the API backend. There is no frontend/backend split, no API serialization layer, no CORS configuration, no separate deployment pipeline. This is the single biggest architectural advantage for a 2-person team.

3. **Eloquent ORM:** Laravel's Eloquent provides expressive relationship modeling, eager loading to prevent N+1 queries, global scopes for multi-tenancy, attribute casting, model events for audit logging, and soft deletes — all declaratively. The weekly schedule query ("give me all sessions for this teacher's Halaqat between Monday and Friday with their assignments and attendance") is a single Eloquent query chain.

4. **Security Primitives:** Laravel provides CSRF protection (automatic on all forms), SQL injection prevention (Eloquent parameterized queries), XSS prevention (Blade's `{{ }}` auto-escaping), mass assignment protection (`$fillable`/$guarded`), rate limiting (built-in middleware), encryption (AES-256-CBC via `Crypt` facade), and hashing (Bcrypt/Argon2 via `Hash` facade). These are not add-ons — they are default behaviors.

5. **Scheduling & Queues:** Session reminder notifications, progress snapshot computation, and invite expiration checks are all cron-like tasks. Laravel's task scheduler (`$schedule->command('snapshots:compute')->dailyAt('03:00')`) handles this elegantly. The queue system can start with the `sync` driver (current repo setting) and upgrade to `database` or `redis` without code changes.

6. **Laravel Sail:** The repo includes `laravel/sail` for Docker-based local development. Both developers get identical environments regardless of OS, with MySQL, Redis, and Mailpit containers pre-configured.

7. **Developer Ecosystem:** Laravel has the richest package ecosystem in PHP — Spatie packages for permissions, activity logging, media library, and translatable models are production-grade and actively maintained. The community is massive, documentation is excellent, and Laracasts provides video tutorials for every feature.

**Why the Change from NestJS?**

The original NestJS decision optimized for TypeScript end-to-end. The Laravel decision optimizes for **speed of delivery and architectural simplicity**. For two full-stack developers building a feature-rich SaaS, having one codebase in one language with one deployment target is more valuable than type-safety across a frontend/backend boundary. The monorepo complexity, OpenAPI code generation, and two-runtime deployment pipeline proposed in the original ADRs would have consumed significant development time for marginal benefit at our team size.

**Consequences:**
- Positive: Single codebase, single deployment, single language — maximum velocity
- Positive: Laravel's batteries-included approach eliminates dozens of package selection decisions
- Positive: Largest PHP framework community, abundant learning resources
- Positive: Sail provides consistent dev environments across the team
- Negative: PHP has a less favorable reputation than TypeScript/Node.js in some developer communities (irrelevant to product quality)
- Negative: No shared types between frontend and backend (mitigated: Livewire eliminates the need — there is no API contract to maintain)
- Negative: PHP's type system is less strict than TypeScript (mitigated: PHP 8.2 enums, union types, readonly properties, and Laravel Pint for code style enforcement)

**Backend Stack (from repo):**
- **Framework:** Laravel 11.31
- **PHP Version:** 8.2+
- **ORM:** Eloquent
- **Dev Tools:** Laravel Sail (Docker), Laravel Pint (code style), Laravel Pail (log viewer), Tinker (REPL)
- **Testing:** PHPUnit 11, Mockery, Collision (better error output)
- **Factories:** FakerPHP for test data generation

---

## ADR-003: Database Selection

**Status:** Accepted — *Supersedes original decision (PostgreSQL 16 on Neon)*

**Context:**
The original documentation proposed PostgreSQL 16 (managed via Neon) with Row-Level Security for multi-tenant isolation. The repository's `.env.example` reveals a different database choice.

**Decision: MySQL 8 (via Laravel Sail)**

The `.env.example` specifies:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ilmora
DB_USERNAME=ilmora
DB_PASSWORD=secret
```

**Rationale:**

1. **Laravel's Default:** MySQL is Laravel's best-supported database. While Laravel supports PostgreSQL, MySQL receives the most testing, the most community solutions, and the most hosting support. When issues arise, MySQL + Laravel solutions are always the first documented.

2. **Eloquent Global Scopes for Multi-Tenancy:** The original ERD proposed PostgreSQL's Row-Level Security (RLS) for tenant isolation. In the Laravel world, the equivalent is Eloquent global scopes — a `BelongsToTenant` trait applied to every tenant-scoped model. This provides application-level isolation that works identically across MySQL and PostgreSQL. The defense-in-depth is maintained through middleware that sets the tenant context on every request.

3. **JSON Column Support:** MySQL 8 supports JSON columns with indexing, sufficient for our `settings`, `recurring_schedule`, and `memorized_ranges` fields. While PostgreSQL's JSONB is more performant for complex JSON queries, our JSON usage is simple key-value storage and array storage — MySQL's JSON is adequate.

4. **Full-Text Search:** MySQL's `FULLTEXT` index supports Arabic text search for Surah name lookup and assignment search. For Uthmani script matching and transliteration search, we'll use `LIKE` with normalized text (stripping diacritical marks before comparison). If search requirements grow complex, we can add Laravel Scout with Meilisearch without changing the primary database.

5. **Hosting Ubiquity:** MySQL is available on every hosting provider, shared hosting, and VPS platform. This matters for an open-source project where community members may self-host on budget infrastructure. PostgreSQL hosting is less universally available, especially in budget Middle Eastern and African hosting environments.

6. **Sail Integration:** Laravel Sail's default Docker configuration includes MySQL. The development environment works out of the box with `sail up`.

**Multi-Tenancy Pattern: Shared Database, Shared Schema, Eloquent Scoped**

```php
// Applied via trait on every tenant-scoped model
class Halaqah extends Model
{
    use BelongsToTenant, SoftDeletes;

    // Eloquent global scope auto-adds: WHERE tenant_id = ?
    // Creating hook auto-sets: tenant_id = auth()->user()->tenant_id
}
```

This is functionally equivalent to PostgreSQL RLS but enforced at the application layer. The trade-off (no database-level enforcement) is acceptable for our team size and is the standard pattern for Laravel multi-tenant applications.

**Consequences:**
- Positive: Best Laravel compatibility, most community support
- Positive: Available on all hosting tiers (critical for open-source self-hosters)
- Positive: Sail Docker setup works immediately
- Negative: No Row-Level Security — tenant isolation depends on application code correctness (mitigated: global scopes apply automatically; integration tests verify isolation)
- Negative: Weaker JSON querying than PostgreSQL's JSONB (acceptable for our use cases)
- Negative: No native UUID type in MySQL 5.x (mitigated: MySQL 8 + Laravel's `$table->uuid()` works fine, though we use auto-increment `bigint` per Laravel convention)

---

## ADR-004: Authentication & Authorization

**Status:** Accepted — *Supersedes original decision (Clerk)*

**Context:**
The original documentation proposed Clerk as an external auth provider. The Laravel repository does not include Clerk, nor any explicit auth package in `composer.json`. We must decide on an auth strategy using the Laravel ecosystem.

**Decision: Laravel Breeze (to be installed) + Spatie Laravel-Permission for RBAC**

**Rationale:**

1. **Laravel Breeze for Authentication:** Breeze provides a minimal, customizable authentication scaffold: registration, login, password reset, email verification, and profile management — all as Blade views that we fully own and can style with Tailwind. Unlike Jetstream (which is opinionated and complex) or Fortify (which is headless), Breeze gives us exactly the starting point we need with zero excess.

   Since the repo uses Livewire, we will install Breeze with the **Blade** starter kit (not Livewire starter — it adds unnecessary complexity for auth pages that don't need reactivity).

   ```bash
   composer require laravel/breeze --dev
   php artisan breeze:install blade
   ```

2. **Laravel Sanctum for API Tokens (Future):** When we add a mobile app or third-party API access (v2.0), Sanctum (included with Laravel 11 by default) provides token-based authentication. For now, session-based auth is sufficient for the web application.

3. **Spatie Laravel-Permission for RBAC:** The de facto standard for roles and permissions in Laravel. Provides `Role` and `Permission` Eloquent models, middleware (`role:teacher`, `permission:halaqat.create`), Blade directives (`@role('teacher')`, `@can('create', $halaqah)`), and a clean API for assigning and checking permissions.

   ```php
   // Assign role
   $user->assignRole('teacher');

   // Check in middleware
   Route::middleware(['role:teacher'])->group(function () {
       Route::resource('halaqat', HalaqahController::class);
   });

   // Check in Blade
   @role('teacher')
       <button>Create Halaqah</button>
   @endrole
   ```

4. **Student Invite-Code Login:** For young students without email addresses, we implement a custom lightweight auth flow:
   - Teacher generates an invite code (stored in `invites` table)
   - Student enters code → system creates a minimal user account with a generated username
   - Student logs in with username + simple password (set by teacher)
   - This bypasses email verification for student accounts

5. **Authorization via Laravel Policies:** Beyond role checks, we use Eloquent Policies for resource-level authorization:
   ```php
   class HalaqahPolicy
   {
       public function update(User $user, Halaqah $halaqah): bool
       {
           return $user->id === $halaqah->teacher_id;
       }
   }
   ```

**Authorization Model:**

| Role | Permissions |
|------|------------|
| `admin` | `schools.manage`, `teachers.manage`, `halaqat.manage`, `reports.view`, `settings.manage` |
| `teacher` | `halaqat.own.manage`, `sessions.manage`, `assignments.manage`, `reports.own.view`, `students.invite` |
| `student` | `schedule.own.view`, `assignments.own.view`, `progress.own.view` |
| `parent` (v2.0) | `schedule.child.view`, `progress.child.view` |

**Why Not Clerk?** Clerk is an excellent external auth service, but it adds vendor dependency, cost ($0.02/MAU after free tier), and an external network dependency for every auth check. With Laravel, authentication is built-in, self-hosted, free, and works offline. For a project targeting global deployment including self-hosted instances, auth must be self-contained.

**Consequences:**
- Positive: Zero vendor lock-in, zero external auth dependencies
- Positive: Full ownership of auth UI (customizable for Arabic-first design)
- Positive: Spatie permission package is the Laravel community standard with 12k+ GitHub stars
- Positive: Works for self-hosted open-source deployments (no Clerk API keys needed)
- Negative: We handle password hashing, session management, and security ourselves (mitigated: Laravel handles all of this securely by default — bcrypt hashing, encrypted cookies, CSRF tokens)
- Negative: No built-in OAuth/social login (mitigated: add `laravel/socialite` for Google OAuth when needed)

**Action Required:** Install Breeze and Spatie packages:
```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

---

## ADR-005: API Design

**Status:** Accepted — *Supersedes original decision (REST with OpenAPI + Code Generation)*

**Context:**
The original documentation proposed a REST API with OpenAPI spec and TypeScript code generation for a separate Next.js frontend. With the switch to Livewire, the API design question changes fundamentally.

**Decision: Server-Rendered (Blade/Livewire) — No API Required for MVP**

**Rationale:**

1. **Livewire Eliminates the API Layer:** Livewire components communicate with the server via its own wire protocol (AJAX requests to `livewire/update`). There is no REST API to design, document, or version. The "API" is the Livewire component's public methods and properties — type-safe PHP on both sides.

2. **No Frontend/Backend Contract to Maintain:** With the original Next.js architecture, we needed OpenAPI specs, code generation, and serialization layers. With Livewire, the data flows directly from Eloquent models to Blade templates via Livewire component properties. No serialization, no deserialization, no API versioning.

3. **Laravel Resource Routes for Admin/Internal:** For any pages that don't require Livewire reactivity, standard Laravel resource routes with Blade views work perfectly:
   ```php
   Route::resource('halaqat', HalaqahController::class);
   ```

4. **Future API (v2.0):** When we build a mobile app or open the platform for third-party integrations, we'll add a RESTful API using Laravel's API resources:
   ```php
   Route::prefix('api/v1')->middleware('auth:sanctum')->group(function () {
       Route::apiResource('halaqat', Api\HalaqahController::class);
   });
   ```
   Laravel API Resources provide JSON serialization with pagination, filtering, and includes. This will be added alongside (not instead of) the Livewire frontend.

**Route Structure:**

```
Web Routes (routes/web.php):
├── / (landing page)
├── /login, /register (Breeze auth)
├── /dashboard (teacher dashboard — Livewire)
├── /schedule (weekly schedule — Livewire)
├── /halaqat/{halaqah} (Halaqah detail — Livewire)
├── /halaqat/{halaqah}/sessions/{session} (session detail — Livewire)
├── /students (student management — Livewire)
├── /student-portal (student read-only — Livewire, separate layout)
└── /settings (user settings — Livewire)

API Routes (routes/api.php) — v2.0, future:
└── /api/v1/... (Sanctum-protected REST API)
```

**Consequences:**
- Positive: No API to design, version, or maintain — massive time savings
- Positive: No serialization layer — data flows directly from Eloquent to Blade
- Positive: Type-safe in PHP (Eloquent models → Livewire properties → Blade templates)
- Negative: No API for mobile apps yet (acceptable: mobile is a v2.0 feature)
- Negative: No third-party integrations yet (acceptable: build when needed)
- Mitigation: Laravel API Resources make adding a REST API straightforward when the time comes

---

## ADR-006: Deployment & Infrastructure

**Status:** Accepted — *Supersedes original decision (Vercel + Railway + Neon + Upstash + Cloudflare R2)*

**Context:**
The original documentation proposed a multi-vendor deployment (Vercel for Next.js, Railway for NestJS, Neon for PostgreSQL, Upstash for Redis, Cloudflare R2 for storage). With a single Laravel application, the deployment model simplifies dramatically.

**Decision: Laravel Forge + DigitalOcean Droplet (or Contabo VPS)**

**Rationale:**

1. **Laravel Forge:** Forge is Tailored for Laravel deployment. It provisions servers, configures Nginx + PHP-FPM + MySQL, sets up SSL (Let's Encrypt), manages queue workers, handles deployments from GitHub (push-to-deploy), and runs scheduled commands. It costs $12/month and eliminates all DevOps work for a 2-person team.

2. **DigitalOcean or Contabo VPS:** A single VPS running the full Laravel stack (Nginx, PHP-FPM, MySQL, Redis) is the simplest, most cost-effective deployment for a bootstrapped project. A $12-24/month droplet handles 10,000+ concurrent teachers. Contabo is a viable alternative with better value-for-money (€4.99/month for 4 vCPUs, 8GB RAM) — Yousef has prior experience with Contabo from the WashX project.

3. **Single Server Architecture (MVP):** For launch, everything runs on one server:
   ```
   Nginx (reverse proxy + static files)
   ├── PHP-FPM (Laravel application)
   ├── MySQL 8 (database)
   ├── Redis (cache + sessions + queue — upgrade from file driver)
   └── Supervisor (queue workers, scheduled commands)
   ```

4. **Laravel Sail for Development:** Both developers use Sail locally (Docker Compose with MySQL, Redis, Mailpit containers). Identical environments, zero "works on my machine" issues.

5. **CI/CD Pipeline:**
   ```
   GitHub Push → GitHub Actions →
     ├── Laravel Pint (code style)
     ├── PHPUnit tests
     ├── Deploy to Staging (auto on `develop` branch via Forge)
     └── Deploy to Production (auto on `main` branch via Forge)
   ```

6. **Future Scaling Path:**
   - **Phase 1 (launch):** Single VPS with Forge
   - **Phase 2 (1,000+ teachers):** Add Redis for cache/queue, separate database server
   - **Phase 3 (10,000+ teachers):** Load balancer + multiple app servers, managed MySQL (PlanetScale or AWS RDS), CDN for assets (Cloudflare)
   - **Phase 4 (global scale):** Consider Laravel Vapor (AWS Lambda serverless) or multi-region deployment

**Cost Comparison:**

| Component | Original (Multi-Vendor) | Actual (Laravel) |
|-----------|------------------------|-------------------|
| Frontend hosting | Vercel Pro $20/mo | — (same server) |
| Backend hosting | Railway ~$15/mo | — (same server) |
| Database | Neon ~$20/mo | — (same server) |
| Redis | Upstash ~$10/mo | — (same server) |
| Server | — | DigitalOcean $24/mo or Contabo €8/mo |
| Management | — | Forge $12/mo |
| **Total** | **~$65-70/mo** | **~$36/mo (DO) or ~$20/mo (Contabo)** |

**Consequences:**
- Positive: 40-70% lower infrastructure cost
- Positive: Single server = single point of management, single set of logs, single backup target
- Positive: Forge eliminates DevOps overhead for a 2-person team
- Positive: Contabo experience carries over from WashX project
- Negative: Single server is a single point of failure (mitigated: automated daily backups, Forge health monitoring, can scale to multi-server quickly via Forge)
- Negative: No edge deployment for global performance (mitigated: Cloudflare CDN for static assets, server location chosen for primary user base)

---

## ADR-007: Security Architecture

**Status:** Accepted — *Supersedes original decision (defense-in-depth with external providers)*

**Context:**
Security requirements remain unchanged — this platform handles Quran education data for students who may be minors. The Laravel framework provides many security features out of the box. This ADR documents what Laravel handles automatically and what additional measures we must implement.

**Decision: Laravel's Built-in Security + Targeted Hardening**

### Layer 1: Laravel's Automatic Protections

| Threat | Laravel's Built-in Protection |
|--------|------------------------------|
| **SQL Injection** | Eloquent ORM uses parameterized PDO queries exclusively. Raw DB queries are banned in code review. |
| **XSS (Cross-Site Scripting)** | Blade's `{{ }}` syntax auto-escapes all output. `{!! !!}` (unescaped) is banned in linting rules. Quran text rendered from trusted, sanitized reference data only. |
| **CSRF (Cross-Site Request Forgery)** | Laravel's `@csrf` directive and `VerifyCsrfToken` middleware protect all POST/PUT/DELETE forms automatically. Livewire requests include CSRF tokens automatically. |
| **Mass Assignment** | Eloquent's `$fillable` property on every model whitelists assignable fields. `$guarded = []` is banned in code review. |
| **Password Security** | Laravel uses Bcrypt (default) or Argon2 hashing. Passwords are never stored in plaintext. `Hash::check()` for verification. |
| **Session Security** | Encrypted cookies, HTTP-only flag, SameSite=Lax, configurable session lifetime. Session regeneration on login (`$request->session()->regenerate()`). |
| **Encryption** | `Crypt` facade provides AES-256-CBC encryption for sensitive fields. `APP_KEY` in `.env` is the encryption key (auto-generated on install). |

### Layer 2: Additional Security Measures (Must Implement)

| Measure | Implementation |
|---------|---------------|
| **Rate Limiting** | Laravel's `RateLimiter` facade. Auth routes: 5 attempts/minute. API routes: 60 requests/minute. Livewire actions: 30 requests/minute. |
| **Security Headers** | Middleware for: `Content-Security-Policy`, `X-Frame-Options: DENY`, `X-Content-Type-Options: nosniff`, `Strict-Transport-Security` (HSTS 1 year), `Referrer-Policy: strict-origin-when-cross-origin`. Consider using `spatie/laravel-csp` package. |
| **Input Validation** | Laravel Form Requests on every controller/Livewire action. Validation rules enforce type, length, and format constraints. |
| **File Upload Security** | Validated MIME types via Form Request rules. Max 5MB. Store in `storage/app` (not public). Serve via signed URLs with expiration. |
| **Audit Logging** | `spatie/laravel-activitylog` for tracking all data mutations with before/after values. All auth events (login, logout, failed attempts) logged via Laravel's event system. |
| **Dependency Scanning** | `composer audit` in CI pipeline. GitHub Dependabot alerts enabled. |
| **Database Backups** | Automated daily backups via `spatie/laravel-backup`. Off-site storage (S3-compatible or separate VPS). |
| **Environment Security** | `APP_DEBUG=false` in production. `APP_ENV=production`. `.env` never committed to Git. Forge manages production secrets. |

### Layer 3: Data Privacy & Compliance

- **GDPR Compliance:** Data processing records maintained. Right to erasure implemented (cascading soft deletes + hard delete command). Consent tracking for notifications. Data export endpoint. Privacy policy and DPA available.
- **PDPL Compliance:** Data stored in region-appropriate infrastructure. Explicit consent for data processing. Data minimization (we collect only what's necessary). Breach notification process documented.
- **Minor Data Protection:** Student profiles store minimal PII. No location tracking. No behavioral analytics on student accounts.

### Layer 4: Security Operations

- **Penetration Testing:** Annual third-party pentest. Quarterly internal security review.
- **Incident Response:** Documented IR plan with roles, communication templates, and escalation procedures.
- **SSL/TLS:** TLS 1.2+ enforced via Nginx configuration. Managed via Forge (Let's Encrypt auto-renewal). HSTS header with 1-year max-age.

**Consequences:**
- Positive: Laravel handles 80% of OWASP Top 10 mitigations automatically
- Positive: Eloquent + Blade's default behaviors prevent the most common vulnerabilities without developer effort
- Positive: Spatie packages (CSP, backup, activity-log) fill remaining gaps with minimal configuration
- Negative: Security depends on developers using Laravel correctly (not bypassing Eloquent, not using `{!! !!}`)
- Accepted: Code review checklist enforces these constraints

---

## ADR-008: Open Source Strategy

**Status:** Accepted — *Unchanged from original*

**Context:**
This decision remains valid regardless of tech stack. The platform serves the global Muslim community for Quran education.

**Decision: Open Core Model with AGPL v3 License**

| Component | License | Description |
|-----------|---------|-------------|
| **Core Platform** | AGPL v3 | Teacher dashboard, weekly schedule, student portal, basic assignment tracking, student login, core Livewire components |
| **Premium Features** | Proprietary (SaaS-only) | Advanced analytics, institutional admin panel, AI features, priority support, white-labeling |
| **Quran Reference Data** | Public Domain | Surah/Ayah metadata, transliterations |

**Laravel-Specific Notes:**

- Laravel itself is MIT-licensed, which is compatible with AGPL for the application layer
- All Spatie packages are MIT-licensed — compatible
- Livewire is MIT-licensed — compatible
- AGPL requires anyone deploying a modified version as a service to release their modifications. This protects against competitors taking the codebase and launching a competing SaaS

**Monetization Model:**

1. **SaaS (Primary):** Hosted version at ilmora.com. Free tier → Pro → Institution pricing.
2. **Dual Licensing:** Organizations wanting to self-host without AGPL obligations pay for a commercial license.
3. **Support Contracts:** Paid support and implementation consulting for large institutions.
4. **Community Goodwill:** The open source core builds trust in the Muslim community, drives adoption, and creates network effects.

**Consequences:** Unchanged from original ADR-008.

---

## ADR-009: Internationalization (i18n) & Localization (l10n)

**Status:** Accepted — *Supersedes original decision (next-intl with ICU MessageFormat)*

**Context:**
The i18n requirements remain identical: Arabic-first, RTL-default, Uthmani Quran script, 7 languages. The implementation changes from Next.js's `next-intl` to Laravel's built-in localization system.

**Decision: Laravel Localization (lang/ directory) + Tailwind Logical Properties for RTL**

### i18n Architecture

1. **Translation Files:** Laravel's `lang/` directory with JSON files per locale:
   ```
   lang/
   ├── ar.json    (source of truth — Arabic)
   ├── en.json
   ├── de.json
   ├── tr.json
   ├── ur.json
   ├── ms.json
   └── fr.json
   ```

   Usage in Blade:
   ```blade
   <h1>{{ __('halaqah.create.title') }}</h1>
   <p>{{ __('schedule.session_count', ['count' => $sessionCount]) }}</p>
   ```

2. **Locale Routing:** Middleware-based locale detection:
   ```php
   // App\Http\Middleware\SetLocale
   public function handle($request, $next)
   {
       $locale = $request->user()?->locale
           ?? $request->cookie('locale')
           ?? $request->getPreferredLanguage(['ar', 'en', 'de', 'tr', 'ur', 'ms', 'fr'])
           ?? 'ar';

       app()->setLocale($locale);
       return $next($request);
   }
   ```
   Arabic is the default — if no preference is detected, the UI renders in Arabic RTL.

3. **RTL/LTR Strategy:**
   - The `<html>` tag receives `dir` and `lang` attributes via Blade layout:
     ```blade
     <html dir="{{ in_array(app()->getLocale(), ['ar', 'ur']) ? 'rtl' : 'ltr' }}" lang="{{ app()->getLocale() }}">
     ```
   - All Tailwind CSS uses logical properties: `ms-*`, `me-*`, `ps-*`, `pe-*` instead of `ml-*`, `mr-*`, `pl-*`, `pr-*`
   - Directional icons (arrows, chevrons) are mirrored via CSS `[dir=rtl] .icon-directional { transform: scaleX(-1); }`

4. **Quran Text Handling:**
   - **Font:** KFGQPC Uthmani Hafs font, loaded via `@font-face` in CSS and bundled via Vite
   - **Unicode:** Quran text stored as Unicode with full tashkeel (diacritical marks)
   - **Rendering:** Always `dir="rtl" lang="ar"` with dedicated `.quran-text` CSS class regardless of UI locale
   - **Search:** MySQL query with diacritics-stripped comparison using a helper function
   - **Ayah Reference:** Standard `[Surah:Ayah]` notation (e.g., `[2:142]`)

5. **Calendar:**
   - Primary: Gregorian (Carbon, Laravel's default date library)
   - Hijri: displayed alongside Gregorian via a PHP Hijri library (`islamic-network/prayer-times` or `hijri-converter`)
   - Week start: configurable per user (Saturday for Middle East, Monday for Europe)

6. **Number Formatting:**
   - Arabic-Indic numerals (٠١٢٣٤٥٦٧٨٩) for Arabic locale via PHP's `NumberFormatter`
   - Western Arabic numerals for all other locales

**Consequences:**
- Positive: Laravel's `__()` helper is simple and works everywhere (Blade, Livewire, PHP)
- Positive: JSON translation files are easy for non-developers to edit
- Positive: No build step for translations (unlike next-intl which requires compilation)
- Negative: No ICU MessageFormat (Laravel uses simpler `:placeholder` syntax). Complex pluralization uses Laravel's `trans_choice()` — sufficient for our needs
- Negative: No URL-based locale prefix (unlike `/ar/dashboard`). Locale is session/cookie-based. Can add URL prefix via route groups if needed.

---

## ADR-010: Real-Time Features

**Status:** Accepted — *Supersedes original decision (Server-Sent Events)*

**Context:**
Teachers need live updates when sessions are rescheduled, assignments are added, or notifications arrive. The original decision proposed SSE (Server-Sent Events). Laravel has its own real-time ecosystem.

**Decision: Livewire Polling (MVP) → Laravel Reverb (v1.5+)**

### Phase 1 — MVP: Livewire Polling

For MVP, we use Livewire's built-in polling:

```blade
{{-- Refresh schedule every 30 seconds --}}
<div wire:poll.30s>
    @include('schedule.weekly-view')
</div>
```

This is the simplest possible "real-time" solution: the Livewire component re-renders from the server every N seconds. No WebSocket server, no Redis pub/sub, no additional infrastructure.

**When to use polling:**
- Schedule view (30-second intervals — session changes are infrequent)
- Notification badge count (60-second intervals)
- Student portal assignment view (60-second intervals)

**Trade-offs:**
- Pro: Zero infrastructure, zero configuration, works immediately
- Con: Not truly real-time (up to 30-second delay)
- Con: Creates server load proportional to connected users × poll frequency

### Phase 2 — v1.5+: Laravel Reverb

When polling load becomes problematic or true real-time is needed, we upgrade to **Laravel Reverb** — Laravel's first-party WebSocket server:

```php
// Broadcasting an event
event(new SessionRescheduled($session));

// Event class
class SessionRescheduled implements ShouldBroadcast
{
    public function broadcastOn(): Channel
    {
        return new PrivateChannel('halaqah.'.$this->session->halaqah_id);
    }
}

// Livewire component listening
#[On('echo-private:halaqah.{halaqahId},SessionRescheduled')]
public function refreshSchedule()
{
    // Schedule auto-refreshes when event received
}
```

**Why Reverb (not Pusher, Ably, or Soketi)?**
- Reverb is first-party Laravel — built by the same team, zero configuration friction
- Self-hosted — no per-message costs, no vendor dependency
- Runs as a separate PHP process alongside the Laravel app
- Native Livewire integration via `Echo` events

**Consequences:**
- Positive: MVP ships with zero real-time infrastructure complexity
- Positive: Polling is sufficient for our update frequency (schedule changes are infrequent)
- Positive: Clear upgrade path to true WebSocket when needed
- Negative: Polling creates unnecessary server load for inactive tabs (mitigated: `wire:poll.visible` only polls when tab is visible)
- Negative: 30-second delay for updates (acceptable for MVP — Quran sessions don't change in real-time)

---

# Part IV: Team Structure & Work Division

## 1. Two-Developer Collaboration Strategy

### Team Composition
- **Developer A (Saudi Arabia, GMT+3):** Yousef
- **Developer B (Germany, GMT+1/+2):** Partner

### Time Zone Analysis

```
           06  07  08  09  10  11  12  13  14  15  16  17  18  19  20  21  22
KSA (GMT+3) [        |===== WORK DAY (9AM-6PM KSA) =====|                   ]
DE  (GMT+1) [  |===== WORK DAY (8AM-5PM DE) =====|                          ]
                            |====== OVERLAP ======|
                           10AM DE / 12PM KSA  to  5PM DE / 7PM KSA
```

**Overlap Window: ~5 hours (10:00-15:00 DE / 12:00-17:00 KSA)**

### Work Division Strategy: Feature-Based (Module Ownership)

Both developers are full-stack, and splitting by frontend/backend is meaningless with Livewire — every feature is full-stack by nature. Module ownership means each developer owns a feature end-to-end: migration, model, Livewire component, Blade view, and tests.

| Module | Primary Owner | Rationale |
|--------|---------------|-----------|
| **Auth, User Management, Tenant System** | Developer A (Yousef) | Foundation layer. Install Breeze, configure Spatie permissions, build tenant middleware. Security-critical — benefits from Yousef's security focus. |
| **Halaqah CRUD + Weekly Schedule View (Core UI)** | Developer B (Germany) | The hardest UI challenge. Calendar layout, drag-and-drop with Alpine.js, RTL calendar rendering. |
| **Assignment System & Quran Data** | Developer A (Yousef) | Quran text handling, Ayah validation, Arabic input components, Surah seeder. Native Arabic speaker advantage. |
| **Student Portal (Read-Only)** | Developer B (Germany) | Separate Blade layout, read-only Livewire components. Builds on schedule view components. |
| **Notification System** | Developer A (Yousef) | Laravel Notifications, mail templates, scheduled reminders. |
| **Analytics & Reporting** | Developer B (Germany) | Chart.js integration, PDF export, progress dashboards. |
| **Shared Infra (Sail, Forge, CI/CD, Database)** | Both (pair) | Critical infrastructure done together during overlap hours. |

### Git Workflow: GitHub Flow

- `main` branch: always deployable, protected, requires PR review
- Feature branches: `feature/ILM-42-weekly-schedule-view`
- Convention: branch names include ticket ID
- PRs require 1 approval from the other developer
- Squash merge to `main`
- Auto-deploy: merge to `main` → Forge deploys to production

### Communication Protocol

| Channel | Usage | Cadence |
|---------|-------|---------|
| **Daily Standup** | 15-min video call during overlap | Daily, 12:00 KSA / 10:00 DE |
| **GitHub PRs** | Code review, technical discussion | Async, < 12 hour review SLA |
| **GitHub Issues** | Task tracking, bug reports | Async |
| **Shared Notion** | Architecture decisions, meeting notes, specs | Async |
| **Emergency** | WhatsApp/Signal direct message | Production incidents only |

### Sprint Planning: 1-Week Sprints

- **Sprint Planning:** 30 minutes, every Sunday (12:00 KSA / 10:00 DE)
- **Sprint Review/Retro:** 30 minutes, every Thursday (same time)

### Code Review Checklist (Laravel-Specific)

- [ ] Form Request validation on every controller/Livewire action
- [ ] `$fillable` defined on every model (no `$guarded = []`)
- [ ] Eager loading used (no N+1 queries — check with `preventLazyLoading()`)
- [ ] `BelongsToTenant` trait on every tenant-scoped model
- [ ] No `{!! !!}` in Blade (only `{{ }}` for auto-escaping)
- [ ] No hardcoded strings (use `__()` for i18n)
- [ ] Tests cover happy path + 1 edge case
- [ ] Logical CSS properties (no `ml-`/`mr-`/`pl-`/`pr-`)
- [ ] Accessible (ARIA labels, keyboard navigation, focus management)
- [ ] PHPDoc on public methods

### CLAUDE.md for the Repository

```markdown
# CLAUDE.md — Ilmora Project Context

## Project
Quran School Management Platform (Ilmora)
Stack: Laravel 11 + Livewire 3 + Alpine.js + Tailwind CSS 3.4 + MySQL 8

## Conventions
- Models: PascalCase singular (Halaqah, not Halaqat)
- Tables: snake_case plural (halaqat, sessions, assignments)
- Controllers: PascalCase with Controller suffix (HalaqahController)
- Livewire components: PascalCase (ScheduleView, AssignmentForm)
- Views: kebab-case (schedule-view.blade.php)
- i18n keys: dot.notation in JSON files (halaqah.create.title)
- Quran references: Surah number (1-114), Ayah number (integer)
- All dates stored as UTC via Carbon, displayed in user's timezone
- Tenant ID auto-set via BelongsToTenant trait global scope

## Architecture Rules
- Never use raw SQL — use Eloquent queries
- Never skip Form Request validation
- Never use {!! !!} in Blade — always {{ }} for auto-escaping
- Never use $guarded = [] — always define $fillable
- Always include BelongsToTenant on tenant-scoped models
- Always use logical CSS properties (ms-*, me-*, ps-*, pe-*)
- Always add __() around user-facing strings (i18n)
- Always eager-load relationships to prevent N+1

## File Structure
app/
├── Http/Controllers/
├── Http/Requests/      (Form Requests)
├── Http/Middleware/     (SetLocale, EnsureTenant)
├── Livewire/           (Livewire components)
├── Models/             (Eloquent models)
├── Models/Concerns/    (Traits: BelongsToTenant, HasRoles)
├── Notifications/
├── Policies/
└── Services/           (Business logic)
resources/
├── views/
│   ├── layouts/        (Blade layouts)
│   ├── livewire/       (Livewire component views)
│   ├── components/     (Blade components)
│   └── emails/         (Mail templates)
└── css/
lang/                   (Translation JSON files)
database/
├── migrations/
├── seeders/            (SurahSeeder, RoleSeeder)
└── factories/
```

## 2. Project Timeline

### Phase 0: Foundation (Weeks 1-2)

| Task | Owner | Deliverable |
|------|-------|-------------|
| Install Breeze (Blade), configure auth | Dev A | Login, register, email verification |
| Install Spatie permissions, seed roles | Dev A | Roles, permissions, policies |
| Tenant model, middleware, global scope | Dev A | Multi-tenancy foundation |
| Tailwind RTL config, Blade layouts, design system | Dev B | Base layout, component library |
| Quran seeder (114 Surahs with metadata) | Dev A | `surahs` table populated |
| i18n setup (lang/ files, SetLocale middleware) | Dev B | Arabic + English translations |
| Sail configuration, GitHub Actions CI | Both | Dev environment, CI pipeline |

### Phase 1: Core Features — MVP (Weeks 3-6)

| Task | Owner | Deliverable |
|------|-------|-------------|
| Halaqah model, migration, CRUD Livewire components | Dev A | Create/edit/archive Halaqat |
| Student management, invite system | Dev A | Add/remove/invite students |
| Weekly Schedule Livewire component | Dev B | Calendar UI with Alpine.js |
| Session CRUD (create, edit, reschedule, cancel) | Dev B | Calendar interaction |
| Assignment Livewire components (create, grade, status) | Dev A | Assignment form + grading |
| Session detail view (assignments + attendance) | Dev B | Integrated session panel |
| Student portal (read-only layout + components) | Dev B | Student-facing views |
| Student progress view | Dev A | Progress history page |

### Phase 2: Polish & Launch (Weeks 7-8)

| Task | Owner | Deliverable |
|------|-------|-------------|
| Laravel Notifications (email reminders) | Dev A | Mail channel + scheduled commands |
| Attendance tracking Livewire component | Dev B | Quick attendance marking |
| Mobile responsiveness pass | Dev B | Responsive schedule view |
| Security hardening (headers, rate limiting, audit) | Dev A | Production-ready security |
| Error handling, loading states, empty states | Both | Polish |
| Beta testing with 5 real teachers | Both | Feedback + fixes |
| Deploy to production via Forge | Both | Live at ilmora.com |

**Milestone: MVP shipped to beta users. Public launch.**

### Phase 3: Growth Features — v1.0 (Weeks 9-14)

| Task | Owner | Deliverable |
|------|-------|-------------|
| Analytics & reporting (Chart.js + PDF) | Dev B | Teacher dashboards, PDF export |
| Additional languages (DE, TR, UR, MS, FR) | Both | Translation files |
| Admin panel for institutions | Dev A | Multi-teacher management |
| Performance optimization | Both | Eager loading audit, caching |
| Landing page + documentation | Dev B | Marketing site |
| Open source release preparation | Both | CONTRIBUTING.md, LICENSE, cleanup |

**Milestone: v1.0 released. Open source repository public. 7 languages supported.**

### Timeline Summary

| Milestone | Target Date | Weeks |
|-----------|-------------|-------|
| Infrastructure Ready | Week 2 | 2 |
| MVP Feature Complete | Week 6 | 4 |
| Public Beta Launch | Week 8 | 2 |
| v1.0 Release | Week 14 | 6 |

**Total: ~3.5 months from kickoff to v1.0**

---

# Appendix A: Technology Stack Summary

| Layer | Technology | Purpose |
|-------|-----------|---------|
| **Backend** | Laravel 11.31 | Full-stack framework |
| **PHP** | 8.2+ | Runtime |
| **Frontend Reactivity** | Livewire 3 | Server-driven reactive components |
| **Client JS** | Alpine.js 3.15 | Lightweight client-side interactivity |
| **Styling** | Tailwind CSS 3.4 | Utility-first CSS, RTL-native |
| **Forms Plugin** | @tailwindcss/forms | Consistent form styling |
| **Build** | Vite 6 | Asset bundling via laravel-vite-plugin |
| **HTTP Client** | Axios | AJAX requests (outside Livewire) |
| **Database** | MySQL 8 | Primary data store |
| **Cache** | File → Redis (upgrade) | Response and query caching |
| **Session** | File → Redis (upgrade) | User session storage |
| **Queue** | Sync → Database → Redis (upgrade) | Background job processing |
| **Auth** | Laravel Breeze (to install) | Authentication scaffold |
| **RBAC** | Spatie Laravel-Permission (to install) | Roles and permissions |
| **Mail** | Laravel Mail | Transactional email |
| **Scheduling** | Laravel Scheduler | Cron-based task scheduling |
| **Notifications** | Laravel Notifications | Multi-channel notifications |
| **Logging** | Laravel Log (Monolog) | Structured logging |
| **Testing** | PHPUnit 11 + Mockery | Automated tests |
| **Code Style** | Laravel Pint | PHP code formatting |
| **Dev Logs** | Laravel Pail | Real-time log viewer |
| **Dev Environment** | Laravel Sail | Docker-based local dev |
| **REPL** | Laravel Tinker | Interactive PHP shell |
| **Deployment** | Laravel Forge | Server provisioning + CI/CD |
| **Hosting** | DigitalOcean / Contabo VPS | Single-server deployment |
| **SSL** | Let's Encrypt (via Forge) | TLS certificates |
| **CDN** | Cloudflare (free tier) | Static asset caching, DDoS protection |

---

# Appendix B: Repository Structure

```
ilmora/
├── CLAUDE.md                        # AI assistant context
├── README.md                        # Project overview
├── LICENSE                          # AGPL v3
├── CONTRIBUTING.md                  # Contribution guidelines
├── composer.json                    # PHP dependencies
├── package.json                     # JS dependencies
├── vite.config.js                   # Vite configuration
├── tailwind.config.js               # Tailwind + RTL config
├── .env.example                     # Environment template
├── .github/
│   ├── workflows/
│   │   └── ci.yml                   # Pint + PHPUnit + Deploy
│   └── PULL_REQUEST_TEMPLATE.md
├── app/
│   ├── Http/
│   │   ├── Controllers/             # Resource controllers
│   │   ├── Requests/                # Form Request validation
│   │   └── Middleware/
│   │       ├── SetLocale.php
│   │       └── EnsureTenant.php
│   ├── Livewire/
│   │   ├── Schedule/
│   │   │   └── WeeklyView.php       # Core schedule component
│   │   ├── Halaqah/
│   │   │   ├── Create.php
│   │   │   ├── Edit.php
│   │   │   └── StudentManager.php
│   │   ├── Assignment/
│   │   │   ├── Form.php
│   │   │   └── GradePanel.php
│   │   ├── Student/
│   │   │   ├── Dashboard.php
│   │   │   └── ProgressView.php
│   │   └── Notification/
│   │       └── NotificationBell.php
│   ├── Models/
│   │   ├── Concerns/
│   │   │   └── BelongsToTenant.php
│   │   ├── User.php
│   │   ├── Tenant.php
│   │   ├── Halaqah.php
│   │   ├── Session.php
│   │   ├── Assignment.php
│   │   ├── Attendance.php
│   │   ├── Invite.php
│   │   ├── Surah.php
│   │   ├── RecurringRule.php
│   │   └── ProgressSnapshot.php
│   ├── Notifications/
│   │   ├── SessionReminder.php
│   │   └── AssignmentDue.php
│   ├── Policies/
│   │   ├── HalaqahPolicy.php
│   │   ├── SessionPolicy.php
│   │   └── AssignmentPolicy.php
│   ├── Services/
│   │   ├── QuranService.php         # Ayah validation, Surah lookup
│   │   ├── ScheduleService.php      # Recurring session generation
│   │   └── ProgressService.php      # Snapshot computation
│   └── Console/Commands/
│       ├── ComputeSnapshots.php
│       ├── SendSessionReminders.php
│       └── ExpireInvites.php
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   ├── app.blade.php        # Teacher layout (RTL-aware)
│   │   │   └── student.blade.php    # Student layout (read-only)
│   │   ├── livewire/                # Livewire component views
│   │   ├── components/              # Blade components (buttons, cards, etc.)
│   │   └── emails/                  # Notification mail templates
│   ├── css/
│   │   └── app.css                  # Tailwind imports + Quran font
│   └── js/
│       └── app.js                   # Alpine.js + Livewire init
├── lang/
│   ├── ar.json                      # Arabic (source of truth)
│   ├── en.json                      # English
│   ├── de.json                      # German
│   ├── tr.json                      # Turkish
│   ├── ur.json                      # Urdu
│   ├── ms.json                      # Malay
│   └── fr.json                      # French
├── database/
│   ├── migrations/                  # Chronological migrations
│   ├── seeders/
│   │   ├── DatabaseSeeder.php
│   │   ├── SurahSeeder.php          # 114 Surahs with metadata
│   │   ├── RoleSeeder.php           # Roles + permissions
│   │   └── DemoSeeder.php           # Demo data for development
│   └── factories/
│       ├── UserFactory.php
│       ├── HalaqahFactory.php
│       └── SessionFactory.php
├── routes/
│   ├── web.php                      # Web routes (Blade + Livewire)
│   └── api.php                      # API routes (v2.0, future)
├── config/
│   ├── database.php
│   ├── auth.php
│   ├── permission.php               # Spatie permissions config
│   └── ilmora.php                   # App-specific config (week start, etc.)
├── tests/
│   ├── Feature/
│   │   ├── Auth/
│   │   ├── Halaqah/
│   │   ├── Schedule/
│   │   └── Assignment/
│   └── Unit/
│       ├── QuranServiceTest.php
│       └── ProgressServiceTest.php
└── docs/
    ├── PRD.md
    ├── ERD.md
    └── ADR.md
```

---

# Change Summary

## What Changed and Why

| Area | Original Decision | New Decision | Reason |
|------|-------------------|-------------|--------|
| **App Name** | Hifz Hub | Ilmora | Partner renamed in repo (.env.example: APP_NAME=Ilmora) |
| **Frontend** | Next.js 15 + React | Livewire 3 + Alpine.js | Repo uses Livewire (composer.json) + Alpine.js (package-lock.json). Eliminates frontend/backend split. |
| **Backend** | NestJS (TypeScript) | Laravel 11.31 (PHP 8.2) | Repo initialized with Laravel (composer.json). Single codebase advantage. |
| **Database** | PostgreSQL 16 (Neon) | MySQL 8 | .env.example: DB_CONNECTION=mysql. Laravel's best-supported DB. |
| **Multi-Tenancy** | PostgreSQL RLS | Eloquent Global Scopes | MySQL doesn't have RLS; Laravel's global scope pattern is the community standard. |
| **Auth** | Clerk (external SaaS) | Laravel Breeze + Spatie Permission | No external auth dependency. Self-hostable. No auth package installed yet. |
| **API Design** | REST + OpenAPI codegen | No API (Livewire server-rendering) | Livewire eliminates the need for a REST API. API deferred to v2.0. |
| **Deployment** | Vercel + Railway + Neon + Upstash + R2 | Forge + single VPS | Single Laravel app = single server. 40-70% cost reduction. |
| **Cache/Queue** | Redis (Upstash) | File driver (upgrade to Redis later) | .env.example: CACHE_DRIVER=file, QUEUE_CONNECTION=sync. Acceptable for MVP. |
| **Real-time** | SSE (Server-Sent Events) | Livewire Polling → Laravel Reverb | Livewire polling is zero-config. Reverb upgrade path when needed. |
| **i18n** | next-intl (ICU MessageFormat) | Laravel lang/ (JSON files) | Laravel's built-in localization. Simpler, no build step. |
| **Monorepo** | Turborepo (apps/web + apps/api) | Single Laravel app | No monorepo needed — everything is one Laravel project. |
| **Package Manager** | pnpm | Composer + npm | PHP ecosystem standard. |
| **Error Tracking** | Sentry | Laravel Log (add Sentry later) | Not yet configured in repo. |
| **Monitoring** | Axiom | Forge monitoring (add Sentry/Axiom later) | Not yet configured in repo. |
| **Code Style** | ESLint + Prettier | Laravel Pint | PHP code formatter included in repo. |
| **Testing** | Vitest + Jest | PHPUnit 11 + Mockery | PHP testing stack included in repo. |

## What Stayed the Same

- All product features, personas, and requirements (PRD content)
- All ERD entities and relationships (only naming conventions changed)
- AGPL v3 open source strategy
- Team structure, sprint cadence, and work division approach
- Security requirements and compliance targets (GDPR, PDPL)
- i18n language list and RTL-first approach
- Project timeline (3.5 months to v1.0)
- Monetization model (freemium tiers)

---

*End of Document*
