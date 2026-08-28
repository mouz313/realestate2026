# Agency CRM — Enquiry & Property Management

A Laravel-based CRM for a single real-estate agency. Core purpose: **capture every enquiry (call / walk-in / online), never lose one, and follow up until close** — while managing 50+ listings across houses, farm houses, flats, offices, plots, shops, etc. for **buy / sell / rent**.

---

## 1. What it solves

An agency receives **100+ enquiries/day**. Each caller has their own demand (category, budget, location, bedrooms). Without a system, leads fall through the cracks.

This CRM:

- Captures every enquiry with **caller, requirement, follow-up date**
- Auto-links to an existing client (or creates one) and **matches available properties**
- Sends daily follow-up reminders (cron)
- Tracks deal progress: **Enquiry → Property Match → Visit → Deal → Quotation → Invoice → Payment**
- Splits commission correctly between **agency** and **sourced-by agent**

---

## 2. Roles

| Role | Scope |
|---|---|
| `super_admin` | Platform owner / agency owner. Full access, manages settings. |
| `admin` | Office staff. Manages all enquiries, properties, deals, finances. |
| `agent` | Field agent. Adds enquiries, sources properties, gets own commission. |

---

## 3. Property Module

### Property types (`category`)
House · Plot · Farm House · Agricultural Land · Flat · Studio Apartment · Office · Shop · Commercial · Industrial

### Transaction types (`transaction_type`)
`buy` · `sell` · `rent` · `installment`

### Status
`available` · `sold` · `rented` · `booked` · `inactive`

### Who can add a property
- **Admin** — uploads property, sets `commission_rate` at creation time
- **Agent** — "sourced" properties set `sourced_by_agent_id = self` automatically

---

## 4. Commission Rules

Locked rules (configured in `app/Services/CommissionCalculator.php`):

### Sale / Buy deal
- `agency_commission = deal_value × property.commission_rate / 100`
- If `sourced_by_agent_id` set → `agent_commission = agency_commission × 30%`
- If no sourced agent → agency keeps 100%
- **Advance / token (bayana)** is a separate value, **not** part of commission %

### Rent deal
- Base = **one month rent**
- Agency take = **50% from client + 50% from tenant = 1 full month rent**
- If `sourced_by_agent_id` set → `agent_commission = agency_take × 10%`
- If no sourced agent → agency keeps 100%

### Formula at a glance

| Scenario | Agency | Agent (if sourced_by_agent) |
|---|---|---|
| Sale @ 2% on 1 Cr | Rs 200,000 | Rs 60,000 |
| Rent @ 30k/mo | Rs 30,000 (1 month) | Rs 3,000 |

---

## 5. Enquiry Module (Call Logs)

This is the **heart** of the CRM. Every lead is a `CallLog` row.

Fields captured on every enquiry:
- **Caller**: name, phone, alternate, lead source
- **Requirement**: category, transaction_type, city, location, bedrooms, budget_min/max
- **Caller role**: `buyer` / `seller` / `tenant` / `landlord`
- **Linked client** (auto-created if phone matches existing client)
- **Matched property** (auto-match available listings to requirement)
- **Assigned agent** (who handles the follow-up)
- **Follow-up date** (mandatory before save)
- **Status**: `new` → `contacted` → `visit_scheduled` → `negotiation` → `closed_won` / `closed_lost`

### Enquiry flow

```
Call / Walk-in / Web
     │
     ▼
[CallLog created] ──► auto-link Client (or create)
     │              ──► auto-match Available Properties
     │
     ▼
[Follow-up scheduled] ──► daily cron reminder to assigned_agent
     │
     ▼
[Visit scheduled] ──► PropertyVisit record
     │
     ▼
[Deal created] ──► Quotation ──► Invoice ──► Payment
     │                                       ──► Commission (auto-split)
     ▼
[Property status = sold | rented]
```

---

## 6. Modules

| Module | Purpose |
|---|---|
| Properties | Listings CRUD, media, documents, commission setup |
| Clients | Sellers + Buyers (separated views) |
| Call Logs (Enquiries) | Core CRM — capture, match, follow-up |
| Property Visits | Schedule + status tracking |
| Deals | Sale / Rent close, links property + client + agent |
| Quotations | Versioned, PDF, mark-sent, client approval |
| Invoices | From quotation, payment tracking, PDF, recurring |
| Payments | Central payments ledger |
| Commissions | Auto-split per rules above |
| Agent Payouts | Track paid commissions to agents |
| Expenses | Agency expenses |
| Cities | Listing metadata |
| Agents | Field staff, profiles |
| Reports | Sales, agent performance, commissions, rent |
| Activity Log | Audit trail |
| Global Search | Cross-module admin search |
| Settings | Branding, SMTP, bank, real-estate defaults |

---

## 7. Tech Stack

| Layer | Tech |
|---|---|
| Backend | PHP 8.3, Laravel 13 |
| DB | MySQL / MariaDB |
| Frontend | Blade, Bootstrap 5, Tabler Icons |
| PDF | barryvdh/laravel-dompdf |
| Auth | Session, role middleware, gates |

---

## 8. Local Install

```bash
git clone <repo>
cd agency
composer install
cp .env.example .env
php artisan key:generate
# edit .env for DB
php artisan storage:link
php artisan migrate --seed
php artisan serve
```

Default logins:
- `superadmin@agency.com` / `password`
- `admin@agency.com` / `password`

---

## 9. Scheduled Tasks

| Command | Schedule | Purpose |
|---|---|---|
| `call:followups` | daily | Email agent for due follow-ups |
| `rent:verify-status` | every 6/9/12 months | Confirm rentals still active |
| `invoices:generate-recurring` | daily | Auto-create recurring invoices |
| `queue:work --stop-when-empty` | every minute | Process mail queue |

Cron:
```
* * * * * cd /path && php artisan schedule:run >> /dev/null 2>&1
```

---

## 10. Commission Service API

`App\Services\CommissionCalculator`

```php
CommissionCalculator::sale($property, $dealValue): [
    'agency_amount' => 200000,
    'agent_amount'  => 60000,   // 0 if no sourced_by_agent
    'source'        => 'sale',
]

CommissionCalculator::rent($property, $monthlyRent): [
    'agency_amount' => 30000,   // = 1 month rent (50/50 split)
    'agent_amount'  => 3000,    // 10% only if sourced_by_agent
    'source'        => 'rent',
]
```

Called from `CommissionController::store` on deal close.

---

## 11. Database Touch-points (this plan)

| Table | Change |
|---|---|
| `properties` | already has `commission_rate`, `sourced_by_agent_id` — no change |
| `commissions` | **add** `agency_amount`, `agent_amount`, `source` (enum) |
| `call_logs` | already has follow-up + status — no change |
| `deals` | already has property + agent + amount — no change |

No destructive migrations. Plan is **additive**.
