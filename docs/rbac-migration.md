# RBAC Migration & Role Model

## Identity model

Two separate authenticatable identities:

| Identity | Model | Login | Purpose |
|---|---|---|---|
| User (staff-side) | `App\Models\User` | `/login` | Agency team: **owner**, **admin**, **staff**, **agent** |
| Client (portal) | `App\Models\Client` | `/portal/login` | Buyers, sellers, tenants, owners |

Within the staff app, **role-based access control (RBAC)** is the source of truth:

- `roles` / `permissions` / `user_roles` / `role_permissions` / `user_permissions` tables.
- A user can hold **multiple roles** — and effective permissions are the union of role permissions plus optional per-user direct overrides.
- `users.role` is kept as a **display cache** of the highest-privilege role (`owner` > `admin` > `staff` > `agent`). All authorization reads go through `$user->hasRole()` / `$user->hasPermission()`.

## Roles

| Role | Scope | Permissions | Agent record? | Sees |
|---|---|---|---|---|
| `super_admin` | Global (platform) | All | No | All companies (management console) |
| `owner` | Global system role, one per company | All | No | Everything in own company |
| `admin` | Per-company system role | All operational permissions | No | Everything in own company |
| `staff` | Per-company system role | All except commissions/payouts/RBAC | **No** | All records, no commission/payout screens |
| `agent` | Per-company system role | Sales-focused set; own-scoped | **Yes** | Only records assigned to / created by them |
| `client` | Per-company (portal helper) | — | — | Portal only |

## Key files

- `database/seeders/RolesAndPermissionsSeeder.php` — system permission catalog + global roles + per-company starter roles.
- `app/Observers/CompanyObserver.php` — creates `admin`/`staff`/`agent` company roles whenever a `Company` is created/restored.
- `app/Traits/HasRoles.php` — `roles()`, `hasRole()`, `hasPermission()`, `assignRole()`, `syncPrimaryRoleCache()`, etc.
- `app/Http/Middleware/CheckRole.php` — `role:<slug>` middleware (hierarchy-aware).
- `app/Http/Middleware/CheckPermission.php` — `permission:<slug>` middleware. Supports alternatives with `|`, e.g. `permission:view_all_commissions|view_own_commissions`.
- `app/Scopes/AgentScope.php` — global query scope that restricts a pure **agent** to their own records.

## Agent scoping

`AgentScope` is registered on `Property`, `Deal`, `Client`, `Quotation`, `PropertyVisit`, `Commission`.
It applies **only** when the authenticated user is an `agent` (has the `agent` role **and** an `agent_id`).
It is skipped for `owner`, `admin`, `staff`, and `super_admin`.

Scoping keys:

| Model | Column | Meaning |
|---|---|---|
| `Property` | `assigned_agent_id` | Agent the listing is assigned to |
| `Deal` | `agent_id` | Agent who closed the deal |
| `Client` | `created_by` | User who created the client |
| `Quotation` | `created_by` | User who created the quotation |
| `PropertyVisit` | `agent_id` | Agent handling the visit |
| `Commission` | `agent_id` | Agent the commission belongs to |

To bypass the scope (admin reports/exports that need everything), use:

```php
Property::withoutGlobalScope(App\Scopes\AgentScope::class);
```

## Create/update conventions

Controllers auto-fill ownership when the current user is an agent:

- `PropertyController::store` — defaults `assigned_agent_id` to the acting agent.
- `DealController::store` — defaults `agent_id` to the acting agent.
- `ClientController::store` — sets `created_by` to the acting user.
- `QuotationController::store` — sets `created_by` to the acting user.

## Legacy conversion (one-time)

Existing installations with the old `users.role` string column should run:

```bash
php artisan rbac:migrate-legacy-roles              # convert users
php artisan rbac:migrate-legacy-roles --dry-run   # preview only
```

What it does:

- `users.role = 'super_admin'` → `super_admin` role.
- `users.role = 'admin'` → `owner` role (first owner per company) or `admin` role.
- `users.role = 'agent'` → `agent` role.
- Backfills `clients.created_by` for orphaned clients (assigns to the first agent user of the company).

The command is idempotent and safe to re-run.

## Testing

```bash
php artisan test --testsuite=Feature
```

Covers: owner/staff/agent access to RBAC screens, agent property scoping, permission enforcement on commissions, and the reviewer of the legacy migration behavior.

## New company onboarding

1. Company created → `CompanyObserver` seeds `admin`, `staff`, `agent` roles under that company.
2. Register → `AuthController::register` creates the company and a user assigned the **owner** role.
3. Owner visits **Admin → Users → Roles & Permissions** to assign roles, grant/deny direct permissions, or manage roles/permissions under **Roles**.