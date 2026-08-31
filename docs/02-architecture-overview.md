# 02. Architecture & Directory Structure

Dokumen ini menjelaskan pola arsitektur yang digunakan di Prolabios dan panduan struktur folder.

---

## 🏛️ Pola Desain (Pola Service-Repository Sederhana)

Codebase ini menerapkan prinsip **Thin Controllers & Rich Services** untuk menjaga kode tetap modular, mudah diuji, dan bebas dari spaghetti:

```
[ HTTP Request ]
       │
       ▼
[ Routing & Middleware ] (Rate Limiter, Security Headers, Force HTTPS)
       │
       ▼
[ Form Request Validation ] (StoreProductRequest, StoreRfqRequest)
       │
       ▼
[ Controller ] (Thin: Hanya handle HTTP request & response view/json)
       │
       ▼
[ Services Layer ] (ProductService, SectorService, DataService, AuditLogger)
       │
       ▼
[ Eloquent Models & DB ] (Product, Rfq, Sector, Post, HomepageSetting)
```

---

## 📂 Struktur Direktori Utama

```
app/
├── Console/Commands/       # Custom Artisan commands (misal: DatabaseBackup, SyncProductSectors)
├── Helpers/                # Utility & Sanitizer helper (HtmlSanitizer, product_url)
├── Http/
│   ├── Controllers/        # Public controllers (PageController, CartController, RfqController)
│   │   └── Admin/          # Admin CRUD controllers (AdminProductController, AdminRfqController)
│   ├── Middleware/         # Security & network middlewares (AdminAuthenticate, SecurityHeaders)
│   └── Requests/           # Form validation rules (StoreProductRequest, UpdateRfqRequest)
├── Jobs/                   # Asynchronous queue jobs (SendRfqSubmittedEmailJob, dll)
├── Mail/                   # Mailable notification templates (RfqSubmittedNotification, dll)
├── Models/                 # Eloquent ORM models (Product, Rfq, RfqItem, Sector, Post, User)
├── Services/               # Domain business logic & data aggregators (ProductService, SectorService)
└── Traits/                 # Reusable logic across controllers (HandlesImageUploads, PaginatesQuery)

resources/
├── css/                    # Tailwind CSS v4 & custom theme styles
├── js/                     # Client scripts & Vite entry points
└── views/                  # Blade templates
    ├── admin/              # Dashboard view components
    ├── layouts/            # Base layouts (app.blade.php)
    └── partials/           # Reusable UI fragments (navbar, footer, search-modal)

routes/
└── web.php                 # Web routes (public, buyer flow, admin protected group)

tests/
└── Feature/                # Automated integration & feature tests (38+ tests)
```

---

## 🔗 Referensi Alur Terkait
- [01. Setup & Developer Workflow](01-setup-and-workflow.md)
- [03. B2B RFQ & Cart Flow](03-b2b-rfq-flow.md)
- [04. Product & Catalog Engine](04-product-and-catalog.md)
