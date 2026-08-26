# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

### Development
```bash
# Start all dev services (web server, queue listener, log viewer, vite)
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
# Run all tests
composer test
# or
php artisan test

# Run a single test file / filter
php artisan test tests/Feature/RfqFlowTest.php
php artisan test --filter=test_rfq_submission_workflow

# Static analysis (PHPStan / Larastan)
./vendor/bin/phpstan analyse

# Code formatting (Laravel Pint)
./vendor/bin/pint
```

### Database & Maintenance
```bash
# Run migrations & seeders
php artisan migrate
php artisan migrate --seed

# Database backup command
php artisan db:backup
```

---

## Architecture & System Overview

**PT. Prolabios Mitra Analitika** is a B2B E-Procurement & Request for Quotation (RFQ) platform built on **Laravel 13.x**, PHP 8.3+, Tailwind CSS v4, and Vite.

### Core Domain: B2B RFQ Workflow
1. **Catalog Carting (Session)**: Buyers add products to RFQ cart (`/cart`, `CartController`).
2. **RFQ Submission (`RfqController`)**: Collects corporate credentials (company info, corporate email, PIC). Dispatches asynchronous jobs for receipt and admin notifications (`app/Jobs/SendRfq*Job.php`).
3. **Operational Follow-up**: Primary workflow forwards RFQ to Sales via WhatsApp / Admin dashboard (`/admin/rfqs`).
4. **Access Control**: RFQ success page restricted to submitting session. Product detail uses numeric IDs (`/produk/detail?id=12`).

### Security & Upload Conventions
- **Uploads**: Handled via `storage/app/public/uploads` (accessible via `/storage/uploads/...`). SVG blocked, images re-encoded to WebP via GD.
- **Security Middlewares**: CSRF, rate-limiting on login/RFQ submission/contact forms, honeypot + CAPTCHA validation (`CaptchaService`), HTML sanitization for rich text.

### Directory Structure & Conventions
- `app/Http/Controllers/`: Public-facing controllers (`PageController`, `CartController`, `RfqController`, `ContactController`).
- `app/Http/Controllers/Admin/`: Admin dashboard controllers (`AdminRfqController`, `AdminProductController`, `AdminProductCategoryController`, etc.).
- `app/Models/`: Eloquent models (`Product`, `ProductCategory`, `Rfq`, `RfqItem`, `Sector`, `Post`, `ContactInquiry`, `HomepageSetting`).
- `app/Services/`: Cross-cutting business logic (`AuditLogger`, `CaptchaService`, `DataService`).
- `app/Jobs/` & `app/Mail/`: Asynchronous queue jobs and Mailable classes for email notifications.
- `app/Http/Middleware/`: Security middlewares (`SecurityHeaders`, `AdminAuthenticate`, `ForceHttps`, `GzipCompress`).
- `routes/web.php`: Web routes separated into public, guest, buyer RFQ signed/tracking routes, and admin group (`/admin/*`).
- `resources/views/`: Blade templates split into layouts, public pages, admin dashboard views, and email templates.
