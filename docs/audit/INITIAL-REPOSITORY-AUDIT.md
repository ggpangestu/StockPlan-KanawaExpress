# Initial Repository Audit

## Audit scope and method

This document preserves the initial repository-wide audit in a durable, readable form. The review covered the dependency manifests, routes, middleware, authentication and authorization, role dashboards, migrations, seeders, factories, models and relationships, controllers and validation, helpers, Blade views and components, Alpine.js and JavaScript, reports, exports, AJAX endpoints, automated tests, and every identifiable business module.

No application code was executed as part of the initial review. No migrations, seeders, tests, builds, schedulers, exports, browser sessions, or database queries were run. No file was modified during that review.

Evidence labels used throughout this document:

- `SOURCE_VERIFIED`: directly observed in repository source. This does not mean runtime-tested.
- `INFERRED_FROM_CODE`: a likely consequence of source behavior, not observed during execution.
- `NEEDS_RUNTIME_VERIFICATION`: source wiring exists or a condition is unknown, but execution is required to establish behavior.
- `PARTIALLY_IMPLEMENTED`: a module has working source paths but important parts of its expected scope are absent or incomplete.
- `SCAFFOLDED_ONLY`: names, schema, UI, or framework structure exists without a complete operational workflow.
- `UNUSED`: source, dependency, field, or view appears to have no active application consumer.
- `UNKNOWN`: the repository does not establish the fact.

## Architecture and dependencies

- `SOURCE_VERIFIED`: `composer.json` requires PHP `^8.3`, Laravel `^13.0`, Doctrine DBAL, Maatwebsite Excel, and Tinker. `composer.lock` pins Laravel framework `v13.6.0`. Laravel Breeze is a development dependency supplying the authentication scaffolding.
- `SOURCE_VERIFIED`: `package.json` uses Vite, Tailwind CSS, Alpine.js with the collapse plugin, ApexCharts, Lucide, and SweetAlert2. Chart.js is installed as well.
- `SOURCE_VERIFIED`: the application is a session-based Blade application. There is no `routes/api.php`, SPA framework, API resource layer, policy directory, observer layer, domain job, or domain event/listener implementation in the repository.
- `SOURCE_VERIFIED`: business behavior is concentrated in controllers and Eloquent models. No application-specific service, action, or repository layer was found.
- `SOURCE_VERIFIED`: `bootstrap/app.php` registers the custom `role` middleware alias. `app/Providers/AppServiceProvider.php` contains no application bootstrapping behavior.
- `SOURCE_VERIFIED`: `app/helpers.php` defines `formatDecimal()`, is autoloaded through Composer, and is used by the raw-material UI.
- `NEEDS_RUNTIME_VERIFICATION`: neither `public/build/manifest.json` nor `public/hot` was present during the audit, so Vite-backed pages require a build or development server before browser behavior can be established.
- `SOURCE_VERIFIED`: the Composer setup script expects `.env.example`, but that file is absent from the repository.
- `SOURCE_VERIFIED`: report and ordering queries use MySQL-specific expressions including `FIELD`, `YEAR`, `MONTH`, and `DATE_FORMAT`.
- `SOURCE_VERIFIED`: configuration and the automated test environment use SQLite defaults in relevant places.
- `NEEDS_RUNTIME_VERIFICATION`: the behavior of the MySQL-specific expressions on the actual configured database, and their portability to SQLite or another driver, was not exercised.

## Module classification

The status in this table is a source-code classification. It is not a runtime test result.

| Module | Source-code status | Summary |
|---|---|---|
| Login and logout | `SOURCE_VERIFIED` | Username/email authentication, remember-me, throttling, session regeneration, logout, and session invalidation are wired. |
| Public registration | `PARTIALLY_IMPLEMENTED` | Registration routes and controller exist, but the login view does not link to registration and omitted role input falls through to the database default of `armada`. |
| Email verification | `PARTIALLY_IMPLEMENTED` | Routes/controllers/views exist, but `User` does not implement `MustVerifyEmail`; verification is not enforced by route middleware. |
| Password reset | `PARTIALLY_IMPLEMENTED` | Standard email reset flow exists; seeded and owner-created operational accounts can lack email addresses. |
| Profile | `PARTIALLY_IMPLEMENTED` | Update/delete controller paths exist; the current layout does not render the component slot used by the profile view. |
| Role middleware and dashboard routing | `SOURCE_VERIFIED` | Exact `owner`, `produksi`, and `armada` role dispatch exists; unknown roles receive 403. |
| Fine-grained authorization | `PARTIALLY_IMPLEMENTED` | Most authorization is role-level. Armada session mutation adds an explicit logged-in Armada ownership check. No policies or gates were found. |
| Armada user administration | `PARTIALLY_IMPLEMENTED` | Owner list/create/store paths exist; resource show/edit/update/delete actions are empty. |
| Raw-material master data | `PARTIALLY_IMPLEMENTED` | List/create/edit/update/archive behavior exists; resource `show()` and `destroy()` are empty. |
| Unit conversion | `SOURCE_VERIFIED` | Purchase-unit-to-base-unit conversion and total-stock calculation are used in inventory workflows. |
| Sealed/opened stock lifecycle | `PARTIALLY_IMPLEMENTED` | Restock adds sealed packages and production opens packages automatically; no explicit package-opening action or opening ledger entry exists. |
| Restock | `SOURCE_VERIFIED` | Stock, current price, and a transaction snapshot are updated in a controller database transaction. |
| Manual stock adjustment | `SOURCE_VERIFIED` | Add/reduce operations affect opened base-unit stock and create ledger snapshots. |
| Raw-material transaction ledger | `PARTIALLY_IMPLEMENTED` | Restocks and adjustments are recorded; production consumption and production waste deductions are not written to this ledger. |
| Current recipe/BOM | `SOURCE_VERIFIED` | Menu-to-material pivot quantities, CRUD, current HPP calculation, and production expansion exist. |
| Versioned or historical BOM | `SCAFFOLDED_ONLY` | No recipe, unit, price, or cost snapshot is persisted for a plan or production run. |
| Production planning | `SOURCE_VERIFIED` | Owner create/update/delete planning and target-quantity flows exist in the production index side panel. |
| Production execution and yield | `PARTIALLY_IMPLEMENTED` | Start/complete, material deduction, waste, and finished-goods creation exist; actual yield is unused and completion has no source-level status guard. |
| Finished-goods inventory | `SOURCE_VERIFIED` | Menu/production batches, quantities, dates, statuses, and allocation are represented and used. |
| Automatic expiry | `NEEDS_RUNTIME_VERIFICATION` | Command and scheduler registration exist; scheduler execution was not observed. |
| Armada distribution | `SOURCE_VERIFIED` | Allocation, removal from available batch stock, sessions, and sold-count entry are connected. |
| Sales | `PARTIALLY_IMPLEMENTED` | Only a mutable aggregate `quantity_sold` is stored; no sale event, order, customer, payment, price, or revenue record exists. |
| Returns inspection | `SOURCE_VERIFIED` | Pending return, ready re-entry, rejection, checker identity, and inspection metadata exist. |
| Waste and disposal | `PARTIALLY_IMPLEMENTED` | Production raw-material waste and rejected-return disposal exist; there is no general finished-goods waste ledger or separate disposal event record. |
| Raw-material transaction report | `SOURCE_VERIFIED` | Filters, KPIs, charts, composition, pagination, and partial AJAX responses exist for available transaction rows. |
| Production report | `PARTIALLY_IMPLEMENTED` | Reporting exists but is target-quantity/current-BOM based; actual output and historical recipe/cost snapshots are absent. |
| XLSX export | `NEEDS_RUNTIME_VERIFICATION` | Export class and route exist; workbook generation was not run. |
| AJAX and live polling | `NEEDS_RUNTIME_VERIFICATION` | Native fetch endpoints and five-second owner polling are wired but were not browser-tested. |
| ROP | `SCAFFOLDED_ONLY` | Minimum stock, static health thresholds, and alerts exist; no complete reorder-point engine or workflow was found. |
| MRP | `PARTIALLY_IMPLEMENTED` | Current BOM multiplied by production target produces reservation/availability visibility; requirements are not persisted, time-phased, or connected to procurement. |
| Factories and seeders | `PARTIALLY_IMPLEMENTED` | User factory/seeding exists; no domain factories or business seed data were found. |
| Automated tests | `PARTIALLY_IMPLEMENTED` | Tests are mostly Breeze authentication/profile tests; business modules are uncovered and at least one route assertion conflicts with current routes. |
| Cache and queue infrastructure | `UNUSED` | Framework tables/configuration exist; no domain job or explicit domain cache use was found. |

## Authentication, roles, and dashboard routing

The route definitions are in `routes/web.php` and `routes/auth.php`.

- `SOURCE_VERIFIED`: `GET /dashboard` invokes `DashboardController::index()`.
- `SOURCE_VERIFIED`: exact role `owner` returns view `dashboard.owner`.
- `SOURCE_VERIFIED`: exact role `produksi` returns a calculated `dashboard.produksi` response.
- `SOURCE_VERIFIED`: exact role `armada` returns view `dashboard.armada`.
- `SOURCE_VERIFIED`: any other authenticated role reaches an HTTP 403 response.
- `SOURCE_VERIFIED`: Owner, Produksi, and Armada route groups use both `auth` and `role:<role>` middleware.
- `SOURCE_VERIFIED`: `app/Http/Middleware/RoleMiddleware.php` performs a direct string comparison. The database role column is an unconstrained string.
- `SOURCE_VERIFIED`: Owner and Produksi domain records are generally not filtered by creator identity. `App\Http\Controllers\Armada\SessionController::updateSold()` and `finish()` explicitly require `armada_user_id` to match the logged-in user.
- `SOURCE_VERIFIED`: no application route uses the `verified` middleware.

`app/Http/Requests/Auth/LoginRequest.php` accepts either username or email and rate-limits authentication attempts using the submitted credential plus IP. `resources/views/auth/login.blade.php` displays a field named `username`; an email can still be submitted through that field because the request determines the credential column. The view contains no registration or forgot-password link.

`App\Http\Controllers\Auth\RegisteredUserController::store()` generates a username from the supplied name and does not set `role`. `database/migrations/2026_05_04_013516_add_username_and_role_to_users_table.php` gives the role column an `armada` default. Therefore public registration is source-wired to create an Armada-role user unless another layer changes the value.

`database/seeders/DatabaseSeeder.php` invokes `UserSeeder`, which upserts `owner`, `produksi`, and three Armada accounts with password `123456` and no email address. The password-reset route is email based, so these seeded accounts have no reset destination until an email is present. `database/factories/UserFactory.php` generates unique username/email values, verified-email timestamps, role `armada`, and provides an `unverified()` state; it is the only factory.

`SOURCE_VERIFIED`: email verification route/controller/view scaffolding exists. Registration dispatches Laravel's `Registered` event, but `app/Models/User.php` does not implement the `MustVerifyEmail` contract used by the standard verification-listener condition; no protected application route requires verification.

`INFERRED_FROM_CODE`: `ConfirmablePasswordController::store()` validates through the current user's nullable email rather than an immutable user ID or username. Which null-email account is selected when multiple operational accounts have null email requires execution against the actual database.

`SOURCE_VERIFIED`: profile update and deletion are scoped to the authenticated user. `resources/views/profile/edit.blade.php` uses `<x-app-layout>`, while `resources/views/layouts/app.blade.php` renders `@yield('content')` and does not output `$slot`. The profile partials are consequently not inserted into the active layout by the source template composition. The only profile navigation link is in the unused `resources/views/layouts/navigation.blade.php`; the active role layout/sidebars do not expose it.

### Incomplete resource actions

| Resource | Source-wired actions | Incomplete or absent generated actions |
|---|---|---|
| `owner.raw-materials` | index, create, store, edit, update, plus custom stock actions | `show()` and `destroy()` are empty. |
| `owner.menus` | index, create, store, edit, update, toggle active | `show()` and `destroy()` are absent from the controller despite resource routes. |
| `owner.productions` | index, store, show, update, destroy | `create()` and `edit()` are absent despite resource routes. |
| `owner.armada` | index, create, store | show, edit, update, and destroy methods are empty. |

`SOURCE_VERIFIED`: `resources/views/owner/productions/create.blade.php` is wholly enclosed in a Blade comment. The active owner planning form is the side panel in `resources/views/owner/productions/index.blade.php`; the standalone create view is `UNUSED`.

## Database and model map

The principal domain chain observed in migrations and Eloquent models is:

```text
users
 ├─ raw_materials ─ raw_material_transactions
 │        └─ menu_raw_material ─ menus
 │                                  └─ production_items ─ productions
 │                                                           ├─ production_wastes
 │                                                           └─ finished_goods
 └─ armada_sessions ─ armada_session_items ─ return_checks
```

### Model relationships

- `SOURCE_VERIFIED`: `RawMaterial` has many raw-material transactions and belongs to many menus.
- `SOURCE_VERIFIED`: `Menu::ingredients()` is a many-to-many relationship through `menu_raw_material`, with pivot field `quantity`.
- `SOURCE_VERIFIED`: `Production` has many production items and production wastes and belongs to its creator.
- `SOURCE_VERIFIED`: `ProductionItem` belongs to a menu. It does not define an inverse `production()` relationship.
- `SOURCE_VERIFIED`: `FinishedGood` belongs to a menu and production and has Armada session items and return checks.
- `SOURCE_VERIFIED`: `ArmadaSession` belongs to an Armada user and allocator and has many items.
- `SOURCE_VERIFIED`: `ArmadaSessionItem` belongs to a session and finished-good batch.
- `SOURCE_VERIFIED`: `ReturnCheck` connects a session item, original finished-good batch, Armada, checker, and optional rejected finished-good batch.
- `SOURCE_VERIFIED`: domain models do not use soft deletes.

### Schema and model observations

- `SOURCE_VERIFIED`: `raw_materials.sealed_stock` is an integer package count. `opened_stock` and recipe usage are decimal base-unit quantities.
- `SOURCE_VERIFIED`: the final migration sequence removes `production_items.wasted_quantity`, while `ProductionItem::$fillable` still includes `wasted_quantity`; that fillable entry is `UNUSED` against the final schema.
- `SOURCE_VERIFIED`: `production_items.actual_quantity` is written as zero during planning and is not updated or read in the application; it is `UNUSED` in the current workflow.
- `SOURCE_VERIFIED`: `production_usage` exists in the raw-material transaction type enum/model/report filters but no controller writes that transaction type; it is `SCAFFOLDED_ONLY`.
- `SOURCE_VERIFIED`: production status `cancelled` exists in schema/UI handling but no controller changes a production to `cancelled`.
- `SOURCE_VERIFIED`: production views recognize a legacy `done` status that is not one of the migration's declared statuses.
- `SOURCE_VERIFIED`: the `down()` path of `database/migrations/2026_06_06_064510_create_production_wastes_table.php` does not restore the removed `production_items.wasted_quantity` column.
- `SOURCE_VERIFIED`: `allocated_by` is created in the Armada sessions migration and addressed again by `database/migrations/2026_06_04_000004_add_allocated_by_to_armada_sessions_table.php`; the later guarded migration is redundant in the forward path and can remove the originally created column if that migration alone is rolled back.
- `SOURCE_VERIFIED`: only `database/factories/UserFactory.php` and user seeders exist. Business-domain tables have no factories or seeders.

## Major workflow traces

### 1. Raw materials, conversion, restock, and adjustment

Owner raw-material routes under `/owner/raw-materials` call `app/Http/Controllers/RawMaterialController.php`.

Creation validates image, name, category, purchase unit, base unit, `conversion_value >= 1`, and minimum stock. New records start with zero stock.

`SOURCE_VERIFIED`: total stock is calculated in base units as:

```text
opened_stock + (sealed_stock × conversion_value)
```

Restock flow:

```text
POST owner/raw-materials/{rawMaterial}/restock
→ RawMaterialController::storeRestock()
→ increment sealed_stock by whole purchase packages
→ optionally update latest_price
→ create raw_material_transactions row of type restock
→ return JSON to the Alpine modal
→ browser code reloads the page and uses a sessionStorage toast
```

The restock ledger row stores converted base-unit `quantity`, purchase-package `purchase_quantity`, total-base-unit `before_stock` and `after_stock`, purchase-package `unit_price`, and `total_price`. The first restock requires a price; later restocks can reuse the material's current price.

`SOURCE_VERIFIED`: `RawMaterialController::updateRestockPrice()` can change any restock row, recalculates its total, and also assigns the edited price to `raw_materials.latest_price`, including when the selected transaction is historical.

Adjustment flow:

```text
POST owner/raw-materials/{rawMaterial}/adjustment
→ RawMaterialController::storeAdjustment()
→ add to or reduce opened_stock only
→ reject a reduction greater than opened_stock
→ create an adjustment transaction with before/after snapshots
→ return JSON and trigger a browser reload
```

Sealed stock is not used to satisfy manual reductions. Purchase/base units and conversion become non-editable after a transaction exists.

Primary views and components are:

- `resources/views/owner/raw-materials/index.blade.php`
- `resources/views/owner/raw-materials/create.blade.php`
- `resources/views/owner/raw-materials/edit.blade.php`
- `resources/views/components/modals/restock-modal.blade.php`
- `resources/views/components/modals/adjustment-modal.blade.php`

### 2. Menu recipes and BOM

`App\Http\Controllers\MenuController::store()` and `update()` validate at least one existing raw material, a pivot quantity of at least `0.01`, selling price, and shelf life.

```text
owner menu form
→ MenuController
→ menus row
→ attach/sync menu_raw_material[raw_material_id => quantity]
→ menu listing recalculates current HPP and current stock readiness
```

Current HPP uses:

```text
(latest purchase price / conversion value) × recipe base-unit quantity
```

`SOURCE_VERIFIED`: production items persist menu and target quantities, not ingredient/unit/price/cost snapshots. Later calculations expand the menu's currently attached ingredients.

`INFERRED_FROM_CODE`: editing a recipe after a plan was created changes what later completion code expands and what historical production reporting calculates.

The menu views are physically stored in `resources/views/owner/Menus/`, while the controller names lowercase `owner.menus.*` views. This matches paths on the audited Windows filesystem. Case-sensitive deployment behavior is `NEEDS_RUNTIME_VERIFICATION`.

### 3. Production planning and MRP-like reservation visibility

Owner planning is handled by `app/Http/Controllers/Owner/ProductionController.php`.

The index loads planned and processing productions, expands each current recipe as pivot quantity multiplied by target quantity, and constructs in-memory physical, reserved, and available material quantities for the planning side panel in `resources/views/owner/productions/index.blade.php`.

Store/update persist plan date, notes, menu ID, target quantity, and `actual_quantity = 0`. Browser logic presents shortage warnings and disables some interactions. The controller validation does not enforce active-menu state, stock sufficiency, reservation sufficiency, or a future plan date.

This is `PARTIALLY_IMPLEMENTED` as MRP: current-demand visibility exists, while persisted requirements, time phasing, procurement orders, and backend reservation enforcement do not.

### 4. Produksi execution, consumption, and waste

Produksi execution routes use `app/Http/Controllers/ProductionController.php`.

```text
GET /produksi/productions
→ planned/processing queue
→ resources/views/prod/index.blade.php

PATCH /produksi/productions/{production}/start
→ planned status becomes processing

POST /produksi/productions/{production}/complete
→ expand current BOM × target quantity
→ add submitted waste quantities
→ precheck physical stock
→ set production completed
→ open enough sealed packages
→ deduct recipe and waste quantities
→ create production_wastes rows
→ create finished_goods at the full target quantity
```

Direct source findings:

- `SOURCE_VERIFIED`: `complete()` has no guard requiring `processing` status.
- `INFERRED_FROM_CODE`: a direct or repeated completion request can consume material and create finished goods again if stock remains sufficient.
- `SOURCE_VERIFIED`: actual output is not collected; the finished-goods quantity is the target quantity.
- `SOURCE_VERIFIED`: normal consumption and production waste do not create `RawMaterialTransaction` rows.
- `SOURCE_VERIFIED`: top-level waste input is validated as an array, but nested material IDs and quantities do not have `exists`, numeric, or minimum validation rules.
- `SOURCE_VERIFIED`: the availability precheck runs before the controller database transaction and does not lock raw-material rows.
- `SOURCE_VERIFIED`: finished-goods production and expiry dates use completion time plus the menu's current `expires_in_days` value.
- `SOURCE_VERIFIED`: `ProductionController::show()` and production views reference `$rawMaterial->unit`, while the material model/schema uses `base_unit`; displayed unit labels can therefore be blank from the template source.

Primary views are:

- `resources/views/prod/index.blade.php`
- `resources/views/prod/show.blade.php`
- `resources/views/owner/productions/index.blade.php`
- `resources/views/owner/productions/show.blade.php`

### 5. Finished goods and expiry

`App\Http\Controllers\Owner\FinishedGoodController::index()` provides owner-side, read-only monitoring with available, empty, expired, and expired/damaged filters.

Each finished-good row is treated as a batch with menu, production, initial/current quantity, production/expiry dates, and lifecycle status.

`app/Console/Commands/ExpireFinishedGoods.php` marks overdue rows whose status is `available` as `expired`, preserving their quantity. `routes/console.php` schedules the command every minute, while an adjacent source comment describes daily execution at 00:01.

`NEEDS_RUNTIME_VERIFICATION`: whether an external scheduler invokes Laravel scheduling is unknown. List and allocation queries also compare dates, but status-only summaries depend on status updates and may differ if the scheduler is not active.

### 6. Distribution, sales counts, returns, and disposal

Owner allocation flow:

```text
POST /owner/armada-sessions
→ validate selected user has Armada role and quantities are positive
→ application-level check for an existing active session
→ lock selected finished_goods rows
→ validate availability, expiry date, and quantity
→ decrement finished_goods.current_quantity
→ create armada_session and armada_session_items
```

The owner UI orders available goods by expiry date but does not force automatic FEFO selection. `resources/views/owner/armada-sessions/index.blade.php` polls `/owner/armada-sessions/live` every five seconds.

Armada flow:

```text
PATCH /armada/sessions/{session}/sold
→ overwrite cumulative quantity_sold

PATCH /armada/sessions/{session}/finish
→ save final sold counts
→ calculate quantity_returned = sent - sold
→ create a pending return_check for each positive return
→ finish the session
```

Produksi return flow:

```text
PATCH /produksi/returns/{returnCheck}
├─ ready
│  └─ add quantity back to the original finished-good batch
└─ expired_damaged
   └─ create a separate rejected finished-good row

PATCH /produksi/returns/{returnCheck}/dispose
→ set rejected batch quantity to zero and status to empty
```

`SOURCE_VERIFIED`: sales are represented only by mutable aggregate item quantities. The schema and controllers contain no monetary sale event, per-sale timestamp, price snapshot, revenue, customer, payment, or sales-order record. Finished-good quantity leaves the available batch during allocation rather than when `quantity_sold` is entered.

## Reports, export, and AJAX

### Raw-material transaction report

`app/Http/Controllers/Owner/TransactionReportController.php` supplies:

- `/owner/reports/transactions`
- `/owner/reports/transactions/chart`
- `/owner/reports/transactions/composition`
- `/owner/reports/transactions/export`

It supports material-name search, transaction type, year, and month filters; paginates 15 rows; calculates transaction/restock/adjustment KPIs; and builds restock purchase-value charts at year/month/day granularity.

`resources/views/owner/reports/transactions/index.blade.php` uses native `fetch`, server-rendered table/KPI partials, ApexCharts, AJAX pagination, and `history.replaceState()`.

`SOURCE_VERIFIED`: chart and composition actions consume year/month but not the page's search/type filter combination. A `production_usage` type filter is present even though the application has no writer for that type.

`app/Exports/TransactionReportExport.php` defines XLSX columns for date, material, type, quantity/unit, before/after stock, and purchase value and applies all four filters. Workbook generation is `NEEDS_RUNTIME_VERIFICATION`.

### Production report

`app/Http/Controllers/Owner/ProductionReportController::index()` returns both the main page and AJAX partial responses. It includes a table across statuses, completed-production KPIs, target portions, average portions per unique production date, top menu/material/waste metrics, and yearly/monthly/weekly trends.

`SOURCE_VERIFIED`: production quantities are target based, and material consumption is recalculated from current recipes. There is no production-report export. `$totalProductionCost` is initialized but not used. The monthly calculation branch writes debug log records for individual production rows.

No finished-goods, sales, finance, ROP, or full MRP reports were found. Finished-goods and finance sidebar entries appear only as commented placeholders.

## Blade, Alpine.js, and JavaScript

`resources/js/app.js` registers Alpine.js, the collapse plugin, Lucide, ApexCharts, and the toast manager.

Active source-wired browser behaviors include:

- sidebar state persisted in `localStorage`;
- expandable raw-material rows;
- JSON restock and adjustment modals;
- optimistic raw-material and menu active-state toggles;
- production-planning side panel;
- production-waste input rows;
- report partial replacement and chart updates;
- Armada-session live polling; and
- success notifications transferred through `sessionStorage`.

Direct frontend findings:

- `SOURCE_VERIFIED`: `addErrorToast()` constructs a hidden toast and never sets its `show` property to true.
- `SOURCE_VERIFIED`: early toast removal can leave `startToastTimer()` reading a toast after it has been removed.
- `SOURCE_VERIFIED`: menu active-state toggling does not inspect `response.ok`, catch request failure, or restore optimistic UI state.
- `SOURCE_VERIFIED`: raw-material scroll restoration searches for `#main-content`, but the active layout contains no element with that ID.
- `SOURCE_VERIFIED`: some raw-material names are interpolated directly into Alpine expressions rather than JSON encoded; quotes or newlines are not escaped for a JavaScript expression.
- `NEEDS_RUNTIME_VERIFICATION`: an initial console rendering displayed mojibake for some non-ASCII source literals. A later explicit UTF-8 read showed valid Unicode symbols/emoji in those locations, so this is not retained as a source defect; browser/deployment encoding still requires runtime confirmation.
- `SOURCE_VERIFIED`: owner mobile navigation omits Menu, the separate Armada Sessions entry, and Production Reports, although routes/views for those destinations exist.
- `SOURCE_VERIFIED`: search/tag controls on the Armada administration page are not wired, and the page contains hard-coded progress values including `67 / 90`, `75%`, and a fixed width.

Unused or disconnected frontend elements:

- `UNUSED`: Chart.js is installed but not imported.
- `UNUSED`: SweetAlert2 is imported/exposed but no call site was found.
- `UNUSED`: `@tailwindcss/vite` is installed but not registered in `vite.config.js`.
- `UNUSED`: `resources/js/bootstrap.js` is empty.
- `UNUSED`: `resources/views/layouts/navigation.blade.php` is not used by the active layout and refers to nonexistent route name `owner.armada`.
- `UNUSED`: Bootstrap and Semantic UI pagination templates exist; application pagination uses the Tailwind templates.

## Automated tests

The repository contains 25 test methods across Breeze authentication tests, five profile tests, one feature placeholder, and one unit placeholder. `phpunit.xml` configures SQLite in memory, array mail/cache/session drivers, and a synchronous queue.

Direct test findings:

- `SOURCE_VERIFIED`: `tests/Feature/ExampleTest.php` expects `GET /` to return HTTP 200.
- `SOURCE_VERIFIED`: `routes/web.php` redirects `/` to `/login`, so the route definition represents a 302 response rather than the asserted 200.
- `NEEDS_RUNTIME_VERIFICATION`: view-rendering tests may require a Vite manifest or hot server because neither Vite marker was present during the audit.
- `SOURCE_VERIFIED`: `tests/Feature/ProfileTest.php` asserts only that the profile request is successful; it does not assert that the profile forms are rendered.
- `SOURCE_VERIFIED`: authentication tests exercise email login rather than the username credential path.
- `SOURCE_VERIFIED`: no tests cover raw materials, conversion, restock, adjustment, BOM, production, finished goods, expiry, Armada allocation, Armada ownership, sold counts, returns, waste, reports, export, AJAX endpoints, ROP, MRP, role allow/deny behavior, or concurrency.

The test suite was not executed during the initial audit.

## Inferred behavior requiring runtime verification

The following are logical source-level risk hypotheses, not observed failures:

- `INFERRED_FROM_CODE`: concurrent restocks or adjustments may lose updates because raw-material rows are not locked or reloaded inside the mutation transaction.
- `INFERRED_FROM_CODE`: production completion has a time-of-check/time-of-use interval; concurrent completions may pass checks against the same stock.
- `INFERRED_FROM_CODE`: when different menu items share a material, separate stale Eloquent material instances used during one completion may overwrite an earlier deduction.
- `INFERRED_FROM_CODE`: simultaneous session finishes may create duplicate return checks because active-state checking occurs before the transaction and no uniqueness constraint enforces one return check per logical return.
- `INFERRED_FROM_CODE`: simultaneous return inspections may process the same pending check twice because pending status is not rechecked after row locking.
- `INFERRED_FROM_CODE`: simultaneous first allocations may create more than one active session for an Armada user because the invariant is application-checked without a database uniqueness constraint.
- `INFERRED_FROM_CODE`: editing recipes or current prices changes inputs later used for historical calculations because snapshots are absent. Unit/conversion fields are controller-locked after transaction history exists, but plans do not store their own unit/conversion snapshot.
- `INFERRED_FROM_CODE`: deletion of an operational user can cascade some history or be blocked by restrictive creator foreign keys depending on the referenced records and live database enforcement.
- `INFERRED_FROM_CODE`: password confirmation for one of several null-email seeded accounts may select a different null-email user.

## Runtime unknowns

The initial static audit did not establish the following:

- `UNKNOWN`: which migrations have been applied in the deployed database and whether live tables match the final migration sequence.
- `UNKNOWN`: the deployed database driver, isolation level, and foreign-key enforcement behavior.
- `UNKNOWN`: whether a Vite build or Vite development server is available in the running environment.
- `UNKNOWN`: whether the Laravel scheduler is continuously running.
- `UNKNOWN`: mail transport and delivery behavior.
- `UNKNOWN`: actual Excel export generation and download behavior.
- `UNKNOWN`: image upload filesystem configuration and browser-accessible URLs.
- `UNKNOWN`: browser execution of Alpine.js, ApexCharts, live polling, modals, and optimistic controls.
- `UNKNOWN`: MySQL-specific query behavior if the application is run against SQLite or another driver.
- `UNKNOWN`: current automated test results beyond mismatches directly identifiable from the source.

Each of these remains `NEEDS_RUNTIME_VERIFICATION`; none was treated as runtime-tested in this audit.
