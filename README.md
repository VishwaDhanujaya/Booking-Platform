# Multi-Tenant Court & Sports Facility Booking Platform

A high-performance, multi-tenant booking engine built with **Laravel** and **Tailwind CSS** designed for sports clubs, court owners (Tennis, Padel, Badminton, Squash), and multi-facility venues.

---

## Key Features

### 🏢 Multi-Tenancy Architecture
- Shared database model with strict tenant isolation via `BelongsToTenant` Eloquent trait and global query scoping.
- Support for custom tenant branding, primary colors, subdomains, and subscription plans (`starter`, `pro`, `enterprise`).

### 🔐 Authentication & Role-Based Access Control (RBAC)
- Role-based permissions for:
  - **Owner**: Full facility settings, tenant management, and staff administration.
  - **Manager**: Operational control over court schedules, pricing rules, and customer ledgers.
  - **Trainer / Staff**: Court coaching schedules and attendance tracking.
  - **Front Desk**: Walk-in reservations, check-ins, and manual bank transfer approvals.
  - **Customer**: Court slot search, booking checkout, credit/pass redemption, and account portal.
- Customer ban management with auto-suspension triggers after excessive no-shows.

### 🎾 Court & Resource Management
- Support for indoor, outdoor, and covered courts across multiple sports (Tennis, Padel, Badminton, Squash).
- Configurable court properties: hourly rates, peak rates, surface types, max capacity, and buffer times.

### 📅 Matrix Availability & Schedule Overrides
- Real-time time slot matrix per court for 7-day rolling availability.
- Recurring operating schedules and one-off blocked time slots for tournament enclosures or court maintenance.

### 💰 Dynamic Pricing Engine
- Automated pricing adjustments evaluating:
  - **Peak / Off-Peak Windows**: E.g., evening peak surcharges.
  - **Seasonal Date Ranges**: Weekend or holiday special rates.
  - **Tiered Discounts**: Student, senior, club member, and multi-slot bulk booking discounts.

### 💳 Payment Abstraction & Internal Tender
- **Payment Contract**: Pluggable `PaymentGatewayInterface` with `ManualOfflinePaymentGateway` implementation supporting **Bank Transfer** (payment pending until staff approval) and **Pay at Venue**.
- **Internal Wallet Credits**: Full audit trail credit ledger (`amount_in`, `amount_out`, `balance_after`, `reason`, `reference`).
- **Customer Passes & Punch Cards**: Prepaid multi-unit passes with unit redemption tracking.

### 🎒 Reusable Add-On Inventory
- Itemized rental extras (rackets, match ball tubes, locker access, floodlights) priced per booking, per hour, or per item.

---

## 🛠 Tech Stack

- **Backend**: [Laravel 11](https://laravel.com) (PHP 8.2+)
- **Frontend**: Blade Components, Tailwind CSS, Alpine.js
- **Database**: SQLite (Local Dev) / MySQL / PostgreSQL
- **Asset Bundler**: [Vite](https://vitejs.dev)

---

## ⚡ Quick Start & Local Setup

### 1. Clone Repository & Install Dependencies
```bash
git clone https://github.com/VishwaDhanujaya/Booking-Platform.git
cd Booking-Platform

composer install
npm install
```

### 2. Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Database Migration & Seeders
Run a fresh migration with full sample seeders (tenants, roles, users, courts, schedules, pricing rules, credit ledgers, and sample bookings):
```bash
php artisan migrate:fresh --seed
```

### 4. Run Development Server
```bash
# Terminal 1: Laravel Backend
php artisan serve

# Terminal 2: Asset Compiler
npm run dev
```

Visit the application at `http://localhost:8000`.

---

## 🔐 Default Demo Accounts

| Role | Email | Password |
|---|---|---|
| **Owner / Admin** | `admin@colombocourts.lk` | `password` |
| **Manager** | `manager@colombocourts.lk` | `password` |
| **Trainer / Staff** | `staff@colombocourts.lk` | `password` |
| **Front Desk** | `frontdesk@colombocourts.lk` | `password` |
| **Customer (Kavinda)** | `kavinda@example.com` | `password` |

---

## 📜 License

This project is open-sourced under the [MIT license](LICENSE).
