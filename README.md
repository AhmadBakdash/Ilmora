# Ilmora

Ilmora is an open-source teacher-focused school management web application built with Laravel 11, Livewire 3, and Tailwind CSS.

## Features

- 📅 **Weekly Timetable** — Visual Mon–Fri timetable with lesson cards
- 🏫 **Multi-tenant** — Multiple schools in a single installation
- 👥 **Group Management** — Create and manage student groups
- 📋 **Attendance Tracking** — Record attendance per lesson per day
- 📝 **Assignment Management** — Create assignments and track student progress
- 🔐 **Role-based Access** — super_admin, school_admin, teacher, student

## Self-Hosting with Docker

### Requirements
- Docker & Docker Compose

### Quick Start

```bash
git clone https://github.com/AhmadBakdash/Ilmora.git
cd Ilmora
cp .env.example .env
# Edit .env to set your credentials
docker-compose up -d
```

Open http://localhost in your browser. On first run, you'll be redirected to /setup to create your school and admin account.

## Development Setup

```bash
git clone https://github.com/AhmadBakdash/Ilmora.git
cd Ilmora
cp .env.example .env
# Configure your database in .env
composer install
npm install && npm run build
php artisan key:generate
php artisan migrate
php artisan db:seed --class=DemoSeeder
php artisan serve
```

## Demo Credentials (after running DemoSeeder)

- Admin: admin@demo.com / password
- Teacher: teacher@demo.com / password
- Students: alice@demo.com, bob@demo.com, etc. / password

## Stack

- Laravel 11
- Livewire 3
- Alpine.js
- Tailwind CSS
- MariaDB / MySQL

## License

MIT License - see [LICENSE](LICENSE)
