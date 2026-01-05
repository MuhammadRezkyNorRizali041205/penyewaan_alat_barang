# ⚠️ AI INSTRUCTIONS (MANDATORY)

This project uses **Vibe Coding with AI**.

BEFORE generating any code, AI MUST:
1. Read this entire README.md
2. Follow the defined architecture and rules
3. Respect Clean Architecture boundaries

If there is any conflict:
➡️ THIS README.md IS THE SINGLE SOURCE OF TRUTH



Backend: Laravel 12 (PHP 8.3+)
Admin Panel: Filament v4
Database: MySQL 8.0+ / MariaDB 10.6+
Authentication: Laravel Sanctum + Spatie Permission
Architecture: Clean Architecture (DDD adapted for Laravel)
Deployment: Production-ready (transactions, policies, observers)

1. CONTEXT-FIRST: Read full system context BEFORE code generation
2. ARCHITECTURE-FIRST: Preserve Clean Architecture layers
3. PRODUCTION-READY: All code deployable without modification
4. HUMAN-AI COLLABORATION: Developer directs, AI executes precisely
5. LONG-TERM FOCUS: Maintainability > quick fixes



Developer: "Implement rental approval feature"
   ↓
AI: Reads this ENTIRE document → Generates COMPLETE implementation
   ↓
Developer: Validates → Provides feedback
   ↓
AI: Iterates while preserving architecture

Senior Laravel 12 Architect (10+ years enterprise experience)
Clean Architecture Expert (DDD, SOLID, Hexagonal)
OWASP Security Specialist (Top 10 compliance)
Production Scalability Engineer
Maintainability Advocate

[ ] 1. Folder structure matches Section 7 exactly?
[ ] 2. Controllers < 20 lines, no business logic?
[ ] 3. All multi-model ops use DB::transaction()?
[ ] 4. Policy authorization implemented?
[ ] 5. FormRequest validation in place?
[ ] 6. Domain layer = pure PHP (no framework)?
[ ] 7. Observers log all state changes?
[ ] 8. Single Responsibility per class?
[ ] 9. Production-ready (no TODOs, no prototypes)?
[ ] 10. Security vulnerabilities eliminated?



❌ 1. Business logic in Controllers
❌ 2. Raw SQL queries (use Eloquent/Query Builder)
❌ 3. Multiple responsibilities per class
❌ 4. Model methods containing business logic
❌ 5. Bypassing FormRequest validation
❌ 6. Skipping Policy authorization
❌ 7. Code outside specified folder structure
❌ 8. Magic methods hiding business intent
❌ 9. No-transaction multi-model operations
❌ 10. Suggesting architecture changes

ENTITIES:
├── Alat (Equipment): Physical rental items
├── Kategori: Equipment classification
├── Penyewaan (Rental): Rental agreements
├── Pengembalian (Return): Return transactions
├── Denda (Fines): Late penalties
└── User: Admin/Petugas/Penyewa roles

WORKFLOWS:
1. Inventory Management (Admin/Petugas)
2. Rental Creation → Approval (Penyewa → Petugas)
3. Usage Period (equipment reserved)
4. Return Processing + Fine Calculation (Petugas)
5. Reporting + Audit (Admin)


                | Alat CRUD | Rental Create | Rental Approve | Returns | Reports
----------------|-----------|---------------|----------------|---------|---------
ADMIN          | ✅        | ✅            | ✅             | ✅      | ✅
PETUGAS        | ✅        | ❌            | ✅             | ✅      | ❌
PENYEWA        | ❌        | ✅            | ❌             | ❌      | ❌ (own)



1. MAX_RENTAL_DAYS = 30
2. FINE_MULTIPLIER = 1.5 (daily rate × days late)
3. Stock validation BEFORE approval
4. Auto-reserve stock on approval
5. Auto-update status on return
6. Full audit trail EVERY state change


       ┌─────────────────┐
       │ Presentation     │ ← HTTP, Filament, Controllers
       │ (Controllers)    │
       └─────────┬─────────┘
                 │
       ┌─────────▼─────────┐
       │ Application       │ ← Services, Actions, Use Cases
       │ (Services)        │
       └─────────┬─────────┘
                 │
       ┌─────────▼─────────┐
       │   Domain          │ ← Pure Business Rules
       │ (Rules/Calcs)     │
       └─────────┬─────────┘
                 │
       ┌─────────▼─────────┐
       │ Infrastructure    │ ← Models, Observers, Repos
       │ (Models)          │
       └───────────────────┘


- Domain layer framework-agnostic (extract to microservice)
- Testable without Laravel (PHPUnit pure PHP)
- Database swappable (MySQL→PostgreSQL→Mongo)
- UI swappable (Filament→Vue→React)



RESPONSIBILITIES:
- HTTP request/response handling
- Input validation (FormRequest)
- Authorization (Policy)
- Service orchestration

ALLOWED: Application Layer only
FORBIDDEN: Model access, business logic, DB queries

FILES:
├── Http/Controllers/Api/PenyewaanController.php
├── Http/Requests/StorePenyewaanRequest.php
├── Filament/Resources/AlatResource.php
└── Policies/PenyewaanPolicy.php


RESPONSIBILITIES:
- Business workflow orchestration
- Cross-aggregate coordination
- Database transactions
- Error handling + logging

ALLOWED: Domain + Infrastructure
FORBIDDEN: HTTP concerns, framework coupling

FILES:
├── Services/PenyewaanService.php
└── Actions/Penyewaan/CreatePenyewaanAction.php

RESPONSIBILITIES:
- Business rules validation
- Domain calculations
- Invariants enforcement
- Pure PHP (NO framework)

ALLOWED: None (standalone PHP)
FORBIDDEN: Laravel, DB, HTTP references

FILES:
├── Domains/Penyewaan/PenyewaanRules.php
└── Domains/Denda/DendaCalculator.php

RESPONSIBILITIES:
- Data persistence (Eloquent)
- Audit logging (Observers)
- External services
- File storage

ALLOWED: Application Layer
FORBIDDEN: Business logic

FILES:
├── Models/Alat.php
└── Observers/AlatObserver.php

app/
├── Actions/                          # Single-responsibility actions
│   └── Penyewaan/
│       ├── CreatePenyewaanAction.php
│       ├── ApprovePenyewaanAction.php
│       └── CancelPenyewaanAction.php
├── Domains/                          # PURE BUSINESS LOGIC
│   ├── Alat/
│   │   ├── AlatRules.php
│   │   └── StockValidator.php
│   ├── Penyewaan/
│   │   ├── PenyewaanRules.php
│   │   └── RentalCalculator.php
│   ├── Pengembalian/
│   │   └── ReturnRules.php
│   └── Denda/
│       └── DendaCalculator.php
├── Services/                         # Application orchestration
│   ├── PenyewaanService.php
│   ├── PengembalianService.php
│   └── DendaService.php
├── Http/
│   ├── Controllers/
│   │   ├── Api/
│   │   │   ├── PenyewaanController.php
│   │   │   └── PengembalianController.php
│   │   └── Web/
│   │       └── DashboardController.php
│   ├── Requests/
│   │   ├── StorePenyewaanRequest.php
│   │   └── ProcessReturnRequest.php
│   └── Middleware/
│       └── CheckRentalAvailability.php
├── Models/                           # Eloquent ORM
│   ├── Alat.php
│   ├── Category.php
│   ├── Penyewaan.php
│   ├── Pengembalian.php
│   └── AlatPenyewaan.php (pivot)
├── Policies/                         # Authorization
│   ├── AlatPolicy.php
│   ├── PenyewaanPolicy.php
│   └── UserPolicy.php
├── Filament/
│   ├── Resources/
│   │   ├── AlatResource.php
│   │   ├── PenyewaanResource.php
│   │   └── CategoryResource.php
│   └── Pages/
│       ├── Dashboard.php
│       └── Reports/
└── Observers/                        # Audit logging
    ├── AlatObserver.php
    ├── PenyewaanObserver.php
    └── PengembalianObserver.php

Classes: PascalCase → PenyewaanService, AlatRules
Methods: camelCase → calculateTotal, validateRental
Properties: camelCase → $totalHarga, $tanggalMulai
Constants: UPPER_SNAKE_CASE → MAX_RENTAL_DAYS = 30
Tables: snake_case → penyewaans, alat_penyewaan
Foreign Keys: {model}_id → penyewa_id, alat_id



Controller: Orchestrate HTTP (5-15 lines MAX)
Service: Coordinate workflow + transactions (20-50 lines)
Domain Rule: Single business validation (10-30 lines)
Model: Data + relationships ONLY (no business logic)


1. FormRequest → HTTP input validation
2. Domain Rules → Business rule validation  
3. Policy → Authorization validation





1. FormRequest → HTTP input validation
2. Domain Rules → Business rule validation  
3. Policy → Authorization validation


Domain Layer: throw DomainException("Specific business error")
Service Layer: catch → Log → rethrow ApplicationException
Controller: catch → JSON response (400/422/500)
NEVER expose: stack traces, SQL errors, internal details

HTTP POST /api/penyewaan {items: [...], dates: {...}}
        ↓ [1] Presentation Layer
StorePenyewaanRequest::rules() → HTTP validation passes
        ↓ [2] Authorization  
$this->authorize('create', Penyewaan::class) → Policy passes  
        ↓ [3] Application Layer
PenyewaanController::store() → PenyewaanService::createRental()
        ↓ [4] Domain Layer
PenyewaanRules::validate($data) → Business rules pass
        ↓ [5] Infrastructure Layer (TRANSACTION)
DB::transaction() {
    Penyewaan::create()           → Model persistence
    AlatPenyewaan::createMany()   → Pivot table
    Alat::increment('stok_disewa') → Stock update
}
        ↓ [6] Audit Layer
AlatObserver::updated() → Audit log
PenyewaanObserver::created() → Audit log
        ↓ [7] Response
return JsonResponse($penyewaan, 201)



Web Routes: CSRF middleware (default)
API Routes: Sanctum token auth
Filament: Built-in CSRF + role gates








---

## 🧠 AI ROLE DEFINITION (MANDATORY)

The AI MUST behave as:

- Senior Laravel 12 Architect (10+ years enterprise experience)
- Clean Architecture Expert (DDD, SOLID, Hexagonal)
- OWASP Security Specialist (Top 10 compliance)
- Production Scalability Engineer
- Maintainability Advocate

---

## ✅ IMPLEMENTATION CHECKLIST (REQUIRED)

Before any feature is considered complete:

- [ ] Folder structure matches **Section: Folder Structure**
- [ ] Controllers < 20 lines, NO business logic
- [ ] All multi-model operations use `DB::transaction()`
- [ ] Policy authorization implemented
- [ ] FormRequest validation present
- [ ] Domain layer is pure PHP (framework-agnostic)
- [ ] Observers log all state changes
- [ ] Single Responsibility per class
- [ ] No TODOs, no prototype code
- [ ] No security vulnerabilities

---

## ❌ STRICT PROHIBITIONS

- ❌ Business logic in Controllers
- ❌ Raw SQL queries
- ❌ Multiple responsibilities per class
- ❌ Business logic inside Models
- ❌ Skipping FormRequest validation
- ❌ Skipping Policy authorization
- ❌ Code outside defined folder structure
- ❌ Magic methods hiding business intent
- ❌ Multi-model operations without transactions
- ❌ Suggesting architecture changes

---

## 🧩 Core Domain Entities

- **Alat (Equipment)** – Physical rental items
- **Kategori (Category)** – Equipment classification
- **Penyewaan (Rental)** – Rental agreements
- **Pengembalian (Return)** – Return transactions
- **Denda (Fine)** – Late penalties
- **User** – Admin / Petugas / Penyewa

---

## 🔄 System Workflows

1. Inventory Management (Admin / Petugas)
2. Rental Creation → Approval (Penyewa → Petugas)
3. Usage Period (reserved equipment)
4. Return Processing + Fine Calculation
5. Reporting & Audit Logging

---

## 👥 Role Permissions Matrix

| Role     | Alat CRUD | Rental Create | Rental Approve | Return | Reports |
|----------|-----------|---------------|----------------|--------|---------|
| Admin    | ✅        | ✅            | ✅             | ✅     | ✅      |
| Petugas | ✅        | ❌            | ✅             | ✅     | ❌      |
| Penyewa | ❌        | ✅            | ❌             | ❌     | ❌ (own)|

---

## 📏 Core Business Rules

- `MAX_RENTAL_DAYS = 30`
- `FINE_MULTIPLIER = 1.5 × daily rate`
- Stock validation BEFORE approval
- Stock auto-reserved on approval
- Status auto-updated on return
- FULL audit trail for every state change

---

## 🏛️ Architecture Overview




---

## 📐 Layer Responsibilities

### 1️⃣ Presentation Layer
**Responsibilities**
- HTTP request/response
- FormRequest validation
- Policy authorization
- Service orchestration

**Forbidden**
- Business logic
- DB queries

**Examples**
- Http/Controllers/Api/PenyewaanController.php
- Http/Requests/StorePenyewaanRequest.php
- Policies/PenyewaanPolicy.php

---

### 2️⃣ Application Layer
**Responsibilities**
- Business workflow orchestration
- Cross-aggregate coordination
- Database transactions
- Error handling & logging

**Forbidden**
- HTTP concerns
- Framework coupling in logic

**Examples**
- Services/PenyewaanService.php
- Actions/Penyewaan/CreatePenyewaanAction.php

---

### 3️⃣ Domain Layer (PURE PHP)
**Responsibilities**
- Business rule validation
- Calculations
- Invariants enforcement

**Forbidden**
- Laravel
- Database
- HTTP

**Examples**
- Domains/Penyewaan/PenyewaanRules.php
- Domains/Denda/DendaCalculator.php

---

### 4️⃣ Infrastructure Layer
**Responsibilities**
- Data persistence
- Observers & audit logs
- External services

**Forbidden**
- Business rules

**Examples**
- Models/Alat.php
- Observers/PenyewaanObserver.php

---


(Full detailed tree preserved exactly as defined in architecture)

---

## 📛 Coding Standards

- Classes: `PascalCase`
- Methods: `camelCase`
- Properties: `camelCase`
- Constants: `UPPER_SNAKE_CASE`
- Tables: `snake_case`
- Foreign Keys: `{model}_id`

---

## 🧪 Validation Pipeline

1. FormRequest → HTTP validation
2. Domain Rules → Business validation
3. Policy → Authorization

---

## ⚠️ Error Handling Strategy

- Domain → `DomainException`
- Service → Catch, log, rethrow
- Controller → Safe HTTP response
- NEVER expose stack traces or SQL errors

---

## 🔐 Security Notes

- Sanctum token auth (API)
- CSRF enabled (Web & Filament)
- Policy-based authorization
- Audit logging via Observers
- Mandatory DB transactions

---

## 🤖 AI MASTER PROMPT (FINAL)

PERUBAHAN NODE & NPM

Sebelumnya

Node.js v23.x (CURRENT / experimental)

Diubah menjadi

Node.js v20.x LTS

Alasan

Stabil

Target resmi Laravel + Vite

Menghindari konflik dependency

2️⃣ PERUBAHAN package.json

Masalah awal:

Konflik antara:

vite

laravel-vite-plugin

@vitejs/plugin-vue

tailwindcss

Solusi final (sinkron & kompatibel):

Menggunakan Vite v7

Menyesuaikan plugin Vue v6

Menghapus dependency yang tidak kompatibel

Versi final yang dipakai:

vite → ^7.3.0

laravel-vite-plugin → ^2.0.1

@vitejs/plugin-vue → ^6.0.0

vue → ^3.5.0

tailwindcss → ^3.4.17

@tailwindcss/forms → ^0.5.9

postcss → ^8.4.x

autoprefixer → ^10.4.x

axios → ^1.11.x

Dependency yang DIHAPUS:

@tailwindcss/vite

concurrently

dependency downgrade / legacy

3️⃣ PERUBAHAN STRATEGI INSTALL INERTIA

Tutorial lama:
php artisan inertia:install vue

❌ Sudah tidak berlaku (Inertia v2)

Strategi baru (resmi Laravel):

Menggunakan Laravel Breeze + Inertia

Setup frontend dilakukan via npm + Vite

4️⃣ PENYESUAIAN VITE

Mengikuti requirement:

Laravel 12 → laravel-vite-plugin v2

Plugin tersebut wajib Vite v7

Menghindari:

npm install --force

npm install --legacy-peer-deps





## AI Vibe Coding Directive (MANDATORY)

This project is developed using AI-assisted vibe coding.
Any AI interacting with this codebase MUST strictly follow the rules below.

---

### 1. SOURCE OF TRUTH
- README.md is the SINGLE source of truth.
- Read README.md COMPLETELY before writing, modifying, or suggesting any code.
- If there is a conflict between README.md and AI assumptions, README.md ALWAYS wins.

---

### 2. ARCHITECTURE RULES
- Laravel 12 + Inertia.js (Vue 3)
- Clean Architecture (Domain → Application → Presentation → Infrastructure)
- Controllers MUST stay thin (no business logic).
- Business logic MUST live in Domain / Service / Action layers.
- NEVER move logic into Models or Controllers.

---

### 3. UI & MENU EXPANSION GOAL
The AI is responsible for progressively building a fully functional application UI.

Mandatory functional menus:
- Dashboard
- Penyewaan
  - List Penyewaan
  - Detail Penyewaan
  - Approve / Reject workflow
- Pengembalian
  - Proses pengembalian
  - Automatic fine calculation
- Laporan
  - Statistik penyewaan
  - Statistik denda

Menus MUST:
- Exist in navigation
- Render real data
- Trigger real backend logic
- NEVER be dummy or placeholder

---

### 4. WORKFLOW RULES
For every feature:
1. Reuse existing logic if it already exists
2. DO NOT recreate or duplicate business logic
3. Expose logic via routes → controllers → Inertia pages
4. Ensure UI actions trigger real backend behavior
5. Ensure authorization & policy checks remain intact

---

### 5. STRICT PROHIBITIONS
The AI MUST NOT:
- Add new business rules without instruction
- Modify existing domain rules unless explicitly told
- Bypass Policies or Authorization
- Add logic directly inside Vue components
- Introduce new architecture patterns
- Suggest shortcuts that violate Clean Architecture

---

### 6. EXPECTED AI BEHAVIOR
- Act as a Senior Laravel Engineer
- Assume tests already exist and are passing
- Focus on connecting logic to UI
- Build features incrementally and completely
- Prefer correctness, maintainability, and clarity over speed

---

### 7. EXECUTION MODE
When given a task:
- Implement the feature end-to-end
- Ensure menu, route, controller, page, and action are all wired correctly
- Do NOT leave partially implemented features
- If unsure, STOP and ask before continuing

---

### 8. DEFAULT TASK INTERPRETATION
If the task is vague (e.g. "make menu work"):
- Expand menus
- Ensure all clicks perform real actions
- Ensure no page is empty or misleading
- Ensure the application feels usable as a real system

---

By continuing, the AI confirms it has read and will obey this directive.




AI VIBE-CODING INSTRUCTION
Marketplace Penyewaan (Tokopedia / Shopee Style)
🚨 MANDATORY RULES (WAJIB)

READ README.md COMPLETELY BEFORE CODING

README.md is the single source of truth

DO NOT invent new architecture

DO NOT modify backend logic unless instructed

Frontend must consume existing controllers & routes

Follow Laravel 12 + Inertia.js + Vue 3 + TailwindCSS

Production-ready UI (no dummy UI, no placeholder logic)

🎯 GOAL

Build a rental marketplace UI similar to Tokopedia / Shopee, but for penyewaan alat/barang, not selling.

🧩 FEATURE SCOPE (FRONTEND ONLY)
1️⃣ Product Listing (Grid View)

Implement a responsive grid layout displaying rental items.

Each card MUST include:

📷 Product image (thumbnail)

🏷 Nama alat

💰 Harga sewa / hari

📦 Stok tersedia

⭐ Status (Tersedia / Disewa / Habis)

🔘 CTA Button: “Sewa Sekarang”

🔘 CTA Button: “Detail”

Grid rules:

Desktop: 4 columns

Tablet: 2 columns

Mobile: 1 column

2️⃣ Visual Style (Tokopedia / Shopee-like)

UI must:

Use rounded cards

Soft shadows

Hover animation (scale / shadow)

Clean spacing

Professional typography

❌ NO Bootstrap
❌ NO inline styles
✅ TailwindCSS only

3️⃣ Page Structure (Inertia)

Implement these pages:

resources/js/Pages/
├── Marketplace/
│   ├── Index.vue      # Product listing
│   ├── Show.vue       # Detail alat

4️⃣ Data Source (IMPORTANT)

Frontend MUST consume data from:

App\Models\Alat


Expected fields:

id

nama

harga_sewa

stok

gambar (path/url)

kategori

deskripsi

❌ Do NOT hardcode products
❌ Do NOT mock data

5️⃣ Marketplace Index Behavior

Show empty state UI if no data

Skeleton loader while loading

Pagination support (Inertia pagination)

Lazy loading images

6️⃣ Detail Page (Show.vue)

Detail page must show:

Large image preview

Gallery support (if multiple images exist)

Harga sewa per hari

Deskripsi alat

Informasi stok

Button:

“Sewa Sekarang” → route to create penyewaan

Disabled if stok = 0

7️⃣ Routing Rules

Use named routes:

alat.index
alat.show
penyewaan.create


Frontend MUST use:

route('alat.show', alat.id)

8️⃣ Componentization (IMPORTANT)

Extract reusable components:

resources/js/Components/
├── ProductCard.vue
├── PriceBadge.vue
├── StockBadge.vue


Each component must:

Single responsibility

Reusable

Props typed clearly

9️⃣ Accessibility & UX

Keyboard navigable

Alt text on images

Loading states

Disabled states clearly visible

🔒 FORBIDDEN

❌ Business logic in Vue
❌ API calls bypassing Inertia
❌ New backend endpoints
❌ Changing database schema
❌ Console logs in production

✅ DEFINITION OF DONE

Marketplace page renders products in grid

UI feels like Tokopedia/Shopee

Fully connected to backend data

Responsive on all devices

Clean, readable Vue code

No errors in console

No failing tests

🧭 AI EXECUTION FLOW

Read README.md

Inspect existing routes & controllers

Build Marketplace Index UI

Build Product Card components

Build Detail page

Ensure routing consistency

Validate responsive behavior

STOP — wait for next instruction



# AI Vibe Coding Instructions – Marketplace Penyewaan

## Mandatory Rules
- READ this README.md fully before writing any code
- README.md is the single source of truth
- Follow Clean Architecture principles
- Do NOT modify existing tests unless explicitly instructed
- All features must pass existing PHPUnit & Inertia tests

---

## Context
This project is a **Laravel 12 + Inertia.js (Vue 3) Marketplace for Equipment Rental**.

The marketplace should behave like **Tokopedia / Shopee**, but adapted for **rental per day**, not sales.

---

## Marketplace UI Goals
Implement a professional marketplace UI with:

### Product Listing (Index)
- Grid layout (responsive)
- Product image (fallback if null)
- Product name
- Rental price per day (formatted in frontend)
- Available stock badge
- "Lihat Detail" / "Sewa Sekarang" button

### Product Detail (Show)
- Large product image
- Full description
- Price per day
- Stock availability
- Category info
- Call-to-action button (Sewa)

---

## Technical Stack (DO NOT CHANGE)
- Laravel 12
- Inertia.js
- Vue 3 (Composition API)
- TailwindCSS
- Vite
- PHPStan + PHPUnit already installed

---

## Frontend Rules
- DO NOT format prices in backend
- DO formatting in Vue (Rupiah formatter)
- DO NOT hardcode routes
- Use Ziggy route helpers
- Components must be reusable

---

## Expected Frontend Structure

resources/js/
├── Pages/
│   └── Marketplace/
│       ├── Index.vue
│       └── Show.vue
├── Components/
│   ├── ProductCard.vue
│   ├── PriceTag.vue
│   └── StockBadge.vue
└── Utils/
    └── currency.js

---

## Backend Rules
- Controller returns Eloquent models
- Do not add presentation logic to backend
- Images are optional (nullable)
- Stock comes from `stok_tersedia`

---

## Quality Bar
- Code must look production-ready
- Naming must be explicit and readable
- Follow existing conventions
- No console errors
- No unused imports

---

## Goal
Build a clean, modern, scalable **Rental Marketplace UI** that:
- Passes all tests
- Looks professional
- Is easy to extend (filter, search, cart, checkout)

Proceed step by step.





## AI Frontend Instructions (Marketplace UI)

MANDATORY RULES:
- DO NOT touch backend logic, routes, controllers, or tests
- DO NOT change component names or paths
- Props from backend are the single source of truth
- MarketplaceTest.php MUST stay passing

CONTEXT:
This project uses:
- Laravel 12
- Inertia.js
- Vue 3
- Tailwind CSS

PAGES TO WORK ON:
- resources/js/Pages/Marketplace/Index.vue
- resources/js/Pages/Marketplace/Show.vue

GOAL:
Build a marketplace-style rental UI similar to Tokopedia/Shopee.

INDEX PAGE REQUIREMENTS:
- Grid layout (responsive)
- Product card with:
  - Image (placeholder allowed)
  - nama_alat
  - harga_sewa_per_hari (formatted: 150.000)
  - stok_tersedia badge
  - "Lihat Detail" button
- No API calls, use props only

SHOW PAGE REQUIREMENTS:
- Large product image
- nama_alat
- deskripsi
- harga_sewa_per_hari
- stok_tersedia
- CTA button: "Ajukan Penyewaan"

DESIGN:
- Clean
- Modern
- Marketplace feel
- Tailwind only (no UI library)

IMPORTANT:
If tests fail, STOP and report the exact error.

EXECUTION MODE — DO NOT EXPLAIN, DO NOT DISCUSS.

Context:
- Laravel + Inertia + Vue 3 + Vite
- MarketplaceTest.php is PASSING and MUST remain PASSING
- Backend and tests are LOCKED

STRICT RULES:
1. Modify ONLY:
   - resources/js/Pages/Marketplace/Index.vue
   - resources/js/Pages/Marketplace/Show.vue
2. Use ONLY props provided by Inertia (App\Models\Alat)
3. NO API calls, NO axios/fetch
4. NO backend changes
5. NO test changes
6. Price formatting must be handled in Vue (example: 150000 → 150.000)
7. Component names, paths, and exports MUST remain EXACT

TASK:
Implement a marketplace-style UI inspired by Tokopedia/Shopee:
- Grid product listing with image placeholder
- Product name, price, stock status
- Clean modern layout (AI vibecoding style)
- Responsive (mobile & desktop)
- Accessible (semantic HTML)

OUTPUT FORMAT (MANDATORY):
1. resources/js/Pages/Marketplace/Index.vue (FULL FILE)
2. resources/js/Pages/Marketplace/Show.vue (FULL FILE)

DO NOT:
- Explain the code
- Add commentary
- Change anything else

If any test fails, STOP and report the error.




.

🔐📄📊 ADVANCED FEATURES — IMPLEMENTATION PROMPT

Mode: Senior Laravel Architect + Filament Expert
Stack: Laravel 12, Inertia (Vue 3), Filament v3
Principle: Secure by default, test-safe, production-ready

🧱 GLOBAL RULES (WAJIB)

❌ Jangan mengubah test yang sudah PASS

❌ Jangan mengubah logika penyewaan inti

❌ Jangan memodifikasi struktur DB tanpa migrasi baru

✅ Gunakan Policy + Gate

✅ Gunakan Service Layer jika perlu

✅ Semua fitur admin hanya via Filament

1️⃣ 🔐 POLICY & ROLE (PEGAWAI / ADMIN)
🎯 Objective

Membatasi aksi sensitif berdasarkan role, bukan UI saja.

📌 Roles

admin

pegawai

user

(asumsi role sudah ada di kolom users.role)

📌 Policy yang WAJIB dibuat
PenyewaanPolicy
Aksi	User	Pegawai	Admin
view	❌	✅	✅
approve	❌	✅	✅
reject	❌	✅	✅
delete	❌	❌	✅
📌 Implementation Rules

Gunakan php artisan make:policy PenyewaanPolicy

Daftarkan policy di AuthServiceProvider

Semua action Filament harus pakai policy

Jangan hardcode role di controller

❌ Forbidden

Tidak boleh cek role langsung di Blade / Vue

Tidak boleh bypass policy di Filament

2️⃣ 📄 INVOICE PDF PENYEWAAN
🎯 Objective

Setiap penyewaan yang approved bisa:

Generate invoice PDF

Di-download oleh user

Diakses oleh admin

📌 Requirements

Gunakan:

barryvdh/laravel-dompdf

File:

InvoiceService

resources/views/pdf/invoice.blade.php

📌 Isi Invoice

Nomor invoice

Nama penyewa

Daftar alat (nama, jumlah, harga)

Lama sewa

Total harga

Tanggal sewa & pengembalian

Status penyewaan

📌 Route Rules

Route hanya aktif jika:

status = approved

Proteksi:

User hanya bisa akses invoice miliknya

Admin & pegawai bisa akses semua

❌ Forbidden

Tidak boleh generate PDF di controller langsung

Tidak boleh expose invoice tanpa auth

3️⃣ 📊 LAPORAN FILAMENT (CHART SEWA & DENDA)
🎯 Objective

Dashboard insight untuk admin & pegawai.

📈 Chart 1 — Penyewaan per Bulan

Type: Line / Bar

Data:

Jumlah penyewaan per bulan

Filter:

Tahun

Sumber:

penyewaans.created_at

💰 Chart 2 — Total Denda

Type: Bar

Data:

Total denda per bulan

Sumber:

pengembalians.denda

📌 Filament Rules

Gunakan:

Filament\Widgets\ChartWidget

Widget hanya muncul untuk:

admin

pegawai

📌 Bonus (jika memungkinkan)

Stat cards:

Total penyewaan

Penyewaan aktif

Total denda bulan ini

🧪 TEST & SAFETY CHECK

MarketplaceTest → tetap PASS

PenyewaanTest → tetap PASS

Policy wajib dipanggil via:

$this->authorize()

Filament authorization

🧠 FINAL EXECUTION INSTRUCTION TO AI

Kerjakan fitur berurutan:

Policy & Authorization

Invoice PDF

Filament Dashboard Charts

Berhenti jika:

Error auth

PDF gagal render

Query berat tanpa index





## AI Instruction — DO NOT IGNORE

You are a Senior Laravel Engineer specialized in **Laravel 12 + Filament v3**.
Work strictly inside this project. Do NOT change existing tests, routes, or business logic.
All existing tests MUST remain PASSING.

---

## PROJECT CONTEXT

This is a **Rental Management System (Sistem Penyewaan Alat Barang)** with:
- Laravel 12
- Filament v3 (Admin & Pegawai panel unified)
- Roles: `admin`, `pegawai`, `user`
- Authentication via `web` guard
- Dashboard already working at `/admin`

Current dashboard already contains:
- StatsOverviewWidget (total penyewaan, aktif, denda bulan ini)
- Chart: Penyewaan per Bulan
- Chart: Denda per Bulan

---

## OBJECTIVE

Extend the **Admin / Pegawai Dashboard** to look and behave like a **professional enterprise system** (similar to real company dashboards).

---

## FEATURES TO IMPLEMENT (STRICT ORDER)

### 1️⃣ Widget: Grafik Omzet Penyewaan
- Monthly revenue chart (LineChart)
- Source: `penyewaans`
- Calculation based on total rental payment
- Visible only to `admin` and `pegawai`

---

### 2️⃣ Widget: Jumlah User & Pegawai
- Show:
  - Total users
  - Total pegawai
  - Total admin
- Use `StatsOverviewWidget`
- Role-based visibility (staff only)

---

### 3️⃣ Widget: Pengembalian Terlambat Hari Ini
- Table widget
- Show rentals that should be returned **before today**
- Columns:
  - Penyewa
  - Alat
  - Tanggal seharusnya kembali
- Visible only to staff

---

### 4️⃣ Widget: Approval Penyewaan Menunggu
- Table widget
- Show `penyewaan` with status `pending`
- Columns:
  - Penyewa
  - Alat
  - Tanggal pengajuan
- Include **Approve / Reject actions**
- Must respect existing policies & tests

---

### 5️⃣ Widget: Export Excel (Dashboard Action)
- Add a dashboard widget/button
- One-click export to Excel
- Export:
  - Penyewaan
  - Pengembalian
  - Denda
- Use `maatwebsite/excel`
- No modal, direct download

---

## TECHNICAL RULES (VERY IMPORTANT)

- DO NOT modify:
  - Existing Controllers
  - Existing Tests
  - Existing Routes
- Use:
  - `app/Filament/Widgets`
  - Existing Models
  - Existing Policies (`isAdmin()`, `isPegawai()`, `isStaff()`)

- All widgets must:
  - Use Filament native widgets
  - Be role-protected via `canView()`
  - Be registered in `AdminPanelProvider`

---

## FINAL CHECK BEFORE STOPPING

Before stopping, ensure:
- All widgets render on `/admin`
- Dashboard looks clean and professional
- `php artisan test` passes with **NO FAILURES**
- No unused files created

If any test fails → STOP and REPORT immediately.




## AI DEBUG & FIX INSTRUCTION (STRICT – DO NOT GUESS)

You are a Senior Laravel 12 + Filament v3 Engineer.
Your task is to **ONLY FIX ERRORS**, not redesign or refactor.

⚠️ DO NOT:
- Change routes
- Change controllers
- Change tests
- Change database schema
- Change existing business logic

You may ONLY edit files inside:
- app/Filament/Widgets/**

---

## CURRENT ERROR CONTEXT

There is a syntax & namespace error inside:

app/Filament/Widgets/PendingApprovalsTable.php

Symptoms:
- Red underline on `Tables\Columns\TextColumn`
- IDE shows unresolved namespace
- Filament widget does not render
- PHP Language Server crashes after save

---

## ROOT CAUSE (HINT – DO NOT IGNORE)

This is **NOT a logic problem**.
This is a **Filament namespace + widget base class misuse**.

The widget:
- Extends the wrong base class OR
- Missing correct `use Filament\Tables;` imports OR
- Using Table API incorrectly for Filament v3

---

## YOUR TASK (STEP BY STEP)

1️⃣ Identify the correct widget base class:
- If using table(): must extend `Filament\Widgets\TableWidget`
- NOT `BaseWidget`

2️⃣ Fix all missing or wrong `use` imports:
- Filament\Widgets\TableWidget
- Filament\Tables
- Filament\Tables\Table
- Filament\Tables\Columns\TextColumn
- Filament\Tables\Actions\Action (if used)

3️⃣ Ensure the widget follows **Filament v3 table widget syntax**:
- protected function getTableQuery()
- public function table(Table $table): Table

4️⃣ DO NOT add new features
5️⃣ DO NOT rename the widget
6️⃣ DO NOT change visibility rules
7️⃣ Result must compile without IDE errors

---

## ACCEPTANCE CRITERIA

✔ No red underline in IDE  
✔ Filament dashboard loads without error  
✔ Widget appears on `/admin`  
✔ `php artisan test` still PASSES  

If unsure → STOP and explain instead of guessing.




VIBECODING PROMPT — Marketplace Penyewaan Alat (CRUD + Role)

ROLE & CONTEXT
Kamu adalah Senior Laravel 12 + Filament v3 Engineer.
Project ini adalah Sistem Informasi Penyewaan Alat berbasis:

Laravel 12

Inertia.js + Vue 3 (frontend marketplace)

Filament v3 (admin & pegawai dashboard)

MySQL

Fokus: Marketplace Penyewaan Alat dengan kontrol role yang ketat

🎯 TUJUAN FITUR

Bangun Marketplace Penyewaan Alat dengan aturan:

👥 ROLE & AKSES
Role	Hak Akses
Admin	CRUD alat + approve penyewaan
Pegawai	CRUD alat + approve penyewaan
User	❌ TIDAK bisa CRUD alat, hanya melihat & menyewa
🧱 ATURAN KERAS (WAJIB DIIKUTI)

❗ JANGAN:

❌ Mengubah struktur database

❌ Mengubah migration yang sudah ada

❌ Mengubah test yang sudah lulus

❌ Mengubah logic backend penyewaan

❌ Menghapus policy atau role yang sudah ada

✔️ BOLEH:

Menambah Policy

Menambah Filament Resource

Menambah Controller Web

Menambah Vue page

Menambah route

Menambah Form Request

🧩 STRUKTUR DATA (SUDAH ADA — JANGAN DIUBAH)

Model utama:

App\Models\Alat

App\Models\Kategori

App\Models\Penyewaan

App\Models\User (role: admin, pegawai, user)

Kolom penting alats:

id

kategori_id

nama_alat

deskripsi

stok_total

stok_tersedia

stok_disewa

harga_sewa_per_hari

gambar (nullable)

🔐 POLICY (WAJIB)
AlatPolicy

Buat App\Policies\AlatPolicy dengan aturan:

- viewAny     → semua user
- view        → semua user
- create      → admin, pegawai
- update      → admin, pegawai
- delete      → admin saja (opsional)


Policy HARUS digunakan:

di Controller

di Filament Resource

🧑‍💼 ADMIN & PEGAWAI (FILAMENT)
Buat Filament Resource:

AlatResource

Fitur:

✅ Create alat

✅ Edit alat

✅ Delete alat

✅ Upload gambar

✅ Validasi stok

❌ Resource tidak muncul untuk user biasa

Resource hanya muncul jika:

Auth::user()->isStaff()

🛒 USER (MARKETPLACE)
Frontend Marketplace (Inertia + Vue)
Page:

resources/js/Pages/Marketplace/Index.vue

resources/js/Pages/Marketplace/Show.vue

RULE:

User hanya bisa melihat

User tidak pernah melihat tombol edit / delete

User hanya bisa klik:

"Sewa Sekarang"

🔁 FLOW PENYEWAAN (JANGAN DIUBAH)

User klik Sewa

Penyewaan masuk status pending

Admin/Pegawai approve via:

Filament

atau Web approval

Stok otomatis dikurangi

🧪 TESTING (WAJIB LOLOS)

Setelah implementasi, jalankan:

php artisan test


Semua test HARUS PASS, terutama:

MarketplaceTest

PenyewaanWebTest

PenyewaanApprovalTest

Jika ada test gagal:
❗ STOP — PERBAIKI, JANGAN LANJUT

🎨 UI REQUIREMENT

Marketplace UI seperti Tokopedia / Shopee versi sewa

Card alat berisi:

Gambar

Nama alat

Harga per hari

Stok tersedia

Tombol "Sewa"

Admin dashboard terlihat professional enterprise

🛑 OUTPUT YANG DIHARAPKAN

AI HARUS:

Membuat Policy

Membuat Filament Resource Alat

Menghubungkan policy ke Filament

Menyusun Marketplace Index & Show

Menjaga user tidak bisa CRUD

Tidak merusak fitur yang sudah ada

🚨 CATATAN PENTING

Jika AI:

Mengubah migration ❌

Menghapus test ❌

Mengubah kolom DB ❌

Mengubah business logic ❌

➡️ Itu SALAH

📌 Kerjakan secara bertahap, rapi, dan profesional.
Anggap ini sistem perusahaan skala besar.



ROLE:
You are a senior Laravel + Inertia + Vue 3 engineer.
You MUST work carefully and incrementally.

CONTEXT:
This project is a Laravel 12 application using:
- Inertia.js
- Vue 3
- Filament Admin Panel
- Marketplace for renting equipment (Alat)

CURRENT STATUS (IMPORTANT FACTS):
- storage:link is already created and WORKING
- Image files EXIST in storage/app/public/alat-images/*
- Images are accessible directly via browser:
  http://127.0.0.1:8000/storage/alat-images/xxxx.jpg
- Marketplace tests are PASSING
- Problem: images DO NOT appear in Marketplace UI

ROOT CAUSE (CONFIRMED):
The backend sends raw image paths (e.g. "alat-images/file.jpg"),
but Vue requires a PUBLIC URL ("/storage/...").

STRICT RULES (DO NOT VIOLATE):
1. DO NOT modify database schema
2. DO NOT modify migrations
3. DO NOT modify existing tests
4. DO NOT break MarketplaceTest.php
5. DO NOT invent new fields in database
6. DO NOT add random packages
7. DO NOT change business logic
8. ONLY touch:
   - Marketplace controller (Index & Show)
   - Vue components (Index.vue / Show.vue)

TASK:
Fix Marketplace image rendering so that:
- Backend sends a computed public URL field (e.g. gambar_url)
- Vue uses ONLY that field
- Images render correctly in Marketplace cards & detail page

BACKEND REQUIREMENTS:
- Map Alat data explicitly (DO NOT send raw model)
- If `gambar` is NULL, send a fallback image
- Use asset('storage/...') to build public URL

FRONTEND REQUIREMENTS:
- Vue MUST NOT compute storage path
- Vue MUST only consume the provided image URL
- Use <img :src="alat.gambar_url">

DELIVERABLE:
1. Show the exact Laravel controller code changes
2. Show the exact Vue template changes
3. No extra explanations
4. No speculative code
5. Keep everything minimal and deterministic

IMPORTANT:
If any step would break tests or existing logic, STOP and report instead of guessing.




PROMPT VIBECODING — ADVANCED STAFF MANAGEMENT (FILAMENT)

Role:
Bertindak sebagai Senior Laravel Architect + Filament Expert yang terbiasa membangun admin panel perusahaan skala besar.

Stack:

Laravel 12

Filament v3

Auth default Laravel (users table dengan kolom role)

Role Sistem:

admin → full access

pegawai → operasional

user → customer (frontend only)

🔐 1️⃣ POLICY USER — APPROVE / REJECT PEGAWAI
🎯 Tujuan

Buat Policy Laravel untuk mengatur aksi sensitif pegawai.

Ketentuan:

HANYA admin yang boleh:

approve pegawai

reject / nonaktifkan pegawai

Pegawai TIDAK boleh approve sesama pegawai

User TIDAK punya akses sama sekali

Implementasi:

Buat UserPolicy

Method wajib:

approve(User $admin, User $pegawai)

reject(User $admin, User $pegawai)

Logic:

$admin->role === 'admin'

$pegawai->role === 'pegawai'

Integrasikan policy ke:

Filament Actions

Widget approval (jika ada)

❗ Jangan hardcode role di controller
❗ Gunakan $user->can(...)

🔄 2️⃣ RESET PASSWORD PEGAWAI (ADMIN ONLY)
🎯 Tujuan

Admin bisa reset password pegawai langsung dari Filament tanpa tahu password lama.

Ketentuan:

Reset hanya bisa dilakukan oleh admin

Password baru:

auto-generate (random)

di-hash (bcrypt)

Tampilkan notifikasi sukses

Optional (jika rapi):

log reset password ke activity log

Implementasi:

Filament Table Action:

Label: Reset Password

Icon: heroicon-m-key

Confirmation required

Setelah reset:

tampilkan password sementara (1x)

atau kirim via notification/log

❗ Jangan ubah sistem auth
❗ Jangan simpan password plaintext

🧾 3️⃣ LOG AKTIVITAS PEGAWAI (ENTERPRISE STYLE)
🎯 Tujuan

Mencatat aktivitas pegawai seperti sistem perusahaan besar.

Contoh aktivitas:

Pegawai approve penyewaan customer X

Pegawai menolak penyewaan

Pegawai memproses pengembalian

Pegawai menghitung denda

Pegawai reset password customer (jika ada)

Implementasi Teknis:

Buat tabel baru: activity_logs

Kolom minimal:

id

user_id (pegawai)

action (string)

description (text)

subject_type (optional)

subject_id (optional)

created_at

Contoh description:

Menangani penyewaan customer Muhammad Rezky

Menyetujui penyewaan alat Kamera DSLR

Menolak penyewaan karena stok tidak tersedia

Integrasi:

Simpan log saat:

approve / reject penyewaan

reset password pegawai

proses pengembalian

Buat Filament Resource: ActivityLogResource

Read-only

Admin & Pegawai bisa lihat

User tidak boleh akses

UI Filament:

Navigation label: Log Aktivitas

Icon: heroicon-m-clipboard-document-check

Sidebar group: Monitoring

🧠 ATURAN WAJIB (JANGAN DILANGGAR)

❌ Jangan ubah struktur tabel users

❌ Jangan gabungkan role logic ke frontend

❌ Jangan pakai asumsi data fiktif

❌ Jangan menghapus fitur existing

✅ Gunakan Policy Laravel

✅ Gunakan Filament Action & Resource

✅ Kode harus production-ready

📦 OUTPUT YANG DIHARAPKAN

UserPolicy.php

Action reset password pegawai

Migration activity_logs

ActivityLog model

ActivityLogResource (Filament)

Integrasi log di proses bisnis

Fokus ke kode.
Jangan beri penjelasan panjang.
Jangan improvisasi di luar requirement.



🎭 ROLE

Bertindaklah sebagai Senior Laravel 12 Backend Engineer, Filament v3 Specialist, dan QA Code Reviewer dengan pengalaman produksi di sistem enterprise (ERP / Marketplace / Internal Dashboard).

Kamu TIDAK MEMBANGUN APLIKASI BARU, tetapi MENSTABILKAN & MERAPIKAN BACKEND YANG SUDAH ADA.

🚫 LARANGAN MUTLAK

❌ Jangan mengubah test
❌ Jangan mengubah struktur database
❌ Jangan menambah migration baru
❌ Jangan menebak nama kolom
❌ Jangan mengubah business logic inti
❌ Jangan menghapus fitur yang sudah ada
❌ Jangan hardcode URL atau path di frontend
❌ Jangan pakai asumsi kolom (SEMUA HARUS SESUAI DB)

Jika ragu → BACA MODEL & MIGRATION TERLEBIH DAHULU

🎯 TUJUAN UTAMA

Menjadikan backend STABIL, KONSISTEN, DAN PRODUKSI-READY sehingga:

Semua halaman web & Filament TIDAK ERROR

Semua widget Filament RENDER DENGAN DATA VALID

Marketplace tampil:

daftar alat

gambar muncul konsisten

Policy role ADMIN / PEGAWAI / USER berjalan benar

Semua PHPUnit test = PASS

Struktur kode rapi & mudah dipelihara

🧠 KONDISI NYATA APLIKASI (FAKTA)
Stack

Laravel 12

PHP 8.4

Filament v3

Inertia + Vue

MySQL

Role User
Role	Hak
admin	full access
pegawai	approve / reject / dashboard
user	marketplace & sewa
🗄️ STRUKTUR DATABASE VALID (JANGAN DILANGGAR)
📦 alats

Kolom VALID:

id
kategori_id
nama_alat
deskripsi
gambar
stok_total
stok_tersedia
stok_disewa
harga_sewa_per_hari
created_at
updated_at


❌ TIDAK ADA:

nama

harga_sewa

stok

📑 pengembalians

Kolom VALID:

id
penyewaan_id
tanggal_pengembalian
status_pengembalian
denda
hari_keterlambatan
petugas_id
created_at


❌ TIDAK ADA:

tanggal_kembali

❗ MASALAH YANG TERIDENTIFIKASI (WAJIB DIBERESKAN)
1️⃣ Error Query

Banyak widget / service memakai kolom yang tidak ada

Contoh ERROR:

Unknown column 'tanggal_kembali'


📌 Solusi wajib:
Gunakan created_at atau tanggal_pengembalian sesuai tabel.

2️⃣ Marketplace Gambar Tidak Konsisten

Fakta:

Gambar disimpan di:

storage/app/public/alat-images/*


php artisan storage:link SUDAH dijalankan

📌 Solusi wajib:

URL gambar HARUS lewat accessor di Model

Frontend TIDAK BOLEH menyusun URL manual

3️⃣ Widget Filament Error

Masalah:

Icon tidak tersedia

Query tidak valid

Widget render tapi kosong

📌 Solusi wajib:

Gunakan heroicon yang VALID

Query hanya pakai kolom yang ADA

Tambahkan canView() berbasis role

4️⃣ Policy & Authorization Tidak Konsisten

Masalah:

approve / reject kadang 500

user biasa bisa akses endpoint staff

📌 Solusi wajib:

Semua akses HARUS via Policy

Controller TIDAK BOLEH pakai if role manual

5️⃣ CRUD Pegawai Belum Profesional

Target:

CRUD Pegawai via Filament

Admin bisa:

tambah pegawai

reset password

ubah role

User biasa TIDAK BOLEH akses

🧱 ATURAN TEKNIS WAJIB
✅ Model Alat

Tambahkan accessor, TANPA ubah kolom DB:

public function getGambarUrlAttribute(): ?string


Gunakan:

Storage::url($this->gambar)

✅ Marketplace Backend

Hanya ambil alat:

stok_tersedia > 0


Kirim data apa adanya

Jangan formatting harga di backend

✅ Marketplace Frontend

Pakai gambar_url

Handle null image dengan placeholder

Jangan pakai hardcoded /storage/...

✅ Filament Widget

Semua widget:

aman

defensif

tidak error walau data kosong

Semua widget pakai:

->canView()

✅ Test

WAJIB:

php artisan test


❌ Jika ada test gagal → STOP & PERBAIKI
❌ Jangan bypass test

📤 OUTPUT YANG HARUS DIBERIKAN AI

Ringkasan masalah (bullet point)

Daftar file yang diperbaiki

Penjelasan singkat kenapa error terjadi

Cuplikan kode penting (bukan dump)

Konfirmasi:

✅ Tests still PASS

🧪 VALIDASI AKHIR (CHECKLIST)

 Marketplace tampil + gambar muncul

 Dashboard admin tidak kosong

 Tidak ada error Livewire

 Tidak ada query invalid

 Role bekerja benar

 PHPUnit test hijau semua

🛑 JIKA RAGU

JANGAN MENEBak.
Baca:

Model

Migration

Test

Jika tetap ragu → jelaskan dulu, jangan eksekusi.

✅ SEKARANG EKSEKUSI

Perbaiki backend BERDASARKAN FILE YANG DIBERIKAN, ikuti aturan di atas TANPA PENGECUALIAN.



VIBECODING PROMPT – FIX TOTAL LOGIC PENYEWAAN (LARAVEL 12)

You are acting as a Senior Laravel Engineer (10+ years experience).

I have a Laravel 12 project for Marketplace Penyewaan Alat.
All tests are PASS, but real user flow is broken.

Your task is to FIX THE BACKEND LOGIC COMPLETELY so the real application flow matches the tested business rules.

🚨 PROBLEM STATEMENT (REAL BUG)
Current issues:

User can open /alat and click Sewa

User fills the form and clicks Ajukan

No error occurs

BUT:

Rental does NOT appear in admin dashboard

Admin cannot approve / reject

Stock does not change

No pending approval is visible

This means the HTTP request flow is NOT using the same logic as the tested domain logic.

🧠 EXPECTED BUSINESS FLOW (MUST BE IMPLEMENTED)
Correct flow:
USER
→ Click Sewa
→ POST /penyewaan
→ PenyewaanController@store
→ Create Penyewaan with:
   - status = 'pending'
   - user_id
   - alat_id
   - tanggal_mulai
   - tanggal_selesai
   - jumlah
   - total_harga
→ Admin sees pending approval
→ Admin approves
→ Stock is reduced

❌ CURRENT PROBLEM CAUSE (LIKELY)

One or more of these are happening:

Controller store() does NOT create a Penyewaan record

status is missing or not pending

Controller bypasses PenyewaanAction

Business logic is duplicated instead of reused

Form submission is not mapped to correct service layer

🎯 YOUR TASKS (MANDATORY)
1️⃣ FIX PenyewaanController@store

Ensure:

Penyewaan is always created

status = 'pending'

Uses domain/service/action layer, not inline logic

Validates stock availability

Uses database transaction

Example structure:

DB::transaction(function () {
   // create penyewaan
});

2️⃣ ENSURE ADMIN CAN SEE PENDING APPROVALS

Admin dashboard must list:

Penyewaan::where('status', 'pending')


Ensure:

status is consistent across DB, model, tests

enum/string is not mismatched (pending vs waiting)

3️⃣ ENSURE STOCK IS REDUCED ONLY ON APPROVE

Stock must:

❌ NOT be reduced when user submits

✅ Be reduced when admin approves

Be handled inside:

ApprovePenyewaanAction

or domain service

Must update:

stok_tersedia

stok_disewa

4️⃣ DO NOT BREAK EXISTING TESTS

Important:

All current tests are PASS

You must align controller logic with test logic

Do NOT modify test files unless absolutely required

5️⃣ ADD SAFETY CHECKS

Add:

Validation for jumlah <= stok_tersedia

Prevent double-approval

Prevent user approving their own rental

🧩 FILES YOU MUST CHECK / FIX

Focus on:

PenyewaanController

StorePenyewaanRequest

PenyewaanAction / Service

ApprovePenyewaanAction

Penyewaan model

Alat model stock logic

🧾 DELIVERABLES

When finished:

User submits rental → appears in admin dashboard

Admin approves → stock updates correctly

Admin rejects → stock unchanged

No logic duplication

Code follows Clean Architecture

Tests still PASS

⚠️ STRICT RULES

Do NOT guess database fields

Use existing schema

Do NOT add frontend hacks

Backend must be the single source of truth

No magic values

No silent failures

🧠 THINK LIKE A PRODUCTION SYSTEM

This is not a demo app.
This is a real rental marketplace with inventory control.

START BY:

Inspecting PenyewaanController@store

Comparing it with approval logic used in tests

Refactoring to use shared domain logic

DO NOT RESPOND WITH THEORY.
FIX THE CODE.


MASTER PROMPT — FIX TOTAL MARKETPLACE PENYEWAAN ALAT (LARAVEL 12)

Role & Mindset
Bertindaklah sebagai Senior Laravel Backend Engineer (Laravel 12) dengan pengalaman membangun marketplace + approval workflow skala perusahaan.
Fokus utama: logika bisnis BENAR, konsisten, aman, dan bisa di-maintain, bukan sekadar “biar jalan”.

🧩 KONTEKS PROYEK (WAJIB DIPAHAMI)

Aplikasi ini adalah Marketplace Penyewaan Alat dengan 3 role:

user → hanya bisa melihat alat & mengajukan penyewaan

pegawai → memproses & membantu approval

admin → approval final + manajemen data

Stack:

Laravel 12

Filament Admin Panel

Blade (Marketplace)

Auth default Laravel (guard web)

Database MySQL

❗ MASALAH KRITIS YANG HARUS DIPERBAIKI (JANGAN LEWAT)
1️⃣ User bisa submit form sewa tapi:

❌ status tidak masuk ke admin

❌ stok tidak berkurang

❌ muncul error Unauthenticated

❌ approval tidak muncul di dashboard admin

➡️ Ini menandakan AUTH FLOW & BUSINESS FLOW SALAH

🎯 TUJUAN PERBAIKAN (HARUS TERCAPAI)
✅ AUTH FLOW YANG BENAR

User WAJIB login sebelum menyewa

Login marketplace menggunakan guard web

Login admin Filament TETAP guard web

Session tidak terpisah

Jika user belum login:

Klik Sewa → redirect ke /login

Setelah login → kembali ke form sewa

✅ BUSINESS FLOW PENYEWAAN (WAJIB IKUT INI)
🧾 Saat USER mengajukan sewa:

Status = pending

STOK TIDAK BERKURANG

Data masuk tabel penyewaans

Muncul di dashboard admin / pegawai

🟢 Saat ADMIN / PEGAWAI approve:

Status → approved

stok_tersedia -= jumlah

stok_disewa += jumlah

Validasi stok (tidak boleh minus)

Pakai DB transaction

🔴 Saat reject:

Status → rejected

Stok tidak berubah

🧠 ATURAN TEKNIS WAJIB (JIKA DILANGGAR = SALAH)

❌ Jangan kurangi stok di controller marketplace

❌ Jangan kurangi stok sebelum approval

❌ Jangan bypass auth

✅ Gunakan:

Policy (approve / reject)

Service / Action class

DB::transaction

Request validation

🗂 FILE YANG HARUS DIPERIKSA & DIPERBAIKI

Periksa dan sesuaikan TANPA MERUSAK TEST:

routes/web.php

PenyewaanController

PenyewaanPolicy

ApprovePenyewaanAction

RejectPenyewaanAction

Model Penyewaan

Model Alat

Blade marketplace (/alat, /penyewaan/create)

Dashboard Filament widgets (approval table)

🧪 SYARAT WAJIB SETELAH FIX

Setelah perbaikan:

php artisan test


➡️ SEMUA TEST HARUS PASS
➡️ Tambahkan test baru jika logic diperbaiki

📌 OUTPUT YANG DIMINTA DARI AI

Penjelasan akar masalah (auth & logic)

Perubahan kode MINIMAL tapi BENAR

Potongan kode penting (controller, policy, action)

Penjelasan alur:

User → Pending

Admin → Approved → stok berkurang

Tidak menambah fitur baru di luar scope

🚫 LARANGAN KERAS

❌ Jangan ganti arsitektur

❌ Jangan ganti guard

❌ Jangan hardcode role

❌ Jangan menghapus middleware auth

❌ Jangan “asal biar jalan”

🧠 PENUTUP

Jika ada konflik antara:

UX cepat

logika bisnis benar

➡️ Pilih logika bisnis benar

Kerjakan seperti aplikasi marketplace perusahaan profesional, bukan tugas demo.

🔥 SELESAI — MULAI PERBAIKI DENGAN DISIPLIN ENGINEERING













ROLE:
You are a Senior Laravel + Inertia.js Engineer with production experience.
You are NOT allowed to add new features, refactor architecture, or introduce breaking changes.
Your ONLY task is to FIX existing errors and make the current system WORK correctly.

PROJECT CONTEXT:
This is a Laravel 12 + Inertia.js + Vue 3 rental marketplace application.
All tests are currently PASSING (`php artisan test`), but there are RUNTIME LOGIC ERRORS in the web flow.

STRICT RULES (MANDATORY):
1. DO NOT add new tables, columns, or features.
2. DO NOT change existing routes unless REQUIRED to fix authentication or 404.
3. DO NOT rename components arbitrarily.
4. DO NOT introduce new middleware.
5. DO NOT touch tests unless a test is wrong (and explain why).
6. Every fix MUST be minimal, justified, and based on the existing codebase.
7. If a file/component is missing, create it ONLY if referenced by existing code.
8. All fixes MUST result in:
   - No 404 on `/alat`
   - No `Unauthenticated` error when logged in
   - Rental request reaches admin
   - Admin can approve/reject
   - Stock decreases ONLY after approval
   - No new errors introduced

CURRENT PROBLEMS TO FIX (DO NOT IGNORE ANY):
1. `/alat` sometimes returns 404 even though the route exists.
2. Inertia page fails when Vue component path does not match `Inertia::render()`.
3. User is logged in (`/dashboard` works) but `auth()->check()` returns false in rental submission.
4. Rental submission (`/penyewaan/create?alat_id=...`) returns `Unauthenticated`.
5. Rental request does not appear in admin approval dashboard.
6. Stock does NOT decrease after rental approval.
7. Image display must gracefully handle `gambar = null`.

REQUIRED DIAGNOSTIC STEPS (YOU MUST DO THESE):
- Verify `routes/web.php` vs middleware usage
- Verify `AlatController` Inertia render path vs Vue file location
- Verify session + auth guard consistency (web guard only)
- Verify form submission uses authenticated route group
- Verify rental approval logic updates stock correctly
- Verify Vue props and backend payload alignment

EXPECTED OUTPUT FORMAT (MANDATORY):
1. Root Cause Analysis (per problem)
2. Exact file(s) to change
3. Exact code changes (before → after)
4. Reason WHY this fixes the issue
5. Confirmation checklist:
   - [ ] /alat loads
   - [ ] user stays authenticated
   - [ ] rental can be submitted
   - [ ] admin sees pending rentals
   - [ ] approval works
   - [ ] stock updates correctly
   - [ ] no new errors

IMPORTANT:
If you are unsure, ASK before making assumptions.
If something already works, DO NOT touch it.
If a fix risks introducing a new bug, DO NOT do it.

FINAL GOAL:
Stabilize the application so that the full rental flow works end-to-end
WITHOUT introducing any new errors or behavior changes.




You are a Senior Laravel + Inertia + Vue UI Engineer.

Context:
- This is a Laravel 12 project using Breeze + Inertia + Vue.
- Authentication logic is already correct and MUST NOT be changed.
- The goal is ONLY to redesign the login UI.

Task:
Redesign the login page to look like a professional
"Sistem Informasi Penyewaan Alat dan Barang".

Rules (MANDATORY):
1. DO NOT change routes, controllers, or auth logic.
2. Only edit: resources/js/Pages/Auth/Login.vue
3. Keep all existing props, form submission, and validation.
4. Must remain fully compatible with Laravel Breeze.

UI Requirements:
- Add a clear system title: "Sistem Informasi Penyewaan Alat & Barang"
- Add a short subtitle explaining the system (rental management, inventory, approval)
- Use modern layout (centered card, soft shadow, rounded corners)
- Use Tailwind CSS only
- Button text should be "Masuk ke Sistem"
- Labels and text must be in Indonesian
- Professional, enterprise-style (not startup flashy, not default template)

Accessibility:
- Inputs must remain accessible
- Error messages must still appear correctly

Output:
- Return the FULL updated Login.vue file
- Do NOT add new dependencies
- Do NOT add backend logic
You are a Senior Laravel Authentication Engineer.

Context:
- Laravel 12
- Authentication uses Laravel Breeze + Inertia + Vue
- User table has a `role` column with values:
  - admin
  - pegawai
  - user
- Filament admin panel is available at /admin
- Frontend user dashboard is at /dashboard

Task:
Implement role-based redirect AFTER login.

Rules (MANDATORY):
1. DO NOT change login routes.
2. DO NOT modify the login form submission logic.
3. DO NOT break Laravel Breeze authentication.
4. Modify ONLY the backend redirect logic after successful login.
5. Code must be production-ready and clean.

Implementation details:
- Edit: app/Http/Controllers/Auth/AuthenticatedSessionController.php
- Inside the `store()` method:
  - After authentication succeeds
  - Redirect:
    - admin → /admin
    - pegawai → /admin
    - user → /dashboard
- Use Auth::user()
- Use redirect()->intended()

Output:
- Show the FULL updated `AuthenticatedSessionController.php`
- Explain briefly why this approach is correct
- Do NOT add new middleware
- Do NOT add new packages



You are a Senior Frontend Engineer specializing in Laravel Breeze + Inertia + Vue + Tailwind.

Context:
- Laravel 12
- Authentication uses Laravel Breeze (Inertia)
- This is a "Sistem Informasi Penyewaan Alat & Barang"
- Login page must be professional, branded, responsive, and animated
- MUST NOT break authentication logic

GOALS:
1. Remove ALL Laravel branding/logo from login page
2. Replace with custom branding:
   - Title: "Penyewaan Alat & Barang"
   - Subtitle: "Sistem manajemen penyewaan peralatan dan inventaris"
3. Fully responsive layout:
   - Mobile: full width, stacked
   - Tablet: centered card
   - Desktop: centered card with proper spacing
4. Add smooth animations:
   - Page fade + slide-up on load
   - Button loading state on submit
   - Subtle input focus animation
5. Keep everything clean, minimal, enterprise-ready

MANDATORY RULES:
- DO NOT modify auth routes
- DO NOT modify controllers
- DO NOT add new dependencies
- ONLY modify Vue components
- Use Tailwind CSS only
- Must be production-ready

FILES TO MODIFY:
1. resources/js/Components/ApplicationLogo.vue
   - Replace Laravel logo with a custom icon or simple SVG
2. resources/js/Layouts/GuestLayout.vue
   - Ensure layout is centered and responsive
3. resources/js/Pages/Auth/Login.vue
   - Improve layout
   - Add animations using Vue <transition> or Tailwind classes
   - Add loading state on submit

OUTPUT:
- Show FULL updated code for:
  - ApplicationLogo.vue
  - GuestLayout.vue
  - Login.vue
- Explain briefly how responsiveness and animation work
- Do NOT add explanations unrelated to the task





You are a Senior Fullstack Engineer (Laravel + Inertia + Vue + Tailwind)
with strong UI/UX and Product mindset.

PROJECT CONTEXT:
- Laravel 12
- Inertia.js + Vue 3
- Tailwind CSS
- Authentication: Laravel Breeze
- Roles: admin, pegawai, user
- Domain: Sistem Informasi Penyewaan Alat & Barang
- Current app is FUNCTIONAL but UI is NOT professional yet

CURRENT PROBLEMS:
1. Dashboard UI looks basic and unprofessional
2. Layout feels like a template, not a real product
3. No payment flow for users after renting
4. Needs better visual hierarchy, spacing, and responsiveness

MAIN GOALS:
A. REDESIGN DASHBOARD UI (NO BACKEND BREAKING)
B. ADD PAYMENT FEATURE FOR USER RENTALS (END-TO-END FLOW)

==================================================
A. DASHBOARD UI REDESIGN
==================================================

Redesign the dashboard to look like a REAL, MODERN, PROFESSIONAL SYSTEM.

UI REQUIREMENTS:
- Clean layout (card-based, good spacing)
- Clear visual hierarchy (heading → stats → actions)
- Fully responsive (mobile / tablet / desktop)
- Smooth micro-interactions (hover, transition)
- Tailwind CSS only (no UI library)

DASHBOARD STRUCTURE:
1. Header Section
   - Page title: "Dashboard"
   - Short subtitle based on role (user / admin / pegawai)

2. Statistic Cards (TOP SECTION)
   - Total Penyewaan
   - Penyewaan Aktif
   - Menunggu Persetujuan
   - Total Denda (if any)
   (Icons + numbers, responsive grid)

3. Quick Actions
   - Lihat Penyewaan Saya
   - Marketplace
   - Pengembalian
   - Laporan (role-based visibility)

4. Recent Activity Table
   - Latest rentals
   - Status badge (pending / approved / rejected / selesai)

RULES:
- DO NOT change routes
- DO NOT change controllers logic
- Only improve Vue pages & layout
- Must look like a SaaS / enterprise product

FILES TO TOUCH (UI ONLY):
- resources/js/Layouts/AuthenticatedLayout.vue
- resources/js/Pages/Dashboard.vue
- reusable UI components if needed

==================================================
B. PAYMENT FEATURE (USER SIDE)
==================================================

Add a PAYMENT FLOW after rental approval.

PAYMENT BUSINESS FLOW:
1. User submits rental
2. Admin approves rental
3. Rental status becomes: "approved_unpaid"
4. User sees "Bayar Sekarang" button
5. User completes payment
6. Status changes to: "paid"
7. Rental becomes active

PAYMENT SCOPE (NO REAL GATEWAY YET):
- Simulate payment (manual / dummy payment)
- Ready to be upgraded to Midtrans later

REQUIRED FEATURES:
- Payment page (UI)
- Payment status handling
- Payment record table
- Payment history for user
- Admin can view payment status

DATA MODEL SUGGESTION:
- payments table
  - id
  - penyewaan_id
  - user_id
  - amount
  - status (pending, paid, failed)
  - paid_at

UI REQUIREMENTS (PAYMENT):
- Clear total price breakdown
- Rental duration & item summary
- Confirm payment button
- Success / failed state UI

RULES:
- Do NOT remove existing rental logic
- Payment must be linked to penyewaan
- Stock logic must remain correct
- Clean, readable, maintainable code

==================================================
OUTPUT EXPECTATION:
- Step-by-step implementation plan
- Updated UI structure (what components change)
- Vue code snippets for:
  - Dashboard UI
  - Payment button logic
  - Payment page UI
- Clear explanation of flow
- NO unnecessary refactors
- NO breaking changes

IMPORTANT:
Think like you are shipping a REAL PRODUCT,
not a school assignment.



Act as a Senior Laravel Engineer with strong Product & UX mindset.

CONTEXT:
This is a production-ready Laravel 12 application for
"Sistem Informasi Penyewaan Alat & Barang".

The system is ALREADY FUNCTIONAL.
DO NOT add dummy data.
DO NOT change existing business logic.
DO NOT break current routes.

YOUR TASKS:

1. Refactor USER DASHBOARD so:
   - All statistics come from REAL database data
   - No hardcoded or dummy values
   - Cards appear conditionally (hide if zero)
   - Actions adapt to rental status (context-aware UI)

2. Implement PAYMENT FLOW:
   - After admin approval, rental status becomes "approved_unpaid"
   - User can pay
   - Payment stored in payments table
   - Status becomes "paid"

3. Implement INVOICE PDF:
   - Generate PDF invoice after payment
   - Professional layout
   - Unique invoice number
   - User can download invoice anytime
   - Use barryvdh/laravel-dompdf

4. Keep everything clean:
   - Respect existing controllers & models
   - Add new controller only if necessary
   - Clear naming & readable code

IMPORTANT RULES:
- NO DUMMY DATA
- NO UI-only fake values
- ALWAYS query database
- Think like this system will be audited

OUTPUT:
- Explain what you change
- Provide controller logic
- Provide blade PDF template
- Show how dashboard queries real data


You are a SENIOR LARAVEL ENGINEER & SYSTEM ARCHITECT.

IMPORTANT:
- DO NOT guess.
- DO NOT create dummy data.
- DO NOT change UI randomly.
- DO NOT introduce new bugs.
- READ THE ENTIRE CODEBASE BEFORE CHANGING ANYTHING.

PROJECT CONTEXT:
This is a Laravel 12 + Inertia + Tailwind + Filament project:
"Sistem Informasi Penyewaan Alat & Barang"

CURRENT PROBLEMS (MUST FIX ALL):
1. Marketplace price is DIFFERENT from Admin Dashboard price
2. Price shown in Marketplace, Dashboard User, Admin Panel, Invoice must be CONSISTENT
3. Price calculation logic is scattered and incorrect
4. Some UI still shows incorrect or outdated price
5. Invoice PDF must reflect the REAL transaction price
6. NO dummy data is allowed
7. Logic must follow Clean Architecture principles

STRICT RULES:
- There MUST be a SINGLE SOURCE OF TRUTH for price
- Price calculation MUST happen in BACKEND ONLY
- Frontend MUST NEVER calculate price
- Marketplace, Dashboard, Invoice MUST read from the SAME data source
- No duplicated price fields
- No magic numbers
- No hardcoded price in Blade/Vue

DATABASE RULES:
- `alat.harga_sewa_per_hari` is the ONLY price field
- `penyewaan.total_harga` MUST be calculated once and stored
- Add `penyewaan.harga_per_hari_snapshot` if needed to preserve historical accuracy

WHAT YOU MUST DO STEP-BY-STEP:
1. Audit all models, controllers, services, Blade/Vue files
2. Identify ALL places where price is:
   - calculated
   - formatted
   - displayed
3. Remove ALL incorrect price logic
4. Centralize price calculation in backend (Service / Controller)
5. Ensure Marketplace reads price ONLY from `Alat`
6. Ensure Dashboard User reads price ONLY from `Penyewaan`
7. Ensure Invoice PDF uses `Penyewaan` data, NOT `Alat`
8. Fix stock reduction logic so it matches approved rentals
9. Ensure Admin approval does NOT change price unexpectedly
10. Ensure everything works on desktop, tablet, and mobile

OUTPUT FORMAT (MANDATORY):
- Explain ROOT CAUSE first
- Show EXACT FILES to change
- Show BEFORE vs AFTER code snippets
- Explain WHY each change is needed
- Confirm that:
  ✓ Marketplace price = Admin price = Invoice price
  ✓ No regression introduced
  ✓ No UI broken
  ✓ No authentication issues

FINAL CHECK:
Before finishing, mentally simulate:
- User views marketplace
- User rents item
- Admin approves
- User pays
- User downloads invoice PDF

If ANY step fails → FIX IT before responding.

DO NOT STOP until ALL problems are fixed.


MASTER PROMPT — FIX TOTAL MARKETPLACE PENYEWAAN ALAT (FRONTEND + BACKEND)

ROLE & CONTEXT

Bertindaklah sebagai Senior Full-Stack Laravel Engineer + UI Engineer dengan pengalaman membangun marketplace production-ready.

Project ini adalah Sistem Informasi Penyewaan Alat & Barang berbasis:

Laravel 12

Inertia.js + Vue 3

Tailwind CSS

Filament (admin)

MySQL

⚠️ PENTING:
JANGAN MENAMBAH FITUR BARU sebelum semua logic existing BENAR & KONSISTEN.

🎯 TUJUAN UTAMA (WAJIB)

Harga di Marketplace = harga di Backend Admin

Frontend & Backend pakai 1 sumber data yang sama

Tidak ada data dummy

UI marketplace profesional (inspirasi Tokopedia, tapi TIDAK meniru)

Semua perhitungan berasal dari database, bukan hardcode

Zero regression (tidak merusak fitur lain)

🧠 LANGKAH WAJIB SEBELUM CODING
1️⃣ AUDIT MODEL & DATABASE (WAJIB)

Baca dan pahami:

App\Models\Alat

Field harga yang BENAR

contoh:

harga_sewa_per_hari

stok_tersedia

Pastikan tidak ada field harga ganda

Tentukan SATU FIELD RESMI untuk harga

👉 Jika ditemukan:

harga_sewa

harga

harga_per_hari

➡️ NORMALISASI → gunakan SATU field saja
➡️ Update semua akses frontend & backend ke field itu

🔗 SINKRONISASI FRONTEND ↔ BACKEND (WAJIB)
2️⃣ CONTROLLER MARKETPLACE

Perbaiki:

AlatController@index

AlatController@show

Pastikan:

return Inertia::render('Alat/Index', [
    'alats' => Alat::select(
        'id',
        'nama_alat',
        'harga_sewa_per_hari',
        'stok_tersedia',
        'gambar'
    )->get()
]);


❌ JANGAN:

format harga di backend

pakai accessor aneh

pakai data dummy

3️⃣ FRONTEND (Vue) — JANGAN HARD CODE

❌ SALAH:

Rp 150.000 / hari


✅ BENAR:

Rp {{ formatRupiah(alat.harga_sewa_per_hari) }} / hari


Pastikan:

Harga hanya berasal dari props

Tidak ada angka statis di template

🎨 UI MARKETPLACE (PROFESIONAL)
4️⃣ DESAIN MARKETPLACE (INSPIRED, NOT CLONE)

Buat layout:

Grid responsif (mobile / tablet / desktop)

Card produk:

gambar

nama

harga per hari

stok tersedia

tombol Sewa

Hover animation ringan

Skeleton loading

❌ Jangan:

meniru Tokopedia 1:1

copy class CSS Tokopedia

✅ Gunakan:

Tailwind

spacing bersih

warna netral + primary brand

💸 LOGIC PENYEWAAN & PEMBAYARAN
5️⃣ SAAT USER MENYEWA (FLOW WAJIB)

User klik Sewa

Masuk ke form penyewaan

Submit → status:

pending


STOK TIDAK BERKURANG DI SINI

6️⃣ SAAT ADMIN APPROVE

✅ Baru di sini:

stok_tersedia -= jumlah

stok_disewa += jumlah

status → approved

❌ Jangan kurangi stok di frontend

🧾 INVOICE PDF (WAJIB)
7️⃣ INVOICE DARI DATA REAL

Buat:

InvoiceController

InvoiceService

Invoice PDF berisi:

Nama penyewa

Nama alat

Jumlah

Harga per hari

Total hari

Total bayar

Status pembayaran

Nomor invoice unik

Gunakan:

barryvdh/laravel-dompdf

Route:

Route::get('/penyewaan/{id}/invoice', ...)


Frontend:

Tombol Download Invoice (PDF)

Hanya muncul jika approved / paid

🔐 ROLE & REDIRECT
8️⃣ ROLE-BASED REDIRECT (WAJIB)

Setelah login:

admin → /admin

pegawai → /admin

user → /dashboard

❌ Jangan hardcode di JS
✅ Gunakan middleware / LoginResponse

🧪 VALIDASI AKHIR (WAJIB)

Sebelum selesai, pastikan:

 Harga marketplace == admin

 Tidak ada hardcoded angka

 Stok berubah hanya via approval

 Invoice PDF valid

 UI responsif

 Tidak ada error baru

 Semua test tetap PASS

⛔ LARANGAN KERAS

❌ Jangan:

menambah tabel tanpa alasan

rename field tanpa migrasi

mengubah logic approval

menambah dummy data

menghapus fitur lama

✅ OUTPUT AKHIR YANG DIHARAPKAN

Marketplace profesional

Harga sinkron 100%

Frontend & backend satu sumber data

Invoice PDF real







🔐 MASTER PROMPT — HARDENING & SECURITY LOCKDOWN
Sistem Informasi Penyewaan Alat & Barang (Laravel 12)

ROLE

Bertindaklah sebagai Senior Application Security Engineer + Laravel Architect
dengan pengalaman hardening aplikasi production (OWASP Top 10).

Kamu bertanggung jawab MENGAMANKAN TOTAL aplikasi tanpa merusak logic existing.

🎯 TUJUAN UTAMA (WAJIB)

❌ Tidak ada vulnerability OWASP Top 10

🛡️ Anti brute-force, bot, & abuse

🚫 Minimalkan risiko DDoS (layer aplikasi)

🔐 Akses berbasis role & policy 100% ketat

🧪 Tidak merusak test yang sudah PASS

⚠️ TIDAK MENAMBAH ERROR BARU

🧠 LANGKAH WAJIB SEBELUM CODING
1️⃣ AUDIT KESELURUHAN

Baca & pahami:

routes/web.php

Semua Controller

Semua Policy

Middleware auth, verified

Model User, Penyewaan, Alat, Pembayaran

Frontend form (Vue)

❌ DILARANG CODING sebelum audit selesai

🛑 OWASP TOP 10 — WAJIB DITUTUP SEMUA
2️⃣ AUTHENTICATION & AUTHORIZATION

Pastikan:

Semua route sensitif pakai:

->middleware(['auth', 'verified'])


Semua aksi:

approve

reject

payment

invoice

pengembalian
WAJIB pakai Policy

❌ Tidak boleh:

if(auth()->user()->role === 'admin')


✅ WAJIB:

$this->authorize('approve', $penyewaan);

3️⃣ MASS ASSIGNMENT (KRITIS)

Periksa SEMUA model:

protected $fillable = [...]


❌ Tidak boleh:

protected $guarded = [];

4️⃣ VALIDATION (WAJIB DI SEMUA REQUEST)

Setiap store, update, process:

WAJIB FormRequest

Validasi:

tanggal

jumlah

stok

harga

status enum

❌ Tidak boleh logic di controller tanpa validasi

5️⃣ SQL INJECTION & XSS

Pastikan:

❌ Tidak ada raw SQL

❌ Tidak ada DB::raw() tanpa alasan

Semua output frontend:

{{ value }}


❌ Jangan pakai v-html kecuali disanitasi

🤖 CAPTCHA & ANTI-BOT (WAJIB)
6️⃣ CAPTCHA (LOGIN + FORM KRITIS)

Gunakan:

Google reCAPTCHA v2 / v3
atau Cloudflare Turnstile (REKOMENDASI)

WAJIB di:

Login

Register

Ajukan Penyewaan

Pembayaran

Backend:

Validasi captcha server-side

Gagal captcha → reject request

🚦 RATE LIMITING & ANTI-DDOS (APP LEVEL)
7️⃣ RATE LIMITING

Gunakan:

Route::middleware(['throttle:10,1'])


Pasang di:

login

register

penyewaan

pembayaran

invoice download

Tambahkan limiter khusus:

RateLimiter::for('login', ...)

8️⃣ SESSION & COOKIE SECURITY

Pastikan:

SESSION_SECURE_COOKIE=true

SESSION_HTTP_ONLY=true

SameSite=Lax / Strict

Logout:

invalidate session

regenerate token

📁 FILE & STORAGE SECURITY
9️⃣ UPLOAD FILE (GAMBAR / BUKTI)

Validasi:

mime

size

Rename file (UUID)

Simpan di /storage/app/private

Akses via controller (signed URL)

❌ Jangan expose langsung public upload

🧾 INVOICE PDF SECURITY
🔟 PDF ACCESS CONTROL

Invoice:

Hanya bisa diakses oleh:

owner

admin

Tidak bisa ditebak via ID

Gunakan UUID / hash

🧠 EXTRA HARDENING (WAJIB)

CSRF aktif di semua form

Disable debug di production

Error message generic (tidak bocorkan stack trace)

Logging aktivitas:

login

approve

payment

download invoice

🧪 FINAL CHECKLIST (WAJIB PASS)

Sebelum selesai:

 OWASP Top 10 aman

 Captcha aktif & tervalidasi

 Rate limit bekerja

 Policy tidak bisa dilewati

 Tidak ada data dummy

 Semua test PASS

 Tidak ada warning di browser console

 Tidak ada 403/500 tidak jelas

⛔ LARANGAN ABSOLUT

❌ Jangan:

menonaktifkan CSRF

bypass policy

menambah middleware aneh

mengubah logic bisnis

menambah fitur baru

✅ OUTPUT YANG DIHARAPKAN

Aplikasi AMAN LEVEL PRODUKSI

Tahan brute-force & bot

Role tidak bisa ditembus




ROLE:
You are a Senior Laravel 12 Fullstack Engineer with deep expertise in
Inertia.js, Blade, Routing, Middleware, Security, and UI/UX debugging.

PROJECT CONTEXT:
This is an existing Laravel 12 project:
"Sistem Informasi Penyewaan Alat dan Barang"

The Marketplace page (/alat) displays items correctly,
BUT the "Sewa" button is NOT clickable / NOT working.

CRITICAL RULES (MANDATORY):
1. DO NOT add dummy data
2. DO NOT hardcode values
3. DO NOT change database schema unless REQUIRED
4. DO NOT break existing routes
5. DO NOT add new errors
6. DO NOT assume — VERIFY by checking existing code
7. Frontend MUST connect to backend properly
8. Read ALL related files before changing anything

---

TASK OBJECTIVE:
Fix the "SEWA" button so that:
- It is clickable
- It redirects or submits correctly
- It respects authentication
- It respects stock availability
- It triggers the rental flow properly

---

STEP-BY-STEP TASKS (MUST FOLLOW ORDER):

### 1️⃣ FRONTEND CHECK (MANDATORY)
- Inspect Marketplace UI (/alat)
- Verify:
  - Is the button <a>, <button>, or form submit?
  - Is it disabled by CSS, overlay, or z-index?
  - Is it blocked by auth middleware?
- Fix:
  - Pointer-events
  - z-index
  - disabled attributes
  - incorrect href or @click binding

### 2️⃣ ROUTE VALIDATION
- Check web.php for:
  - penyewaan.create
  - penyewaan.store
- Ensure:
  - Route exists
  - HTTP method matches (GET for form, POST for submit)
  - Route name used in frontend is CORRECT

### 3️⃣ AUTH FLOW (IMPORTANT)
- If user is NOT logged in:
  - Redirect to /login
- If logged in:
  - Allow access to sewa page
- Use middleware auth properly
- DO NOT block marketplace browsing

### 4️⃣ CONTROLLER LOGIC
- Verify AlatController & PenyewaanController
- Ensure:
  - Alat ID is passed correctly
  - Stock > 0 validation exists
  - No abort(403/404) incorrectly triggered
- Wrap logic in DB::transaction where needed

### 5️⃣ UI INTERACTION FIX
- Ensure button:
  - Has hover + active state
  - Is not overlapped by invisible elements
- Ensure cursor:pointer
- Ensure no JS error blocks click

### 6️⃣ ERROR HANDLING
- If stock = 0:
  - Disable button gracefully
  - Show “Stok habis”
- If route missing:
  - Fix route reference (do NOT create fake route)

---

OUTPUT REQUIREMENTS:
- Explain the ROOT CAUSE of why "Sewa" was not clickable
- Show ONLY necessary code changes
- Specify file paths (Blade / Vue / Controller / Route)
- NO placeholders
- NO pseudo-code
- NO unrelated refactor

FINAL CHECK:
- Click "Sewa" → works
- Logged out → redirected to login
- Logged in → goes to penyewaan flow
- Stock reduces correctly after sewa
- No console error
- No 404 / 403

FAIL CONDITION:
If "Sewa" still does not work → YOU FAILED. FIX AGAIN.



ROLE:
You are a Senior Laravel 12 Engineer.
You MUST act as a debugger and system integrator, not a UI designer.

PROJECT:
Laravel 12 — Sistem Informasi Penyewaan Alat dan Barang

CURRENT PROBLEM (CRITICAL):
The "Sewa" button on `/alat` page CANNOT be clicked AND
NO rental (penyewaan) data is inserted into the database.

THIS IS A BUSINESS LOGIC FAILURE, NOT UI ONLY.

---

ABSOLUTE RULES:
1. The "Sewa" action MUST create a record in database
2. The flow MUST use proper CRUD (CREATE penyewaan)
3. DO NOT fake data
4. DO NOT use dummy placeholders
5. DO NOT bypass database
6. DO NOT silently fail
7. If something is missing, FIX IT PROPERLY
8. All changes must be REAL, TRACEABLE, and WORKING

---

EXPECTED RENTAL FLOW (MANDATORY):

1️⃣ USER clicks "SEWA" on marketplace (/alat)
2️⃣ SYSTEM checks authentication:
   - If NOT logged in → redirect to /login
   - If logged in → continue
3️⃣ SYSTEM opens rental form OR directly submits rental
4️⃣ SYSTEM validates:
   - alat_id exists
   - stok > 0
   - tanggal mulai & selesai valid
5️⃣ SYSTEM inserts into:
   - tabel penyewaan
   - tabel detail_penyewaan (if exists)
6️⃣ SYSTEM reduces stock in alat table
7️⃣ SYSTEM redirects with SUCCESS message
8️⃣ DATA MUST APPEAR in database

---

MANDATORY TECHNICAL CHECKLIST (YOU MUST VERIFY ALL):

### A. ROUTING
- Ensure `penyewaan.store` route EXISTS
- HTTP METHOD must be POST
- Route name used in frontend MUST MATCH

### B. FRONTEND ACTION
- The "Sewa" button MUST:
  - Be a `<form method="POST">` OR
  - A link to `/penyewaan/create?alat_id=XX`
- CSRF token MUST exist
- Button MUST NOT be disabled
- No overlay or z-index blocking click

### C. CONTROLLER (CRITICAL)
- PenyewaanController::store MUST:
  - Receive alat_id
  - Use auth()->id()
  - Insert to database using Eloquent
  - Use DB::transaction
  - Handle stock decrement

### D. DATABASE
- Ensure tables:
  - penyewaan
  - alat
- Ensure foreign keys match
- Ensure fillable fields exist

### E. ERROR VISIBILITY
- If insert fails → throw error
- If validation fails → show message
- NO silent failure allowed

---

WHAT YOU MUST OUTPUT:

1. ROOT CAUSE why "Sewa" does NOT save to database
2. EXACT file(s) to fix:
   - route file
   - blade / inertia component
   - controller
3. REAL Laravel code (NOT pseudo-code)
4. Explanation of data flow from click → DB
5. Confirmation checklist:
   - Button clickable ✅
   - Data saved to DB ✅
   - Stock updated ✅

---

FAIL CONDITION:
If after your fix:
- Database still empty
- Button still not working
- Or flow is incomplete

YOU MUST DEBUG AGAIN UNTIL IT WORKS.
NO EXCUSES.

THIS IS A CRUD SYSTEM, NOT A DEMO.
