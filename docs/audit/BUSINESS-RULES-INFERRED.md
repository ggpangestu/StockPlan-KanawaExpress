# Business Rules Observed or Inferred from Code

## Scope and interpretation

This register records only rules directly encoded by the application or strongly implied by those rules. It does not assert that the rules are approved business policy, and it does not claim runtime verification.

- `SOURCE_VERIFIED` means the described validation, branch, formula, mutation, or authorization is directly present in source.
- `INFERRED_FROM_CODE` means the outcome follows from the code but was not exercised.
- `NEEDS_RUNTIME_VERIFICATION` identifies behavior that must still be observed in an applied environment.
- `PARTIALLY_IMPLEMENTED`, `SCAFFOLDED_ONLY`, `UNUSED`, and `UNKNOWN` are used where the executable rule has an explicit implementation gap.

Confidence is confidence in the static reading, not confidence that the rule reflects stakeholder intent.

## Authentication and roles

### BR-AUTH-001 — Login identifier selection

- **Description:** A login may supply `username` or `email`. The selected value is treated as an email when it passes PHP email validation; otherwise it is treated as a username. Password is always required.
- **Evidence:** `LoginRequest::rules()` and `LoginRequest::authenticate()` in `app/Http/Requests/Auth/LoginRequest.php`.
- **Evidence basis:** `SOURCE_VERIFIED`
- **Confidence:** High
- **Affected modules:** Authentication, users
- **Test coverage:** `tests/Feature/Auth/AuthenticationTest.php` covers email login, invalid password, and logout; username login is not covered.

### BR-AUTH-002 — Login throttling

- **Description:** Five failed attempts are allowed per normalized login identifier plus IP-derived key; further attempts are rate-limited until the limiter becomes available.
- **Evidence:** `LoginRequest::ensureIsNotRateLimited()` and `throttleKey()` in `app/Http/Requests/Auth/LoginRequest.php`.
- **Evidence basis:** `SOURCE_VERIFIED`
- **Confidence:** High
- **Affected modules:** Authentication
- **Test coverage:** No explicit throttling test found.

### BR-AUTH-003 — Public-registration defaults

- **Description:** Registration requires name, unique email, and confirmed password; it generates a unique username from the name and omits `role`, causing the database default role `armada` to apply.
- **Evidence:** `RegisteredUserController::store()` and `generateUsername()` in `app/Http/Controllers/Auth/RegisteredUserController.php`; `database/migrations/2026_05_04_013516_add_username_and_role_to_users_table.php`.
- **Evidence basis:** `SOURCE_VERIFIED`
- **Confidence:** High
- **Affected modules:** Authentication, users, Armada
- **Test coverage:** `tests/Feature/Auth/RegistrationTest.php` covers successful registration and redirect, but does not assert generated username or role.

### BR-AUTH-004 - Exact role routing and access

- **Description:** Dashboard selection and role middleware recognize exact role strings. `owner`, `produksi`, and `armada` receive their corresponding dashboard/access; other values receive HTTP 403 where the checks apply.
- **Evidence:** `DashboardController::index()` in `app/Http/Controllers/DashboardController.php`; `RoleMiddleware::handle()` in `app/Http/Middleware/RoleMiddleware.php`; role route groups in `routes/web.php`.
- **Evidence basis:** `SOURCE_VERIFIED`
- **Confidence:** High
- **Affected modules:** Authorization, dashboards, all role-scoped modules
- **Test coverage:** No role allow/deny or dashboard-routing tests found.

### BR-AUTH-005 - Owner-created Armada accounts

- **Description:** Owner-side account creation requires name, unique username, and confirmed password, forces role `armada`, and does not collect an email address.
- **Evidence:** `ArmadaController::store()` in `app/Http/Controllers/ArmadaController.php`.
- **Evidence basis:** `SOURCE_VERIFIED`
- **Confidence:** High
- **Affected modules:** Users, Owner Armada administration, password recovery, email verification
- **Test coverage:** No Armada-account administration test found. The account workflow is `PARTIALLY_IMPLEMENTED` because its show/edit/update/delete resource actions are empty.

## Raw materials and inventory

### BR-RM-001 — New raw-material state

- **Description:** A new raw material requires identity/category, purchase/base units, conversion at least `1`, and nonnegative minimum stock. It starts with zero sealed and opened stock and records the authenticated creator.
- **Evidence:** `RawMaterialController::store()` in `app/Http/Controllers/RawMaterialController.php`.
- **Evidence basis:** `SOURCE_VERIFIED`
- **Confidence:** High
- **Affected modules:** Raw-material master, inventory
- **Test coverage:** No domain test found.

### BR-RM-002 — Base-unit stock calculation

- **Description:** Physical raw-material stock in base units equals opened stock plus sealed package count multiplied by conversion value.
- **Evidence:** `RawMaterial::getTotalStockAttribute()` and `convertToBaseUnit()` in `app/Models/RawMaterial.php`.
- **Evidence basis:** `SOURCE_VERIFIED`
- **Confidence:** High
- **Affected modules:** Raw materials, BOM, planning, production, dashboards, reports
- **Test coverage:** No domain test found.

### BR-RM-003 — Stock-health bands

- **Description:** Stock health uses `total_stock / max(minimum_stock, 1)`: at most `1` is critical, at most `1.5` warning, at most `2` caution, and above `2` healthy.
- **Evidence:** `RawMaterial::getStockHealthAttribute()` in `app/Models/RawMaterial.php`.
- **Evidence basis:** `SOURCE_VERIFIED`
- **Confidence:** High
- **Affected modules:** Raw-material monitoring, ROP-like alerts
- **Test coverage:** No domain test found.

### BR-RM-004 — Restock packages, conversion, and price

- **Description:** Restock accepts a positive whole-number purchase quantity. It adds packages to sealed stock, converts the quantity to base units for the ledger, requires a price on the first restock, may update the current latest price, and stores before/after total-stock and purchase-value snapshots.
- **Evidence:** `RawMaterialController::storeRestock()` in `app/Http/Controllers/RawMaterialController.php`.
- **Evidence basis:** `SOURCE_VERIFIED`
- **Confidence:** High
- **Affected modules:** Restock, raw-material stock, transaction ledger, reports
- **Test coverage:** No domain test found.

### BR-RM-005 — Restock price correction changes current price

- **Description:** Only a restock transaction's price can be edited. The handler recalculates that row's total and also writes the edited value to the material's `latest_price`, regardless of the transaction's age.
- **Evidence:** `RawMaterialController::updateRestockPrice()` in `app/Http/Controllers/RawMaterialController.php`.
- **Evidence basis:** `SOURCE_VERIFIED`
- **Confidence:** High
- **Affected modules:** Raw materials, HPP, production cost displays, transaction reports
- **Test coverage:** No domain test found.

### BR-RM-006 — Adjustments affect opened stock only

- **Description:** Manual additions and reductions change only opened base-unit stock. A reduction greater than opened stock is rejected even if sealed stock would make total physical stock sufficient. Each accepted adjustment writes a transaction snapshot.
- **Evidence:** `RawMaterialController::storeAdjustment()` in `app/Http/Controllers/RawMaterialController.php`.
- **Evidence basis:** `SOURCE_VERIFIED`
- **Confidence:** High
- **Affected modules:** Adjustments, raw-material stock, transaction ledger
- **Test coverage:** No domain test found.

### BR-RM-007 — Unit configuration locks after transaction history

- **Description:** Purchase unit, base unit, and conversion value are accepted during edit only when the material has no transactions. Name, category, minimum stock, and image remain editable.
- **Evidence:** `RawMaterialController::edit()` and `update()` in `app/Http/Controllers/RawMaterialController.php`.
- **Evidence basis:** `SOURCE_VERIFIED`
- **Confidence:** High
- **Affected modules:** Raw-material master, unit conversion, historical ledger interpretation
- **Test coverage:** No domain test found.

### BR-RM-008 - Raw-material production usage is outside the transaction ledger

- **Description:** Production completion and production waste directly mutate stock, while restock/adjustment handlers create ledger rows. No writer for transaction type `production_usage` was found.
- **Evidence:** `ProductionController::complete()` in `app/Http/Controllers/ProductionController.php`; `RawMaterialController` transaction creation; `RawMaterialTransaction::TYPE_PRODUCTION_USAGE` in `app/Models/RawMaterialTransaction.php`.
- **Evidence basis:** `SOURCE_VERIFIED`
- **Confidence:** High
- **Affected modules:** Production, raw-material ledger, transaction reports, traceability
- **Test coverage:** No domain test found. Implementation status: `PARTIALLY_IMPLEMENTED`; the `production_usage` hook is `SCAFFOLDED_ONLY`.

### BR-RM-009 - Create/update conversion and price boundaries differ

- **Description:** Raw-material creation requires `conversion_value >= 1`; a pre-transaction edit permits `conversion_value >= 0.01`. Unit names are required free-form strings with length limits, not references to a unit master. Initial restock accepts a supplied price of `0`, while restock price correction requires a value strictly greater than `0`.
- **Evidence:** `RawMaterialController::store()`, `update()`, `storeRestock()`, and `updateRestockPrice()` in `app/Http/Controllers/RawMaterialController.php`.
- **Evidence basis:** `SOURCE_VERIFIED`
- **Confidence:** High
- **Affected modules:** Raw-material master, unit conversion, restock, costing
- **Test coverage:** No boundary/consistency test found.

### BR-ROP-001 - Three low-stock thresholds coexist

- **Description:** The stock-health accessor uses ratios to `minimum_stock` (`<=1`, `<=1.5`, `<=2`); the Owner raw-material attention query uses `total_stock <= minimum_stock * 1.5`; the Produksi dashboard uses fixed `total_stock < 10`. No demand/lead-time reorder-point calculation is called by these rules.
- **Evidence:** `RawMaterial::getStockHealthAttribute()` in `app/Models/RawMaterial.php`; `RawMaterialController::index()`; `DashboardController::produksiDashboard()`.
- **Evidence basis:** `SOURCE_VERIFIED`
- **Confidence:** High
- **Affected modules:** Raw materials, dashboards, ROP-like warnings
- **Test coverage:** No threshold/ROP test found. Full ROP is `SCAFFOLDED_ONLY`.

## Menu and recipe/BOM

### BR-BOM-001 — Recipe requirements

- **Description:** A menu requires at least one existing raw material, with each ingredient quantity at least `0.01` base units. Selling price must be nonnegative and shelf life at least one day.
- **Evidence:** `MenuController::store()` and `update()` in `app/Http/Controllers/MenuController.php`.
- **Evidence basis:** `SOURCE_VERIFIED`
- **Confidence:** High
- **Affected modules:** Menu, recipe/BOM, finished goods
- **Test coverage:** No domain test found.

### BR-BOM-002 — Current-cost HPP

- **Description:** Menu HPP is the sum of recipe quantity multiplied by current purchase price divided by current conversion value. Profit is selling price minus HPP; margin is profit divided by selling price when selling price is positive.
- **Evidence:** `MenuController::index()` in `app/Http/Controllers/MenuController.php`; analogous calculations in both production controllers.
- **Evidence basis:** `SOURCE_VERIFIED`
- **Confidence:** High
- **Affected modules:** Menu, production detail, cost display
- **Test coverage:** No domain test found.

### BR-BOM-003 — One-unit readiness

- **Description:** A menu is marked ready only when every ingredient's total physical stock covers one recipe quantity.
- **Evidence:** `MenuController::index()` in `app/Http/Controllers/MenuController.php`.
- **Evidence basis:** `SOURCE_VERIFIED`
- **Confidence:** High
- **Affected modules:** Menu, raw-material stock
- **Test coverage:** No domain test found.

### BR-BOM-004 - Current recipe governs historical calculations

- **Description:** Production plans store menu and target quantities, not recipe versions. Completion and production reports reload the menu's current ingredients, so changing a recipe can change later deductions and historical report calculations for an existing plan.
- **Evidence:** Schema in `database/migrations/2026_05_18_005715_create_menus_table.php` and `database/migrations/2026_06_02_184133_create_productions_table.php`; `ProductionController::complete()`; `Owner\ProductionReportController::index()`.
- **Evidence basis:** Missing snapshots and current-relation loads are `SOURCE_VERIFIED`; the effect after a recipe edit is `INFERRED_FROM_CODE`.
- **Confidence:** High
- **Affected modules:** BOM, planning, production, reports, costing
- **Test coverage:** No domain test found. Historical/versioned BOM status: `SCAFFOLDED_ONLY`.

### BR-BOM-005 - Active-state selection is UI/query level, not write enforcement

- **Description:** Menu create/edit selection queries expose only active raw materials, and production planning exposes only active menus/raw materials. The menu and plan store/update validators use `exists` without an active-state condition, so a direct request can reference an inactive record; existing recipes and completion still traverse attached inactive ingredients.
- **Evidence:** `MenuController::create()`, `edit()`, `store()`, and `update()`; `Owner\ProductionController::index()`, `store()`, and `update()`; `ProductionController::complete()`.
- **Evidence basis:** Query/validation differences are `SOURCE_VERIFIED`; direct-request acceptance is `INFERRED_FROM_CODE` and `NEEDS_RUNTIME_VERIFICATION`.
- **Confidence:** High
- **Affected modules:** Raw materials, Menu/BOM, production planning, execution
- **Test coverage:** No active/inactive domain test found.

## Production planning and MRP-like calculation

### BR-PLAN-001 - Plan creation

- **Description:** A plan requires a syntactically valid date and at least one existing menu with positive integer target quantity. It is created as `planned`, records its creator, and initializes each item's actual quantity to zero. No today/future-date rule is present, so past dates satisfy the declared validation.
- **Evidence:** `Owner\ProductionController::store()` in `app/Http/Controllers/Owner/ProductionController.php`.
- **Evidence basis:** Validation and initialization are `SOURCE_VERIFIED`; acceptance of a past but valid date is `INFERRED_FROM_CODE` and `NEEDS_RUNTIME_VERIFICATION`.
- **Confidence:** High
- **Affected modules:** Production planning
- **Test coverage:** No domain test found.

### BR-PLAN-002 — Plan edit and delete eligibility

- **Description:** Only `planned` or `cancelled` plans can be edited. A plan in `processing` or `completed` cannot be deleted; therefore `planned` and `cancelled` can be deleted by the implemented condition.
- **Evidence:** `Owner\ProductionController::update()` and `destroy()` in `app/Http/Controllers/Owner/ProductionController.php`.
- **Evidence basis:** `SOURCE_VERIFIED`
- **Confidence:** High
- **Affected modules:** Production planning
- **Test coverage:** No domain test found.

### BR-MRP-001 — Calculated reservations

- **Description:** Raw-material reservations are calculated as current recipe quantity times target for all `planned` and `processing` productions. Displayed available stock equals physical stock minus this aggregate.
- **Evidence:** `Owner\ProductionController::index()` in `app/Http/Controllers/Owner/ProductionController.php`.
- **Evidence basis:** `SOURCE_VERIFIED`
- **Confidence:** High
- **Affected modules:** Production planning, raw-material availability, MRP
- **Test coverage:** No domain test found.

### BR-MRP-002 — Reservations are advisory

- **Description:** Reservations are not persisted, and plan store/update validates identifiers and positive quantities but does not enforce material availability. Consequently, the source allows plans whose aggregate requirements exceed displayed availability.
- **Evidence:** Comparison of `Owner\ProductionController::index()`, `store()`, and `update()`; no reservation table/column in migrations.
- **Evidence basis:** `INFERRED_FROM_CODE`
- **Confidence:** High
- **Affected modules:** Production planning, MRP, stock availability
- **Test coverage:** No domain test found. MRP status: `PARTIALLY_IMPLEMENTED`.

## Production execution

### BR-PROD-001 — Start transition

- **Description:** The start handler changes `planned` to `processing`. For any other status it makes no status change but still returns a success message.
- **Evidence:** `ProductionController::start()` in `app/Http/Controllers/ProductionController.php`.
- **Evidence basis:** `SOURCE_VERIFIED`
- **Confidence:** High
- **Affected modules:** Production execution
- **Test coverage:** No domain test found.

### BR-PROD-002 - Completion stock requirement

- **Description:** Before completion, required stock is aggregated from current recipe quantity times target plus submitted waste. Completion returns an error when a found raw material's total physical stock is insufficient. Only the top-level `wasted_materials` array is validated: nested IDs/quantities have no `exists`, numeric, or minimum rules; a missing material is skipped by the precheck but later assumed to exist in the mutation branch.
- **Evidence:** `ProductionController::complete()` in `app/Http/Controllers/ProductionController.php`.
- **Evidence basis:** `SOURCE_VERIFIED`; malformed-input runtime response/rollback is `NEEDS_RUNTIME_VERIFICATION`.
- **Confidence:** High
- **Affected modules:** Production, BOM, raw-material stock, waste
- **Test coverage:** No domain test found.

### BR-PROD-003 — Automatic package opening

- **Description:** When opened stock cannot cover a deduction, production opens the ceiling of the shortage divided by conversion value, decreases sealed stock by that package count, increases opened stock by the converted amount, and then deducts the requirement.
- **Evidence:** Both normal-consumption and waste branches of `ProductionController::complete()`.
- **Evidence basis:** `SOURCE_VERIFIED`
- **Confidence:** High
- **Affected modules:** Production, sealed/opened raw-material stock
- **Test coverage:** No domain test found.

### BR-PROD-004 — Full-target finished-good creation

- **Description:** Completion creates one finished-good batch per positive production item at full target quantity, dated on completion and expiring after the menu's current shelf-life days. `actual_quantity` is not used.
- **Evidence:** `ProductionController::complete()`; `production_items.actual_quantity` schema; repository search for its use.
- **Evidence basis:** `SOURCE_VERIFIED`
- **Confidence:** High
- **Affected modules:** Production, actual yield, finished goods
- **Test coverage:** No domain test found. Actual-yield implementation is `UNUSED`/`PARTIALLY_IMPLEMENTED`.

### BR-PROD-005 - Waste recording and deduction

- **Description:** Positive submitted waste entries that pass the method's ad hoc `!empty`/`> 0` conditions create `production_wastes` records and separately deduct the same base-unit quantity from raw-material stock, opening packages as needed. The nested entries do not have formal per-field validation.
- **Evidence:** Waste branch of `ProductionController::complete()`.
- **Evidence basis:** `SOURCE_VERIFIED`
- **Confidence:** High
- **Affected modules:** Production, waste, raw-material stock
- **Test coverage:** No domain test found.

### BR-PROD-006 — Completion has no lifecycle guard

- **Description:** The completion action contains no check that the production is currently `processing` or not already `completed`. A second accepted request reaches the same deduction and finished-good creation code if stock validation passes.
- **Evidence:** `ProductionController::complete()` in `app/Http/Controllers/ProductionController.php`.
- **Evidence basis:** First sentence `SOURCE_VERIFIED`; repeated-request consequence `INFERRED_FROM_CODE`
- **Confidence:** High
- **Affected modules:** Production, raw-material stock, finished goods, data integrity
- **Test coverage:** No domain test found. Runtime reproduction is `NEEDS_RUNTIME_VERIFICATION`.

## Finished goods, distribution, sales counts, and returns

### BR-FG-001 — Automatic expiry condition

- **Description:** The expiry command changes an `available` finished-good batch to `expired` when its expiry date is strictly earlier than the current date. The schedule invokes this command every minute.
- **Evidence:** `app/Console/Commands/ExpireFinishedGoods.php` and `routes/console.php`.
- **Evidence basis:** `SOURCE_VERIFIED`
- **Confidence:** High
- **Affected modules:** Finished goods, scheduling
- **Test coverage:** No command/schedule test found. Actual scheduler operation is `NEEDS_RUNTIME_VERIFICATION`.

### BR-DIST-001 - Allocation eligibility

- **Description:** Allocation requires a user whose role is `armada`, at least one positive batch quantity, an `available` nonexpired batch, and sufficient current quantity. Allocation immediately reduces finished-good current quantity and marks a depleted batch `empty`. Batch IDs arrive as `items` array keys rather than validated `items.*` fields; the transaction resolves each key with `firstOrFail()`.
- **Evidence:** `Owner\ArmadaSessionController::store()` in `app/Http/Controllers/Owner/ArmadaSessionController.php`.
- **Evidence basis:** `SOURCE_VERIFIED`; malformed-key HTTP behavior is `NEEDS_RUNTIME_VERIFICATION`.
- **Confidence:** High
- **Affected modules:** Distribution, finished goods, Armada
- **Test coverage:** No domain test found.

### BR-DIST-002 - One active Armada session

- **Description:** Allocation rejects an Armada user who already has an active session. The rule is enforced in application code and has no supporting database uniqueness constraint.
- **Evidence:** `Owner\ArmadaSessionController::store()`; Armada session migration.
- **Evidence basis:** `SOURCE_VERIFIED`
- **Confidence:** High
- **Affected modules:** Distribution, Armada
- **Test coverage:** No domain or concurrency test found. Concurrent enforcement is `NEEDS_RUNTIME_VERIFICATION`.

### BR-DIST-003 - FEFO is presentation ordering, and expiry day is inclusive

- **Description:** Eligible finished-good batches are ordered by earliest expiry for display, but the Owner manually selects quantities; the controller does not auto-allocate FEFO. Allocation and ready-return checks reject dates earlier than today, so a batch expiring today remains eligible. The expiry command likewise changes status only when `expired_date < today`.
- **Evidence:** `Owner\ArmadaSessionController::index()` and `store()`; `Produksi\ReturnCheckController::update()`; `ExpireFinishedGoods::handle()` in `app/Console/Commands/ExpireFinishedGoods.php`.
- **Evidence basis:** `SOURCE_VERIFIED`
- **Confidence:** High
- **Affected modules:** Finished goods, distribution, returns, expiry
- **Test coverage:** No FEFO or expiry-boundary test found.

### BR-SALE-001 - Sold quantities are cumulative counts

- **Description:** An Armada user may update sold quantities only for their own active session. Each supplied value overwrites the item's cumulative `quantity_sold`, must be a nonnegative integer, and cannot exceed quantity sent. The save loop visits every session item and uses zero when an item ID is omitted, so an omitted item resets its stored sold count to zero; validation does not require every item key.
- **Evidence:** `Armada\SessionController::updateSold()` and `saveSoldQuantities()` in `app/Http/Controllers/Armada/SessionController.php`.
- **Evidence basis:** Loop/default and validation behavior are `SOURCE_VERIFIED`; HTTP behavior for omitted keys is `NEEDS_RUNTIME_VERIFICATION`. `updateSold()` calls this loop without a transaction, so a later over-limit item can leave earlier updates persisted (`INFERRED_FROM_CODE`); `finish()` wraps it in a transaction.
- **Confidence:** High
- **Affected modules:** Armada, sales counts, authorization
- **Test coverage:** No domain test found. Quantity capture is `PARTIALLY_IMPLEMENTED`; the absent event/order/revenue layer is `SCAFFOLDED_ONLY`.

### BR-SALE-002 - Inventory leaves ready stock at allocation, not sale entry

- **Description:** Finished-good `current_quantity` is decremented when the Owner allocates a batch to an Armada session. Updating `quantity_sold` performs no further finished-good stock mutation, so the removed quantity represents stock in field custody/dispatch, not a sale-time inventory movement.
- **Evidence:** `Owner\ArmadaSessionController::store()` and `Armada\SessionController::updateSold()`/`saveSoldQuantities()`.
- **Evidence basis:** Mutation timing is `SOURCE_VERIFIED`; its business interpretation is `INFERRED_FROM_CODE`.
- **Confidence:** High
- **Affected modules:** Finished goods, distribution, Armada, sales counts
- **Test coverage:** No stock-timing/sales-ledger test found.

### BR-RETURN-001 — Session finish derives returns

- **Description:** Finishing an owned active session saves final sold counts, calculates returned quantity as sent minus sold, creates one pending return check for each positive result, and marks the session finished with a timestamp.
- **Evidence:** `Armada\SessionController::finish()` in `app/Http/Controllers/Armada/SessionController.php`.
- **Evidence basis:** `SOURCE_VERIFIED`
- **Confidence:** High
- **Affected modules:** Armada, distribution, returns
- **Test coverage:** No domain test found.

### BR-RETURN-002 — Ready return re-enters original batch

- **Description:** A pending return classified `ready` may re-enter inventory only when the original batch has not expired. Its quantity is added to the original finished-good row and status is set to `available`.
- **Evidence:** `Produksi\ReturnCheckController::update()` in `app/Http/Controllers/Produksi/ReturnCheckController.php`.
- **Evidence basis:** `SOURCE_VERIFIED`
- **Confidence:** High
- **Affected modules:** Returns, Produksi, finished goods
- **Test coverage:** No domain test found.

### BR-RETURN-003 — Rejection and disposal

- **Description:** A pending return classified `expired_damaged` creates a separate rejected finished-good row linked from the return check. Disposal is permitted only for such a linked check and changes the rejected row to zero quantity and `empty`.
- **Evidence:** `Produksi\ReturnCheckController::update()` and `dispose()`.
- **Evidence basis:** `SOURCE_VERIFIED`
- **Confidence:** High
- **Affected modules:** Returns, waste/disposal, finished goods
- **Test coverage:** No domain test found. There is no separate disposal-event ledger: `PARTIALLY_IMPLEMENTED` audit history.

## Reports

### BR-REPORT-001 — Transaction report population

- **Description:** Transaction table/KPI/export queries can filter by material-name search, type, year, and month. Purchase value sums only restock `total_price`. Purchase-trend queries (including the index/AJAX response and chart endpoint) and the composition endpoint apply year/month but not material search/type.
- **Evidence:** `Owner\TransactionReportController` and `app/Exports/TransactionReportExport.php`.
- **Evidence basis:** `SOURCE_VERIFIED`
- **Confidence:** High
- **Affected modules:** Raw-material transaction reports, AJAX, export
- **Test coverage:** No report/export test found. XLSX generation and AJAX behavior are `NEEDS_RUNTIME_VERIFICATION`.

### BR-REPORT-002 - Production metrics use target and current BOM

- **Description:** Production KPIs and trends consider completed production and sum target quantities. Year/month filters and trend buckets use `plan_date`, because no completion timestamp is stored on `productions`. Material-use summaries recalculate current recipe quantity times target; waste summaries use persisted production-waste quantities.
- **Evidence:** `Owner\ProductionReportController::index()` in `app/Http/Controllers/Owner/ProductionReportController.php`.
- **Evidence basis:** `SOURCE_VERIFIED`
- **Confidence:** High
- **Affected modules:** Production reports, BOM, waste
- **Test coverage:** No report test found. Historical values after recipe changes are `INFERRED_FROM_CODE` and `NEEDS_RUNTIME_VERIFICATION` with representative data.
