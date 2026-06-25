# AGENTS.md

This file provides guidance to Codex (Codex.ai/code) when working with code in this repository.

## Project Overview

**MARK-UP** is a Laravel 12 EdTech platform for mentoring and business case competition preparation. It serves public marketing pages, user authentication, and a backend API (secured with Laravel Sanctum) for managing mentors, bookings, packages, transactions, and educational videos.

## Commands

```bash
# First-time setup (install deps, generate key, run migrations, build assets)
composer run setup

# Start all dev services concurrently (PHP server, queue, Pail logs, Vite)
composer run dev

# Run tests (clears config cache first)
composer run test

# Run a single test file
php artisan test tests/Feature/ExampleTest.php

# Asset compilation
npm run dev    # Vite dev server with HMR
npm run build  # Production build

# Database
php artisan migrate
php artisan migrate:fresh --seed
```

## Architecture

### Routing

- `routes/web.php` — server-side rendered Blade views (home, about, contact, product, login, register)
- `routes/api.php` — JSON API endpoints under `/api/`, protected with `auth:sanctum` middleware
- Authentication: web forms post to API (`/api/login`, `/api/register`, `/api/logout`)

### Blade Layout

All public pages extend `resources/views/layouts/app.blade.php`, which includes:
- `resources/views/components/navbar.blade.php`
- `resources/views/components/footer.blade.php`
- Vite-compiled assets, Google Fonts (Plus Jakarta Sans), FontAwesome CDN

Page content goes in `@yield('content')`.

### Models & Database

Models live in `app/Models/`. There are two naming conventions in use — a sign of legacy table integration:

| Model | Table | Notes |
|---|---|---|
| `User` | `users` | Standard Laravel auth + Sanctum (`HasApiTokens`) |
| `Mentor` | `MENTOR` | Uppercase legacy table, no timestamps, PK: `ID_BOOKING` |
| `Package` | `PACKAGE` | Uppercase legacy table, PK: `ID_PACKAGE` |
| `MentoringBooking` | `MENTORING_BOOKING` | PK: `ID_MENTORING_BOOKING` |
| `Transaction` | `TRANSACTIONS` | PK: `ID_TRANSACTIONS` |
| `Video` | `VIDEO` | PK: `ID_VIDEO` |

When adding new models that integrate with the legacy tables, follow the uppercase pattern and disable timestamps (`public $timestamps = false`).

### API & Authentication

`AuthController` handles registration, login (returns a Sanctum Bearer token), and logout. The API is designed for consumption by an external client (mobile/Flutter). Protect new API routes with `middleware('auth:sanctum')`.

### Frontend Stack

- **Tailwind CSS 4.0** via `@tailwindcss/vite` plugin — configured in `resources/css/app.css`
- Brand colors defined as CSS variables: `--navy: #1a2b56`, `--yellow: #f5eb5e`
- Vanilla JS in `resources/js/app.js` — handles scroll reveal, counter animations, card tilt effects, and particle animations via `IntersectionObserver`
- FontAwesome loaded from CDN in the layout, not via npm

### Testing

PHPUnit with two suites: `Unit` (`tests/Unit/`) and `Feature` (`tests/Feature/`). Tests use an in-memory SQLite database (configured in `phpunit.xml`) — never the app's real database.
