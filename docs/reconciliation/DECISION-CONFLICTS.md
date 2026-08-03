# Decision Conflicts

## Scope

This register contains only meaningful superseded choices, implementation-versus-intent conflicts, unresolved alternatives, undocumented implementation decisions, and runtime-dependent conflicts. It does not repeat the complete topic comparison in `docs/reconciliation/CHAT-VS-REPOSITORY.md`.

“Intentional,” “partial,” or “accidental” below is an evidence-based appearance, not stakeholder approval. Every entry involving business intent remains open until its named decision owner confirms it.

## Confirmed Superseded History

### DC-S01 — Minimum-stock colors described as ROP

- **Initial historical approach:** Red/yellow/green thresholds were initially described as ROP.
- **Later historical approach:** History explicitly separated static minimum-stock alerts from `average usage × lead time + safety stock`; full ROP was phased until data existed.
- **Repository implementation:** Static minimum/ratio/fixed thresholds exist; no full ROP inputs or calculation exists.
- **Conflict type:** `SUPERSEDED_HISTORY`
- **Operational impact:** Calling current alerts ROP would misstate capability and planning assurance.
- **Affected modules:** Raw materials, dashboards, ROP, procurement reporting.
- **Implementation appearance:** Intentional early-phase implementation.
- **Decision owner:** Project owner/business analyst.
- **Required decision:** Confirm canonical terminology and the future ROP definition/phase gate.
- **Safe interim treatment:** Call the current feature “minimum-stock/stock-health alerts,” not full ROP.
- **Evidence:** `docs/history/01-inventory-rop-foundation.extract.md` (“ROP analysis and evolution”); `docs/audit/CURRENT-IMPLEMENTATION.md` (“Reorder point”); `docs/audit/BUSINESS-RULES-INFERRED.md` (BR-ROP-001).

### DC-S02 — Dashboard and sidebar architecture

- **Initial historical approach:** A second dashboard layout, separate role dashboard routes, config-driven menus, permission navigation, and user helpers were considered at different stages.
- **Later historical approach:** One `/dashboard`, one `layouts/app.blade.php` shell, role-specific dashboard views, and role-specific sidebar partials became the preferred direction; config/permissions were deferred.
- **Repository implementation:** Shared layout and exact role dashboard dispatch exist; role sidebars are active, with mobile gaps and an unused Breeze navigation partial.
- **Conflict type:** `SUPERSEDED_HISTORY`
- **Operational impact:** Following an older proposal would create competing shells or navigation sources.
- **Affected modules:** Authentication shell, dashboard, role navigation, responsive UI.
- **Implementation appearance:** Intentional current architecture with partial navigation completeness.
- **Decision owner:** Technical collaborator/maintainer.
- **Required decision:** Confirm the active partial structure and approved mobile menu inventory.
- **Safe interim treatment:** Treat the shared shell and active role partials as current; preserve older structures only as history.
- **Evidence:** `docs/history/03-sidebar-role-and-git-workflow.extract.md` (“Decision evolution”); `docs/history/05-dashboard-architecture-and-raw-material-lifecycle.extract.md` (“Dashboard layout correction,” “Sidebar architecture”); `docs/audit/CURRENT-IMPLEMENTATION.md` (“Dashboard dispatch,” “Blade and JavaScript”).

### DC-S03 — Full no-reload mutation versus controlled reload

- **Initial historical approach:** Update all raw-material derived state in JavaScript after an AJAX mutation.
- **Later historical approach:** Use controlled reload for complex inventory mutations so the server recalculates state; retain AJAX for reports and other focused interactions.
- **Repository implementation:** Inventory feedback uses reload/sessionStorage patterns, while reports use fetch/partial replacement and Armada live data polls.
- **Conflict type:** `SUPERSEDED_HISTORY`
- **Operational impact:** Treating both recommendations as current would produce duplicate state logic and inconsistent toast behavior.
- **Affected modules:** Raw materials, modals, toasts, reports, Armada sessions.
- **Implementation appearance:** Intentional hybrid architecture; individual error paths remain partial.
- **Decision owner:** Technical collaborator/UX owner.
- **Required decision:** None for the historical supersession; failure/retry UX still needs confirmation.
- **Safe interim treatment:** Use the later hybrid strategy as the documented design and label browser behavior unverified.
- **Evidence:** `docs/history/02-toast-raw-material-report-evolution.extract.md` (“Reload versus no-reload evolution”); `docs/audit/CURRENT-IMPLEMENTATION.md` (“Blade and JavaScript implementation”).

### DC-S04 — Raw-material creation with stock and price

- **Initial historical approach:** Create the material master while entering sealed/opened stock and current price.
- **Later historical approach:** Create master data at zero stock; establish inventory through restock or adjustment so movement is explainable.
- **Repository implementation:** Creation initializes stock to zero; restock and adjustment are separate operations.
- **Conflict type:** `SUPERSEDED_HISTORY`
- **Operational impact:** Reviving the old flow would permit unexplained opening balances outside the current transaction contract.
- **Affected modules:** Raw-material master, restock, adjustment, ledger.
- **Implementation appearance:** Intentional.
- **Decision owner:** Project owner/inventory process owner.
- **Required decision:** Confirm whether any future opening-balance process is needed and how it must be audited.
- **Safe interim treatment:** Document zero-stock creation and separate movements only.
- **Evidence:** `docs/history/01-inventory-rop-foundation.extract.md` (“Create versus restock separation”); `docs/history/05-dashboard-architecture-and-raw-material-lifecycle.extract.md` (“Create-state revision”); `docs/audit/CURRENT-IMPLEMENTATION.md` (“Master data”).

### DC-S05 — Unified report filters versus table/chart separation

- **Initial historical approach:** Search/type/time filters should affect all report components.
- **Later historical approach:** Table/export use search, type, year, month; charts use year/month to avoid misleading purchase trends.
- **Repository implementation:** The later split is implemented in controller/report paths.
- **Conflict type:** `SUPERSEDED_HISTORY`
- **Operational impact:** Using the earlier rule would change chart semantics and could produce empty/misleading purchase charts for adjustment types.
- **Affected modules:** Transaction report, AJAX, charts, export.
- **Implementation appearance:** Intentional.
- **Decision owner:** Project owner/report owner.
- **Required decision:** None unless stakeholders want to reopen filter semantics.
- **Safe interim treatment:** Treat the later locked filter split as current design; runtime output remains unverified.
- **Evidence:** `docs/history/02-toast-raw-material-report-evolution.extract.md` (“Filter-semantics evolution”); `docs/history/04-inventory-handover-and-production-report.extract.md` (“Transaction Report decisions”); `docs/audit/BUSINESS-RULES-INFERRED.md` (BR-REPORT-001).

## Implementation Versus Historical-Intent Conflicts

### DC-C01 — Username-only versus username/email login

- **Initial historical approach:** Breeze's email-oriented assumptions were present during setup.
- **Later historical approach:** Username became the preferred operational login identifier; email was optional or removable from that contract.
- **Repository implementation:** `LoginRequest` accepts username or email and infers the lookup field from credential syntax, while the UI labels one username input.
- **Conflict type:** `IMPLEMENTATION_CONFLICT`
- **Operational impact:** Users, help text, throttling keys, collision behavior, recovery, and support procedures may follow different identifier rules.
- **Affected modules:** Login, user accounts, password recovery, tests.
- **Implementation appearance:** Intentional dual-identifier code, but approval is unknown.
- **Decision owner:** Project owner/security owner.
- **Required decision:** Choose username-only, email-only, or dual login and define cross-field collision behavior.
- **Safe interim treatment:** Describe the exact source behavior without calling it the official policy.
- **Evidence:** `docs/history/01-inventory-rop-foundation.extract.md` (“Username login decision”); `docs/audit/BUSINESS-RULES-INFERRED.md` (BR-AUTH-001); `app/Http/Requests/Auth/LoginRequest.php`.

### DC-C02 — Explainable stock movement versus incomplete ledger

- **Initial historical approach:** Raw Materials → Transactions → Stock Calculation, with restock and adjustment snapshots.
- **Later historical approach:** “Inventory movement should be explainable through transactions”; production-consumption ledger was favored for auditability but deferred after Production Report V1.
- **Repository implementation:** Aggregate stock columns drive operations. Restock/adjustment rows exist, while package opening, production consumption, and production waste do not enter `raw_material_transactions`.
- **Conflict type:** `IMPLEMENTATION_CONFLICT`
- **Operational impact:** Current stock cannot be reconstructed from the ledger, and transaction reports omit major stock decreases.
- **Affected modules:** Raw-material stock, production, waste, transaction report, ROP/MRP inputs.
- **Implementation appearance:** Partial implementation caused by an acknowledged deferral.
- **Decision owner:** Project owner/inventory process owner with technical collaborator.
- **Required decision:** Define aggregate-versus-ledger authority and required movement events/snapshots.
- **Safe interim treatment:** Treat aggregate stock as operational state and the ledger as incomplete audit evidence.
- **Evidence:** `docs/history/04-inventory-handover-and-production-report.extract.md` (“production_usage gap,” “consumption options”); `docs/audit/DATA-MODEL-AUDIT.md` (“Sources of truth”); `docs/audit/KNOWN-RISKS.md` (VD-09/DI-01).

### DC-C03 — Production result capture versus full-target output

- **Initial historical approach:** Produksi should perform production and input production results.
- **Later historical approach:** Production Report design explicitly questioned target versus actual output; no final historical equation was recorded.
- **Repository implementation:** `actual_quantity` remains zero; completion creates finished goods and reports portions using `target_quantity`.
- **Conflict type:** `IMPLEMENTATION_CONFLICT`
- **Operational impact:** Finished stock, output KPIs, material efficiency, waste rates, and later sales availability may describe plan rather than physical output.
- **Affected modules:** Production, finished goods, production report, inventory reconciliation.
- **Implementation appearance:** Partial/scaffolded actual-yield design with intentional target fallback unknown.
- **Decision owner:** Project owner and Produksi process owner.
- **Required decision:** Define actual yield, recorder, timing, allowed variance, and finished-goods equation.
- **Safe interim treatment:** Label all current output quantities as target-based; do not call them measured production.
- **Evidence:** `docs/history/01-inventory-rop-foundation.extract.md` (“Produksi responsibilities”); `docs/history/04-inventory-handover-and-production-report.extract.md` (“data meaning concerns”); `docs/audit/KNOWN-RISKS.md` (VD-08).

### DC-C04 — One-time production completion versus unguarded completion

- **Initial historical approach:** Planning and Produksi execution imply a lifecycle from plan to processing to completion.
- **Later historical approach:** Repeated completion and transition prerequisites were raised but not resolved.
- **Repository implementation:** Start checks planned status, but complete has no current-status/idempotency guard or production-row lock and re-enters stock deduction, waste, and batch creation.
- **Conflict type:** `IMPLEMENTATION_CONFLICT`
- **Operational impact:** Repeated or concurrent requests may create duplicate irreversible stock effects.
- **Affected modules:** Production, raw materials, waste, finished goods, reports.
- **Implementation appearance:** Likely accidental safety omission around otherwise intentional completion logic.
- **Decision owner:** Technical collaborator plus project/Produksi owner.
- **Required decision:** Confirm the legal transition graph and duplicate-request outcome.
- **Safe interim treatment:** Treat completion as unsafe to repeat; do not state it is idempotent or status-enforced.
- **Evidence:** `docs/history/04-inventory-handover-and-production-report.extract.md` (“unresolved/verification items”); `docs/audit/BUSINESS-RULES-INFERRED.md` (BR-PROD-006); `docs/audit/KNOWN-RISKS.md` (VD-10/CR-04).

### DC-C05 — Commercial sales/revenue scope versus quantity-only capture

- **Initial historical approach:** The system should report sales per menu, income, Armada expenses where required, and commission.
- **Later historical approach:** Sales analytics and broader business dashboards were deferred until production/consumption foundations stabilized, but not rejected.
- **Repository implementation:** Only mutable cumulative sold quantities exist; there are no sale events, price snapshots, customer, payment, revenue, commission, or sales report structures.
- **Conflict type:** `INTENDED_NOT_IMPLEMENTED`
- **Operational impact:** Current data cannot support auditable revenue, cash, customer, or commission statements.
- **Affected modules:** Armada, sales, finance, reports, commissions.
- **Implementation appearance:** Intentional phased omission/partial scope.
- **Decision owner:** Project owner/business owner.
- **Required decision:** Confirm which commercial records and reports belong in project scope and their recognition rules.
- **Safe interim treatment:** Call current data “sold quantity per session item,” not sales transactions or revenue.
- **Evidence:** `docs/history/01-inventory-rop-foundation.extract.md` (“Information the owner needs,” “Automated responsibilities”); `docs/history/04-inventory-handover-and-production-report.extract.md` (“Roadmap”); `docs/audit/KNOWN-RISKS.md` (VD-11).

### DC-C06 — Test-before-review intent versus repository test evidence

- **Initial historical approach:** The team workflow called for testing the project and browser console before review.
- **Later historical approach:** No later test strategy or executed results were recorded.
- **Repository implementation:** Only Breeze-style auth/profile and example tests exist; operational modules have no automated coverage. The root feature test expects 200 while `/` redirects.
- **Conflict type:** `IMPLEMENTATION_CONFLICT`
- **Operational impact:** Completion/runtime claims cannot be supported, and the visible test contract contains a direct mismatch.
- **Affected modules:** All modules, CI/deployment, collaboration workflow.
- **Implementation appearance:** Partial test scaffolding; root mismatch appears accidental.
- **Decision owner:** Technical maintainer/collaborator.
- **Required decision:** Confirm supported test database/environment and acceptance coverage required before release.
- **Safe interim treatment:** Report test presence and unknown execution status only.
- **Evidence:** `docs/history/03-sidebar-role-and-git-workflow.extract.md` (“Commit and safety rules”); `docs/audit/CURRENT-IMPLEMENTATION.md` (“Tests currently present”); `docs/audit/KNOWN-RISKS.md` (VD-04/MT-03).

## Multiple Historical Decisions Without a Clear Final Decision

### DC-M01 — Current recipe calculation versus recipe snapshots/history

- **Initial historical approach:** Calculate production use dynamically from Production, Production Items, current BOM, and Waste so reporting can proceed without schema work.
- **Later historical approach:** A consumption ledger and historical snapshots were recognized as stronger for auditability, but no effective-date/version design was accepted.
- **Repository implementation:** Plans, completion, HPP display, and production reporting load current recipe/master values; no snapshots or recipe versions exist.
- **Conflict type:** `BUSINESS_DECISION_REQUIRED`
- **Operational impact:** Recipe edits can alter later completion needs and historical report interpretation.
- **Affected modules:** Menu/BOM, planning, production, HPP, production report, MRP.
- **Implementation appearance:** Intentional immediate expedient with incomplete historical design.
- **Decision owner:** Project owner/Produksi owner with technical collaborator.
- **Required decision:** Decide whether and when recipe, unit, conversion, and cost inputs become immutable snapshots.
- **Safe interim treatment:** State that all relevant calculations use the current recipe; do not promise historical stability.
- **Evidence:** `docs/history/04-inventory-handover-and-production-report.extract.md` (“Two material-consumption options,” “data meaning concerns”); `docs/audit/BUSINESS-RULES-INFERRED.md` (BR-BOM-004); `docs/audit/DATA-MODEL-AUDIT.md` (“Missing historical sources”).

### DC-M02 — HPP and cost basis

- **Initial historical approach:** Derive HPP from normalized latest purchase price and recipe quantities; a Production Report cost KPI was proposed.
- **Later historical approach:** Do not display `Rp 0` as cost; mark cost unavailable until a defensible valuation method exists. Historical correction versus latest price remained unresolved.
- **Repository implementation:** Menu/detail HPP uses current latest price/current recipe; production cost is not calculated in Production Report.
- **Conflict type:** `BUSINESS_DECISION_REQUIRED`
- **Operational impact:** Current displays can be mistaken for historical or accounting cost and may change after price edits.
- **Affected modules:** Restock, menu, BOM, production detail, reporting, profit/margin.
- **Implementation appearance:** Partial current-price display, not a complete cost system.
- **Decision owner:** Business owner/accounting owner.
- **Required decision:** Choose latest, weighted average, FIFO/batch, standard, or another costing basis and its effective date/rounding.
- **Safe interim treatment:** Label the calculation “current-price estimated HPP”; report cost as unavailable where not computed.
- **Evidence:** `docs/history/04-inventory-handover-and-production-report.extract.md` (“KPI corrections”); `docs/history/05-dashboard-architecture-and-raw-material-lifecycle.extract.md` (“Price and cost handling”); `docs/audit/DATA-MODEL-AUDIT.md` (“Calculated values”).

### DC-M03 — Advisory reservations versus persisted/enforced reservations

- **Initial historical approach:** Simplified MRP should expand planned output, compare on-hand stock, and calculate shortage/net need.
- **Later historical approach:** History did not decide whether reservations were display-only, persisted, enforced, or time-phased.
- **Repository implementation:** Current-BOM requirements for planned/processing work are computed in memory; plan writes do not enforce availability and no reservation entity exists.
- **Conflict type:** `BUSINESS_DECISION_REQUIRED`
- **Operational impact:** Multiple plans can claim the same physical stock, and displayed availability is not a guarantee.
- **Affected modules:** Planning, raw-material stock, MRP, production completion.
- **Implementation appearance:** Intentional partial visualization.
- **Decision owner:** Project owner and production/inventory planner.
- **Required decision:** Define whether reservation is advisory or binding, when it begins/ends, and whether it must persist.
- **Safe interim treatment:** Call the values calculated planning indicators only.
- **Evidence:** `docs/history/01-inventory-rop-foundation.extract.md` (“Simplified MRP”); `docs/audit/BUSINESS-RULES-INFERRED.md` (BR-MRP-001/002); `docs/audit/HUMAN-QUESTIONS.md` (HQ-055/HQ-096/HQ-097).

### DC-M04 — Raw-material waste semantics

- **Initial historical approach:** Adjustment Reduce could mean damage, expiry, loss, correction, unrecorded use, or waste; it should not silently define them all.
- **Later historical approach:** Dedicated production waste and unit-aware analytics appeared, but reason/responsibility and whether waste is additional to recipe use remained unresolved.
- **Repository implementation:** Production waste is separately stored and deducted in addition to recipe consumption; generic adjustments still lack a reason taxonomy; neither writes a complete shared movement audit.
- **Conflict type:** `BUSINESS_DECISION_REQUIRED`
- **Operational impact:** Waste rates, accountability, stock reconciliation, and reports may mix physical loss with correction.
- **Affected modules:** Adjustments, production, waste, transaction ledger, reports.
- **Implementation appearance:** Partial distinction with missing policy/context.
- **Decision owner:** Produksi process owner and business owner.
- **Required decision:** Define waste categories, units, responsibility, approval, and whether recipe quantities include expected loss.
- **Safe interim treatment:** Keep adjustment and production-waste data semantically separate and avoid aggregate mixed-unit totals.
- **Evidence:** `docs/history/02-toast-raw-material-report-evolution.extract.md` (“Adjustment Reduce ambiguity”); `docs/history/04-inventory-handover-and-production-report.extract.md` (“KPI corrections”); `docs/audit/HUMAN-QUESTIONS.md` (HQ-058/HQ-059).

### DC-M05 — MRP visibility versus procurement plan

- **Initial historical approach:** A strict industrial MRP framing and purchase recommendations were requested.
- **Later historical approach:** A simplified, phased MRP was accepted; forecasting, time-phased procurement, EOQ/lot sizing, and recommendations were deferred.
- **Repository implementation:** Only current-BOM plan requirement/reservation visibility exists.
- **Conflict type:** `HISTORICAL_PLAN_ONLY`
- **Operational impact:** Stakeholders may expect weekly/monthly purchasing output that current data and source cannot produce.
- **Affected modules:** MRP, ROP, procurement, forecasting, production planning.
- **Implementation appearance:** Intentional foundation, not a complete planning engine.
- **Decision owner:** Project owner/business analyst.
- **Required decision:** Define the approved endpoint of “MRP” for this project and the data-collection exit criteria.
- **Safe interim treatment:** Use “MRP-like current-plan material requirement calculation.”
- **Evidence:** `docs/history/01-inventory-rop-foundation.extract.md` (“MRP analysis and evolution”); `docs/history/04-inventory-handover-and-production-report.extract.md` (“Roadmap”); `docs/audit/CURRENT-IMPLEMENTATION.md` (“Material requirements planning”).

## Implementation Decisions Absent from History

### DC-U01 — Public registration creates default Armada users

- **Initial historical approach:** Seeded internal owner/Produksi/Armada accounts and username-oriented login were discussed.
- **Later historical approach:** No public-registration policy was recorded.
- **Repository implementation:** Public guest registration routes create and authenticate a user without assigning a role; database default makes the role `armada`. The custom login does not link registration.
- **Conflict type:** `BUSINESS_DECISION_REQUIRED`
- **Operational impact:** Unapproved users may obtain an operational role; account ownership/support and username generation become public concerns.
- **Affected modules:** Authentication, authorization, Armada accounts, security.
- **Implementation appearance:** Likely retained Breeze functionality combined with an intentional role default; final policy unknown.
- **Decision owner:** Project owner/security owner.
- **Required decision:** Enable, restrict, invite-gate, or remove public registration and define its role assignment.
- **Safe interim treatment:** Record it as a directly routable current behavior, not an approved feature.
- **Evidence:** No approval in `docs/history/`; `docs/audit/CURRENT-IMPLEMENTATION.md` (“Registration”); `docs/audit/KNOWN-RISKS.md` (AU-01/DI-11); `routes/auth.php`.

### DC-U02 — Automatic sealed-package opening

- **Initial historical approach:** Sealed packages and opened base-unit stock were defined; production consumption was expected.
- **Later historical approach:** No precise package-opening or remainder rule was recorded.
- **Repository implementation:** Completion opens the ceiling of base-unit shortage divided by conversion, moves the full package contents to opened stock, and deducts usage; no opening event is persisted.
- **Conflict type:** `IMPLEMENTED_BUT_UNDOCUMENTED`
- **Operational impact:** Remainders, package counts, traceability, and physical handling follow an implicit algorithm.
- **Affected modules:** Production, raw-material stock, unit conversion, ledger.
- **Implementation appearance:** Intentional algorithm with undocumented business approval.
- **Decision owner:** Inventory/Produksi process owner.
- **Required decision:** Confirm the algorithm, remainder custody, and whether opening requires its own movement record.
- **Safe interim treatment:** Describe the exact calculation only and do not generalize it beyond production completion.
- **Evidence:** `docs/history/01-inventory-rop-foundation.extract.md` (“Sealed/opened semantics”); `docs/audit/BUSINESS-RULES-INFERRED.md` (BR-PROD-003); `ProductionController::complete()` in `app/Http/Controllers/ProductionController.php`.

### DC-U03 — Shelf-life snapshot and automatic expiry cadence

- **Initial historical approach:** Suitable leftovers could be chilled and reused.
- **Later historical approach:** No expiry-day formula, inclusive boundary, scheduler cadence, or operator was recorded.
- **Repository implementation:** Finished batches persist completion date plus current menu shelf-life; available batches expire only after the date has passed. The command is scheduled every minute while its comment says daily.
- **Conflict type:** `BUSINESS_DECISION_REQUIRED`
- **Operational impact:** Product eligibility and safety depend on a source rule and external scheduler that have no preserved approval or runtime proof.
- **Affected modules:** Finished goods, allocation, returns, dashboard/reporting, scheduler.
- **Implementation appearance:** Intentional expiry feature with cadence/comment inconsistency; operational setup unknown.
- **Decision owner:** Business/food-safety owner and deployment operator.
- **Required decision:** Confirm shelf-life source, inclusive boundary, timezone, cadence, and scheduler ownership.
- **Safe interim treatment:** State the source condition exactly and mark scheduler operation unverified.
- **Evidence:** `docs/history/01-inventory-rop-foundation.extract.md` (“Operating model”); `docs/audit/BUSINESS-RULES-INFERRED.md` (BR-FG-001/BR-DIST-003); `docs/audit/KNOWN-RISKS.md` (PT-04).

### DC-U04 — Allocation-time stock deduction and one active session

- **Initial historical approach:** Owner distributes goods and Armada records sold/returned quantities.
- **Later historical approach:** No custody/consignment/accounting event or one-session invariant was recorded.
- **Repository implementation:** Allocation immediately deducts ready finished goods; later sold entry performs no deduction. An application check permits one active session per Armada without a database invariant.
- **Conflict type:** `BUSINESS_DECISION_REQUIRED`
- **Operational impact:** Inventory ownership, availability, loss responsibility, sale timing, and concurrent-session handling depend on this interpretation.
- **Affected modules:** Finished goods, distribution, Armada, sales, returns.
- **Implementation appearance:** Intentional workflow, undocumented business meaning; concurrency protection partial.
- **Decision owner:** Business owner/Armada operations owner.
- **Required decision:** Define transfer/custody/sale semantics and whether one active session is strict.
- **Safe interim treatment:** Call it “allocation removes quantity from ready stock into an active Armada session.”
- **Evidence:** `docs/history/01-inventory-rop-foundation.extract.md` (“Owner/Armada responsibilities”); `docs/audit/BUSINESS-RULES-INFERRED.md` (BR-DIST-002/BR-SALE-002); `docs/audit/HUMAN-QUESTIONS.md` (HQ-071/HQ-072).

### DC-U05 — Return re-entry and rejected-batch representation

- **Initial historical approach:** Unsold suitable goods may be reused; unsuitable goods should be handled as waste.
- **Later historical approach:** Inspection/ready/rejected/disposal was discovered from collaborator files, without preserved approval of storage mechanics.
- **Repository implementation:** Ready returns restore the original batch; `expired_damaged` creates a separate rejected finished-good row linked to the check.
- **Conflict type:** `BUSINESS_DECISION_REQUIRED`
- **Operational impact:** Batch traceability, shelf life, reason taxonomy, quantities, and audit reporting depend on whether returns merge or create distinct batches.
- **Affected modules:** Armada sessions, return checks, finished goods, expiry, disposal.
- **Implementation appearance:** Intentional collaborator implementation, but stakeholder approval unknown.
- **Decision owner:** Produksi/business quality owner.
- **Required decision:** Define inspection criteria, ready-batch identity, rejected taxonomy, and evidence retention.
- **Safe interim treatment:** Describe original-batch restoration and rejected clone as implementation facts only.
- **Evidence:** `docs/history/04-inventory-handover-and-production-report.extract.md` (“Discovery of collaborator implementation”); `docs/audit/BUSINESS-RULES-INFERRED.md` (BR-RETURN-002/003); `docs/audit/HUMAN-QUESTIONS.md` (HQ-077–HQ-080).

### DC-U06 — Disposal as destructive state mutation

- **Initial historical approach:** Disposal of unsuitable returned goods was within broad future waste handling.
- **Later historical approach:** No immutable disposal-event content, approval, or responsibility rule was recorded.
- **Repository implementation:** Disposal zeroes the rejected batch and marks it empty; no separate event stores actor, time, reason, method, approval, or disposed quantity.
- **Conflict type:** `BUSINESS_DECISION_REQUIRED`
- **Operational impact:** Evidence of what was disposed is reduced to indirect prior state and timestamps; accountability and repeat handling are unclear.
- **Affected modules:** Returns, finished goods, waste/disposal, reports/audit.
- **Implementation appearance:** Partial implementation; omission may be scope-driven rather than deliberate retention policy.
- **Decision owner:** Business/Produksi quality owner.
- **Required decision:** Define mandatory disposal record and approval/audit requirements.
- **Safe interim treatment:** Do not call current state change a complete disposal ledger.
- **Evidence:** `docs/history/04-inventory-handover-and-production-report.extract.md` (“collaborator lifecycle”); `docs/audit/KNOWN-RISKS.md` (VD-12/DI-09); `ReturnCheckController::dispose()` in `app/Http/Controllers/Produksi/ReturnCheckController.php`.

## Runtime-Dependent Conflicts

### DC-R01 — Concurrent stock mutations and ledger snapshots

- **Initial historical approach:** Stock mutation and its transaction record should commit atomically.
- **Later historical approach:** History did not specify row-locking, transaction isolation, or concurrent-user behavior.
- **Repository implementation:** Database transactions exist in key flows, but restock/adjustment do not lock/reload material rows; production checks stock before its transaction and does not visibly lock raw materials.
- **Conflict type:** `RUNTIME_VERIFICATION_REQUIRED`
- **Operational impact:** Lost updates, stale checks, negative/incorrect stock, or non-chaining snapshots are plausible.
- **Affected modules:** Restock, adjustments, production completion, raw-material ledger.
- **Implementation appearance:** Intentional transaction boundaries with partial concurrency protection.
- **Decision owner:** Technical maintainer/database owner.
- **Required decision:** Confirm required consistency/isolation guarantees after safe concurrency reproduction.
- **Safe interim treatment:** Record this as a plausible risk, not a reproduced defect.
- **Evidence:** `docs/history/05-dashboard-architecture-and-raw-material-lifecycle.extract.md` (“DB transaction”); `docs/audit/KNOWN-RISKS.md` (CR-01–CR-03); `docs/audit/RUNTIME-VERIFICATION-CHECKLIST.md` (“Concurrency and idempotency checks”).

### DC-R02 — Duplicate production, return, and disposal processing

- **Initial historical approach:** Lifecycle actions were discussed as singular operational transitions.
- **Later historical approach:** Duplicate/idempotent behavior was not resolved.
- **Repository implementation:** Completion lacks a state guard; session finish and return inspection have pre-lock checks; disposal does not serialize the return check. Static tracing indicates plausible repeated effects.
- **Conflict type:** `RUNTIME_VERIFICATION_REQUIRED`
- **Operational impact:** Duplicate raw-material deduction, finished batches, return checks, stock re-entry, rejected goods, or disposal effects may occur.
- **Affected modules:** Production, finished goods, Armada sessions, returns, disposal.
- **Implementation appearance:** Likely accidental/partial idempotency protection.
- **Decision owner:** Technical collaborator plus process owners.
- **Required decision:** Define idempotent response versus rejection, then validate current behavior safely.
- **Safe interim treatment:** Treat these actions as potentially non-repeatable and unverified; do not claim reproduced duplication.
- **Evidence:** `docs/audit/KNOWN-RISKS.md` (CR-04/CR-06–CR-08); `docs/audit/RUNTIME-VERIFICATION-CHECKLIST.md` (RV-142–RV-147); cross-reference DC-C04.

### DC-R03 — Database engine and migration history

- **Initial historical approach:** MySQL was selected; no supported-version or upgrade matrix was recorded.
- **Later historical approach:** No later deployment decision resolved test-engine portability or legacy migration histories.
- **Repository implementation:** Runtime engine/applied schema are unknown; tests configure SQLite while queries contain MySQL-specific functions. Duplicate/asymmetric migrations and rollback hazards are source-verified.
- **Conflict type:** `RUNTIME_VERIFICATION_REQUIRED`
- **Operational impact:** Fresh install, upgraded databases, rollback, tests, and reports may not share the same schema/query behavior.
- **Affected modules:** Database bootstrap, reports, planning, tests, deployment.
- **Implementation appearance:** Mixed portability assumptions and migration-history remnants; intentionality varies.
- **Decision owner:** Deployment/database owner and technical collaborator.
- **Required decision:** Confirm production/test engines, applied migration sequence, and supported fresh/upgrade path.
- **Safe interim treatment:** Do not assert a clean migration or SQLite-compatible system.
- **Evidence:** `docs/history/01-inventory-rop-foundation.extract.md` (“Technology stack”); `docs/audit/KNOWN-RISKS.md` (VD-05–VD-07/PT-01/DI-13/DI-14); `docs/audit/RUNTIME-VERIFICATION-CHECKLIST.md` (RV-007–RV-016).

### DC-R04 — Expiry scheduler operation

- **Initial historical approach:** Reusable leftovers required suitability handling; scheduler behavior was not defined.
- **Later historical approach:** No operational scheduler owner/cadence was recorded.
- **Repository implementation:** An expiry command is scheduled every minute, despite a daily comment; no scheduler execution or live expired-data transition was inspected.
- **Conflict type:** `RUNTIME_VERIFICATION_REQUIRED`
- **Operational impact:** Past-dated goods may remain available if the scheduler is absent, and cadence may not match operating policy.
- **Affected modules:** Finished goods, allocation, returns, dashboards, deployment.
- **Implementation appearance:** Intentional automatic expiry with ambiguous cadence/configuration.
- **Decision owner:** Business quality owner and deployment operator.
- **Required decision:** Confirm cadence, timezone, ownership, monitoring, and failure handling; then observe a safe run.
- **Safe interim treatment:** State source scheduling only; do not claim automatic expiry operates in deployment.
- **Evidence:** `docs/audit/CURRENT-IMPLEMENTATION.md` (“Finished goods and expiry”); `docs/audit/KNOWN-RISKS.md` (DI-08/PT-04/PT-05); `docs/audit/RUNTIME-VERIFICATION-CHECKLIST.md` (RV-090–RV-094).

### DC-R05 — Browser assets, AJAX, toasts, polling, and XLSX

- **Initial historical approach:** Responsive Blade/Alpine interactions, AJAX reports, stable charts, toasts, and export were repeatedly reported complete.
- **Later historical approach:** History itself warns that those labels are checkpoint claims requiring repository/runtime verification.
- **Repository implementation:** Source wiring exists, but the audit did not build Vite, open a browser, poll, invoke report AJAX, or generate an XLSX. It also found direct script/template gaps and deployment portability risks.
- **Conflict type:** `RUNTIME_VERIFICATION_REQUIRED`
- **Operational impact:** User-visible rendering, errors, charts, pagination, polling, exports, and case-sensitive view loading may fail despite source presence.
- **Affected modules:** Shared UI, reports, Armada live sessions, exports, deployment.
- **Implementation appearance:** Substantially intentional source wiring with partial error/deployment paths.
- **Decision owner:** Technical collaborator/QA/deployment owner.
- **Required decision:** Confirm supported browser/device/platform and evidence needed to call these features supported.
- **Safe interim treatment:** Use “source-wired; not runtime-verified.”
- **Evidence:** `docs/history/02-toast-raw-material-report-evolution.extract.md` (“reported status,” “verification warnings”); `docs/audit/CURRENT-IMPLEMENTATION.md` (“Runtime status boundary”); `docs/audit/KNOWN-RISKS.md` (VD-13/PT-02/PT-03/MT-09).

## Decision Handling Rule

No entry in this register authorizes a code change. After a decision is made, capture the owner, decision date, evidence, exceptions, and related verification result in a future canonical decision log. Until then, the safe interim treatment remains the only statement this reconciliation supports.
