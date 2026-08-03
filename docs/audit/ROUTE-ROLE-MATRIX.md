# Route and Role Matrix

## Scope and interpretation

This document is derived from `routes/web.php`, `routes/auth.php`, `bootstrap/app.php`, the referenced controllers, and `app/Http/Middleware/RoleMiddleware.php`. It is `SOURCE_VERIFIED`; no route was requested and no HTTP response was runtime-tested. Consequently, actual dispatch/render/database behavior remains `NEEDS_RUNTIME_VERIFICATION`.

Conventions used below:

- `web` is Laravel's implicit browser middleware group for routes loaded from `routes/web.php` and `routes/auth.php`.
- `auth` permits any authenticated account; `role:owner`, `role:produksi`, and `role:armada` perform an exact comparison against `auth()->user()->role`.
- "Any authenticated" is not fine-grained authorization. Except for Armada session ownership checks inside controller actions, no policies or gates were found.
- `(unnamed)` means the declaration has no explicit route name.
- `MISSING ACTION` means `Route::resource()` generated the route, but the named controller method does not exist.
- `EMPTY ACTION` means the controller method exists but contains no implementation.
- Response descriptions record intended source branches only. They do not assert a successful runtime response.
- `routes/api.php` is absent; no application API route group was found. JSON endpoints below are authenticated web routes with session/CSRF middleware, not stateless API routes.

## Public, guest, and authentication routes

| HTTP method | URI | Route name | Controller action | Middleware | Allowed role | Source response or view | Completion note |
|---|---|---|---|---|---|---|---|
| GET | `/` | `(unnamed)` | Route closure | `web` | Public | Redirect to `/login` | `SOURCE_VERIFIED`; conflicts with the unexecuted `tests/Feature/ExampleTest.php` expectation of HTTP 200. |
| GET | `/up` | `(unnamed)` | Framework health closure configured by `Application::withRouting()` | No `web`/`api` route-group middleware; global middleware still applies, and the path is excepted from maintenance blocking | Public | Framework health HTML, or JSON when JSON is preferred | `SOURCE_VERIFIED` configuration in `bootstrap/app.php`; `NEEDS_RUNTIME_VERIFICATION`. |
| GET | `/register` | `register` | `RegisteredUserController::create` | `web`, `guest` | Guest only | `auth.register` | `SOURCE_VERIFIED` / `PARTIALLY_IMPLEMENTED`: route is public but unlinked from the custom login view. |
| POST | `/register` | `(unnamed)` | `RegisteredUserController::store` | `web`, `guest` | Guest only | Create/login user, then redirect to `dashboard` | `SOURCE_VERIFIED` / `PARTIALLY_IMPLEMENTED`: role is omitted and falls through to the database default `armada`. |
| GET | `/login` | `login` | `AuthenticatedSessionController::create` | `web`, `guest` | Guest only | `auth.login` | `SOURCE_VERIFIED`; runtime render unknown. |
| POST | `/login` | `(unnamed)` | `AuthenticatedSessionController::store` via `LoginRequest` | `web`, `guest` | Guest only | Authenticate, regenerate session, redirect intended URL or `dashboard` | `SOURCE_VERIFIED`; username/email credential selection and rate limiting are in `app/Http/Requests/Auth/LoginRequest.php`. |
| GET | `/forgot-password` | `password.request` | `PasswordResetLinkController::create` | `web`, `guest` | Guest only | `auth.forgot-password` | `SOURCE_VERIFIED` / `PARTIALLY_IMPLEMENTED`: seeded operational users have no email. |
| POST | `/forgot-password` | `password.email` | `PasswordResetLinkController::store` | `web`, `guest` | Guest only | Back with status or email validation/broker error | `SOURCE_VERIFIED`; mail/broker behavior `NEEDS_RUNTIME_VERIFICATION`. |
| GET | `/reset-password/{token}` | `password.reset` | `NewPasswordController::create` | `web`, `guest` | Guest only | `auth.reset-password` | `SOURCE_VERIFIED`; runtime render unknown. |
| POST | `/reset-password` | `password.store` | `NewPasswordController::store` | `web`, `guest` | Guest only | On success redirect to `login`; otherwise back with errors | `SOURCE_VERIFIED`; broker/database/mail integration `NEEDS_RUNTIME_VERIFICATION`. |
| GET | `/verify-email` | `verification.notice` | `EmailVerificationPromptController::__invoke` | `web`, `auth` | Any authenticated | Redirect to `dashboard` if verified; otherwise `auth.verify-email` | `SOURCE_VERIFIED` / `PARTIALLY_IMPLEMENTED`: verification is not enforced by application routes. |
| GET | `/verify-email/{id}/{hash}` | `verification.verify` | `VerifyEmailController::__invoke` | `web`, `auth`, `signed`, `throttle:6,1`; request authorization also checks route ID/email hash | The matching authenticated user with a valid signed URL/hash | Mark verified and redirect intended URL or `dashboard?verified=1` | `SOURCE_VERIFIED`; notification and signed-link runtime behavior unverified. |
| POST | `/email/verification-notification` | `verification.send` | `EmailVerificationNotificationController::store` | `web`, `auth`, `throttle:6,1` | Any authenticated | Redirect to `dashboard` if already verified; otherwise back with status | `SOURCE_VERIFIED` / `PARTIALLY_IMPLEMENTED`: `User` does not implement `MustVerifyEmail`; mail is unverified. |
| GET | `/confirm-password` | `password.confirm` | `ConfirmablePasswordController::show` | `web`, `auth` | Any authenticated | `auth.confirm-password` | `SOURCE_VERIFIED`; runtime render unknown. |
| POST | `/confirm-password` | `(unnamed)` | `ConfirmablePasswordController::store` | `web`, `auth` | Any authenticated | Store confirmation timestamp, redirect intended URL or `dashboard`; validation error otherwise | `SOURCE_VERIFIED` / `PARTIALLY_IMPLEMENTED`: lookup uses the current user's nullable email. |
| PUT | `/password` | `password.update` | `PasswordController::update` | `web`, `auth` | Any authenticated | Back with `password-updated` status | `SOURCE_VERIFIED`; runtime validation/persistence unverified. |
| POST | `/logout` | `logout` | `AuthenticatedSessionController::destroy` | `web`, `auth` | Any authenticated | Logout, invalidate session, regenerate CSRF token, redirect `/` | `SOURCE_VERIFIED`; runtime session behavior unverified. |

## Shared authenticated routes

| HTTP method | URI | Route name | Controller action | Middleware | Allowed role | Source response or view | Completion note |
|---|---|---|---|---|---|---|---|
| GET | `/dashboard` | `dashboard` | `DashboardController::index` | `web`, `auth` | `owner`, `produksi`, or `armada`; other role values abort 403 | `dashboard.owner`, calculated `dashboard.produksi`, or `dashboard.armada` according to exact role | `SOURCE_VERIFIED`; no dashboard/role routing tests found. |
| GET | `/profile` | `profile.edit` | `ProfileController::edit` | `web`, `auth` | Any authenticated | `profile.edit` | `SOURCE_VERIFIED` / `PARTIALLY_IMPLEMENTED`: source layout uses `@yield('content')` while this component view supplies a slot. |
| PATCH | `/profile` | `profile.update` | `ProfileController::update` | `web`, `auth` | Any authenticated | Redirect to `profile.edit` with status | `SOURCE_VERIFIED`; self-scoped through `$request->user()`. |
| DELETE | `/profile` | `profile.destroy` | `ProfileController::destroy` | `web`, `auth` | Any authenticated | Delete current user, logout/invalidate session, redirect `/` | `SOURCE_VERIFIED`; foreign-key effects on operational users/data `NEEDS_RUNTIME_VERIFICATION`. |

## Owner: raw materials and stock

All rows in this section have `web`, `auth`, `role:owner` middleware and allow only the exact `owner` role.

| HTTP method | URI | Route name | Controller action | Middleware | Allowed role | Source response or view | Completion note |
|---|---|---|---|---|---|---|---|
| GET | `/owner/raw-materials` | `owner.raw-materials.index` | `RawMaterialController::index` | `web`, `auth`, `role:owner` | `owner` | `owner.raw-materials.index` | `SOURCE_VERIFIED`. |
| GET | `/owner/raw-materials/create` | `owner.raw-materials.create` | `RawMaterialController::create` | `web`, `auth`, `role:owner` | `owner` | `owner.raw-materials.create` | `SOURCE_VERIFIED`. |
| POST | `/owner/raw-materials` | `owner.raw-materials.store` | `RawMaterialController::store` | `web`, `auth`, `role:owner` | `owner` | Create material, redirect to index with toast | `SOURCE_VERIFIED`; upload/storage runtime unverified. |
| GET | `/owner/raw-materials/{raw_material}` | `owner.raw-materials.show` | `RawMaterialController::show` | `web`, `auth`, `role:owner` | `owner` | No response is implemented | `SOURCE_VERIFIED` / `EMPTY ACTION`. |
| GET | `/owner/raw-materials/{raw_material}/edit` | `owner.raw-materials.edit` | `RawMaterialController::edit` | `web`, `auth`, `role:owner` | `owner` | `owner.raw-materials.edit` | `SOURCE_VERIFIED`. |
| PUT, PATCH | `/owner/raw-materials/{raw_material}` | `owner.raw-materials.update` | `RawMaterialController::update` | `web`, `auth`, `role:owner` | `owner` | Update material, redirect to index with toast | `SOURCE_VERIFIED`; source conditionally freezes conversion/unit fields after transactions. |
| DELETE | `/owner/raw-materials/{raw_material}` | `owner.raw-materials.destroy` | `RawMaterialController::destroy` | `web`, `auth`, `role:owner` | `owner` | No response is implemented | `SOURCE_VERIFIED` / `EMPTY ACTION`. |
| POST | `/owner/raw-materials/{rawMaterial}/restock` | `owner.raw-materials.store-restock` | `RawMaterialController::storeRestock` | `web`, `auth`, `role:owner` | `owner` | JSON success when JSON is expected; otherwise redirect to index with toast | `SOURCE_VERIFIED`; database and concurrent update behavior `NEEDS_RUNTIME_VERIFICATION`. |
| POST | `/owner/raw-materials/{rawMaterial}/adjustment` | `owner.raw-materials.store-adjustment` | `RawMaterialController::storeAdjustment` | `web`, `auth`, `role:owner` | `owner` | JSON success when JSON is expected; otherwise redirect to index with toast | `SOURCE_VERIFIED`; changes opened stock only. |
| PATCH | `/owner/raw-material-transactions/{transaction}/price` | `owner.raw-materials.update-restock-price` | `RawMaterialController::updateRestockPrice` | `web`, `auth`, `role:owner` | `owner` | Back with toast; abort 403 unless transaction type is restock | `SOURCE_VERIFIED`; editing an old row also sets the material's current latest price. |
| PATCH | `/owner/raw-materials/{rawMaterial}/toggle-active` | `owner.raw-materials.toggle-active` | `RawMaterialController::toggleActive` | `web`, `auth`, `role:owner` | `owner` | JSON `{success, is_active}` | `SOURCE_VERIFIED`; browser failure handling unverified. |

## Owner: menus / current BOM

All rows in this section have `web`, `auth`, `role:owner` middleware and allow only the exact `owner` role. Controllers request view keys under `owner.menus.*`; the inspected filesystem directory is `resources/views/owner/Menus`, so case-sensitive deployment behavior is `NEEDS_RUNTIME_VERIFICATION`.

| HTTP method | URI | Route name | Controller action | Middleware | Allowed role | Source response or view | Completion note |
|---|---|---|---|---|---|---|---|
| GET | `/owner/menus` | `owner.menus.index` | `MenuController::index` | `web`, `auth`, `role:owner` | `owner` | `owner.menus.index` | `SOURCE_VERIFIED`; view-path case issue noted above. |
| GET | `/owner/menus/create` | `owner.menus.create` | `MenuController::create` | `web`, `auth`, `role:owner` | `owner` | `owner.menus.create` | `SOURCE_VERIFIED`; view-path case issue noted above. |
| POST | `/owner/menus` | `owner.menus.store` | `MenuController::store` | `web`, `auth`, `role:owner` | `owner` | Create menu/pivot ingredients, redirect to index | `SOURCE_VERIFIED`; upload/storage runtime unverified. |
| GET | `/owner/menus/{menu}` | `owner.menus.show` | `MenuController::show` | `web`, `auth`, `role:owner` | `owner` | No controller method exists | `SOURCE_VERIFIED` / `MISSING ACTION`. |
| GET | `/owner/menus/{menu}/edit` | `owner.menus.edit` | `MenuController::edit` | `web`, `auth`, `role:owner` | `owner` | `owner.menus.edit` | `SOURCE_VERIFIED`; view-path case issue noted above. |
| PUT, PATCH | `/owner/menus/{menu}` | `owner.menus.update` | `MenuController::update` | `web`, `auth`, `role:owner` | `owner` | Update menu/sync pivot ingredients, redirect to index | `SOURCE_VERIFIED`. |
| DELETE | `/owner/menus/{menu}` | `owner.menus.destroy` | `MenuController::destroy` | `web`, `auth`, `role:owner` | `owner` | No controller method exists | `SOURCE_VERIFIED` / `MISSING ACTION`. |
| PATCH | `/owner/menus/{menu}/toggle-active` | `owner.menus.toggle-active` | `MenuController::toggleActive` | `web`, `auth`, `role:owner` | `owner` | JSON `{success, is_active}` | `SOURCE_VERIFIED`; client performs optimistic state change without response/error restoration. |

## Owner: production planning and finished goods

All rows in this section have `web`, `auth`, `role:owner` middleware and allow only the exact `owner` role.

| HTTP method | URI | Route name | Controller action | Middleware | Allowed role | Source response or view | Completion note |
|---|---|---|---|---|---|---|---|
| GET | `/owner/productions` | `owner.productions.index` | `Owner\ProductionController::index` | `web`, `auth`, `role:owner` | `owner` | `owner.productions.index` with calculated in-memory reservations | `SOURCE_VERIFIED`. |
| GET | `/owner/productions/create` | `owner.productions.create` | `Owner\ProductionController::create` | `web`, `auth`, `role:owner` | `owner` | No controller method exists | `SOURCE_VERIFIED` / `MISSING ACTION`; the active create UI is the index side panel, and the separate create Blade file is commented out. |
| POST | `/owner/productions` | `owner.productions.store` | `Owner\ProductionController::store` | `web`, `auth`, `role:owner` | `owner` | Create plan/items, redirect to index | `SOURCE_VERIFIED`; backend does not enforce stock/reservation availability. |
| GET | `/owner/productions/{production}` | `owner.productions.show` | `Owner\ProductionController::show` | `web`, `auth`, `role:owner` | `owner` | `owner.productions.show` | `SOURCE_VERIFIED`; calculations use current BOM/current prices. |
| GET | `/owner/productions/{production}/edit` | `owner.productions.edit` | `Owner\ProductionController::edit` | `web`, `auth`, `role:owner` | `owner` | No controller method exists | `SOURCE_VERIFIED` / `MISSING ACTION`; editing is implemented through the index panel and update endpoint. |
| PUT, PATCH | `/owner/productions/{production}` | `owner.productions.update` | `Owner\ProductionController::update` | `web`, `auth`, `role:owner` | `owner` | Reject non-planned/non-cancelled state; otherwise replace items and redirect | `SOURCE_VERIFIED`; runtime state transitions unverified. |
| DELETE | `/owner/productions/{production}` | `owner.productions.destroy` | `Owner\ProductionController::destroy` | `web`, `auth`, `role:owner` | `owner` | Reject processing/completed; otherwise delete and redirect | `SOURCE_VERIFIED`; database cascade effects unverified. |
| GET | `/owner/stok-jadi` | `owner.stok-jadi.index` | `Owner\FinishedGoodController::index` | `web`, `auth`, `role:owner` | `owner` | `owner.finished-goods.index` | `SOURCE_VERIFIED`; read-only inventory/filter view. |

## Owner: Armada users and distribution sessions

All rows in this section have `web`, `auth`, `role:owner` middleware and allow only the exact `owner` role.

| HTTP method | URI | Route name | Controller action | Middleware | Allowed role | Source response or view | Completion note |
|---|---|---|---|---|---|---|---|
| GET | `/owner/armada` | `owner.armada.index` | `ArmadaController::index` | `web`, `auth`, `role:owner` | `owner` | `owner.armada.index` | `SOURCE_VERIFIED`. |
| GET | `/owner/armada/create` | `owner.armada.create` | `ArmadaController::create` | `web`, `auth`, `role:owner` | `owner` | `owner.armada.create` | `SOURCE_VERIFIED`. |
| POST | `/owner/armada` | `owner.armada.store` | `ArmadaController::store` | `web`, `auth`, `role:owner` | `owner` | Create Armada user, redirect to index | `SOURCE_VERIFIED`. |
| GET | `/owner/armada/{armada}` | `owner.armada.show` | `ArmadaController::show` | `web`, `auth`, `role:owner` | `owner` | No response is implemented | `SOURCE_VERIFIED` / `EMPTY ACTION`. |
| GET | `/owner/armada/{armada}/edit` | `owner.armada.edit` | `ArmadaController::edit` | `web`, `auth`, `role:owner` | `owner` | No response is implemented | `SOURCE_VERIFIED` / `EMPTY ACTION`. |
| PUT, PATCH | `/owner/armada/{armada}` | `owner.armada.update` | `ArmadaController::update` | `web`, `auth`, `role:owner` | `owner` | No response is implemented | `SOURCE_VERIFIED` / `EMPTY ACTION`. |
| DELETE | `/owner/armada/{armada}` | `owner.armada.destroy` | `ArmadaController::destroy` | `web`, `auth`, `role:owner` | `owner` | No response is implemented | `SOURCE_VERIFIED` / `EMPTY ACTION`. |
| GET | `/owner/armada-sessions` | `owner.armada-sessions.index` | `Owner\ArmadaSessionController::index` | `web`, `auth`, `role:owner` | `owner` | `owner.armada-sessions.index` | `SOURCE_VERIFIED`; loads users, eligible goods, active sessions, and recent sessions. |
| GET | `/owner/armada-sessions/live` | `owner.armada-sessions.live` | `Owner\ArmadaSessionController::live` | `web`, `auth`, `role:owner` | `owner` | JSON active-session snapshot and update time | `SOURCE_VERIFIED`; polled by browser every five seconds; runtime polling unverified. |
| POST | `/owner/armada-sessions` | `owner.armada-sessions.store` | `Owner\ArmadaSessionController::store` | `web`, `auth`, `role:owner` | `owner` | Allocate selected batches, redirect to session index | `SOURCE_VERIFIED`; database/concurrency behavior `NEEDS_RUNTIME_VERIFICATION`. |

## Owner: reports, AJAX, and export

All rows in this section have `web`, `auth`, `role:owner` middleware and allow only the exact `owner` role.

| HTTP method | URI | Route name | Controller action | Middleware | Allowed role | Source response or view | Completion note |
|---|---|---|---|---|---|---|---|
| GET | `/owner/reports/transactions` | `owner.reports.transactions` | `Owner\TransactionReportController::index` | `web`, `auth`, `role:owner` | `owner` | Full request: `owner.reports.transactions.index`; AJAX: JSON containing rendered table/KPI partials and data | `SOURCE_VERIFIED`; browser/AJAX and database-specific query behavior unverified. |
| GET | `/owner/reports/transactions/chart` | `owner.reports.transactions.chart` | `Owner\TransactionReportController::chart` | `web`, `auth`, `role:owner` | `owner` | JSON purchase trend and available months | `SOURCE_VERIFIED`; applies year/month, not search/type filters. |
| GET | `/owner/reports/transactions/composition` | `owner.reports.transactions.composition` | `Owner\TransactionReportController::composition` | `web`, `auth`, `role:owner` | `owner` | JSON transaction composition | `SOURCE_VERIFIED`; applies year/month, not search/type filters. |
| GET | `/owner/reports/transactions/export` | `owner.reports.transactions.export` | `Owner\TransactionReportController::export` | `web`, `auth`, `role:owner` | `owner` | XLSX download via `TransactionReportExport` | `SOURCE_VERIFIED` source path; file generation/download `NEEDS_RUNTIME_VERIFICATION`. |
| GET | `/owner/reports/productions` | `owner.reports.productions` | `Owner\ProductionReportController::index` | `web`, `auth`, `role:owner` | `owner` | Full request: `owner.reports.productions.index`; AJAX: JSON containing rendered partials/metrics/trend data | `SOURCE_VERIFIED` / `PARTIALLY_IMPLEMENTED`: metrics use target output and current BOM; browser/database behavior unverified. |

## Produksi routes

All rows in this section have `web`, `auth`, `role:produksi` middleware and allow only the exact `produksi` role.

| HTTP method | URI | Route name | Controller action | Middleware | Allowed role | Source response or view | Completion note |
|---|---|---|---|---|---|---|---|
| GET | `/produksi/productions` | `produksi.productions.index` | `ProductionController::index` | `web`, `auth`, `role:produksi` | `produksi` | `prod.index` | `SOURCE_VERIFIED`. |
| GET | `/produksi/productions/{production}` | `produksi.productions.show` | `ProductionController::show` | `web`, `auth`, `role:produksi` | `produksi` | `prod.show` with requirements/availability/current-cost calculations | `SOURCE_VERIFIED`; some view/controller display references use nonexistent `RawMaterial::unit` instead of `base_unit`. |
| PATCH | `/produksi/productions/{production}/start` | `produksi.productions.start` | `ProductionController::start` | `web`, `auth`, `role:produksi` | `produksi` | If planned, change to processing; in all states, return back with the same success message | `SOURCE_VERIFIED` / `PARTIALLY_IMPLEMENTED`: a non-planned state is a silent no-op rather than an error. |
| POST | `/produksi/productions/{production}/complete` | `produksi.productions.complete` | `ProductionController::complete` | `web`, `auth`, `role:produksi` | `produksi` | On shortage back with error; otherwise deduct current-BOM/waste stock, create finished goods, redirect index | `SOURCE_VERIFIED` / `PARTIALLY_IMPLEMENTED`: no required starting status, actual yield, consumption ledger, or complete nested waste validation. |
| GET | `/produksi/returns` | `produksi.returns.index` | `Produksi\ReturnCheckController::index` | `web`, `auth`, `role:produksi` | `produksi` | `prod.returns.index` | `SOURCE_VERIFIED`. |
| PATCH | `/produksi/returns/{returnCheck}` | `produksi.returns.update` | `Produksi\ReturnCheckController::update` | `web`, `auth`, `role:produksi` | `produksi` | Restore ready goods or create rejected batch; redirect return index | `SOURCE_VERIFIED`; concurrent/idempotent behavior unverified. |
| PATCH | `/produksi/returns/{returnCheck}/dispose` | `produksi.returns.dispose` | `Produksi\ReturnCheckController::dispose` | `web`, `auth`, `role:produksi` | `produksi` | Back with success after zeroing rejected batch; invalid state aborts 403 | `SOURCE_VERIFIED` / `PARTIALLY_IMPLEMENTED`: no separate disposal audit event is written. |

## Armada routes

All rows in this section have `web`, `auth`, `role:armada` middleware and allow only the exact `armada` role. The two mutation actions also perform `SOURCE_VERIFIED` controller-level ownership and active-session checks.

| HTTP method | URI | Route name | Controller action | Middleware | Allowed role | Source response or view | Completion note |
|---|---|---|---|---|---|---|---|
| GET | `/armada/sessions` | `armada.sessions.index` | `Armada\SessionController::index` | `web`, `auth`, `role:armada` | `armada` | `armada.sessions.index` for the logged-in Armada's active/recent sessions | `SOURCE_VERIFIED`; query is user-scoped. |
| PATCH | `/armada/sessions/{session}/sold` | `armada.sessions.update-sold` | `Armada\SessionController::updateSold` | `web`, `auth`, `role:armada` | Owning `armada` user only | Back with success after overwriting cumulative sold quantities; foreign/inactive session aborts 403 | `SOURCE_VERIFIED` / `PARTIALLY_IMPLEMENTED`: this is not an event-level sales ledger. |
| PATCH | `/armada/sessions/{session}/finish` | `armada.sessions.finish` | `Armada\SessionController::finish` | `web`, `auth`, `role:armada` | Owning `armada` user only | Finalize sold/returned quantities, create pending return checks, redirect session index | `SOURCE_VERIFIED`; repeat/concurrent submission behavior `NEEDS_RUNTIME_VERIFICATION`. |

## Incomplete resource actions summary

These routes are reachable in the declared route surface but do not have a complete controller action. They must not be represented as implemented features.

| Route name | Source condition | Classification |
|---|---|---|
| `owner.raw-materials.show` | `RawMaterialController::show` exists but is empty | `SOURCE_VERIFIED` / `PARTIALLY_IMPLEMENTED` / `EMPTY ACTION` |
| `owner.raw-materials.destroy` | `RawMaterialController::destroy` exists but is empty | `SOURCE_VERIFIED` / `PARTIALLY_IMPLEMENTED` / `EMPTY ACTION` |
| `owner.menus.show` | `Route::resource()` generated the route; `MenuController::show` is absent | `SOURCE_VERIFIED` / `PARTIALLY_IMPLEMENTED` / `MISSING ACTION` |
| `owner.menus.destroy` | `Route::resource()` generated the route; `MenuController::destroy` is absent | `SOURCE_VERIFIED` / `PARTIALLY_IMPLEMENTED` / `MISSING ACTION` |
| `owner.productions.create` | `Route::resource()` generated the route; `Owner\ProductionController::create` is absent | `SOURCE_VERIFIED` / `PARTIALLY_IMPLEMENTED` / `MISSING ACTION` |
| `owner.productions.edit` | `Route::resource()` generated the route; `Owner\ProductionController::edit` is absent | `SOURCE_VERIFIED` / `PARTIALLY_IMPLEMENTED` / `MISSING ACTION` |
| `owner.armada.show` | `ArmadaController::show` exists but is empty | `SOURCE_VERIFIED` / `PARTIALLY_IMPLEMENTED` / `EMPTY ACTION` |
| `owner.armada.edit` | `ArmadaController::edit` exists but is empty | `SOURCE_VERIFIED` / `PARTIALLY_IMPLEMENTED` / `EMPTY ACTION` |
| `owner.armada.update` | `ArmadaController::update` exists but is empty | `SOURCE_VERIFIED` / `PARTIALLY_IMPLEMENTED` / `EMPTY ACTION` |
| `owner.armada.destroy` | `ArmadaController::destroy` exists but is empty | `SOURCE_VERIFIED` / `PARTIALLY_IMPLEMENTED` / `EMPTY ACTION` |

The actual runtime response for missing/empty actions is deliberately not asserted here; it is `NEEDS_RUNTIME_VERIFICATION`.

## Non-HTTP scheduled entry point

`routes/console.php` schedules `goods:check-expired` every minute. The command is implemented by `app/Console/Commands/ExpireFinishedGoods.php`. It has no HTTP method, URI, role, or browser middleware, and actual scheduler execution is `UNKNOWN` / `NEEDS_RUNTIME_VERIFICATION`.

The same file also registers Laravel's sample closure command `inspire`, which prints an `Inspiring::quote()`. It has no domain consumer or role gate and is `SOURCE_VERIFIED` / `UNUSED` by the audited business workflows.
