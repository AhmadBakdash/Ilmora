# CLAUDE.md — Ilmora Project Context

## Project
Quran School Management Platform (Ilmora)
Stack: Laravel 11 + Livewire 3 + Alpine.js + Tailwind CSS + MySQL 8

## Conventions
- Models: PascalCase singular (Halaqah, Session, Assignment)
- Tables: snake_case plural (halaqat, sessions, assignments)
- Livewire components: PascalCase in subdirectories (Schedule/WeeklyView)
- Views: kebab-case (weekly-view.blade.php)
- i18n keys: dot.notation in JSON (halaqah.create.title)
- Quran references: Surah number (1-114), Ayah number (integer)
- All dates stored as UTC, displayed in user timezone
- Tenant scoping via BelongsToTenant trait

## Rules
- Never use raw SQL — use Eloquent
- Never skip Form Request validation
- Never use {!! !!} in Blade — always use {{ }}
- Never use $guarded = [] — always define $fillable
- Always use BelongsToTenant on tenant-scoped models
- Always use logical CSS properties (ms-*, me-*, not ml-*, mr-*)
- Always wrap user-facing strings with __() for i18n
- Always eager-load relationships (prevent N+1)
