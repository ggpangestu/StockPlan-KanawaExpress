# Data Model Audit

## Scope and evidence conventions

This document describes the schema and Eloquent model layer observable in the repository. It is a static audit only; migrations were not executed and database contents were not queried.

- `SOURCE_VERIFIED`: directly present in migrations, models, controllers, or queries.
- `INFERRED_FROM_CODE`: a likely consequence of the verified implementation, not observed at runtime.
- `NEEDS_RUNTIME_VERIFICATION`: requires an applied database and runtime inspection.
- `PARTIALLY_IMPLEMENTED`: data structures exist, but the application does not maintain a complete workflow or history.
- `SCAFFOLDED_ONLY`: a schema or code hook exists without an active writer/workflow.
- `UNUSED`: present in schema/model code but no active application use was found.
- `UNKNOWN`: the repository does not establish the answer.

Primary schema sources are `database/migrations/*.php`; model sources are `app/Models/*.php`.

## Domain map

```text
users
 |--< raw_materials --< raw_material_transactions
 |          >-- menu_raw_material --< menus
 |                                      |--< production_items >-- productions
 |                                      |                         |--< production_wastes
 |                                      |                         `--< finished_goods
 |                                      `------------------------------^
 |
 `--< armada_sessions --< armada_session_items >-- finished_goods
                                  `-- return_checks
                                       |-- original finished_good
                                       `-- rejected finished_good (optional)
```

The diagram shows principal business links, not every creator/checker foreign key.

## Table inventory

### Identity and framework infrastructure

| Table | Important columns and constraints | Application use | Evidence status |
|---|---|---|---|
| `users` | `name`; unique `username`; nullable unique `email`; nullable `email_verified_at`; `password`; unconstrained string `role` defaulting to `armada`; remember token; timestamps | Authentication and the `owner`, `produksi`, and `armada` role switch. Also referenced as creator, allocator, Armada assignee, and return checker. | `SOURCE_VERIFIED` |
| `password_reset_tokens` | Email primary key, token, nullable creation time | Laravel password-reset flow. Its email key contrasts with operational seeded users that have no email. | `SOURCE_VERIFIED`; live usability is `NEEDS_RUNTIME_VERIFICATION` |
| `sessions` | String ID; nullable indexed `user_id`; IP, user agent, payload, indexed last activity | Database-session infrastructure supplied by the framework migration. | `SOURCE_VERIFIED`; active configured driver is `UNKNOWN` without runtime/environment inspection |
| `cache` | String primary key, value, indexed expiration | Framework cache infrastructure. No explicit domain cache usage was found. | `UNUSED` by domain code |
| `cache_locks` | String primary key, owner, indexed expiration | Framework atomic-lock infrastructure. No explicit domain lock usage was found. | `UNUSED` by domain code |
| `jobs` | Queue name, payload, attempts, reservation/availability/creation times | Queue infrastructure. No domain jobs were found. | `UNUSED` by domain code |
| `job_batches` | Batch counts, failed IDs, options, lifecycle timestamps | Queue batching infrastructure. No domain batch workflow was found. | `UNUSED` by domain code |
| `failed_jobs` | Unique UUID, connection, queue, payload, exception, failure time | Failed-queue infrastructure. No domain jobs were found. | `UNUSED` by domain code |

Sources: `database/migrations/0001_01_01_000000_create_users_table.php`, `database/migrations/0001_01_01_000001_create_cache_table.php`, `database/migrations/0001_01_01_000002_create_jobs_table.php`, and `database/migrations/2026_05_04_013516_add_username_and_role_to_users_table.php`.

### Raw-material inventory

| Table | Important columns and constraints | Application use | Evidence status |
|---|---|---|---|
| `raw_materials` | Identity/image/category; integer `sealed_stock`; decimal(12,2) `opened_stock`; `purchase_unit`; `base_unit`; decimal(12,2) `conversion_value`; decimal(12,2) `minimum_stock`; nullable decimal(12,2) `latest_price`; `is_active`; `created_by` cascading to `users` | The current physical raw-material balance and unit conversion configuration. | `SOURCE_VERIFIED` |
| `raw_material_transactions` | Cascading `raw_material_id`; enum `type` (`restock`, `adjustment_add`, `adjustment_reduce`, `production_usage`); base-unit decimal(12,2) `quantity`; nullable purchase-unit decimal(12,2) `purchase_quantity`; nullable decimal(12,2) `unit_price` and `total_price`; total-stock decimal(12,2) `before_stock` and `after_stock`; notes; nullable/null-on-delete `created_by` | Ledger rows are written for restocks and manual adjustments. | `PARTIALLY_IMPLEMENTED` because production consumption and production waste do not write ledger rows |

Sources: `database/migrations/2026_05_11_043513_create_raw_materials_table.php`, `database/migrations/2026_05_14_161126_create_raw_material_transactions_table.php`, `database/migrations/2026_05_14_175937_make_latest_price_nullable_on_raw_materials_table.php`, `database/migrations/2026_05_14_184018_add_price_columns_to_raw_material_transactions_table.php`, and `database/migrations/2026_05_16_133719_add_purchase_quantity_to_raw_material_transactions_table.php`.

### Menu and recipe/BOM

| Table | Important columns and constraints | Application use | Evidence status |
|---|---|---|---|
| `menus` | Name/category; decimal(12,2) sale `price`; `expires_in_days` default `1`; image/description; `is_active`; `created_by` referencing `users` with the database default delete behavior | Current menu master, price, shelf life, and active state. | `SOURCE_VERIFIED` |
| `menu_raw_material` | Surrogate ID; cascading `menu_id` and `raw_material_id`; decimal(10,2) base-unit `quantity`; timestamps | Current recipe/BOM quantity for one menu ingredient. There is no database uniqueness constraint on `(menu_id, raw_material_id)`. | `SOURCE_VERIFIED`; recipe history is absent, so historical BOM support is `SCAFFOLDED_ONLY` |

Sources: `database/migrations/2026_05_18_005715_create_menus_table.php` and `database/migrations/2026_06_02_205119_add_expires_in_days_to_menus_table.php`.

### Production and finished goods

| Table | Important columns and constraints | Application use | Evidence status |
|---|---|---|---|
| `productions` | `plan_date`; enum `status` (`planned`, `processing`, `completed`, `cancelled`); planning `notes`; nullable `execution_notes`; `created_by` referencing `users` with the database default delete behavior | Production-plan header and lifecycle state. | `SOURCE_VERIFIED` |
| `production_items` | Cascading `production_id`; `menu_id` with default delete behavior; integer `target_quantity`; integer `actual_quantity` default `0`; timestamps | Planned menu output lines. | `PARTIALLY_IMPLEMENTED`: target is used, but actual output is not maintained |
| `production_wastes` | Cascading `production_id`; cascading `raw_material_id`; decimal(10,2) `quantity`; timestamps | Records raw material reported wasted during completion. It has no production-item/menu attribution, reason/notes, reporting actor, or dedicated occurrence timestamp beyond framework timestamps. | `SOURCE_VERIFIED`; contextual waste history is `PARTIALLY_IMPLEMENTED` |
| `finished_goods` | `menu_id`; nullable `production_id`; integer `initial_quantity` and `current_quantity`; production/expiry dates; string `status` default `available`; timestamps | Finished-good batches, including available, empty, expired, and rejected `expired_damaged` records. | `SOURCE_VERIFIED` |

Sources: `database/migrations/2026_06_02_184133_create_productions_table.php`, `database/migrations/2026_06_02_211015_create_finished_goods_table.php`, `database/migrations/2026_06_04_020411_add_execution_notes_to_productions_table.php`, `database/migrations/2026_06_04_184849_add_wasted_quantity_to_production_items_table.php`, `database/migrations/2026_06_06_064510_create_production_wastes_table.php`, and `database/migrations/2026_06_04_000002_create_return_checks_table.php`.

### Distribution, sales counts, and returns

| Table | Important columns and constraints | Application use | Evidence status |
|---|---|---|---|
| `armada_sessions` | Cascading `armada_user_id`; nullable/null-on-delete `allocated_by`; `session_date`; enum `status` (`active`, `finished`); start/finish timestamps; notes | One distribution run assigned to an Armada user. The one-active-session rule is application-enforced only. | `SOURCE_VERIFIED` |
| `armada_session_items` | Cascading `armada_session_id` and `finished_good_id`; integer `quantity_sent`; integer `quantity_sold` and `quantity_returned` default `0` | Per-batch allocation and cumulative sold/returned counts. | `PARTIALLY_IMPLEMENTED`: sale quantities exist, but no sale-event, price, revenue, order, payment, or customer data exists |
| `return_checks` | Cascading session-item, original-finished-good, and Armada user IDs; nullable/null-on-delete checker and rejected-finished-good IDs; integer `quantity`; enum `status` (`pending`, `ready`, `expired_damaged`); checked time; notes | Produksi inspection decision and link to an optional rejected finished-good batch. Original finished-good, Armada, and quantity values duplicate facts derivable from the linked session item/session. | `SOURCE_VERIFIED` |

Sources: `database/migrations/2026_06_04_000001_create_armada_sessions_table.php`, `database/migrations/2026_06_04_000002_create_return_checks_table.php`, and `database/migrations/2026_06_04_000004_add_allocated_by_to_armada_sessions_table.php`.

## Eloquent relationships

| Model | Defined relationships | Important absent or asymmetric inverse | Evidence status |
|---|---|---|---|
| `User` | `hasMany` Armada sessions as assignee; `hasMany` return checks as Armada | No defined inverse for raw-material/menu/production creator, session allocator, or return checker. | `SOURCE_VERIFIED` |
| `RawMaterial` | `hasMany` transactions; `belongsToMany` menus; `belongsTo` creator | The `menus()` side does not request pivot quantity/timestamps, whereas `Menu::ingredients()` does. | `SOURCE_VERIFIED` |
| `RawMaterialTransaction` | `belongsTo` raw material and creator | None material to current workflows. | `SOURCE_VERIFIED` |
| `Menu` | `belongsToMany` raw materials as `ingredients`, including pivot quantity/timestamps | No creator, production-item, or finished-good inverse is defined. | `SOURCE_VERIFIED` |
| `Production` | `hasMany` items; `belongsTo` creator; `hasMany` wastes | No finished-goods relationship is defined. | `SOURCE_VERIFIED` |
| `ProductionItem` | `belongsTo` menu | No `production()` relationship despite `production_id`. | `SOURCE_VERIFIED` |
| `ProductionWaste` | `belongsTo` raw material | No `production()` relationship despite `production_id`. | `SOURCE_VERIFIED` |
| `FinishedGood` | `belongsTo` menu and production; `hasMany` Armada session items; `hasMany` return checks through the original `finished_good_id` | No inverse relationship exposes return checks where this row is the rejected batch through `rejected_finished_good_id`. | `SOURCE_VERIFIED` |
| `ArmadaSession` | `belongsTo` Armada and allocator; `hasMany` items | None material to current workflows. | `SOURCE_VERIFIED` |
| `ArmadaSessionItem` | `belongsTo` session and finished good; `hasOne` return check | The database does not enforce the implied one-to-one return-check relation. | `SOURCE_VERIFIED` |
| `ReturnCheck` | `belongsTo` session item, original finished good, Armada, checker, and rejected finished good | None material to current workflows. | `SOURCE_VERIFIED` |

Sources: `app/Models/*.php`.

### Model/schema attribute mismatches

- `SOURCE_VERIFIED`: `raw_materials` and `RawMaterial` expose `purchase_unit` and `base_unit`, not `unit`. `ProductionController::show()` stores `$ing->unit` in its calculated array, and `resources/views/dashboard/produksi.blade.php`, `resources/views/prod/show.blade.php`, and `resources/views/owner/productions/show.blade.php` also read `->unit`. These references have no declared model/schema source; rendered labels remain `NEEDS_RUNTIME_VERIFICATION`.
- `SOURCE_VERIFIED`: `ProductionItem::$fillable` includes `wasted_quantity` after the final migration sequence removes that column.
- `SOURCE_VERIFIED`: owner production views recognize legacy status `done`, while the schema enum contains only `planned`, `processing`, `completed`, and `cancelled`; no writer for `done` was found.

## Stock fields and mutation paths

### Raw-material stock

`RawMaterial::getTotalStockAttribute()` in `app/Models/RawMaterial.php` defines:

```text
total_stock = opened_stock + (sealed_stock * conversion_value)
```

- `sealed_stock` is a package count. The schema declares it as integer, while the model casts it as `decimal:2`.
- `opened_stock`, recipe quantities, transaction quantities, and waste quantities represent base-unit quantities.
- `conversion_value` is the number of base units in one purchase/sealed unit.
- Restock increments `sealed_stock` by an integer purchase quantity.
- Manual add/reduce adjustments affect only `opened_stock`.
- Production completion opens `ceil(shortage / conversion_value)` sealed packages when opened stock is insufficient, transfers their converted amount into opened stock, and then deducts consumption.
- Production waste follows the same package-opening and deduction pattern.

Evidence: `RawMaterialController::storeRestock()`, `RawMaterialController::storeAdjustment()`, and `ProductionController::complete()` in `app/Http/Controllers/RawMaterialController.php` and `app/Http/Controllers/ProductionController.php`. All statements above are `SOURCE_VERIFIED`; their behavior against a live database is not runtime-tested.

There is no stock field or ledger event specifically recording that a package was opened. The change is represented only by mutations to `sealed_stock` and `opened_stock`: `PARTIALLY_IMPLEMENTED` stock-state history.

### Finished-good stock

- `initial_quantity` is the batch quantity created by production completion.
- `current_quantity` is the remaining inventory available in that row.
- Allocation to an Armada session immediately subtracts from `current_quantity`; a batch becomes `empty` at zero.
- A return classified `ready` adds its quantity back to the original batch and forces status to `available`, provided the expiry date has not passed.
- A return classified `expired_damaged` creates a separate finished-good row with that status and with initial/current quantity equal to the rejected quantity.
- Disposal zeroes the rejected row's `current_quantity` and changes its status to `empty`.
- The expiry command changes overdue `available` rows to `expired` without zeroing quantity.

Evidence: `Owner\ArmadaSessionController::store()`, `Produksi\ReturnCheckController::update()`, `Produksi\ReturnCheckController::dispose()`, and `app/Console/Commands/ExpireFinishedGoods.php`: `SOURCE_VERIFIED`.

## Calculated values

| Value | Formula/source | Persistence and caveat | Evidence status |
|---|---|---|---|
| Raw-material total stock | `opened_stock + sealed_stock * conversion_value` | Accessor only; transaction rows snapshot this total before/after restock or adjustment. | `SOURCE_VERIFIED` |
| Stock-health ratio | `total_stock / max(minimum_stock, 1)` | Accessor returns `critical` at `<= 1`, `warning` at `<= 1.5`, `caution` at `<= 2`, otherwise `healthy`. | `SOURCE_VERIFIED` |
| Raw-material attention filter | `total_stock <= minimum_stock * 1.5` | SQL filter; differs from the accessor when `minimum_stock` is `0`. | `SOURCE_VERIFIED` |
| Produksi dashboard low-stock list | `total_stock < 10` | Fixed threshold independent of `minimum_stock` and stock-health bands. | `SOURCE_VERIFIED` |
| Restock base quantity | `purchase_quantity * conversion_value` | Saved as transaction `quantity`; restock package count also saved separately. | `SOURCE_VERIFIED` |
| Restock total price | `purchase_quantity * unit_price` | Saved on the transaction. Price is per purchase unit, not per base unit. | `SOURCE_VERIFIED` |
| Menu HPP | Sum of `recipe quantity * (raw_material.latest_price / conversion_value)` | Calculated in request handling/view preparation from current price and current recipe; not snapshotted. | `SOURCE_VERIFIED` |
| Menu profit and margin | `price - HPP`; `profit / price * 100` when price is positive | Calculated for the menu listing; not persisted. | `SOURCE_VERIFIED` |
| Menu readiness | Every ingredient's `total_stock >= one recipe quantity` | Tests readiness for one menu unit, not a requested production quantity. | `SOURCE_VERIFIED` |
| Planned material requirement | Sum of `pivot.quantity * target_quantity` | Recomputed from current BOM. | `SOURCE_VERIFIED` |
| Reserved raw-material stock | Requirements for all `planned` and `processing` productions | In-memory only; no reservation table or reserved column. | `PARTIALLY_IMPLEMENTED` |
| Available raw-material stock | `physical total_stock - calculated reserved_stock` | Display/planning value only; can be negative and is not enforced by the plan store/update controller. | `SOURCE_VERIFIED` calculation; enforcement consequence is `INFERRED_FROM_CODE` |
| Production deduction | Current BOM times target, plus submitted waste | Deducted at completion. Normal use is not written to the raw-material transaction ledger. | `PARTIALLY_IMPLEMENTED` audit trail |
| Finished-good expiry | Completion date plus current `menu.expires_in_days` (fallback `1`) | Persisted on the finished-good row. | `SOURCE_VERIFIED` |
| Armada return quantity | `quantity_sent - quantity_sold` | Persisted in the session item at finish; positive quantities are copied into return checks. | `SOURCE_VERIFIED` |
| Production report portions | Sum of `target_quantity` for completed production | Does not use `actual_quantity`. | `SOURCE_VERIFIED` |
| Production report material use | Current recipe quantity times stored target | Historical values can change when a recipe changes. | `SOURCE_VERIFIED` formula; historical effect is `INFERRED_FROM_CODE` |

## Sources of truth, duplication, and missing history

### Multiple or duplicated representations

1. **Raw-material quantity:** `raw_materials.sealed_stock` and `opened_stock` are the live balance; `raw_material_transactions.before_stock` and `after_stock` are only snapshots. The ledger is not a complete reconstruction source because production use/waste is absent. `PARTIALLY_IMPLEMENTED`.
2. **Current price versus restock price:** `raw_materials.latest_price` is the current price, while restock rows have `unit_price`. Editing any historical restock also replaces `latest_price`, so chronology alone does not determine which row supplies the current value. `SOURCE_VERIFIED`.
3. **Low-stock meaning:** the stock-health accessor, raw-material attention query, and Produksi dashboard use three different thresholds. `SOURCE_VERIFIED`.
4. **Production output:** `production_items.target_quantity`, `production_items.actual_quantity`, and `finished_goods.initial_quantity` could describe planned, actual, and stocked output. In current code, actual stays zero and finished goods are created at full target. `PARTIALLY_IMPLEMENTED`.
5. **Finished-good lifecycle:** both `current_quantity` and `status` encode availability. Expired rows may retain positive quantity; rejected rows use a separate status; empty is explicitly set in allocation/disposal paths. `SOURCE_VERIFIED`.
6. **Distribution and return-check facts:** `quantity_sent`, `quantity_sold`, and `quantity_returned` summarize a batch allocation. A positive return copies `quantity_returned`, `finished_good_id`, and the session's `armada_user_id` into `return_checks`; all are derivable through `armada_session_item_id`. No database uniqueness or cross-row check guarantees one check per item or agreement among the copies. `SOURCE_VERIFIED`.
7. **Distribution time:** `session_date` and `started_at` both represent session start at different precisions. Both are set when allocating. `SOURCE_VERIFIED`.

### Missing historical or transactional sources of truth

1. **Recipe/BOM version:** no production-plan or production-completion snapshot stores ingredient identities and quantities. Historical calculations read `menu_raw_material` as it exists now. `SCAFFOLDED_ONLY`.
2. **Cost snapshot:** neither production items nor finished goods store unit material costs/HPP at planning or completion. Menu and production-detail HPP calculations use current `latest_price` and current recipes; the production report does not currently calculate cost. `SCAFFOLDED_ONLY`.
3. **Actual yield:** `actual_quantity` is initialized to `0` during plan creation/replacement, but no active input writes an actual yield and no reader uses it. `UNUSED` as an operational yield source.
4. **Raw-material production ledger:** the `production_usage` enum value and model constant exist, but no writer was found. Normal consumption and recorded waste mutate raw-material stock without a `raw_material_transactions` row. `SCAFFOLDED_ONLY`/`PARTIALLY_IMPLEMENTED`.
5. **Package-opening history:** no table records when or why sealed packages become opened stock. `UNKNOWN` as a business requirement; absent in current implementation.
6. **Finished-good movement ledger:** allocations, ready returns, rejected returns, expiry, and disposal mutate rows or create batches, but there is no generalized movement/event table. `PARTIALLY_IMPLEMENTED` auditability.
7. **Sale and revenue ledger:** only a mutable cumulative `quantity_sold` exists. No order, event time, customer, unit-price snapshot, revenue, payment, or refund table exists. `SCAFFOLDED_ONLY` for a full sales domain.
8. **Persisted MRP/ROP:** reserved stock is calculated in memory. There are no demand, lead-time, reorder-point, safety-stock, planned-order, purchase-order, or time-phased MRP tables. `SCAFFOLDED_ONLY`.
9. **Production-waste context:** waste rows identify only production, raw material, quantity, and framework timestamps. They do not identify the affected production item/menu, reason/category, reporting user, approval, or a raw-material ledger movement. `PARTIALLY_IMPLEMENTED` traceability.
10. **Production completion time:** `productions` has `plan_date`, status, and generic timestamps but no dedicated `completed_at`. Finished-good rows store a date, while production reports filter/bucket completed runs by `plan_date`. `PARTIALLY_IMPLEMENTED` temporal history.

## Unused or scaffolded schema/model fields

| Item | Finding | Status | Evidence |
|---|---|---|---|
| `production_items.actual_quantity` | Set to `0` when plan items are created/replaced; no path writes a measured actual yield and no reader was found in the active workflow. | `UNUSED` as actual-yield data | `Owner\ProductionController::store()` and `update()`; repository search |
| `ProductionItem::$fillable['wasted_quantity']` | Final schema removes this column, but the model still lists it. | `UNUSED` stale model attribute | `app/Models/ProductionItem.php`; the two waste migrations |
| `raw_material_transactions.type = production_usage` | Enum, constant, report label, and filter support exist; no transaction creation with this type was found. | `SCAFFOLDED_ONLY` | raw-material transaction migration/model/report controller and repository search |
| `productions.status = cancelled` | Schema, filters, ordering, and owner edit/delete conditions recognize it; no controller action sets it. | `SCAFFOLDED_ONLY` | production migration and controllers |
| `RawMaterialTransaction::scopeTimeframe()` | Model scope implements today/week/month/year filtering, but no current controller/view/export call site was found; transaction reports use separate year/month scopes. | `UNUSED` | `app/Models/RawMaterialTransaction.php`; `app/Http/Controllers/Owner/TransactionReportController.php`; `resources/views/owner/reports/transactions/` |
| Cache and queue tables | Framework schema exists; no domain cache/queue producers or jobs were found. | `UNUSED` by domain code | framework migrations and `app/` search |

## Database integrity constraints not represented in schema

The following are verified absences from migrations; whether existing production data violates them is `NEEDS_RUNTIME_VERIFICATION`.

- No database constraint limits `users.role` to `owner`, `produksi`, or `armada`.
- No unique key on `menu_raw_material(menu_id, raw_material_id)`.
- No unique key on `production_items(production_id, menu_id)`.
- No check constraints guarantee nonnegative stock, target/output, allocation, sale, return, or waste quantities.
- No database constraint permits only one active Armada session per Armada user.
- No unique key on `armada_session_items(armada_session_id, finished_good_id)`.
- No unique key on `return_checks.armada_session_item_id`, despite the model declaring `hasOne`.
- There is no foreign-key-level guarantee that `armada_sessions.armada_user_id` points to a user whose role is `armada`.
- No check constrains final unrestricted-string `finished_goods.status` to the four model constants.
- No check guarantees `finished_goods.current_quantity <= initial_quantity`, nonnegative quantities, or `expired_date >= production_date`.
- No check guarantees `armada_session_items.quantity_sold + quantity_returned <= quantity_sent`.
- No composite constraint guarantees that `return_checks.finished_good_id`, `armada_user_id`, and `quantity` agree with the linked session item/session and its `quantity_returned`.
- No constraint guarantees `raw_material_transactions.quantity = purchase_quantity * conversion_value`; the conversion is not snapshotted on the transaction.
- No constraint guarantees `raw_material_transactions.total_price = purchase_quantity * unit_price`.

## Delete behavior and audit implications

- Deleting a raw-material creator cascades deletion of that user's raw materials. Those deletions cascade to raw-material transactions, recipe pivot rows, and production-waste rows through their own foreign keys.
- Transaction `created_by`, session `allocated_by`, return `checked_by`, and rejected-finished-good links are nullable and use `nullOnDelete`.
- Menu and production creator foreign keys use the database default restrictive behavior; so do menu links from production items/finished goods and production links from finished goods.
- Deleting an Armada user cascades their sessions and return checks. Session deletion cascades session items; session-item deletion cascades its return checks.
- Deleting an original `finished_goods` row cascades its `armada_session_items`, and those deletions cascade their `return_checks`; allocation, sold-count, and return history can therefore be erased at the schema level. A rejected-batch reference uses `nullOnDelete` instead.
- Models do not use soft deletes.

These rules are `SOURCE_VERIFIED` from migrations. The exact result of deleting a heavily referenced user/record, including which restrictive key stops an operation first, is `NEEDS_RUNTIME_VERIFICATION` against the applied schema and database engine.

## Migration inconsistencies

1. `database/migrations/2026_06_04_000004_add_allocated_by_to_armada_sessions_table.php` conditionally adds `allocated_by`, but the preceding create migration already defines it. Its `up()` normally does nothing on a fresh migration chain, while its `down()` removes the pre-existing column. This makes rollback behavior asymmetric. `SOURCE_VERIFIED`.
2. `database/migrations/2026_06_04_184849_add_wasted_quantity_to_production_items_table.php` adds `wasted_quantity`; `database/migrations/2026_06_06_064510_create_production_wastes_table.php` removes it after creating the normalized waste table. The latter migration's `down()` drops only `production_wastes` and does not restore `wasted_quantity`. Rolling both migrations back in reverse can then ask the earlier migration to drop a column that was not restored. `SOURCE_VERIFIED` migration sequence; actual engine behavior is `NEEDS_RUNTIME_VERIFICATION`.
3. `finished_goods.status` begins as an enum of `available`, `empty`, and `expired`, then `database/migrations/2026_06_04_000002_create_return_checks_table.php` changes it to an unrestricted string so `expired_damaged` can be stored. The model contains all four constants, while the down migration restores the three-value enum. `SOURCE_VERIFIED`.
4. `raw_materials.sealed_stock` is an integer column but is cast to `decimal:2` by `RawMaterial`. Restock validation accepts only integer packages, so the active workflow remains integral despite the cast mismatch. `SOURCE_VERIFIED`.
5. `ProductionItem::$fillable` still names the removed `wasted_quantity` column. `SOURCE_VERIFIED` stale model/schema mismatch.
6. Schema-changing migrations use `change()` for `latest_price` and `finished_goods.status`. Whether all intended database drivers execute and reverse those changes consistently is `NEEDS_RUNTIME_VERIFICATION`.
7. `database/migrations/2026_05_04_013516_add_username_and_role_to_users_table.php` adds a non-null unique `username` without backfilling rows that may already exist from the base users migration. Fresh empty migration and upgrade-with-data paths can differ. The source condition is `SOURCE_VERIFIED`; upgrade behavior is `NEEDS_RUNTIME_VERIFICATION`.
8. Rolling back `database/migrations/2026_05_14_175937_make_latest_price_nullable_on_raw_materials_table.php` requests a non-null/defaulted decimal after the forward migration permits null data. How existing nulls are handled is engine/data dependent and `NEEDS_RUNTIME_VERIFICATION`.
9. The return-check migration changes `finished_goods.status` from an enum to string. Its `down()` drops return checks and then restores the three-value enum, but rejected finished-good rows with `expired_damaged` can remain. Rollback with such rows is `NEEDS_RUNTIME_VERIFICATION`.

## Runtime checks still required

This document does not establish:

- which migrations are applied in the live database;
- whether the live schema matches the final migration chain;
- the active database engine, isolation level, SQL mode, and foreign-key enforcement;
- whether duplicate recipe/session/return rows or negative quantities already exist;
- whether delete cascades/restrictions behave as expected with current data;
- whether decimal precision and status-changing migrations behave consistently on the configured engine.

Each item is `NEEDS_RUNTIME_VERIFICATION`; none was tested during this audit.
