# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

### Setup & Development
```bash
# First-time project setup (install deps, generate key, migrate, build assets)
composer setup

# Start all dev services concurrently (web server, queue listener, log viewer, vite)
composer dev

# Run individual services
php artisan serve
npm run dev
php artisan queue:listen --tries=1 --timeout=0
php artisan pail --timeout=0

# Build frontend assets
npm run build
```

### Testing & Code Quality
```bash
# Run all tests (configured for in-memory SQLite)
composer test
# or
php artisan test

# Run a single test file / filter
php artisan test tests/Feature/RfqFlowTest.php
php artisan test --filter=test_rfq_submission_workflow

# Static analysis (PHPStan / Larastan level 5)
./vendor/bin/phpstan analyse

# Code formatting (Laravel Pint)
./vendor/bin/pint
```

### Database & Maintenance
```bash
# Run migrations & seeders
php artisan migrate
php artisan migrate --seed

# Sync product sectors from legacy CSV column to pivot table
php artisan products:sync-sectors

# Database backup
php artisan db:backup

# Cache clear & production optimization
php artisan optimize:clear
php artisan optimize
```

---

## Architecture & System Overview

**PT. Prolabios Mitra Analitika** is a B2B E-Procurement & Request for Quotation (RFQ) platform built on **Laravel 13.x**, PHP 8.3+, Tailwind CSS v4, and Vite.

### Design System & UI Guidelines
- Visual Style: **Modern Flat Precision** (see [`DESIGN.md`](./DESIGN.md)). Clean industrial laboratory aesthetic, confident typography, solid contrast planes, zero drop shadows (`box-shadow: none`), zero heavy black borders, and sharp 4px–6px corner radii.
- Brand Tokens: Ruby Red (`#A6171C`) primary CTA/navigation, Sunny Gold (`#F1C045`) accents, clean Canvas (`#F8F9FA`), and White surfaces (`#FFFFFF`).

### Core Domain: B2B RFQ Workflow
1. **Catalog Carting (Session)**: Buyers add products to RFQ cart (`/cart`, `CartController`).
2. **RFQ Submission (`RfqController`)**: Collects corporate credentials (company info, corporate email, PIC). Dispatches asynchronous jobs (`app/Jobs/SendRfq*Job.php`).
3. **Operational Follow-up**: Primary workflow forwards RFQ to Sales via WhatsApp / Admin dashboard (`/admin/rfqs`).
4. **Routing Gotchas**:
   - Product buy URL (`/produk/{slug}/beli`) must be registered before product detail (`/produk/{slug}`).
   - Canonical slugs (`/produk/{slug}`) with legacy ID fallback (`/produk/detail?id=12`).
   - RFQ success page is restricted to the submitting session.

### Security & Upload Conventions
- **Admin Access**: `AdminAuthenticate` middleware verifies both `Auth::check()` and `$user->is_admin`.
- **Uploads**: Handled via `storage/app/public/uploads` (accessible via `/storage/uploads/...`). SVG is blocked; images are stripped of metadata and re-encoded to WebP via GD.
- **Security Middlewares**: CSRF, rate-limiting on login (`admin-login`), RFQ submission (`rfq-submission`), and contact forms (`contact-form`); honeypot + CAPTCHA validation (`CaptchaService`); CSP nonce via `@nonce` directive.

### Directory Structure & Conventions
- `app/Http/Controllers/`: Public controllers (`PageController`, `CartController`, `RfqController`, `ContactController`).
- `app/Http/Controllers/Admin/`: Admin controllers (`AdminRfqController`, `AdminProductController`, `AdminSectorController`, etc.).
- `app/Models/`: Eloquent models (`Product`, `ProductCategory`, `Rfq`, `RfqItem`, `Sector`, `Post`, `ContactInquiry`, `HomepageSetting`).
- `app/Services/`: Business logic (`AuditLogger`, `CaptchaService`, `DataService`).
- `app/Jobs/` & `app/Mail/`: Queue jobs and Mailable classes for buyer and admin notifications.
- `app/Http/Middleware/`: Security middlewares (`SecurityHeaders`, `AdminAuthenticate`, `ForceHttps`, `GzipCompress`).
- `resources/views/`: Blade templates split into layouts, public catalog/RFQ views, admin cockpit, and emails.
- `docs/`: Modular flow-by-flow operational and architectural documentation.
