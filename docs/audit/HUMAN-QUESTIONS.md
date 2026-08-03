# Human Questions

## Purpose

These questions cannot be resolved confidently from source alone. Each one records the current code evidence so a project owner or collaborator can confirm intended behavior without confusing intent with implementation.

Evidence labels are used as follows: directly observed repository facts are `SOURCE_VERIFIED`; tentative meaning is `INFERRED_FROM_CODE`; unresolved intent is `UNKNOWN`; and behavior requiring execution is `NEEDS_RUNTIME_VERIFICATION`.

## Product ownership and operating model

| ID | Question for a human | Why confirmation is needed | Current evidence |
|---|---|---|---|
| HQ-001 | Who is the authoritative product owner for inventory, production, distribution, and financial rules, and who can approve each rule? | Several modules encode operational assumptions without specifications or domain tests. | Controllers and Blade views are the principal rule sources (`SOURCE_VERIFIED`); approval ownership is `UNKNOWN`. |
| HQ-002 | Is this application intended for one business/site and one shared inventory, or must it support multiple warehouses, branches, kitchens, or legal entities? | All audited stock appears global; there are no location/tenant keys. | Domain migrations contain no warehouse/tenant model (`SOURCE_VERIFIED`); intended scope is `UNKNOWN`. |
| HQ-003 | Which database engine and versions are supported in development, CI, and deployment? | The code contains MySQL-specific report SQL while tests declare SQLite in memory. | Query/test configuration mismatch is `SOURCE_VERIFIED`; supported matrix is `UNKNOWN`. |
| HQ-004 | Which timezone and business-day cutoff govern production, expiry, dispatch, returns, and reports? | Date comparisons use application helpers such as `today()` and database date functions. | Date handling is `SOURCE_VERIFIED`; business cutoff is `UNKNOWN`. |
| HQ-005 | Are Indonesian and English mixed labels intentional, and what character encoding is required for imported/deployed data? | Several views mix languages and legitimately use non-ASCII symbols/emoji. | Source strings are `SOURCE_VERIFIED`; intended language/encoding policy and deployed rendering are `UNKNOWN`. |

## Registration, authentication, and accounts

| ID | Question for a human | Why confirmation is needed | Current evidence |
|---|---|---|---|
| HQ-010 | Should public self-registration be enabled at all? | Direct guest registration creates an authenticated account even though the login page does not link to registration. | Public register routes and controller are `SOURCE_VERIFIED`; intended exposure is `UNKNOWN`. |
| HQ-011 | If public registration remains available, which role should a new user receive and who approves/activates it? | The controller omits `role`, causing the database default `armada` to apply. | Default-role behavior is `SOURCE_VERIFIED`; security intent is `UNKNOWN`. |
| HQ-012 | Is username, email, or either one the official login identifier? | `LoginRequest` dynamically accepts username/email, while the custom login UI presents a username-oriented field. | Dual lookup is `SOURCE_VERIFIED`; user-facing contract is `UNKNOWN`. |
| HQ-013 | Must usernames and emails be unique across the combined identifier namespace—for example, can one user's username equal another user's email? | A shared login input can make cross-field collisions ambiguous even if each column is individually unique. | Dynamic lookup is `SOURCE_VERIFIED`; collision policy is `UNKNOWN`. |
| HQ-014 | Are operational users expected to have email addresses? | Seeded owner/produksi/armada accounts have null email, while password reset and verification are email-based. | Seeder/auth mismatch is `SOURCE_VERIFIED`; account policy is `UNKNOWN`. |
| HQ-015 | Is email verification required for any role or route? If so, when should verification mail be sent? | Verification endpoints exist, but the model/routes do not visibly enforce a verified-user gate. | Endpoints are `SOURCE_VERIFIED`; enforcement intent is `UNKNOWN`. |
| HQ-016 | Should owners be able to create, edit, deactivate, reset, or delete produksi and armada users, and which fields are editable? | Owner-side Armada create/index exist, while show/edit/update/delete actions are empty and no Produksi account administration module was found. | Armada administration is `PARTIALLY_IMPLEMENTED`; intended lifecycle is `UNKNOWN`. |
| HQ-017 | Should users be allowed to delete their own account after it owns inventory/production/distribution history? | Generic profile deletion exists and domain tables reference users. | Endpoint/FKs are `SOURCE_VERIFIED`; historical retention rule is `UNKNOWN`. |
| HQ-018 | Are the seeded accounts and shared password strictly development fixtures, or are they part of an expected deployment bootstrap? | `UserSeeder` creates predictable credentials. | Seeder content is `SOURCE_VERIFIED`; operational use is `UNKNOWN`. |

## Roles, authorization, and dashboards

| ID | Question for a human | Why confirmation is needed | Current evidence |
|---|---|---|---|
| HQ-020 | Are `owner`, `produksi`, and `armada` the complete role vocabulary? | Dashboard dispatch handles exactly these roles and rejects other values. | Exact role match is `SOURCE_VERIFIED`; future/custom roles are `UNKNOWN`. |
| HQ-021 | Is role-level route middleware sufficient, or are there record-level permissions beyond the explicit Armada session ownership check? | No policies were found; most owner/produksi actions rely on route groups. | Authorization structure is `SOURCE_VERIFIED`; intended per-record rules are `UNKNOWN`. |
| HQ-022 | Which modules must appear in mobile navigation for each role? | The owner mobile navigation omits modules visible elsewhere, including Menu, Armada Sessions, and Production Report. | Navigation difference is `SOURCE_VERIFIED`; design intent is `UNKNOWN`. |
| HQ-023 | Are the counts, percentages, search, and tag controls on the Owner Armada account-administration page operational metrics, demonstrations, or placeholders? | That account page contains hard-coded values and disconnected controls; this is distinct from the query-backed role dashboards. | Hard-coded/inert account UI is `SOURCE_VERIFIED`; approved behavior is `UNKNOWN`. |

## Raw materials, units, restock, and adjustment

| ID | Question for a human | Why confirmation is needed | Current evidence |
|---|---|---|---|
| HQ-030 | What exactly do `sealed_stock` and `opened_stock` represent for every material: package count versus base-unit quantity? | Calculations treat sealed stock as packages and opened stock as base units. | Formula is `SOURCE_VERIFIED`; business interpretation is `INFERRED_FROM_CODE`. |
| HQ-031 | May `conversion_value` be fractional, and should it ever change after transactions, plans, or production exist? | It is used to interpret sealed packages and calculate costs/consumption. | Numeric fractional values are accepted and editing is controller-locked after a transaction exists (`SOURCE_VERIFIED`); the intended lifecycle policy is `UNKNOWN`. |
| HQ-032 | When production needs opened stock, is opening `ceil(shortage / conversion)` sealed packages the approved rule? What happens to the unused remainder? | Completion converts whole sealed packages and retains remainder as opened stock. | Algorithm is `SOURCE_VERIFIED`; operational approval is `UNKNOWN`. |
| HQ-033 | Can restock receive partial packages, or must purchase quantity always be whole packages? | Current validation/storage is package-oriented and sealed stock is integer-like. | Current shape is `SOURCE_VERIFIED`; partial-package rule is `UNKNOWN`. |
| HQ-034 | Are adjustments allowed only against opened stock, or must users also correct sealed package counts and conversion mistakes? | Adjustment code changes opened stock only. | Current implementation is `SOURCE_VERIFIED`; intended scope is `UNKNOWN`. |
| HQ-035 | Which value is authoritative when stock and its transaction snapshots disagree: aggregate stock columns or the ledger? | Both current stock and `before_stock`/`after_stock` transaction snapshots are stored, but there is no reconciliation contract. | Duplicate representations are `SOURCE_VERIFIED`; source of truth is `UNKNOWN`. |
| HQ-036 | May a historical restock price be edited, and should that update the material's current/latest price? | The edit action changes historical unit price and current latest price. | Behavior is `SOURCE_VERIFIED`; accounting intent is `UNKNOWN`. |
| HQ-037 | What price basis defines inventory cost and HPP: latest purchase price, weighted average, FIFO, batch price, or another method? | Menu listing and production-detail HPP calculations use current latest price divided by conversion; the production report does not currently calculate cost. | Current display calculation is `SOURCE_VERIFIED`; required costing/reporting method is `UNKNOWN`. |
| HQ-038 | What is the approved unit/decimal precision and rounding policy for quantities, conversions, prices, HPP, and report totals? | Database decimals and UI formatting exist, but no single documented rounding policy was found. | Formatting/calculations are `SOURCE_VERIFIED`; business rounding is `UNKNOWN`. |
| HQ-039 | Does inactive mean unavailable for new recipes only, unavailable for production too, or historically hidden everywhere? | `is_active` is used selectively across queries. | Field/filtering is `SOURCE_VERIFIED`; lifecycle semantics are `UNKNOWN`. |

## Recipes and BOM history

| ID | Question for a human | Why confirmation is needed | Current evidence |
|---|---|---|---|
| HQ-040 | Is a menu's ingredient list a recipe/BOM, and is each pivot quantity the base-unit requirement per one finished unit/cup? | Production multiplies pivot quantity by target quantity. | Meaning is `INFERRED_FROM_CODE`; owner confirmation is required. |
| HQ-041 | Must a production plan preserve the recipe version that existed when it was planned, started, or completed? | Only the current `menu_raw_material` pivot is stored; production items do not snapshot recipe lines. | Missing history is `SOURCE_VERIFIED`; required effective date is `UNKNOWN`. |
| HQ-042 | Should historical production material-use reports and production-detail HPP remain unchanged when a recipe or current price is edited, and should a plan snapshot the unit/conversion values it started with? | Production reports traverse the current recipe for material use; HPP detail views use current recipe/current price; production items contain no unit/conversion snapshot. | Retroactive/current-master-data effects are `INFERRED_FROM_CODE`; reporting requirement is `UNKNOWN`. |
| HQ-043 | May a menu recipe change while a production is planned or processing? If yes, which version should completion consume? | Planning/reservation and completion load current BOM at their respective request times. | Current-BOM use is `SOURCE_VERIFIED`; intended locking/version rule is `UNKNOWN`. |
| HQ-044 | Are substitute materials, optional ingredients, yield loss factors, or multi-level BOMs required? | The pivot models a flat menu-to-material quantity only. | Flat BOM is `SOURCE_VERIFIED`; extended scope is `UNKNOWN`. |

## Production, consumption ledger, waste, and actual yield

| ID | Question for a human | Why confirmation is needed | Current evidence |
|---|---|---|---|
| HQ-050 | What is “actual production yield,” who records it, and can it differ per menu from the planned target? | `actual_quantity` exists but completion creates finished goods for `target_quantity`. | Current gap is `SOURCE_VERIFIED`, `PARTIALLY_IMPLEMENTED`; required rule is `UNKNOWN`. |
| HQ-051 | Should finished goods equal actual good output, target output, target minus waste, or another reconciled value? | Waste records raw-material loss, while output remains the full target. | Current behavior is `SOURCE_VERIFIED`; intended yield equation is `UNKNOWN`. |
| HQ-052 | Is production allowed to complete directly from `planned`, or must it first be `processing`? | Start and complete actions exist, but completion has no visible status guard. | Guard absence is `SOURCE_VERIFIED`; transition policy is `UNKNOWN`. |
| HQ-053 | What should happen if production completion is submitted more than once: reject, return the prior result idempotently, or create another batch? | No visible completion-state guard or idempotency key exists. | Repetition risk is `INFERRED_FROM_CODE`; desired behavior is `UNKNOWN`. |
| HQ-054 | Who may cancel production, in which states, and what happens to reservations/materials? | `cancelled` is recognized by schema, ordering, and edit/delete rules, but no transition writer was found. | The transition is `SCAFFOLDED_ONLY`; the intended rule is `UNKNOWN`. |
| HQ-055 | Should planned/processing raw-material reservations be persisted/enforced or remain display-only calculations? | Reservations are calculated from current plans/BOM but not stored. | MRP-like calculation is `SOURCE_VERIFIED`, `PARTIALLY_IMPLEMENTED`; intent is `UNKNOWN`. |
| HQ-056 | Must every production material deduction create an immutable `raw_material_transactions` row of type `production_usage`? | The type/report option exists but completion does not write it. | Gap is `SOURCE_VERIFIED`; ledger requirement is `UNKNOWN`. |
| HQ-057 | If production-use ledger rows are required, which fields must they carry: production ID, menu/item, planned versus actual consumption, sealed packages opened, price snapshot, waste, operator, and stock snapshots? | Current transaction schema has generic quantities/snapshots but no direct production foreign key. | Schema is `SOURCE_VERIFIED`; required traceability is `UNKNOWN`. |
| HQ-058 | Is waste input measured as raw-material base units, and can the same material appear more than once? | Waste persists production/material/quantity but nested validation and uniqueness intent are unclear. | Storage is `SOURCE_VERIFIED`; input rule is `UNKNOWN`. |
| HQ-059 | Should waste reduce stock in addition to recipe consumption, or is it already included in recipe consumption? | Completion deducts calculated recipe demand and records waste separately. | Current flow is `SOURCE_VERIFIED`; physical-accounting meaning is `UNKNOWN`. |
| HQ-060 | Does `execution_notes` differ from `notes`, and which role owns each field? | Both are present in production fillable/schema/view flows. | Fields are `SOURCE_VERIFIED`; semantics are `UNKNOWN`. |

## Finished goods, distribution, sales, returns, and disposal

| ID | Question for a human | Why confirmation is needed | Current evidence |
|---|---|---|---|
| HQ-070 | Is expiry based on production date plus menu `expires_in_days`, and is the product valid through the expiry date or only before it? | The command expires batches whose date is earlier than today. | Boundary is `SOURCE_VERIFIED`; regulatory/business rule is `UNKNOWN`. |
| HQ-071 | Is one active dispatch session per Armada user a strict invariant, and should the database enforce it? | The controller checks it, but no schema uniqueness expresses the conditional invariant. | Application check is `SOURCE_VERIFIED`; strictness is `UNKNOWN`. |
| HQ-072 | What event does allocation represent: stock transfer, consignment, delivery load, custody, or sale inventory? | Allocation immediately removes quantity from ready finished-good stock and creates session items. | Flow is `SOURCE_VERIFIED`; accounting meaning is `UNKNOWN`. |
| HQ-073 | Is manually entered `quantity_sold` sufficient as the sales record? | No sale header/line, unit price, discount, tax, customer, payment, receipt, or revenue table was found. | Quantity-only sales are `SOURCE_VERIFIED`, `PARTIALLY_IMPLEMENTED`; required sales scope is `UNKNOWN`. |
| HQ-074 | Must prices be captured at dispatch or at sale, and where does revenue belong? | Neither dispatch session nor item stores a sale price/revenue value. | Missing fields are `SOURCE_VERIFIED`; commercial rule is `UNKNOWN`. |
| HQ-075 | Can sold quantities be corrected after a session finishes, and is an approval/audit trail required? | Update is limited to active sessions; finished-session correction workflow is absent. | Current limitation is `SOURCE_VERIFIED`; correction policy is `UNKNOWN`. |
| HQ-076 | Must all unsold goods go through Produksi inspection, or can eligible stock return automatically? | Finish creates pending return checks for every positive unsold quantity. | Current flow is `SOURCE_VERIFIED`; operational rule is `UNKNOWN`. |
| HQ-077 | Which conditions permit a return to ready stock—temperature, seal condition, time out of storage, expiry, damage, or other checks? | The implemented decision records ready versus expired/damaged plus optional notes, with only expiry explicitly checked. | Current check is `SOURCE_VERIFIED`; inspection policy is `UNKNOWN`. |
| HQ-078 | Should expired and damaged be separate outcomes with separate reasons, or is the combined `expired_damaged` state intentional? | One combined state and one rejected batch status are used. | Combined state is `SOURCE_VERIFIED`; intended taxonomy is `UNKNOWN`. |
| HQ-079 | When rejected goods are disposed, what quantity, reason, method, timestamp, operator, approval, and evidence must be retained? | Disposal sets the rejected clone to zero/empty without a separate disposal ledger. | Current mutation is `SOURCE_VERIFIED`, `PARTIALLY_IMPLEMENTED`; retention rule is `UNKNOWN`. |
| HQ-080 | Should ready returns restore the original batch, create a distinct return batch, or create an immutable movement record? | Current code adds quantity to the original batch. | Behavior is `SOURCE_VERIFIED`; traceability requirement is `UNKNOWN`. |
| HQ-081 | Are returns allowed after expiry, and if not, which timezone/date boundary applies? | Ready decision rejects an already expired original batch. | Check is `SOURCE_VERIFIED`; approved boundary is `UNKNOWN`. |
| HQ-082 | Must owner and armada users see return status/history, or is that restricted to Produksi? | Produksi has the inspection view; other roles mainly see session data. | Visible surfaces are `SOURCE_VERIFIED`; stakeholder visibility is `UNKNOWN`. |

## Reports, exports, ROP, and MRP

| ID | Question for a human | Why confirmation is needed | Current evidence |
|---|---|---|---|
| HQ-090 | Which filters should apply consistently to transaction table, KPIs, chart, composition, and export? | Search/type filtering is not consistently applied to every component. | Difference is `SOURCE_VERIFIED`; desired semantics are `UNKNOWN`. |
| HQ-091 | Should the transaction export match the visible page, the entire filtered result, or a separate statutory format? | An Excel export exists, but runtime output was not opened and filter expectations are not documented. | Export code is `SOURCE_VERIFIED`; output contract is `UNKNOWN` and `NEEDS_RUNTIME_VERIFICATION`. |
| HQ-092 | Should production reports use targets, actual yields, or both? | Current calculations are target/current-BOM oriented and actual yield is not populated. | Current report basis is `SOURCE_VERIFIED`; reporting requirement is `UNKNOWN`. |
| HQ-093 | Which missing reports are required: finished-goods movements, dispatch/sales/revenue, returns/disposal, stock valuation, waste, ROP, or MRP? | Only transaction and production report modules were found. | Existing scope is `SOURCE_VERIFIED`, `PARTIALLY_IMPLEMENTED`; required scope is `UNKNOWN`. |
| HQ-094 | What does ROP mean for this project: a simple minimum threshold, statistical reorder point, safety stock, or a purchase recommendation? | The application has `minimum_stock` and low-stock ratios but no lead-time/demand calculation. | Current ROP is `SCAFFOLDED_ONLY`; intended formula is `UNKNOWN`. |
| HQ-095 | If ROP is required, which demand history, lead time, service level, supplier, order cycle, unit, and rounding rules apply? | None of these planning inputs appears in the audited schema. | Missing planning inputs are `SOURCE_VERIFIED`; parameters are `UNKNOWN`. |
| HQ-096 | What does MRP mean here: display of requirements, net material plan, procurement proposal, or production scheduling? | Current code calculates current-BOM needs/reservations but does not persist or release a plan. | Current MRP is `PARTIALLY_IMPLEMENTED`; target scope is `UNKNOWN`. |
| HQ-097 | Should MRP include on-hand sealed/opened stock, active-plan reservations, safety stock, expected receipts, waste/yield, lead times, and lot sizing? | Only on-hand/current plan/BOM concepts are observable. | Present inputs are `SOURCE_VERIFIED`; full netting rule is `UNKNOWN`. |
| HQ-098 | Which role owns ROP/MRP review and who is authorized to act on recommendations? | No recommendation/approval/procurement workflow or role assignment was found. | Workflow is absent (`SCAFFOLDED_ONLY`/`UNKNOWN`). |
| HQ-099 | Should production reports group completed output by planned date, actual completion date, or both? | The report uses `productions.plan_date`; there is no `completed_at`, while finished goods receive a production date at completion. | Current date sources are `SOURCE_VERIFIED`; intended reporting basis is `UNKNOWN`. |

## Collaborator and implementation-history decisions

| ID | Question for a human | Why confirmation is needed | Current evidence |
|---|---|---|---|
| HQ-100 | Which incomplete resource actions and commented views are deliberate placeholders versus interrupted work? | Several generated routes/actions are empty or missing, and the production create view is commented out. | Incompleteness is `SOURCE_VERIFIED`; intent is `UNKNOWN`. |
| HQ-101 | Why are there both owner and produksi production controllers, and which role owns planning, starting, completing, correcting, and reporting? | Responsibilities are split across `Owner/ProductionController.php` and `ProductionController.php`. | Split is `SOURCE_VERIFIED`; intended boundary is `UNKNOWN`. |
| HQ-102 | Was `production_items.wasted_quantity` intentionally replaced by `production_wastes`, and is rollback compatibility required? | The later migration drops the column and the model still lists it. | Migration/model history is `SOURCE_VERIFIED`; collaborator decision is `UNKNOWN`. |
| HQ-103 | Why was `allocated_by` added in a second migration after being present in the create migration? Are existing deployments on different migration histories? | Fresh-schema duplication may differ from upgraded databases. | Duplicate definition is `SOURCE_VERIFIED`; deployment history is `UNKNOWN`. |
| HQ-104 | Are legacy production status strings such as `done` present in real databases, and should they remain readable? | Views/source contain mixed current/legacy status terms. | Source strings are `SOURCE_VERIFIED`; deployed data is `UNKNOWN`. |
| HQ-105 | Is the every-minute expiry schedule deliberate despite the daily comment, and who operates the scheduler? | Code and comment disagree; external scheduler state is unavailable. | Disagreement is `SOURCE_VERIFIED`; operations intent is `UNKNOWN`. |
| HQ-106 | Is the unused Breeze navigation intended to replace the current sidebars, or is it abandoned scaffolding? | `layouts/navigation.blade.php` is not used by the active layout and references a nonexistent route name. | Partial is `UNUSED`; design direction is `UNKNOWN`. |
| HQ-107 | Are Chart.js, SweetAlert, `@tailwindcss/vite`, and the empty `bootstrap.js` planned dependencies or leftover scaffolding? | Package/import/config usage is inconsistent. | Current usage is `SOURCE_VERIFIED`/`UNUSED`; collaborator plan is `UNKNOWN`. |
| HQ-108 | Are case-insensitive deployments assumed for the `owner/Menus` view directory, or must Linux/case-sensitive deployment be supported? | Controller view names use lowercase while the directory is capitalized. | Casing mismatch is `SOURCE_VERIFIED`; platform decision is `UNKNOWN`. |
| HQ-109 | What is the intended source and update path for the hard-coded/inert values and controls on the Owner Armada account-administration page? | UI elements are present there without corresponding query/control behavior. | Current UI is `PARTIALLY_IMPLEMENTED`; design source is `UNKNOWN`. |
| HQ-110 | Is there external documentation, spreadsheet logic, legacy system behavior, or collaborator knowledge that defines stock conversions, recipes, yield, sales, returns, ROP, or MRP? | No in-repository specification or domain test suite establishes those rules. | Repository specification gap is `SOURCE_VERIFIED`; external source is `UNKNOWN`. |
| HQ-111 | Which behaviors have already been accepted in production, and which are prototypes that must not be treated as contractual? | Static code cannot establish operational acceptance. | Runtime/acceptance history is `UNKNOWN` and requires human evidence. |

## Requested response format

For efficient follow-up, answer each relevant question with:

| Field | Requested answer |
|---|---|
| Question ID | `HQ-nnn` |
| Decision/answer | Confirmed rule or “not decided” |
| Decision owner | Named role/person |
| Effective date | Date/version from which the rule applies |
| Existing evidence | Policy, spreadsheet, screenshot, production observation, or collaborator note |
| Exceptions | Role, product, material, date, location, or legacy-data exceptions |
| Runtime verification needed | Related `RV-nnn` checks, if any |

Answers should be recorded as stakeholder decisions; they should not be presented as `SOURCE_VERIFIED` repository behavior unless a later source audit finds corresponding code.
