# Current Implementation

## Purpose and evidence boundary

This document describes only the implementation observable in the repository at audit time. It records routes, classes, schema, templates, and calculations that are directly present in source.

- Every positive implementation statement is `SOURCE_VERIFIED` unless a narrower status is shown.
- `SOURCE_VERIFIED` means inspected in source; it does **not** mean runtime-tested.
- Browser behavior, database effects, exports, scheduling, and integration behavior remain `NEEDS_RUNTIME_VERIFICATION` unless explicitly stated otherwise.
- Risk hypotheses and business-policy questions are intentionally outside the scope of this document.

## Application shape

The repository is a Laravel Blade application with session authentication and role-separated route groups.

| Area | Current source implementation |
|---|---|
| Backend | PHP `^8.3`, Laravel `^13.0`, Eloquent, controller-oriented business logic |
| Frontend | Blade, Tailwind CSS, Vite, Alpine.js, Alpine collapse, ApexCharts, Lucide |
| Authentication | Laravel Breeze-derived session authentication with a customized username/email credential request |
| Authorization | `auth` middleware plus custom exact-string `role` middleware; no policies or gates found |
| Reporting/export | Blade/AJAX reports, ApexCharts, Maatwebsite Excel transaction export |
| Persistence | Laravel migrations and Eloquent models; database driver at runtime is `UNKNOWN` |
| Domain architecture | Controllers and models; no application-specific service/action/repository layer found |

Primary manifests and bootstrapping files are `composer.json`, `composer.lock`, `package.json`, `vite.config.js`, `bootstrap/app.php`, and `app/Providers/AppServiceProvider.php`.

`app/helpers.php` is autoloaded through Composer and supplies `formatDecimal()`. There is no `routes/api.php`; current application routes are in `routes/web.php`, `routes/auth.php`, and `routes/console.php`.

## Request access and roles

### Shared access

- `/` redirects to `/login`.
- `/dashboard` requires authentication and dispatches by the current user's exact `role` string.
- `/profile` GET/PATCH/DELETE routes require authentication but no role.
- Authentication routes are loaded from `routes/auth.php`.

### Dashboard dispatch

`App\Http\Controllers\DashboardController::index()` currently maps:

| Role | Dashboard response |
|---|---|
| `owner` | `resources/views/dashboard/owner.blade.php` |
| `produksi` | Calculated data rendered by `resources/views/dashboard/produksi.blade.php` |
| `armada` | `resources/views/dashboard/armada.blade.php` |
| Any other value | HTTP 403 |

### Role route groups

- Owner endpoints use `auth` and `role:owner` and cover raw materials, menus, production planning, finished goods, Armada users/sessions, and reports.
- Produksi endpoints use `auth` and `role:produksi` and cover production execution and returned-goods inspection/disposal.
- Armada endpoints use `auth` and `role:armada` and cover assigned sessions, cumulative sold counts, and session completion.
- `app/Http/Middleware/RoleMiddleware.php` compares the authenticated user's role directly with the route argument.
- No route currently uses Laravel's `verified` middleware.

## Authentication and account management

### Login and logout

`App\Http\Requests\Auth\LoginRequest` accepts either a `username` input or a separate `email` input, prefers a nonempty `username`, then treats the selected value as an email when it passes email validation and otherwise as a username. It rate-limits attempts by the selected credential and IP. `App\Http\Controllers\Auth\AuthenticatedSessionController` regenerates the session after successful login and invalidates the session on logout.

The active form is `resources/views/auth/login.blade.php`. It displays a username-labelled input and does not display registration or forgot-password links.

### Registration

`PARTIALLY_IMPLEMENTED`: guest GET/POST `/register` routes, `RegisteredUserController`, and `resources/views/auth/register.blade.php` exist. The controller derives a username from the entered name and does not assign a role. The users migration supplies the default role `armada`. Registration is not linked from the login view.

### Email verification and password recovery

`PARTIALLY_IMPLEMENTED`: Breeze-derived verification routes/controllers/views are present. `app/Models/User.php` does not implement `MustVerifyEmail`, and application routes do not require verification.

`PARTIALLY_IMPLEMENTED`: email-based forgot/reset-password routes and views are present. `database/seeders/UserSeeder.php` creates operational users without email addresses, so the source data for those seeded accounts contains no email reset destination.

`PARTIALLY_IMPLEMENTED`: `ConfirmablePasswordController::store()` builds its credential check from the current user's nullable email rather than ID/username. The runtime result of that query was not exercised and remains `NEEDS_RUNTIME_VERIFICATION`.

### Profiles

`App\Http\Controllers\ProfileController` and `App\Http\Requests\ProfileUpdateRequest` implement authenticated profile update and deletion. Password update has its own authenticated route/controller.

`PARTIALLY_IMPLEMENTED`: `resources/views/profile/edit.blade.php` uses the `AppLayout` component and its slot, while `resources/views/layouts/app.blade.php` renders a section with `@yield('content')` and does not emit `$slot`. The profile partials therefore are not connected to an output location in the current layout templates. A profile link exists only in the unused `resources/views/layouts/navigation.blade.php`, not in the active role layout/sidebars.

## User and role administration

`App\Http\Controllers\ArmadaController` provides Owner-side Armada user listing, creation form, and creation. Its resource `show`, `edit`, `update`, and `destroy` methods are present but empty, so the resource is `PARTIALLY_IMPLEMENTED`.

The associated views are:

- `resources/views/owner/armada/index.blade.php`
- `resources/views/owner/armada/create.blade.php`

`database/seeders/DatabaseSeeder.php` invokes `UserSeeder`, which upserts one owner, one produksi, and three Armada users with a common source-defined password and null email fields. `database/factories/UserFactory.php` is the only factory; it creates a unique username/email, verified email timestamp, role `armada`, and a shared factory password, with an `unverified()` state that clears the verification timestamp. There are no factories or seed datasets for operational domain records.

## Raw-material inventory

### Master data

`App\Http\Controllers\RawMaterialController` implements Owner-side index, create, store, edit, update, restock, adjustment, restock-price update, and active-state toggle operations.

The master record includes name/category/image, purchase and base units, conversion value, minimum stock, sealed stock, opened stock, latest purchase price, and active state. Creation starts stock at zero and requires conversion value at least `1`; a pre-transaction edit permits conversion value at least `0.01`. Unit and conversion editing is disabled by controller/view logic once transactions exist.

`PARTIALLY_IMPLEMENTED`: the generated resource `show()` and `destroy()` actions are empty. Archiving is represented through active-state toggling rather than deletion.

Views/components:

- `resources/views/owner/raw-materials/index.blade.php`
- `resources/views/owner/raw-materials/create.blade.php`
- `resources/views/owner/raw-materials/edit.blade.php`
- `resources/views/components/modals/restock-modal.blade.php`
- `resources/views/components/modals/adjustment-modal.blade.php`

### Unit conversion and stock representation

The current source uses two stock fields:

- `sealed_stock`: integer count of unopened purchase packages.
- `opened_stock`: decimal quantity expressed in the base unit.

Total stock in base units is calculated as:

```text
opened_stock + (sealed_stock × conversion_value)
```

`conversion_value` is therefore the number of base units represented by one purchase package.

### Restock

`RawMaterialController::storeRestock()` validates a whole purchase-package quantity, increments `sealed_stock`, optionally updates `latest_price`, and creates a `raw_material_transactions` restock row inside a database transaction.

The restock transaction stores:

- converted base-unit quantity;
- purchase-package quantity;
- before/after total-stock snapshots;
- price per purchase package; and
- total purchase value.

The first restock requires a price; a later restock can reuse the current `latest_price`. `updateRestockPrice()` changes the selected transaction's `unit_price` and `total_price` and also assigns that unit price to the material's `latest_price`.

### Adjustment

`RawMaterialController::storeAdjustment()` adds to or reduces `opened_stock`. A reduction above current opened stock is rejected by the controller. Each operation creates an adjustment ledger row with base-unit quantity and before/after total-stock snapshots. Sealed packages are not opened for this endpoint.

### Ledger scope

`PARTIALLY_IMPLEMENTED`: `raw_material_transactions` is populated by restock and manual adjustment flows. The transaction type set and report filters include `production_usage`, but production completion does not write that type. Production ingredient use and production waste are deducted directly from stock and recorded elsewhere or not in this ledger.

## Menus and recipes/BOM

`App\Http\Controllers\MenuController` implements index, create, store, edit, update, and active-state toggle behavior. A menu has selling price, active state, shelf-life days, and a many-to-many ingredient relationship to raw materials.

`Menu::ingredients()` uses `menu_raw_material` with pivot field `quantity`. Production code treats that value as the material requirement per target menu portion by multiplying it by target quantity. Store/update validates at least one existing material and a positive ingredient quantity.

Menu create/edit selection queries show active raw materials only, but store/update validates material existence without an `is_active` condition. Thus the UI selection and backend validation contracts are not identical; direct-request behavior remains `NEEDS_RUNTIME_VERIFICATION`.

The current HPP calculation uses current material data:

```text
(raw_material.latest_price / raw_material.conversion_value)
× menu_raw_material.quantity
```

The menu list also calculates readiness from current stock and current recipe quantities.

`PARTIALLY_IMPLEMENTED`: generated resource routes include `show` and `destroy`, but those controller methods do not exist. Menu deactivation is implemented through the custom toggle route.

`SCAFFOLDED_ONLY`: there is no versioned recipe, ingredient snapshot, price snapshot, unit snapshot, or cost snapshot model/table. Current production and report paths use the currently attached recipe.

Menu templates are stored under `resources/views/owner/Menus/`, while controller view names use lowercase `owner.menus.*`. Runtime behavior on a case-sensitive filesystem is `NEEDS_RUNTIME_VERIFICATION`.

## Production planning

`app/Http/Controllers/Owner/ProductionController.php` handles Owner planning. Its active interface is the planning side panel in `resources/views/owner/productions/index.blade.php`.

The owner can source-wise:

- list and filter paginated productions across all stored statuses;
- create a production with plan date, notes, menu, and target quantity;
- view a production;
- update a plan; and
- delete a plan through the resource endpoint.

Production items are created with a target quantity and `actual_quantity = 0`.

For reservation calculations, the index separately selects only `planned` and `processing` productions, expands each current menu recipe by target quantity, and builds in-memory values for physical stock, planned reservation, and available stock. The planning UI displays the resulting availability/shortage information.

`PARTIALLY_IMPLEMENTED`: stock/reservation sufficiency and menu active state are presented in browser-side planning behavior but are not controller validation rules. Requirements/reservations are not persisted in their own table.

`SCAFFOLDED_ONLY`: `resources/views/owner/productions/create.blade.php` is entirely inside a Blade comment. Resource `create` and `edit` actions are not present in the controller, even though resource routes generate those endpoints.

## Production execution and raw-material use

`app/Http/Controllers/ProductionController.php` implements the Produksi queue, detail page, start action, and completion action.

### Start

`start()` checks for planned status and changes it to processing. Produksi views are:

- `resources/views/prod/index.blade.php`
- `resources/views/prod/show.blade.php`

### Complete

`complete()` currently:

1. expands each production item's current menu recipe by target quantity;
2. combines recipe requirements with submitted waste quantities;
3. checks calculated physical material stock;
4. updates production status and execution metadata;
5. opens enough sealed packages by decrementing `sealed_stock` and increasing `opened_stock` by conversion value;
6. deducts recipe and waste quantities from opened stock;
7. inserts `production_wastes` records for submitted waste; and
8. creates `finished_goods` rows using target quantities.

`PARTIALLY_IMPLEMENTED` source gaps in this workflow:

- `complete()` does not contain a required-current-status check.
- Nested waste item identifiers and quantities do not have explicit per-item validation rules.
- `actual_quantity` is not collected or updated.
- finished-goods quantity is populated from target quantity.
- normal ingredient consumption and waste deductions do not create `raw_material_transactions` rows.
- material prechecking occurs before the database transaction and no raw-material row locking appears in the method.
- unit display references use `unit` in controller/view data, whereas the raw-material schema/model uses `base_unit`.

Production date is set from completion time. Expiry date is calculated from completion time and the menu's current `expires_in_days` value.

## Finished goods and expiry

`App\Http\Controllers\Owner\FinishedGoodController::index()` provides an Owner monitoring page in `resources/views/owner/finished-goods/index.blade.php`.

Each `finished_goods` row represents a menu/production batch and stores initial quantity, current quantity, production date, expiry date, and status. The index supports available, empty, expired, and expired/damaged filtering.

`app/Console/Commands/ExpireFinishedGoods.php` updates overdue available batches to expired while retaining quantity. `routes/console.php` schedules the command every minute. Whether the application scheduler is operating is `UNKNOWN` and `NEEDS_RUNTIME_VERIFICATION`.

There is no Owner create/edit endpoint for finished-goods batches; current creation occurs through production completion and rejected-return processing.

## Armada allocation and field sessions

`app/Http/Controllers/Owner/ArmadaSessionController.php` implements the Owner allocation page, a live-data endpoint, and session creation.

Session creation validates an Armada-role user, validates positive batch quantities, checks for an existing active session, locks selected finished-goods rows, checks availability and expiry, decrements batch `current_quantity`, and creates `armada_sessions` plus `armada_session_items` records.

The owner view is `resources/views/owner/armada-sessions/index.blade.php`. Its JavaScript polls the live endpoint at a five-second interval. Browser polling behavior is `NEEDS_RUNTIME_VERIFICATION`.

Available batches are ordered by expiry for presentation. Selection quantities are submitted by the Owner; the controller does not automatically construct a FEFO allocation.

## Armada sold counts and session finish

`app/Http/Controllers/Armada/SessionController.php` and `resources/views/armada/sessions/index.blade.php` implement the Armada interface.

- `updateSold()` overwrites the session item's cumulative `quantity_sold`.
- `finish()` saves final cumulative sold quantities, calculates `quantity_returned` as sent minus sold, inserts a pending return check for each positive return, and changes the session from active to finished.
- Both mutation actions require the session's `armada_user_id` to equal the authenticated Armada user's ID.

`PARTIALLY_IMPLEMENTED`: the current sales representation is an aggregate quantity on an allocation item. There are no application tables/models/controllers for an individual sale, sale time, customer, selling-price snapshot, order, payment, or revenue transaction.

Finished-goods quantity is decremented when the Owner allocates stock to the session. Updating `quantity_sold` does not perform an additional finished-goods deduction.

## Returns and disposal

`app/Http/Controllers/Produksi/ReturnCheckController.php` and `resources/views/prod/returns/index.blade.php` implement returned-goods inspection by the Produksi role.

Current outcomes are:

- `ready`: add returned quantity to the original finished-good batch.
- `expired_damaged`: create a separate rejected finished-good record linked from the return check.

Inspection records retain the session item, original finished-good batch, Armada user, checker, checked timestamp/status, notes, and optional rejected finished-good reference.

The `dispose()` action sets the rejected batch quantity to zero and status to empty.

`PARTIALLY_IMPLEMENTED`: raw-material production waste has its own `production_wastes` records, while rejected finished-good disposal is represented by changing the rejected batch. There is no general finished-goods waste/disposal ledger or standalone disposal-event record.

## Reports and export

### Raw-material transaction report

`app/Http/Controllers/Owner/TransactionReportController.php` implements the Owner transaction report, chart endpoint, composition endpoint, and export endpoint. `resources/views/owner/reports/transactions/index.blade.php` and its partials render the interface.

The current source supports:

- material-name search;
- transaction-type, year, and month filters;
- 15-row pagination;
- transaction, restock, and adjustment KPIs;
- restock purchase-value trend data;
- transaction-type composition data;
- native-fetch partial replacement and AJAX pagination; and
- query-string synchronization with `history.replaceState()`.

The chart and composition actions consume year/month filters. The tabular/export paths also consume material search and type. `production_usage` is listed even though no current application writer inserts that transaction type.

`app/Exports/TransactionReportExport.php` defines a Maatwebsite Excel export containing date, material, type, quantity/unit, before/after stock, and purchase-value fields. Actual XLSX generation is `NEEDS_RUNTIME_VERIFICATION`.

### Production report

`app/Http/Controllers/Owner/ProductionReportController.php` and templates under `resources/views/owner/reports/productions/` implement full-page and AJAX partial responses.

The current report includes:

- a production table across statuses;
- completed-production KPIs;
- target-portion totals and averages;
- top menu, material, and waste insights; and
- yearly, monthly, or weekly trend data.

Year/month filters and trend buckets use `productions.plan_date`, not a persisted completion timestamp. Production quantities are calculated from target quantities. Material totals are expanded from current menu recipes. There is no production export route/class. The controller initializes `$totalProductionCost` without using it and writes debug log entries when both year and month are selected.

No current report implementation was found for finished goods, individual sales, revenue/finance, a reorder-point calculation, or a complete material-requirements plan.

## ROP and MRP-related behavior

### Reorder point

`SCAFFOLDED_ONLY`: raw materials store `minimum_stock`, and dashboards/views calculate health labels or alerts from current stock and static thresholds. No lead-time field, demand-rate calculation, safety-stock calculation, reorder recommendation record, purchase requisition, or ROP report exists.

### Material requirements planning

`PARTIALLY_IMPLEMENTED`: production planning expands current BOM quantities by target quantity and subtracts planned/processing demand from physical stock for in-memory availability. There is no persisted requirement/reservation entity, time-bucket plan, supplier/procurement workflow, or backend enforcement of the displayed reservation.

## Blade and JavaScript implementation

`resources/js/app.js` initializes Alpine.js, Alpine collapse, Lucide, ApexCharts, and toast state. Blade templates contain additional inline Alpine/native JavaScript for module-specific interactions.

Current source-wired interactions include:

- role sidebars with persisted collapse state;
- raw-material row expansion and restock/adjustment modals;
- active-state toggles;
- production-plan side panel and waste rows;
- report charts, filters, partial fetching, and pagination;
- Armada live-session polling; and
- post-reload success toasts through `sessionStorage`.

These interactions are `NEEDS_RUNTIME_VERIFICATION` because no browser session or Vite build was run during the audit.

### Present but disconnected or unused

- `UNUSED`: Chart.js is installed but not imported by application JavaScript.
- `UNUSED`: SweetAlert2 is exposed from `resources/js/app.js`, but no invocation was found.
- `UNUSED`: `@tailwindcss/vite` is installed but is not registered in `vite.config.js`.
- `UNUSED`: `resources/js/bootstrap.js` is empty.
- `UNUSED`: `resources/views/layouts/navigation.blade.php` is not used by the active layout and names a nonexistent `owner.armada` route.
- `UNUSED`: bundled Bootstrap and Semantic UI pagination views are not selected by current pagination rendering.
- `UNUSED`: `resources/views/owner/productions/create.blade.php` contains only commented Blade source.

### Direct template/script gaps

- `PARTIALLY_IMPLEMENTED`: `addErrorToast()` adds a toast with `show` false and contains no transition to true.
- `PARTIALLY_IMPLEMENTED`: menu toggle code updates optimistically and has no response-status check, failure handler, or restoration path.
- `PARTIALLY_IMPLEMENTED`: raw-material scroll restoration targets `#main-content`, which is absent from the active layout.
- `PARTIALLY_IMPLEMENTED`: some values are inserted directly into Alpine expressions without JSON encoding.
- `PARTIALLY_IMPLEMENTED`: owner mobile navigation does not include every destination exposed in desktop navigation/routes.
- `PARTIALLY_IMPLEMENTED`: Armada administration includes source-visible but unwired search/tag controls and hard-coded progress values.

## Data-model implementation summary

Current domain models are:

- `app/Models/User.php`
- `app/Models/RawMaterial.php`
- `app/Models/RawMaterialTransaction.php`
- `app/Models/Menu.php`
- `app/Models/Production.php`
- `app/Models/ProductionItem.php`
- `app/Models/ProductionWaste.php`
- `app/Models/FinishedGood.php`
- `app/Models/ArmadaSession.php`
- `app/Models/ArmadaSessionItem.php`
- `app/Models/ReturnCheck.php`

Principal relationships:

```text
RawMaterial ← menu_raw_material → Menu
Menu ← ProductionItem → Production
Production → ProductionWaste
Production + Menu → FinishedGood
User(Armada) + User(Allocator) → ArmadaSession → ArmadaSessionItem
ArmadaSessionItem + FinishedGood + Users → ReturnCheck
```

Important current source characteristics:

- no domain model uses soft deletes;
- `ProductionItem` has a menu relationship but no inverse production relationship method;
- `actual_quantity` is initialized but has no operational update/read path;
- `wasted_quantity` remains in `ProductionItem::$fillable` although the final migration sequence removes the column;
- return checks can reference both the original batch and a separately created rejected batch; and
- recipe, price, unit, conversion, and production-cost history are not snapshotted.

## Tests currently present

The repository contains Breeze-derived feature tests for registration, login, email verification, password confirmation/reset/update, and profile behavior, plus placeholder example feature/unit tests. `phpunit.xml` configures SQLite in memory and array/synchronous test services.

No current automated tests exercise the operational modules: raw-material inventory, unit conversion, restock, adjustment, recipe/BOM, production planning/execution, finished goods, expiry scheduling, Armada distribution, sold counts, returns, waste, reports, export, AJAX, ROP, or MRP.

`tests/Feature/ExampleTest.php` expects `/` to return HTTP 200, while `routes/web.php` defines a redirect to `/login`. The suite itself was not executed, so overall test results remain `UNKNOWN` and `NEEDS_RUNTIME_VERIFICATION`.

## Runtime status boundary

The following implementation components exist in source but were not executed during this audit:

- migrations and seeders;
- authentication mail and password reset delivery;
- Vite build and browser asset loading;
- Alpine.js interactions, ApexCharts rendering, AJAX endpoints, and live polling;
- image upload/storage and served URLs;
- the finished-goods expiry command and scheduler;
- report queries against the configured production database;
- XLSX creation/download; and
- the automated test suite.

Their runtime state is `UNKNOWN`; each is `NEEDS_RUNTIME_VERIFICATION`. This document makes no runtime-success claim for them.
