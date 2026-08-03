# Runtime Verification Checklist

## Purpose and safety boundary

Every item below is pending and has status `NEEDS_RUNTIME_VERIFICATION`. None of these checks was executed during the repository audit.

Run them only in an isolated review environment with a disposable database and disposable storage. Do not point the application, queue, scheduler, mailer, or test commands at production services. Capture the application commit, PHP/Composer/Node versions, database engine/version, environment variables with secrets redacted, command output, HTTP status, relevant database before/after snapshots, and browser console/network evidence.

## Review-environment prerequisites

- [ ] RV-001 — Clone or copy the audited revision into an isolated workspace; confirm `git status --short` before testing. Expected evidence: revision identifier and clean baseline.
- [ ] RV-002 — Prepare separate disposable databases for the configured production-family engine (expected MySQL/MariaDB) and SQLite where compatibility is being assessed. Expected evidence: engine names and versions.
- [ ] RV-003 — Create a review-only environment file from documented/configured values without reusing production secrets. Use a log or sandbox mail transport. Expected evidence: redacted configuration summary.
- [ ] RV-004 — Install locked Composer and npm dependencies without updating lock files. Expected evidence: install logs and unchanged `composer.lock`/`package-lock.json`.
- [ ] RV-005 — Build front-end assets without changing dependency versions. Expected evidence: Vite build result and manifest path. Treat generated build output as disposable and do not commit it as part of this audit.
- [ ] RV-006 — Take schema/data snapshots before each destructive workflow group and restore the disposable database between groups. Expected evidence: snapshot identifiers and restore confirmation.

## Bootstrap, migrations, and portability

- [ ] RV-007 — On a disposable copy of the pre-username schema containing representative existing users, apply `2026_05_04_013516_add_username_and_role_to_users_table.php`. Record how the non-null unique username column is handled; do not use live accounts. Evidence: before/after schema/data and migration output.
- [ ] RV-008 — In a disposable database with at least one null `raw_materials.latest_price`, attempt rollback of `2026_05_14_175937_make_latest_price_nullable_on_raw_materials_table.php`. Record whether the older non-null/default constraint can be restored safely. Evidence: row/schema snapshot and output.
- [ ] RV-009 — In a disposable database containing an `expired_damaged` finished-good row, roll back `2026_06_04_000002_create_return_checks_table.php`. Record how the engine handles restoration of the three-value status enum. Evidence: before/after row/schema and output.
- [ ] RV-010 — On an empty MySQL/MariaDB review database, run the migrations in repository order and confirm that the guarded second `allocated_by` migration is a no-op. Then restore a snapshot, roll back only that later migration, and inspect whether it removes the column created by the earlier migration. Evidence: migration table, schema before/after, and complete command output. Covers `KNOWN-RISKS.md` VD-07.
- [ ] RV-011 — On an empty SQLite review database, run the same migrations. Record unsupported DDL, enum, foreign-key, or column-change behavior. Evidence: command output and resulting schema.
- [ ] RV-012 — Roll forward through `2026_06_06_064510_create_production_wastes_table.php`, then roll back exactly that migration in the disposable database. Confirm whether `production_items.wasted_quantity` is restored. Evidence: schema before/after. Covers VD-06.
- [ ] RV-013 — Compare the migrated schemas for all domain tables against their Eloquent `$fillable`/casts, especially `ProductionItem::$fillable`. Evidence: schema dump and mismatch list. Covers VD-05.
- [ ] RV-014 — Seed a fresh database and verify that all seeded owner, produksi, and armada users satisfy schema constraints and can be uniquely identified. Evidence: redacted user/role rows and seeder output.
- [ ] RV-015 — Run representative report queries on MySQL/MariaDB and SQLite, specifically queries using `FIELD`, `YEAR`, `MONTH`, and `DATE_FORMAT`. Evidence: response/error and generated SQL. Covers portability risk PT-01.
- [ ] RV-016 — Deploy or mount the project on a case-sensitive filesystem and open all menu routes. Confirm whether `owner.menus.*` resolves `resources/views/owner/Menus/`. Evidence: HTTP status and framework error if any. Covers PT-02.
- [ ] RV-017 — Start the application before and after a Vite build. Confirm the behavior when `public/build/manifest.json` is absent, and confirm all CSS/JS asset responses after the build. Evidence: page response, network waterfall, and browser console. Covers PT-03.
- [ ] RV-018 — Validate the documented installation/bootstrap procedure with the repository as-is, noting the absence of `.env.example`. Evidence: exact missing assumptions; do not add configuration during this audit.
- [ ] RV-019 — With explicit operations approval, inspect only a read-only schema/configuration artifact or disposable clone of the deployed environment. Record migration status, schema drift, database driver/version, isolation level, SQL mode, and foreign-key enforcement; do not query sensitive business rows or mutate the deployment. Evidence: redacted artifact metadata and comparison with migrations.

## Authentication and account lifecycle

- [ ] RV-020 — Log in using a seeded username and correct password, then with a wrong password; confirm redirect, session regeneration, error display, and throttling after five failed attempts. Evidence: statuses, session ID rotation, and UI screenshots.
- [ ] RV-021 — Create a disposable user with both username and email; confirm login by each identifier. Then create two review users designed to expose username/email identifier collisions and record which account is selected. Evidence: redacted rows and authentication results.
- [ ] RV-022 — Verify the login page advertises only the intended identifier(s) and check whether browser validation permits an email value in the username input. Evidence: rendered markup and successful/failed flows.
- [ ] RV-023 — Navigate directly to public registration while logged out. Confirm that it is reachable, creates a username, assigns the database-default role, logs in the user, and redirects to the role dashboard. Evidence: route status and redacted created row. Do not use a production database.
- [ ] RV-024 — Exercise generated-username collision handling sequentially and with two synchronized registrations whose names normalize to the same base. Evidence: resulting usernames, responses, and uniqueness behavior on disposable accounts.
- [ ] RV-025 — Request password reset for a user with email and for a seeded null-email user using a sandbox mailer. Confirm delivery/link behavior without sending real mail. Evidence: captured mail and broker response.
- [ ] RV-026 — Exercise email verification endpoints for a disposable user and confirm whether a verification notification is automatically issued, whether the link works, and whether any application route actually requires verification. Evidence: mail capture, `email_verified_at`, route results.
- [ ] RV-027 — Confirm a password for (a) a normal email user and (b) a null-email seeded user. Determine whether the nullable-email credential lookup can select the wrong account. Evidence: redacted queries/log and result. Covers the inferred password-confirmation ambiguity.
- [ ] RV-028 — Open the profile route in a browser and confirm whether the profile, password, and delete forms render inside the application layout. Evidence: screenshot and DOM. Covers VD-03.
- [ ] RV-029 — Submit valid and invalid profile updates and password changes; confirm validation feedback/toasts and persistence. Evidence: before/after rows, UI, console.
- [ ] RV-030 — Delete disposable users with and without domain history (created materials/menus/productions, allocated sessions, checked returns). Record whether foreign keys cascade, restrict, or leave references. Evidence: database before/after. Covers DI-10.
- [ ] RV-031 — Request each authenticated route with an unauthenticated client and with owner, produksi, armada, and an intentionally unknown role. Confirm redirects/403s and exact dashboard routing. Evidence: route-role response grid.

## Layout and client-side behavior

- [ ] RV-040 — Open each owner, produksi, and armada layout at desktop and mobile widths. Confirm sidebar/mobile navigation contains the intended modules and route links. Evidence: screenshots and link audit.
- [ ] RV-041 — Trigger success, validation, and error flash messages. Confirm toast visibility, dismissal timer, and absence/presence of JavaScript exceptions. Covers VD-13.
- [ ] RV-042 — Toggle a menu's active state while the server succeeds, returns validation/auth errors, returns 500, and is unreachable. Confirm UI rollback/error handling. Evidence: network and DOM state.
- [ ] RV-043 — Navigate paginated/filtered report and raw-material pages and reload them. Confirm scroll restoration, including the missing `main-content` element assumption. Evidence: DOM and scroll position.
- [ ] RV-044 — Use raw-material/menu names containing quotes, HTML-significant characters, non-ASCII Indonesian text, and line breaks in a disposable database. Confirm correct escaping, display, and JavaScript behavior. Evidence: rendered HTML and console; do not use attack payloads outside the sandbox.
- [ ] RV-045 — Inspect all audited pages for mojibake and encoding inconsistencies. Evidence: screenshots tied to source strings.
- [ ] RV-046 — Open the owner Armada account page; test search/tag/filter controls and compare displayed progress/summary figures with database values. Evidence: UI interaction and query-backed comparison.
- [ ] RV-047 — Temporarily render the otherwise unused `resources/views/layouts/navigation.blade.php` only in a disposable branch/environment, or use a view-rendering test without changing the audited tree. Confirm the undefined route failure. Evidence: renderer result. Do not commit a source change.

## Raw materials, units, restock, and adjustment

- [ ] RV-050 — Create a material with base unit, purchase unit, conversion value, minimum stock, nullable/current price, and image. Confirm initial sealed/opened stock is zero and the stored image is accessible. Evidence: request, row, storage URL.
- [ ] RV-051 — Exercise exact raw-material validation boundaries: create conversion below/at `1`, pre-transaction update conversion below/at `0.01`, empty/overlength free-form unit strings, oversized/non-image uploads, and duplicate names/categories. Record acceptance or rejection from the declared rules without presuming uniqueness or a controlled unit vocabulary. Evidence: validation response matrix.
- [ ] RV-052 — Restock a whole-package quantity. Verify sealed stock, unchanged opened stock, base-unit transaction quantity, `purchase_quantity`, `before_stock`/`after_stock`, unit price, total price, creator, and latest price. Explicitly confirm that purchase/base units are not snapshotted on the transaction and are read from the related material. Evidence: before/after material and ledger row.
- [ ] RV-053 — Submit restock through both normal form and AJAX request paths. Confirm redirect/JSON contracts and UI feedback. Evidence: status, payload, DOM.
- [ ] RV-054 — Edit a historical restock price and confirm which transaction fields and `raw_materials.latest_price` change. Then inspect menu-list and production-detail HPP values; do not expect a production-cost value in the current production report. Evidence: before/after rows and recalculated views.
- [ ] RV-055 — Apply positive and negative opened-stock adjustments at boundary values. Confirm sealed stock is not consumed/changed, negative stock is rejected, and snapshots/transaction types are correct. Evidence: before/after rows.
- [ ] RV-056 — Toggle a material active/inactive over AJAX and confirm menu creation, production availability, dashboard, and report inclusion rules. Evidence: UI/query behavior by state.
- [ ] RV-057 — Request the generated raw-material `show` and `destroy` resource routes and record the actual HTTP response from the empty actions. Do not delete a valuable record; use a disposable material. Covers VD-01.
- [ ] RV-058 — With a non-integral or fractional conversion value and fractional base stock, verify displayed totals, format/rounding, restock totals, and production deductions. Evidence: exact database decimals and rendered values.
- [ ] RV-059 — Reconcile a sequence of restocks and adjustments from ledger deltas/snapshots to `sealed_stock`, `opened_stock`, and calculated `total_stock`. Record whether the ledger is sufficient to reproduce the aggregate. Evidence: reconciliation worksheet.

## Recipes/BOM and menu lifecycle

- [ ] RV-060 — Create and edit a menu with multiple ingredients and fractional quantities. Confirm pivot synchronization, active filters, expiry days, image handling, and HPP display. Evidence: menu/pivot rows and rendered result.
- [ ] RV-061 — Request resource actions not implemented by `MenuController`, especially `show` and `destroy`, only against disposable data. Record response/error without changing source. Covers VD-01.
- [ ] RV-062 — Before a material has transaction history, create a plan and change its permitted unit/conversion fields; compare the plan's requirements before/after. Separately, after a restock/completion, confirm that unit/conversion edits are blocked, edit the recipe and compare completed-production material-use reports, then edit current/restock price and compare menu/production-detail HPP. Evidence: validation results, snapshots, and view/report deltas.
- [ ] RV-063 — Deactivate a menu/material that already appears in an open or completed plan; verify which detail, completion, and report screens can still load it. Evidence: responses and relation data.

## Production planning, consumption, waste, and yield

- [ ] RV-070 — Create production plans through the implemented owner index/store flow with one/multiple menu targets, past/today/future syntactically valid dates, and both an explicit null/empty `notes` field and a request that omits `notes` entirely. Verify response, declared date acceptance, `planned` status, creator, target quantities, and initial `actual_quantity`. Evidence: validation responses/errors and production/item rows.
- [ ] RV-071 — Open the owner production resource `create`, `edit`, and generated unsupported routes; record response/view behavior. Covers the scaffolded create page and incomplete resource surface.
- [ ] RV-072 — Compare planned/processing reservation calculations with hand-calculated current-BOM requirements, including two plans and two menus sharing a material. Evidence: input rows and displayed availability.
- [ ] RV-073 — Start a planned production and repeat the start request. Confirm allowed state transitions and responses.
- [ ] RV-074 — Complete a simple production with sufficient opened stock. Verify material deduction, production status/notes, waste rows, finished-good row, dates, quantity, and raw-material ledger. Explicitly confirm the absence/presence of `production_usage` rows. Evidence: transaction-scoped before/after snapshots.
- [ ] RV-075 — Complete when only sealed packages are available. Verify package opening uses the observed ceiling calculation, moves the expected base units into opened stock, and deducts demand without an unexplained remainder. Repeat with fractional conversion/demand. Evidence: exact arithmetic.
- [ ] RV-076 — Complete one plan containing multiple menus that share a raw material. Confirm combined requirements are checked/deducted exactly once. Evidence: hand calculation and before/after row values. Covers CR-03.
- [ ] RV-077 — Complete with insufficient total stock and with sufficient total stock but edge-case sealed/opened composition. Confirm atomic rejection and unchanged database state on failure; compare the shortage message's `purchase_unit` label with the base-unit arithmetic. Evidence: response and before/after snapshot.
- [ ] RV-078 — Submit valid, missing, negative, oversized, duplicate, and foreign raw-material waste entries. Confirm server validation and transaction rollback. Evidence: response matrix and row counts. Covers DI-05.
- [ ] RV-079 — Record a target different from actual physical yield if the UI/API permits it. Confirm whether `actual_quantity` can be captured and whether finished goods use target or actual. Covers VD-08.
- [ ] RV-080 — Submit completion twice sequentially for the same production. Use a database snapshot and minimal disposable quantities. Confirm whether material/finished-good/waste effects duplicate. Covers VD-10/CR-04.
- [ ] RV-081 — Attempt completion from `planned`, `processing`, `completed`, and `cancelled` states. Record the actual state guards. Evidence: response and row diffs.
- [ ] RV-082 — Induce a safe exception midway through completion in a disposable environment (for example a controlled validation/constraint failure). Confirm that material, production, waste, and finished-goods changes roll back together. Evidence: database transaction trace.
- [ ] RV-083 — Determine whether any implemented UI or route writes `actual_quantity` or `cancelled`, and whether legacy `done` values can be rendered safely. Evidence: route/UI trace and seeded disposable status rows.
- [ ] RV-084 — Render production detail and Produksi dashboard rows with known base/purchase units. Compare every displayed unit label with the source's `unit` versus `base_unit` references and capture blank/mismatched labels. Evidence: DOM/screenshot and model attributes.

## Finished goods and expiration

- [ ] RV-090 — Confirm completed production creates one batch per item with production/expiry dates derived from the expected fields and timezone. Evidence: production/menu/finished-good rows.
- [ ] RV-091 — Run the expiry command manually against disposable batches dated yesterday, today, and tomorrow. Confirm the strict date boundary and status/quantity effects. Evidence: before/after rows and command output.
- [ ] RV-092 — Run the scheduler in a review environment long enough to observe the configured cadence. Confirm the command is discovered and invoked every minute, and identify the intended host scheduling requirement. Evidence: scheduler logs. Covers PT-04/PT-05.
- [ ] RV-093 — With the scheduler deliberately inactive, load finished-goods/dashboard screens containing an available but past-dated batch. Record whether queries treat it as available. Evidence: rendered stats/table. Covers DI-08.
- [ ] RV-094 — Exercise finished-goods status filters and compare dashboard/controller statistics with a truth table across available, empty, expired, and expired_damaged plus zero/nonzero quantities. Evidence: row set and displayed counts.

## Distribution, sales quantities, returns, and disposal

- [ ] RV-100 — Allocate valid, non-expired stock to an Armada user. Confirm locked batch decrement, empty status at zero, active session, allocator, and item quantities. Evidence: before/after rows.
- [ ] RV-101 — Attempt allocation exceeding quantity, from expired/non-available batches, with wrong-role users, nonexistent/non-numeric batch keys, same-day-expiry batches, and a second active session. Confirm server-set `session_date`/`started_at`, validation/404 outcomes, and atomicity. Evidence: response/data matrix.
- [ ] RV-102 — Poll the owner live-session endpoint while an Armada user updates sold quantities. Confirm five-second UI refresh, JSON shape, totals, and authorization. Evidence: network captures.
- [ ] RV-103 — As the assigned Armada user, update sold quantities at zero, full sent quantity, invalid over/negative values, and a partial array that omits a previously nonzero item. In a multi-item session, place an over-limit value after a valid changed value and inspect whether the earlier update persists. As another Armada user, attempt the same session. Confirm ownership, validation, omission reset, and atomicity. Evidence: statuses and before/after rows.
- [ ] RV-104 — Finish a session with partial sales. Verify sold and returned values, one pending return check per returned item, final session status/time, and no revenue/sale rows. Evidence: before/after rows.
- [ ] RV-105 — Finish a zero-return session and verify no return checks are created. Evidence: session/item/return rows.
- [ ] RV-106 — Process a pending return as ready before expiry. Confirm quantity is restored to the original batch and status becomes available. Test the expiry boundary. Evidence: rows before/after.
- [ ] RV-107 — Process a pending return as expired/damaged. Confirm the rejected finished-good clone fields and linkage. Evidence: original, clone, return-check rows.
- [ ] RV-108 — Dispose an expired/damaged return. Confirm clone quantity/status mutation and what durable audit evidence remains. Repeat the request and record idempotency. Evidence: before/after and second response.
- [ ] RV-109 — Attempt to reprocess a non-pending return and dispose a pending/ready/unlinked return. Confirm guards and unchanged stock. Evidence: response/data matrix.
- [ ] RV-110 — Request incomplete Armada account resource actions against a disposable account, avoiding deletion of seeded/shared users. Record actual HTTP responses. Covers VD-01.
- [ ] RV-111 — Compare every “sold” display/API value with persisted fields and verify explicitly that price, revenue, customer, payment, and sale transaction data are absent. This is a scope confirmation, not a request to add fields.
- [ ] RV-112 — In a disposable database only, create a finished-good batch with session item and return-check history, then delete the batch through a direct review harness/model operation. Confirm the configured cascade into allocation/sold/return history and contrast the rejected-batch `nullOnDelete` link. Evidence: before/after row counts and foreign-key trace.

## Reports, exports, and AJAX

- [ ] RV-120 — Seed a small, hand-calculable transaction dataset. Verify transaction table rows, KPIs, type/year/month/search filters, pagination, sort order, labels, and decimal formatting. Confirm separately that model `scopeTimeframe()` has no current report UI/controller consumer.
- [ ] RV-121 — Apply search and type filters and compare the table/KPIs with chart and composition totals. Record whether all components share intended filters. Covers MT-07.
- [ ] RV-122 — Select `production_usage` and confirm whether it is empty after actual production completion. Correlate with RV-074.
- [ ] RV-123 — Request transaction report partial/AJAX endpoints with valid, invalid, unauthenticated, and wrong-role requests. Confirm JSON/HTML contracts and error handling.
- [ ] RV-124 — Export the same filtered transaction report through `TransactionReportExport`. Open the resulting workbook in a safe parser/viewer and compare rows, totals, types, dates, and filters to the on-screen result. Evidence: checksum, sheet metadata, comparison. Do not open untrusted macros; the expected format is generated by the library.
- [ ] RV-125 — Verify production-report KPIs, trend, table, insights, and material-usage values against a hand-calculated dataset. Include a completed run whose `plan_date` differs from finished-good production date, plus planned, processing, cancelled/legacy status, and changed recipes. Confirm plan-date bucketing and that current production-report output contains no implemented cost metric; test current-price HPP separately under RV-054/RV-062.
- [ ] RV-126 — Confirm whether `totalProductionCost` appears in any response or rendered view and whether its omission is intended. Evidence: controller data/view output.
- [ ] RV-127 — Confirm production report logging behavior, log volume, and sensitive data exposure when monthly filters are requested. Evidence: isolated log excerpt with secrets redacted.
- [ ] RV-128 — Verify that finished-goods, sales/revenue, return/disposal, and ROP/MRP report endpoints are absent rather than hidden by navigation or roles. Evidence: route list and authorized UI navigation.

## ROP and MRP scope verification

- [ ] RV-130 — For materials below, equal to, and above `minimum_stock`, compare raw-material cards/dashboard warnings and stock-health ratios. Confirm exact threshold boundaries and whether units are base units.
- [ ] RV-131 — Search all role UIs and routes for a replenishment recommendation, reorder quantity, lead time, safety stock, purchase order, supplier, or notification workflow. Record any runtime-discovered capability not visible statically.
- [ ] RV-132 — For MRP-like reservations, compare displayed availability against current BOM demand from all planned/processing productions. Confirm whether any reservation row is persisted or stock is merely calculated.
- [ ] RV-133 — Change a BOM after planning and observe whether reserved requirements change retroactively. Evidence: before/after requirements.
- [ ] RV-134 — Confirm no procurement proposal, lead-time offset, lot-sizing, scheduled receipt, or material-plan release exists behind AJAX/UI paths. Evidence: network/route/UI audit.

## Concurrency and idempotency checks

Run these only with scripted barriers in an isolated database, minimal disposable records, and a restore point. Capture transaction timestamps and SQL logs. Do not approximate concurrency by clicking production-like data.

- [ ] RV-140 — Submit two simultaneous restocks for one material; verify final stock equals the sum and every `before_stock`/`after_stock` ledger snapshot chains correctly. Covers CR-01.
- [ ] RV-141 — Submit simultaneous positive/negative adjustments for one material; verify no lost update or negative result. Covers CR-01.
- [ ] RV-142 — Pause production completion after availability calculation, mutate the same material in a second transaction, then resume. Confirm whether completion revalidates/locks stock. Covers CR-02.
- [ ] RV-143 — Submit two simultaneous completions for one production. Confirm exactly-once or duplicate effects. Covers CR-04.
- [ ] RV-144 — Submit two simultaneous allocations for the same Armada user and distinct/overlapping batches. Confirm the one-active-session invariant and batch quantities. Covers CR-05.
- [ ] RV-145 — Submit two simultaneous finishes for one session. Confirm a single final transition and no duplicate return checks. Covers CR-06.
- [ ] RV-146 — Submit two simultaneous decisions for one pending return. Confirm stock is restored/cloned only once. Covers CR-07.
- [ ] RV-147 — Submit simultaneous disposal requests for one rejected return. Confirm deterministic response and durable state. Covers CR-08.

## Automated tests and final evidence

- [ ] RV-150 — Run the existing test suite unchanged using its configured SQLite environment. Record all passes/failures; specifically capture the `/` status mismatch and any Vite/migration issues. Do not edit tests.
- [ ] RV-151 — Run a route-list command and compare method, URI, name, action, and middleware with `ROUTE-ROLE-MATRIX.md`. Record framework-generated resource actions and any differences caused by the installed framework version.
- [ ] RV-152 — Render every named Blade response reachable by seeded roles and collect server errors, missing routes/views/assets, and browser console errors.
- [ ] RV-153 — Confirm queue, mail, and scheduler processes are either deliberately configured for the review environment or disabled; no external recipient should receive review traffic.
- [ ] RV-154 — After all checks, compare repository status with the baseline. Preserve only review evidence outside the application tree and discard generated data/assets unless separately approved.

## Result recording template

For each item, record:

| Field | Value |
|---|---|
| Check ID | `RV-nnn` |
| Result | Pass / Fail / Blocked / Not run |
| Environment | Commit, PHP, framework, database, browser |
| Preconditions | Disposable records and relevant state |
| Observed result | Exact status, UI, database delta, or error |
| Expected from source | Cited class/method/view/migration |
| Evidence location | Review artifact reference, outside committed application source |
| Follow-up owner | Project owner / developer / operations / product collaborator |

Completing a check may change a finding from `NEEDS_RUNTIME_VERIFICATION` to a runtime-observed result, but it must not retroactively relabel static source evidence as runtime-tested.
