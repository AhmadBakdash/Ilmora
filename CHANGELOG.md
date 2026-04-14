# Ilmora Changelog

All notable changes to Ilmora will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).

## [Unreleased]

### Added
- Initial application scaffold with Laravel 11
- Role-based access control middleware (`CheckRole`)
- School context middleware (`EnsureSchoolExists`)
- Docker setup with Nginx reverse proxy and PHP artisan serve entrypoint
- Database seeders for schools, users, and lessons
- Teacher and student dashboards
- Lesson listing views seeded via database
