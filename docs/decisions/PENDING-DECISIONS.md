# Pending Decisions

## Purpose

This is the editable register for decisions that materially affect documentation or future development. It is intentionally smaller than the audit and verification question sets. The 30 entries below are at different decision stages and may contain proposed or final answers. A decision status records the current documentation state; it does not by itself prove matching source implementation or runtime behavior.

Use only these statuses: `PENDING`, `PROVISIONAL`, `NEEDS_OWNER_CONFIRMATION`, `NEEDS_COLLABORATOR_CONFIRMATION`, `NEEDS_RUNTIME_VERIFICATION`, `DEFERRED`, `CONFIRMED`, `REJECTED`, `OUT_OF_SCOPE`, and `SUPERSEDED`.

When evidence or an answer is supplied, preserve the existing evidence, update the applicable response fields, review the answer with the required decision maker, and add a separate confirmed entry to `docs/decisions/DECISION-LOG.md` only when its approval and traceability requirements are satisfied. Do not turn this register itself into canonical policy.

## Register Summary

| Status | Count |
| --- | ---: |
| `CONFIRMED` | 7 |
| `PROVISIONAL` | 14 |
| `NEEDS_RUNTIME_VERIFICATION` | 5 |
| `NEEDS_COLLABORATOR_CONFIRMATION` | 2 |
| `DEFERRED` | 2 |
| **Total entries** | **30** |

## 1. Decision authority

### PD-AUTHORITY-001 — Decision authority and approval ownership

| Field | Value |
| --- | --- |
| Decision ID | `PD-AUTHORITY-001` |
| Title | Decision authority and approval ownership |
| Status | `CONFIRMED` |
| Decision needed | Name who may approve business, technical, operational, security, and documentation decisions, including who resolves disagreements. |
| What is currently known from source | Source code contains behavior but no approval registry or canonical specification. |
| What is known from history | Conversation participants made and revised recommendations, but no durable decision-authority model was recorded. |
| Why it remains unresolved | The authority model is recorded as confirmed, but named approval provenance, Decision Log traceability, and any Produksi or Armada operational-validation boundaries remain unresolved. |
| Required decision maker or evidence source | Original project owner/business stakeholder, with the project developer identifying the current technical/documentation owner. |
| What work it blocks | Confirmation of every other pending decision; canonical decision log, business rules, process documentation, and agent context. |
| Safe interim documentation treatment | Describe the recorded authority model as a decision marked `CONFIRMED`; continue treating repository behavior and historical statements as evidence, not as approval or runtime verification. |
| Source references | `SQ-I-001`; `docs/reconciliation/RECONCILIATION-METADATA.md`; `docs/reconciliation/CHAT-VS-REPOSITORY.md` (`DV-02`). |
| Proposed answer | The Owner of Kanawa Express has final authority over the business process and business requirements. The project developer/system analyst translates the existing business process into functional and non-functional requirements and into the system design. The Owner has granted the developer discretion to make reasonable adjustments in order to produce a more effective solution, provided that the original business objectives and operational intent are preserved. |
| Final answer | The Owner of Kanawa Express is the primary authority for business processes, operational requirements, and the intended outcomes of the system. The project developer, who is also the owner of the software project and acts as the system analyst, is responsible for studying the existing manual business process, translating it into functional and non-functional requirements, and designing the corresponding system solution. Requirements and material differences from the original process are reviewed with the Owner. The Owner has approved the resulting direction and has delegated reasonable discretion to the project developer to refine or slightly adjust the process when necessary to achieve a better overall solution, provided that the approved business objectives are preserved. Technical architecture, implementation, security controls, and documentation decisions are the responsibility of the project developer, with consultation from the technical collaborator when decisions affect modules developed collaboratively. When a technical decision materially changes a business rule or operational process, the Owner of Kanawa Express retains final approval authority. |
| Answered by | Project developer/system analyst, based on requirements discussions and approval from the Owner of Kanawa Express |
| Decision date | 2026-08-03 |
| Notes | The system originates from the Owner’s existing manual business process. The developer first models that process, drafts the functional and non-functional requirements, and asks the Owner to review meaningful differences. Minor technical or workflow refinements may be decided by the developer under the discretion granted by the Owner. Operational details may still require validation from the relevant Produksi or Armada users. |

### PD-AUTHORITY-002 — Acceptance of collaborator-designed workflows

| Field | Value |
| --- | --- |
| Decision ID | `PD-AUTHORITY-002` |
| Title | Acceptance of collaborator-designed workflows |
| Status | `PROVISIONAL` |
| Decision needed | Identify which production, expiry, distribution, sale-count, return, and disposal behaviors were deliberate designs, prototypes, or unfinished work, and which were accepted by the project owner. |
| What is currently known from source | Concrete collaborator-era behavior exists for package opening, target-based batches, allocation-time deduction, sessions, cumulative sold counts, return re-entry, rejected batches, disposal, and expiry. |
| What is known from history | These workflows were discovered from collaborator-supplied files; the conversations explicitly lacked the collaborator's full reasoning. |
| Why it remains unresolved | Code proves implementation choices, not the collaborator's intent or stakeholder acceptance. |
| Required decision maker or evidence source | Technical collaborator first; project owner must separately approve business consequences. |
| What work it blocks | Canonical production, finished-goods, distribution, sales, returns, disposal, and ownership documentation. |
| Safe interim documentation treatment | Describe source-visible mechanics only and label their approval status unresolved. |
| Source references | `SQ-I-001`; `SQ-L-011`; `VR-P1-013`; `DC-U02`; `DC-U03`; `DC-U04`; `DC-U05`; `DC-U06`; `docs/history/04-inventory-handover-and-production-report.extract.md` (“Discovery of collaborator implementation”). |
| Proposed answer | The collaborator-designed workflows are accepted provisionally as the current development baseline because they already provide a readable end-to-end movement of raw materials, finished goods, Armada custody, sold quantities, returned goods, rejected goods, and expiry states. The system as a whole is not considered final, and the current mechanics may be refined as the project develops. |
| Final answer |  |
| Answered by |  |
| Decision date |  |
| Notes | The technical collaborator’s original reasoning is not fully documented. Production-plan stock enforcement, repeated completion behavior, actual production yield, duplicate return processing, rejected-batch handling, and disposal history remain separate unresolved or verification items. Automatic expiry should be distinguished from disposal: expiry changes batch availability/status, while disposal is a later action that may reduce or close the rejected quantity. |

## 2. Authentication and account lifecycle

### PD-AUTH-001 — Public registration and default Armada access

| Field | Value |
| --- | --- |
| Decision ID | `PD-AUTH-001` |
| Title | Public registration and default Armada access |
| Status | `CONFIRMED` |
| Decision needed | Decide whether public self-registration is allowed and, if so, what role, activation, approval, and audit rules apply. |
| What is currently known from source | Guest registration routes create and authenticate a user; the controller does not assign a role, so the database default supplies `armada`. The login page does not link registration. |
| What is known from history | History describes an internal role-based application and seeded accounts, but contains no public-registration approval. |
| Why it remains unresolved | The registration policy is recorded as confirmed, but current source behavior conflicts with it and correction and runtime status remain unverified. |
| Required decision maker or evidence source | Project owner and security/account owner. |
| What work it blocks | Authentication/account documentation, onboarding, route-access claims, and account security decisions. |
| Safe interim documentation treatment | Document public self-registration as disallowed by the recorded decision, and separately document the source-visible registration route and default-role behavior as implementation gaps; do not claim they have been corrected or runtime-verified. |
| Source references | `SQ-I-002`; `VR-P0-001`; `DC-U01`; `docs/reconciliation/CHAT-VS-REPOSITORY.md` (`AU-03`). |
| Proposed answer | Public users must not be allowed to create their own accounts. Armada accounts are created and managed by the Owner. |
| Final answer | Public self-registration is not permitted. Every Armada account must be created by the Owner through the internal account-management process. A newly created public account must not automatically receive the armada role or immediate access to the application. The existing public registration routes and database default role do not represent the approved account workflow and must be treated as an implementation gap until corrected. |
| Answered by | Project developer/system analyst |
| Decision date | 2026-08-03 |
| Notes | This application is intended for internal operational use. Account creation is controlled by the Owner. The public registration route currently visible in source conflicts with this decision and should later be handled as a separate remediation task. |

### PD-AUTH-002 — Official login identifier, email, verification, and recovery

| Field | Value |
| --- | --- |
| Decision ID | `PD-AUTH-002` |
| Title | Official login identifier, email, verification, and recovery |
| Status | `PROVISIONAL` |
| Decision needed | Choose username-only, email-only, or dual login; define cross-field collisions, whether each role requires email, verification enforcement, and recovery for null-email accounts. |
| What is currently known from source | The request accepts username or email; the UI is username-labelled. Operational users may have null email while reset/verification routes are email-oriented and verification is not enforced. |
| What is known from history | Username was the preferred operational identifier and email was considered optional; no final recovery/verification policy was preserved. |
| Why it remains unresolved | Current backend, UI, seed/account data, and Breeze-derived recovery paths express different contracts. |
| Required decision maker or evidence source | Project owner/security owner, informed by the project developer. |
| What work it blocks | Login help, account fields, password recovery, email verification, user administration, and authentication tests. |
| Safe interim documentation treatment | Describe exact source behavior; do not call username-only or dual login the approved policy. |
| Source references | `SQ-I-003`; `VR-P1-001`; `VR-P1-002`; `DC-C01`; `docs/reconciliation/CHAT-VS-REPOSITORY.md` (`AU-02`; `AU-04`). |
| Proposed answer | Username should remain the primary operational login identifier. Whether email is mandatory for Owner, Produksi, and Armada accounts is still under consideration, but the current preferred direction is to require an email address for account confirmation, security notifications, and recovery support. Accounts continue to be created and administered by the Owner. Password recovery is provisionally handled by the Owner rather than directly by operational users. A password-reset action should require confirmation through the Owner’s verified email or another strong Owner-authentication mechanism and must create an audit record identifying the affected account, acting Owner, time, and reason. Whether each account must verify its own email before use remains undecided. |
| Final answer |  |
| Answered by |  |
| Decision date |  |
| Notes | The current direction is not final. Requiring every operational user to have an email may improve recovery and notification, but it may also complicate account creation for users without suitable email access. Confirmation through the Owner’s email alone must not replace proper authorization and audit logging. The final decision must separately define: official login identifier, mandatory email by role, email ownership, verification requirements, reset authority, user notification, and audit history. |

### PD-AUTH-003 — Account retention, self-deletion, and record ownership

| Field | Value |
| --- | --- |
| Decision ID | `PD-AUTH-003` |
| Title | Account retention, self-deletion, and record ownership |
| Status | `CONFIRMED` |
| Decision needed | Decide whether operational users are deleted, deactivated, or anonymized; what historical records survive; and which role-level resources require per-record ownership rules. |
| What is currently known from source | Authenticated self-deletion exists; foreign keys may cascade, null, restrict, or erase linked history. Authorization is mostly role-level, with explicit ownership checks mainly on Armada session changes. |
| What is known from history | Three-role middleware was accepted and fine-grained permission tooling deferred, but retention and per-record access were not defined. |
| Why it remains unresolved | The retention and administrative-control policy is recorded as confirmed, but source-visible self-deletion, foreign-key effects, assignment history, and record-level authorization remain implementation or runtime gaps. |
| Required decision maker or evidence source | Project owner/security and data-retention owner. |
| What work it blocks | Account lifecycle, role-access matrix, audit retention, profile documentation, and safe deletion verification. |
| Safe interim documentation treatment | Document the recorded retention and account-control policy; separately describe source-visible self-deletion and observed ownership checks without promising safe deletion, individual assignment attribution, or comprehensive record authorization. |
| Source references | `SQ-L-001`; `SQ-L-002`; `VR-P0-014`; `VR-P1-003`; `docs/reconciliation/CHAT-VS-REPOSITORY.md` (`AU-05`; `RA-02`). |
| Proposed answer | Operational accounts should be deactivated rather than permanently deleted. Users must not be allowed to delete or deactivate their own accounts. The Owner controls account details, password changes, activation, deactivation, and reassignment. Historical production, distribution, return, transaction, and audit records must remain stored. |
| Final answer | Operational accounts belong to the company and must not be permanently deleted. When an account is no longer in active use, the Owner may deactivate it, although deactivation is expected to be uncommon. Users cannot delete, deactivate, or independently change the administrative state of their own accounts. Only the Owner may edit account details, reset passwords, activate, deactivate, or reassign operational accounts. All historical records created through an account—including production, distribution, sold-count, return, disposal, and inventory records—must remain stored and linked to that account. An operational account may be reassigned to another employee because the account represents a company-controlled operational identity; before reassignment, the Owner must change its password and update the current account-holder details. Account reassignment must not erase or rewrite historical records. |
| Answered by | Project developer/system analyst |
| Decision date | 2026-08-03 |
| Notes | The current source includes authenticated self-deletion, which conflicts with this decision and must later be treated as an implementation gap. Because accounts may be reused by different employees, a future assignment-history record should identify the employee assigned to the account, the assignment start and end dates, and the Owner who performed the reassignment. Without assignment history, older actions can be attributed only to the company account, not reliably to the individual employee who performed them. Role-level access remains the default, while Armada users may additionally require ownership restrictions for their assigned sessions and operational records. |

## 3. Raw-material stock and ledger

### PD-INVENTORY-001 — Authoritative raw-material stock source

| Field | Value |
| --- | --- |
| Decision ID | `PD-INVENTORY-001` |
| Title | Authoritative raw-material stock source |
| Status | `CONFIRMED` |
| Decision needed | Define which representation wins when sealed/opened aggregate stock and transaction history disagree, plus the approved reconciliation/correction event. |
| What is currently known from source | Operational flows read mutable `sealed_stock` and `opened_stock`; transaction rows hold movement and before/after total snapshots, but cannot replay all movements. |
| What is known from history | Base-unit normalization and transaction-explainable movement were strong principles, but no precedence/reconciliation contract was chosen. |
| Why it remains unresolved | The operational stock-source rule is recorded as confirmed, but production-consumption and waste ledger coverage, sealed-stock correction, and runtime reconciliation behavior remain unresolved. |
| Required decision maker or evidence source | Owner/inventory process owner, with technical/data input. |
| What work it blocks | Canonical stock rules, reconciliation, correction policy, ledger/report definitions, and ROP/MRP data provenance. |
| Safe interim documentation treatment | Document sealed and opened balances as the recorded operational stock authority and `total_stock` as derived; separately disclose incomplete ledger coverage and unverified reconciliation and correction behavior. |
| Source references | `SQ-I-004`; `VR-P0-006`; `DC-C02`; `docs/reconciliation/CHAT-VS-REPOSITORY.md` (`ST-03`). |
| Proposed answer | Each raw material has its own stock record. Materials that differ in brand, package size, weight, quantity, or conversion details are treated as different raw materials. sealed_stock represents unopened packages, while opened_stock represents opened quantity in the base unit. total_stock is calculated from both values. Restocking increases sealed stock. Production consumes opened stock first and automatically opens sealed stock when necessary. |
| Final answer | The authoritative operational raw-material balance is represented by sealed_stock and opened_stock. total_stock is a derived value calculated as opened_stock + (sealed_stock × conversion_value) and is not maintained as an independent stock balance. Restocking increases the sealed-stock balance. Production consumes opened stock first and may convert unopened packages into opened base-unit stock when the opened quantity is insufficient. If the available total stock cannot satisfy production requirements, production must not proceed. When the system balance, transaction history, or observed inventory appears inconsistent, a manual physical stock check must be performed. The verified physical quantity becomes the basis for correction. The discrepancy must be corrected through an explicit stock adjustment using the appropriate add or reduce operation, and the adjustment must include a reason. Existing transaction history must not be silently rewritten. |
| Answered by | Project developer/system analyst |
| Decision date | 2026-08-03 |
| Notes | Transaction history serves as an audit trail, while the current sealed/opened balances drive operational availability. Production-consumption and waste ledger coverage remain separate unresolved implementation matters. The audited source currently applies manual adjustments to opened_stock; correcting discrepancies in unopened package counts may require a separate sealed-stock adjustment or stock-opname mechanism. |

### PD-INVENTORY-002 — Production-consumption movement ledger

| Field | Value |
| --- | --- |
| Decision ID | `PD-INVENTORY-002` |
| Title | Production-consumption movement ledger |
| Status | `PROVISIONAL` |
| Decision needed | Decide whether every production use, package opening, and production waste deduction requires an immutable movement event and define required trace fields. |
| What is currently known from source | Production deducts material directly; `production_usage` exists as a transaction type/filter but has no writer. Waste uses a separate table and package opening has no event. |
| What is known from history | A consumption ledger was identified as valuable for audit/ROP, but intentionally deferred so Production Report V1 could proceed. |
| Why it remains unresolved | The deferral explains missing records but does not decide the final ledger boundary or schema. |
| Required decision maker or evidence source | Inventory/business owner and Produksi representative, advised by the technical collaborator. |
| What work it blocks | Complete material ledger, production audit, material-use reporting, stock reconciliation, ROP inputs, and MRP history. |
| Safe interim documentation treatment | Explicitly label raw-material transactions partial and production use/waste outside that ledger. |
| Source references | `SQ-I-009`; `VR-P0-005`; `DC-C02`; `docs/reconciliation/CHAT-VS-REPOSITORY.md` (`TR-01`). |
| Proposed answer | Every raw-material quantity consumed by a production run should be recorded as an immutable production-consumption movement. Production waste must also be permanently recorded and linked to the production that caused it. Automatic conversion from sealed stock to opened stock does not need to create a separate transaction because it changes the stock state but not the total physical quantity. Production usage may be calculated from the applicable recipe and the production output, but the resulting material quantities should be persisted so that historical usage does not change when a recipe is edited later. |
| Final answer |  |
| Answered by |  |
| Decision date |  |
| Notes | The final schema is not yet fixed. Production usage and production waste may be stored in one movement ledger with different movement types or in linked dedicated tables. At minimum, each permanent record should identify the production, raw material, movement type, quantity, base unit, stock before and after, responsible user, timestamp, and relevant notes. Existing movement history must not be edited or deleted. A correction should be represented by a new adjustment or correction record. The source audit reports that actual_quantity is currently not maintained, so the production-output basis for usage calculations remains a separate unresolved decision. |

### PD-INVENTORY-003 — Sealed/opened handling, adjustments, units, and precision

| Field | Value |
| --- | --- |
| Decision ID | `PD-INVENTORY-003` |
| Title | Sealed/opened handling, adjustments, units, and precision |
| Status | `CONFIRMED` |
| Decision needed | Confirm whole-package rules, automatic opening/remainder handling, fractional conversions, quantity/price rounding, and whether adjustments may change only opened stock or also sealed packages/conversion errors. |
| What is currently known from source | Restock accepts whole packages; adjustment affects opened stock only; production opens the ceiling number of packages and keeps the remainder opened. Validation/decimal boundaries are not fully uniform. |
| What is known from history | Purchase/base-unit normalization and sealed/opened meanings were accepted, but detailed precision, partial-package, and correction policies were not settled. |
| Why it remains unresolved | The sealed/opened, precision, adjustment, and HPP rules are recorded as confirmed, but the controlled sealed-stock or conversion-correction procedure and consistent runtime enforcement remain unresolved. |
| Required decision maker or evidence source | Owner/inventory operator and Produksi representative. |
| What work it blocks | Exact stock formulas, validation documentation, stock-opname procedures, correction workflow, and test cases. |
| Safe interim documentation treatment | Document the recorded rules while describing current endpoint validation and calculations separately; state that controlled correction behavior and consistent enforcement are not yet verified. |
| Source references | `SQ-L-003`; `VR-P1-004`; `VR-P1-005`; `DC-U02`; `docs/reconciliation/CHAT-VS-REPOSITORY.md` (`RM-02`; `RM-03`; `ST-02`; `PR-03`). |
| Proposed answer | Restocking must use whole purchase packages. Any unused remainder from an opened package becomes opened stock. Conversion values may use decimals. Raw-material quantities use two decimal places. Manual adjustment may change opened stock but not sealed stock. Menu HPP is calculated from the ingredient quantities required for one menu portion. |
| Final answer | Restocking must use whole purchase packages; partial-package restock is not permitted. When production requires more material than the available opened stock, the system may open sufficient sealed packages. Any unused remainder from those packages becomes opened stock in the material’s base unit. Conversion values may contain decimal values when required. Raw-material quantities are recorded with two decimal places. Ordinary stock adjustment may add or reduce opened stock but may not directly change sealed stock. Incorrect conversion values or sealed-package discrepancies require a controlled correction procedure rather than an ordinary adjustment. Menu HPP is calculated when the menu recipe is defined by calculating the cost of each ingredient quantity required for one menu portion and summing all ingredient costs. Ingredient calculations should retain their calculation precision until they are summed, and the final menu HPP is rounded to the nearest whole rupiah. |
| Answered by | Project developer/system analyst |
| Decision date | 2026-08-03 |
| Notes | HPP should be rounded only after all ingredient costs have been summed, rather than rounding every ingredient cost first. A separate controlled procedure is still required when physical sealed-package stock differs from the system because ordinary adjustment affects opened stock only. |

### PD-INVENTORY-004 — Archive/inactive behavior across downstream workflows

| Field | Value |
| --- | --- |
| Decision ID | `PD-INVENTORY-004` |
| Title | Archive/inactive behavior across downstream workflows |
| Status | `PROVISIONAL` |
| Decision needed | Define whether inactive materials/menus are unavailable only for new selection or also for open plans, production completion, reports, and history. |
| What is currently known from source | UI queries filter active records selectively; backend existence validation and current relationships can still accept or traverse inactive records. |
| What is known from history | Archive rather than deletion was accepted, but its downstream lifecycle semantics were not specified. |
| Why it remains unresolved | “Inactive” has no single approved cross-module meaning. |
| Required decision maker or evidence source | Project owner with inventory/Produksi operational input. |
| What work it blocks | Raw-material/menu lifecycle documentation, planning/production rules, route validation, and historical visibility. |
| Safe interim documentation treatment | Document only where current queries filter active records; avoid claiming global enforcement. |
| Source references | `SQ-L-004`; `docs/reconciliation/CHAT-VS-REPOSITORY.md` (`RM-01`; `BM-01`). |
| Proposed answer | Inactive raw materials cannot be selected for new recipes, purchases, adjustments, production plans, or other new operational transactions. Inactive menus cannot be selected for new production, distribution, or other new workflows. Deactivation does not remove or hide historical records. Existing production, stock, recipe, distribution, sales, waste, and report history must remain visible and continue to reference the original inactive record. Production that has already started may be completed. Inactive raw materials and menus may be reactivated. A production plan created before deactivation should provisionally remain valid and may be completed unless the Owner explicitly cancels or replaces it. |
| Final answer |  |
| Answered by |  |
| Decision date |  |
| Notes | Deactivation is a reversible lifecycle state, not deletion. It prevents new operational use but does not rewrite historical relationships. Because production plans are normally created and completed within the same day or within approximately half a day, deactivation during an open plan is expected to be rare. Backend validation should eventually enforce the rule consistently rather than relying only on filtered user-interface selections. |

## 4. Production lifecycle and actual yield

### PD-PRODUCTION-001 — Production transitions and repeated completion policy

| Field | Value |
| --- | --- |
| Decision ID | `PD-PRODUCTION-001` |
| Title | Production transitions and repeated completion policy |
| Status | `PROVISIONAL` |
| Decision needed | Define valid start, complete, cancel, reopen, and correction transitions, including whether a repeated completion is rejected, idempotently returns the prior result, or represents authorized rework. |
| What is currently known from source | Start changes `planned` to `processing`; completion has no required current-status guard; `cancelled` has no writer; repeated completion can re-enter mutation logic. |
| What is known from history | Owner/Produksi lifecycle was intended, but completion prerequisites, cancellation, correction, and idempotency were left unresolved. |
| Why it remains unresolved | Source safety behavior and stakeholder process intent are both incomplete; runtime duplicate effects were not executed. |
| Required decision maker or evidence source | Project owner and Produksi lead first; isolated runtime verification after the rule is approved. |
| What work it blocks | Production state machine, completion safety, recovery procedures, finished-goods integrity, and production tests. |
| Safe interim documentation treatment | Show only source-visible transitions and state that completion must not be assumed repeat-safe or status-enforced. |
| Source references | `SQ-I-007`; `VR-P0-002`; `VR-P0-003`; `VR-P1-007`; `DC-C04`; `DC-R02`. |
| Proposed answer | The Owner creates a production plan in planned status. Produksi starts the plan, changing it to processing, and only a production in processing status may be completed. A production in planned status may be cancelled by the Owner. A production in processing or completed status may not be cancelled through the normal cancellation action. Completion may be executed only once. Any repeated completion request must be rejected by the backend and must not deduct raw materials or create finished goods again. Produksi may start and complete production, while cancellation and correction authority belongs to the Owner. A completed production may be reopened only through an Owner-authorized correction workflow. |
| Final answer |  |
| Answered by |  |
| Decision date |  |
| Notes | Hiding the completion button after the first completion is a user-interface safeguard only and is not sufficient. The backend must verify that the current status is processing before applying completion mutations. The source-aligned term planned should remain in use unless the source status is intentionally renamed to pending. A note such as spilled material explains production waste, but it does not by itself define how an incorrect actual output is corrected. Reopening must specify whether previously deducted raw materials and created finished goods are reversed, preserved, or corrected through separate immutable records. |

### PD-PRODUCTION-002 — Actual production yield and finished-goods quantity

| Field | Value |
| --- | --- |
| Decision ID | `PD-PRODUCTION-002` |
| Title | Actual production yield and finished-goods quantity |
| Status | `PROVISIONAL` |
| Decision needed | Define actual yield, who records it, when it is captured/corrected, allowed variance, and whether finished goods equal target, measured good output, target minus loss, or another reconciled value. |
| What is currently known from source | `actual_quantity` starts at zero and is not updated/read; completion creates finished goods and production metrics from target quantity. |
| What is known from history | Produksi was expected to input production results; later report discussion explicitly left target versus actual unresolved. |
| Why it remains unresolved | The implemented target fallback is observable but has no approved physical-yield meaning. |
| Required decision maker or evidence source | Project owner and Produksi operational representative. |
| What work it blocks | Production completion, finished-goods quantity, waste/yield reconciliation, production reports, and planning feedback. |
| Safe interim documentation treatment | Label current output/report values as target-based; never call them measured actual output. |
| Source references | `SQ-I-008`; `VR-P0-004`; `DC-C03`; `docs/reconciliation/CHAT-VS-REPOSITORY.md` (`PR-04`). |
| Proposed answer | Finished goods are based on the production plan’s target_quantity. Produksi executes the plan created by the Owner and may complete it only after the required target quantity has been produced. The system automatically uses the target quantity as the completed production quantity when Produksi submits completion. A separate actual quantity that is lower or higher than the target is not accepted in the normal workflow. Raw materials that are spilled, damaged, or otherwise lost during production must be recorded through the production-waste input. Such waste does not reduce the required finished-goods target; Produksi must continue production until the target output is fulfilled. Producing more than the target is not permitted as part of the same production plan. |
| Final answer |  |
| Answered by |  |
| Decision date |  |
| Notes | Under this policy, actual_quantity is not a separately measured yield. It either needs to be set automatically equal to target_quantity, renamed to avoid suggesting measured variance, or removed if redundant. A new production plan may be used when additional legitimate production is required after completion. However, a new production plan cannot correct an overstated finished-goods balance; that scenario still requires a separate Owner-authorized finished-goods correction or reconciliation procedure. Existing completed production records should not be silently edited. |

### PD-PRODUCTION-003 — Production waste meaning and accountability

| Field | Value |
| --- | --- |
| Decision ID | `PD-PRODUCTION-003` |
| Title | Production waste meaning and accountability |
| Status | `PROVISIONAL` |
| Decision needed | Define whether submitted waste is additional to recipe use, its base unit and precision, duplicate-material handling, required reasons, responsible actor, approval, and correction path. |
| What is currently known from source | Submitted production waste is stored per production/material and deducted in addition to current-BOM consumption; nested validation, reason, actor, and shared-ledger context are incomplete. |
| What is known from history | History separated adjustment correction from waste and rejected mixed-unit totals, but did not settle the waste equation or accountability fields. |
| Why it remains unresolved | The current deduction algorithm exists without an approved physical/accounting definition. |
| Required decision maker or evidence source | Produksi lead, project owner, and inventory/accounting representative. |
| What work it blocks | Waste procedure, production consumption ledger, yield reconciliation, waste reporting, and validation requirements. |
| Safe interim documentation treatment | Describe persisted submitted quantities and extra deduction only; do not infer reason, responsibility, or approved loss model. |
| Source references | `VR-P1-008`; `DC-M04`; `docs/reconciliation/CHAT-VS-REPOSITORY.md` (`WA-01`); `docs/reconciliation/VERIFICATION-REGISTER.md` (`VR-P0-005`). |
| Proposed answer | Production waste represents raw-material loss in addition to the normal recipe quantity. Because the finished-goods quantity must still equal the production target, material that is spilled, damaged, contaminated, or otherwise unusable is deducted separately from recipe consumption. Waste quantities use each material’s base unit and follow the approved two-decimal quantity precision. Produksi records the waste and must provide a reason. Waste normally becomes effective when submitted, while specified exceptional cases require Owner review or approval. Separate waste incidents involving the same material should provisionally be stored as separate records so that each incident retains its own quantity, reason, actor, and timestamp; reports may aggregate those records by material or production. |
| Final answer |  |
| Answered by |  |
| Decision date |  |
| Notes | The conditions requiring Owner approval remain undefined, such as waste above a quantity, value, or percentage threshold. If waste is entered incorrectly, the Owner may use stock adjustment to restore or reduce the operational stock balance. However, adjustment alone should not silently alter the original waste record. A linked Owner-authorized correction record should identify the original waste entry, corrected quantity, reason, acting Owner, and timestamp. |

## 5. Recipe snapshots and costing

### PD-RECIPE-001 — Recipe and master-data snapshot timing

| Field | Value |
| --- | --- |
| Decision ID | `PD-RECIPE-001` |
| Title | Recipe and master-data snapshot timing |
| Status | `PROVISIONAL` |
| Decision needed | Choose whether recipe quantities, units, conversion, shelf life, and cost inputs are snapshotted at plan, start, completion, or not at all, and define amendments. |
| What is currently known from source | Plans store menu/target only; planning, completion, HPP, and reports reload current relationships/master values. |
| What is known from history | Current-BOM calculation was accepted as an immediate expedient; historical drift was recognized, but no snapshot design/effective date was approved. |
| Why it remains unresolved | Source implements current-state calculation while history preserves multiple options without a final decision. |
| Required decision maker or evidence source | Project owner and Produksi lead, with technical/data design input. |
| What work it blocks | Historical production meaning, plan stability, consumption reports, shelf-life evidence, costing, and correction rules. |
| Safe interim documentation treatment | State that current master/BOM data governs the observable calculations; do not promise historical immutability. |
| Source references | `SQ-I-006`; `VR-P0-007`; `DC-M01`; `docs/reconciliation/CHAT-VS-REPOSITORY.md` (`BM-03`). |
| Proposed answer | The recipe and related production inputs are provisionally locked when Produksi starts the production plan. A plan created before production starts does not permanently retain the recipe version available at planning time. If the recipe changes after the plan is created but before production starts, the production uses the latest active recipe available when the Start Production action is performed. At that point, the ingredient quantities, base units, conversion values, applicable material-cost inputs, and shelf-life rule should be copied into a production-specific snapshot. Changes made to the recipe or master data after production has started must not change the material requirements, cost basis, expiry calculation, or historical meaning of that production. The production cost should use the material prices applicable when production starts, while the standard menu HPP may continue to be recalculated when the menu recipe or current material prices are updated. The finished-goods batch should use the shelf-life rule captured when production starts. Changes made after production starts apply only to future production runs and do not automatically amend the production already in progress. |
| Final answer |  |
| Answered by |  |
| Decision date |  |
| Notes | This answer defines the intended policy but has not yet been verified against runtime behavior. The audited source currently reloads current recipe and master-data relationships during planning, completion, HPP calculation, and reporting, so the application may not yet preserve the proposed start-time snapshot. A snapshot means storing copies of the values used by the production rather than only retaining references to editable master records. At minimum, the snapshot should preserve the menu, raw-material identifier and descriptive reference, ingredient quantity, base unit, conversion value, unit-cost input, recipe cost, and shelf-life value used for the production. The exact snapshot schema, price-source method, amendment procedure, and handling of a recipe change while a production remains in processing status still require technical design and runtime verification. Until implemented, documentation must distinguish intended start-time locking from the current source behavior, which may still produce historical drift when master data is edited. |

### PD-RECIPE-002 — Historical restock-price correction and HPP basis

| Field | Value |
| --- | --- |
| Decision ID | `PD-RECIPE-002` |
| Title | Historical restock-price correction and HPP basis |
| Status | `PROVISIONAL` |
| Decision needed | Decide whether correcting an old restock changes current latest price, who may correct it, and whether HPP/valuation uses latest, weighted average, FIFO/batch, standard, or another basis. |
| What is currently known from source | Editing a selected restock recalculates it and assigns its price to `latest_price`; menu/detail HPP uses current latest price divided by current conversion. Production Report does not calculate cost. |
| What is known from history | Historical correction and current price were explicitly recognized as different accounting concepts; misleading zero cost was rejected, but no valuation method was chosen. |
| Why it remains unresolved | Current display calculation is not evidence of approved historical/accounting policy. |
| Required decision maker or evidence source | Business/finance and inventory owner. |
| What work it blocks | HPP terminology, historical valuation, profit/margin, production cost, restock permissions, and report definitions. |
| Safe interim documentation treatment | Call the existing value a current-price HPP display calculation; report historical/accounting cost as undecided. |
| Source references | `SQ-I-005`; `VR-P0-008`; `DC-M02`; `docs/reconciliation/CHAT-VS-REPOSITORY.md` (`BM-02`; `ST-01`). |
| Proposed answer | Historical restock records may be corrected by the Owner when an input error is discovered. A correction must preserve an audit trail containing the previous value, corrected value, reason, acting Owner, and correction timestamp. latest_price is provisionally defined as the purchase price from the most recent valid restock according to the effective restock date, not simply the restock record that was edited most recently. Correcting the chronologically latest restock may therefore update latest_price. Correcting an older restock must not replace the price of a newer restock. The standard menu HPP uses the material’s current latest_price, divided by its current conversion value and multiplied by the ingredient quantity required for one menu portion. Standard menu HPP may change when current material prices change. Historical production cost, however, should use the material price inputs captured when production starts, in accordance with the production snapshot policy. Later restocks or corrections must not automatically rewrite the recorded cost basis of completed production. When the first restock for a material is created, a valid purchase price is mandatory; the restock cannot be submitted without it. |
| Final answer |  |
| Answered by |  |
| Decision date |  |
| Notes | The audited source currently assigns the edited restock price directly to latest_price, even when the edited record may not be the chronologically latest restock. This is observable implementation behavior, but it should not yet be treated as the approved pricing policy. The provisional policy distinguishes three concepts: historical restock price, current latest purchase price, and production-time historical cost. The standard menu HPP is a current-price estimate, while a completed production’s cost should remain based on its start-time snapshot. Recalculating completed production using every new latest_price would create historical drift and conflict with PD-RECIPE-001. A historical restock correction may require recalculation of inventory valuation or accounting reports, but it should not silently overwrite immutable production snapshots. The project has not yet selected a formal inventory valuation method such as weighted average or FIFO. Until that decision is made, documentation should call the menu value a current latest-price HPP rather than an accounting inventory valuation or realized production cost. |

### PD-RECIPE-003 — Recipe quantity meaning and supported BOM complexity

| Field | Value |
| --- | --- |
| Decision ID | `PD-RECIPE-003` |
| Title | Recipe quantity meaning and supported BOM complexity |
| Status | `CONFIRMED` |
| Decision needed | Confirm that each pivot quantity is the base-unit requirement for one finished unit/cup and decide whether substitutes, optional ingredients, yield factors, or multi-level BOMs are required or deferred. |
| What is currently known from source | A flat menu-to-material pivot is multiplied by target quantity; no substitutes, optional lines, loss factors, or multi-level structures exist. |
| What is known from history | Fixed per-cup recipes in base units were foundational; advanced BOM capabilities were not approved. |
| Why it remains unresolved | The per-cup, base-unit, flat single-level BOM rule is recorded as confirmed, while recipe-snapshot implementation and any future advanced-BOM scope remain separate unresolved or deferred matters. |
| Required decision maker or evidence source | Project owner and Produksi/menu owner. |
| What work it blocks | Recipe definition, validation, production requirements, MRP meaning, and module roadmap. |
| Safe interim documentation treatment | Document the recorded per-cup and flat-BOM rule; describe snapshot behavior and advanced capabilities separately under `PD-RECIPE-001` and the applicable deferred scope, without implying implementation or runtime verification. |
| Source references | `SQ-O-002`; `VR-P1-006`; `VR-P3-005`; `docs/reconciliation/CHAT-VS-REPOSITORY.md` (`BM-01`). |
| Proposed answer | Each recipe ingredient quantity represents the amount of that raw material required to produce one finished cup of the menu. Ingredient quantities are defined using the raw material’s base unit, such as millilitres or grams. The production requirement for each raw material is calculated by multiplying its per-cup recipe quantity by the production target quantity. Each menu has one current active recipe. The current recipe structure is a flat, single-level relationship directly connecting a menu to its raw materials. Substitute ingredients, optional ingredient lines, and multi-level BOM structures are not required in the current project scope. The system does not apply an automatic production-loss or yield factor. Any actual material loss is recorded separately as production waste. |
| Final answer | Recipe quantities represent the raw-material requirements for one finished cup. All recipe quantities use their respective raw-material base units. Production requirements are calculated as the per-cup ingredient quantity multiplied by the target number of cups. Each menu may have only one current active recipe for operational use. The supported BOM is flat and single-level: a menu directly references the raw materials required to produce it. Substitute ingredients, optional ingredients, automatic loss factors, and multi-level BOM structures are not included in the current scope. Material loss is not estimated or automatically added to recipe requirements; actual loss must be recorded through the production-waste workflow. |
| Answered by | Project developer/system analyst |
| Decision date | 2026-08-04 |
| Notes | “One active recipe” refers to the current operational recipe for a menu. Historical production records may still retain recipe snapshots captured when production starts, as defined in PD-RECIPE-001; therefore, changing the active recipe must not rewrite the recipe values used by earlier production runs. Advanced BOM capabilities are deferred rather than permanently rejected. If recipe variants, ingredient substitutions, optional toppings, semi-finished components, or multi-stage production are required later, they must be introduced through separate business and technical decisions. |

## 6. Distribution, sales, returns, and disposal

### PD-DISTRIBUTION-001 — Allocation, custody, and active-session meaning

| Field | Value |
| --- | --- |
| Decision ID | `PD-DISTRIBUTION-001` |
| Title | Allocation, custody, and active-session meaning |
| Status | `CONFIRMED` |
| Decision needed | Define whether allocation is transfer, dispatch, custody, consignment, or another event; when stock ownership/responsibility changes; and whether one active session per Armada is a strict invariant. |
| What is currently known from source | Allocation immediately decreases ready finished-good stock and creates a session; sold entry makes no further stock deduction. One active session is application-checked but not database-enforced. |
| What is known from history | Owner distribution and Armada sold/return responsibilities were intended; exact custody/accounting meaning and session invariant were not recorded. |
| Why it remains unresolved | The allocation, custody, and one-active-session business rule is recorded as confirmed, but collaborator rationale, database concurrency enforcement, and runtime exactly-once behavior remain unresolved. |
| Required decision maker or evidence source | Project/business owner and Armada operations representative, informed by the collaborator. |
| What work it blocks | Finished-goods movement, custody/loss responsibility, distribution procedure, sale timing, returns, and concurrency requirements. |
| Safe interim documentation treatment | Document the recorded custody timing and single-session rule; separately state the current source mechanics and that database-level enforcement, concurrency safety, and runtime behavior remain unverified. |
| Source references | `SQ-I-010`; `VR-P0-009`; `VR-P1-009`; `DC-U04`; `docs/reconciliation/CHAT-VS-REPOSITORY.md` (`DS-02`). |
| Proposed answer | Allocation represents the dispatch and custody transfer of finished goods from warehouse-ready stock to a specific Armada. The Owner creates the allocation. When the allocation is recorded and the goods are handed to the Armada, ready finished-goods stock decreases immediately and responsibility for the allocated goods transfers to that Armada. Allocation is not a sale. Recording a sold quantity reduces the quantity remaining in the Armada session but does not deduct warehouse-ready stock again. Unsold goods that remain suitable for sale are returned to ready finished-goods stock. Unsuitable goods are recorded as waste through the applicable return and waste procedure. Each Armada may have only one active distribution session at a time, and this invariant must be enforced at both application and database levels. |
| Final answer | Allocation is a transfer of physical custody from warehouse-ready stock to an Armada, not a completed sale. The Owner creates the allocation and hands the allocated finished goods to the Armada. At that point, the allocated quantity is removed from ready finished-goods stock and becomes the responsibility of the assigned Armada. Sales recorded by the Armada do not reduce warehouse stock again because the stock movement already occurred during allocation. The Armada records cumulative sold quantities and closes its distribution session. Unsold goods are handled by Produksi: goods that remain suitable for sale are returned to ready finished-goods stock, while goods that are no longer suitable are recorded as waste. Each Armada is limited to one active distribution session at a time. The one-active-session rule must be enforced by both application validation and a database-level concurrency safeguard. |
| Answered by | Project developer/system analyst |
| Decision date | 2026-08-04 |
| Notes | The operational flow is: ready finished-goods stock → Owner allocation → Armada custody → sold or returned → session closed. If the application does not provide a separate Armada receipt-confirmation action, creation of the allocation is treated as evidence that the goods were handed over and accepted. If receipt confirmation is introduced later, the exact stock-deduction and custody-transfer point should be reviewed. Armada records sold quantities and closes the session, while Produksi receives and classifies returned goods. Returned usable goods re-enter ready finished-goods stock; unsuitable returned goods must be routed to a traceable waste or disposal record. A user-interface or service-level check alone is insufficient to guarantee one active session because simultaneous requests could bypass it; database-level protection and transaction handling are required. |

### PD-SALES-001 — Sales, revenue, payment, and commission scope

| Field | Value |
| --- | --- |
| Decision ID | `PD-SALES-001` |
| Title | Sales, revenue, payment, and commission scope |
| Status | `PROVISIONAL` |
| Decision needed | Decide whether cumulative sold quantity is the complete current scope or whether immutable sale events, price snapshots, customers, payments, revenue, expenses, corrections, and commission are required. |
| What is currently known from source | Session items store mutable cumulative sold counts only; no sale/order/payment/customer/revenue/commission records or reports exist. |
| What is known from history | Sales income, popular menu, possible expenses, commission, and sales reports were repeatedly desired but later analytics were deferred. |
| Why it remains unresolved | Meaningful historical intent exists, while repository scope stops at operational counts and no current priority/financial contract is approved. |
| Required decision maker or evidence source | Business/finance owner and Armada operations representative. |
| What work it blocks | Sales module boundaries, revenue/commission documentation, price timing, correction rules, and sales reports. |
| Safe interim documentation treatment | Call current data cumulative sold quantities, not sales transactions, revenue, or accounting records. |
| Source references | `SQ-I-011`; `VR-P0-010`; `DC-C05`; `docs/reconciliation/CHAT-VS-REPOSITORY.md` (`DS-03`; `DS-04`). |
| Proposed answer | The current sales scope is limited to cumulative sold quantities for each menu item within an Armada distribution session. Individual customer purchases are not stored as immutable sale-event records. Armada records and updates the cumulative sold quantity while its session remains active. The current workflow does not store a sale-price snapshot, customer identity, payment method, revenue amount, or Armada commission. Recording sold quantities does not deduct ready warehouse stock again because the allocated goods already left ready stock when the distribution session was created. The cumulative sold quantities become operationally final when Armada ends the distribution session. Operating-expense functionality may be considered later but is not yet defined as part of the proposed current sales scope. |
| Final answer |  |
| Answered by |  |
| Decision date |  |
| Notes | Armada is responsible for recording and correcting cumulative sold quantities during the active distribution session. The correction policy after a session has been closed remains undefined and should not be inferred from the current mutable fields. Because sale prices are not snapshotted and revenue is not calculated, the current cumulative sold records cannot provide reliable historical revenue if menu prices change. Revenue, payment methods, customer records, commissions, financial expenses, profit calculations, and transaction-level sales history are outside the current operational sales scope and should be treated as deferred until separately approved. Menu-popularity reporting may still use cumulative sold quantities without introducing financial sales records. |

### PD-RETURN-001 — Return inspection and stock re-entry rules

| Field | Value |
| --- | --- |
| Decision ID | `PD-RETURN-001` |
| Title | Return inspection and stock re-entry rules |
| Status | `PROVISIONAL` |
| Decision needed | Define which returns require Produksi inspection, readiness criteria, expiry/timezone boundary, outcome taxonomy, and whether ready goods restore the original batch or create a distinct return batch/movement. |
| What is currently known from source | All positive unsold quantity creates pending checks; `ready` checks expiry and restores the original batch; `expired_damaged` creates a rejected batch. |
| What is known from history | Unsold goods may be reused if suitable, and collaborator flow introduced inspection, but temperature, time, seal, damage, and batch rules were not decided. |
| Why it remains unresolved | Source behavior does not establish approved food-safety/quality policy or batch traceability. |
| Required decision maker or evidence source | Business/quality owner and Produksi operational representative. |
| What work it blocks | Return SOP, finished-goods re-entry, expiry rules, status taxonomy, visibility, and return reporting. |
| Safe interim documentation treatment | Describe the two source outcomes and expiry check; do not equate them with an approved inspection standard. |
| Source references | `SQ-I-012`; `SQ-L-007`; `VR-P1-010`; `DC-U05`; `docs/reconciliation/CHAT-VS-REPOSITORY.md` (`RT-01`). |
| Proposed answer | Every positive unsold finished-goods return must be inspected by the Produksi team before it can re-enter ready stock. A returned item may be classified as ready only when it is linked to a valid finished-goods batch, has not reached its expiry boundary, and passes the applicable physical-condition inspection. No Owner approval is required for the normal inspection result. A ready return must restore quantity to the original finished-goods batch rather than creating a new production batch, thereby preserving its original production date, expiry date, menu identity, and traceability. A return that is expired or damaged must not re-enter ready stock and must be classified through the expired_damaged or rejected-stock path before being recorded as waste or disposal. Expiry is managed using a date-based rule rather than a separately entered expiry time. |
| Final answer |  |
| Answered by |  |
| Decision date |  |
| Notes | The returned quantity must never receive a new expiry period merely because it was returned. Restoring it to the original batch preserves its original age and expiry identity. The phrase “exists in finished-goods inventory and is not expired” is not yet a complete readiness standard; Produksi may also need to check whether the cup or packaging is intact, leaking, opened, contaminated, or visibly damaged. The current status taxonomy uses ready and expired_damaged, although reports may later distinguish expired waste from damaged waste. Because expiry is stored by date only, the system still needs one precise boundary rule: whether the product becomes expired at the beginning of the expiry date or after the expiry date ends in the application timezone. Until confirmed, documentation should not invent that boundary. |

### PD-RETURN-002 — Duplicate return processing behavior

| Field | Value |
| --- | --- |
| Decision ID | `PD-RETURN-002` |
| Title | Duplicate return processing behavior |
| Status | `NEEDS_RUNTIME_VERIFICATION` |
| Decision needed | Establish whether overlapping session-finish, return-inspection, or disposal requests can create duplicate checks, stock re-entry, rejected batches, or repeated effects. |
| What is currently known from source | Static tracing found checks outside or not repeated after locking and no database uniqueness for the implied one-to-one return check. These are plausible risks, not reproduced outcomes. |
| What is known from history | The lifecycle was described singularly; no concurrency/idempotency evidence or rule was recorded. |
| Why it remains unresolved | Only an isolated applied runtime/database check can establish actual interleaving effects. |
| Required decision maker or evidence source | Safe disposable runtime/database evidence reviewed by the technical collaborator and process owner. |
| What work it blocks | Claims of exactly-once return processing, concurrency guarantees, stock integrity, and return/disposal acceptance. |
| Safe interim documentation treatment | Label duplicate effects plausible and unverified; do not state that they occur or are prevented. |
| Source references | `SQ-I-012`; `VR-P0-011`; `DC-R02`; `docs/reconciliation/DECISION-CONFLICTS.md` (`DC-U05`; `DC-U06`). |
| Proposed answer | Return-related lifecycle actions must have exactly-once effects. Finishing a distribution session more than once must not create duplicate return checks. A return inspection may have only one final outcome: either ready or expired_damaged. Processing a ready return more than once must restore its quantity to the original finished-goods batch only once. Processing an expired or damaged return more than once must create only one rejected record or rejected batch. A disposal quantity may be applied only once and must never reduce rejected stock repeatedly. After the first successful action, the corresponding user-interface action is removed or disabled. Any repeated backend request must be rejected or return the already-recorded result without applying another stock or lifecycle mutation. |
| Final answer |  |
| Answered by |  |
| Decision date |  |
| Notes | The business rule is clear, but implementation safety remains unverified. Removing or hiding a button after the first action prevents ordinary repeated clicks but does not protect against browser retries, duplicated HTTP requests, stale pages, direct endpoint calls, or two requests processed concurrently. Runtime verification should test at least: finishing the same session twice, concurrently finishing one session, processing one return as ready twice, processing one return as expired or damaged twice, and submitting the same disposal twice. The database should enforce any one-to-one relationships where possible, while each mutation should run inside a transaction that rechecks the current status after acquiring the relevant lock. A successful test must confirm that duplicate requests do not create duplicate checks, duplicate rejected records, repeated stock re-entry, or repeated disposal deductions. |

### PD-DISPOSAL-001 — Durable disposal audit history

| Field | Value |
| --- | --- |
| Decision ID | `PD-DISPOSAL-001` |
| Title | Durable disposal audit history |
| Status | `PROVISIONAL` |
| Decision needed | Define required disposed quantity, reason, method, actor, timestamp, approval, evidence, retention, and correction/void behavior. |
| What is currently known from source | Disposal zeroes the rejected finished-good row and marks it empty; there is no standalone immutable disposal event. |
| What is known from history | Disposal appeared as a collaborator lifecycle step, but no audit-retention policy was documented. |
| Why it remains unresolved | Current mutation proves behavior but not the evidence stakeholders require after quantity is removed. |
| Required decision maker or evidence source | Business/quality owner, Produksi representative, and any accounting/compliance owner. |
| What work it blocks | Disposal procedure, finished-goods waste history, accountability, reports, and retention rules. |
| Safe interim documentation treatment | State that disposal is represented by destructive state mutation; do not call it a complete audit trail. |
| Source references | `SQ-I-013`; `VR-P0-012`; `DC-U06`; `docs/reconciliation/CHAT-VS-REPOSITORY.md` (`WA-02`; `RT-02`). |
| Proposed answer | Every disposal action must create a permanent and traceable disposal record. The record must preserve the disposed quantity, disposal reason, disposal method, responsible Produksi user, and automatic disposal date and time. Produksi may perform the disposal without routine Owner approval. Photo evidence is optional and may be attached when useful. Disposal history must not be deleted. After disposal, the original rejected finished-goods record must remain available for historical traceability, while its available rejected quantity may become zero and its lifecycle status must indicate that it has been disposed of or emptied. |
| Final answer |  |
| Answered by |  |
| Decision date |  |
| Notes | The current source appears to represent disposal mainly by reducing the rejected quantity to zero and changing its state. This is not sufficient as a durable audit history because the original disposed quantity, method, reason, actor, and time may no longer be independently reportable. A separate immutable disposal-event record should therefore be linked to the rejected batch or return-inspection result. The rejected record must not be deleted after disposal. Optional photo evidence should be stored as a reference or attachment linked to the disposal event rather than replacing the structured disposal fields. The correction or void policy remains unresolved. Until it is defined, documentation must not claim that an incorrect disposal can be edited, reversed, or restored through ordinary stock adjustment. |

## 7. ROP and MRP scope

### PD-PLANNING-001 — ROP terminology and future formula

| Field | Value |
| --- | --- |
| Decision ID | `PD-PLANNING-001` |
| Title | ROP terminology and future formula |
| Status | `PROVISIONAL` |
| Decision needed | Confirm the current feature name and decide whether future ROP means average usage × lead time + safety stock or another approved formula, including data history and ownership. |
| What is currently known from source | The application has `minimum_stock` and several static health/attention thresholds; it has no demand rate, lead time, safety stock, supplier, reorder recommendation, or ROP report. |
| What is known from history | History explicitly distinguished minimum-stock alerts from true ROP and proposed phased data collection before a formula. |
| Why it remains unresolved | Current terminology and future calculation/phase exit criteria have not been approved by a named owner. |
| Required decision maker or evidence source | Project owner/business analyst and inventory/procurement owner. |
| What work it blocks | Canonical ROP terminology, alert documentation, data collection, future formula, procurement roadmap, and acceptance criteria. |
| Safe interim documentation treatment | Call current behavior minimum-stock or stock-health alerts; describe full ROP as undecided future scope. |
| Source references | `SQ-I-014`; `VR-P3-001`; `DC-S01`; `docs/reconciliation/CHAT-VS-REPOSITORY.md` (`PL-01`). |
| Proposed answer | The current application feature must not yet be described as a complete Reorder Point or ROP feature. The existing table status is provisionally treated as a minimum-stock status or stock-health indicator based on the material’s configured minimum_stock value. A full ROP capability is proposed as a future planning feature. Its provisional calculation model is average material usage during lead time + safety stock, but the final formula and configuration rules still require confirmation. Average usage should be derived from actual raw-material consumption recorded for production, together with qualifying production waste or damaged-material deductions. Restock quantities and ordinary stock adjustments must not be treated as material usage. Historical information should be available for weekly, monthly, and yearly analysis, although the exact rolling period used by the ROP calculation has not yet been selected. Lead time represents the period between initiating a purchase or reorder and the material becoming available for operational use. Its final ownership and whether it is configured per raw material or per supplier remain undecided. In the initial phase, the configured minimum_stock may provisionally serve as the safety-stock baseline. The safety-stock calculation may later be developed using historical usage trends and variability. The Owner is responsible for reviewing and managing ROP-related settings and recommendations. |
| Final answer |  |
| Answered by |  |
| Decision date |  |
| Notes | The existing status displayed in the raw-material table is not evidence that the application currently implements ROP. Until average usage, lead time, safety stock, and reorder recommendations are implemented, documentation should refer to the feature as a minimum-stock status, stock-health status, or another neutral operational label rather than ROP. Average usage should be calculated from base-unit raw-material movements, not directly from the number of finished cups produced, although finished production quantities may indirectly determine recipe-based material consumption. Waste and damaged-material quantities should only be included when they represent genuine operational consumption; adjustment records used to correct stock discrepancies should be excluded. Weekly, monthly, and yearly periods may be useful as reporting views, but the exact calculation window for ROP remains unresolved. Lead time still requires a business rule, including whether it is maintained for each material, supplier, or purchasing condition. Using minimum_stock as an initial safety-stock baseline is provisional and should not be represented as a trend-based automatic calculation. Future trend-based safety stock requires sufficient reliable production-consumption and waste history. |

### PD-PLANNING-002 — MRP and reservation scope

| Field | Value |
| --- | --- |
| Decision ID | `PD-PLANNING-002` |
| Title | MRP and reservation scope |
| Status | `PROVISIONAL` |
| Decision needed | Decide whether MRP stops at advisory current-BOM requirement/reservation visibility or must include persisted/binding reservations, net requirements, time buckets, receipts, lead time, lot sizing, and procurement release. |
| What is currently known from source | Planned/processing current-BOM demand is calculated in memory and subtracted for displayed availability; it is not persisted, time-phased, procurement-linked, or enforced on plan writes. |
| What is known from history | Simplified MRP was accepted as phased direction; industrial/time-phased planning and purchase recommendations were deferred. |
| Why it remains unresolved | “MRP” can refer to multiple scopes, and neither present naming nor target phase has a named approval. |
| Required decision maker or evidence source | Project owner/business analyst and production/inventory planner. |
| What work it blocks | Canonical MRP description, reservation guarantees, planning procedures, procurement scope, and roadmap. |
| Safe interim documentation treatment | Use “advisory current-BOM requirement/reservation visibility,” not complete MRP/procurement planning. |
| Source references | `SQ-I-014`; `VR-P3-002`; `DC-M03`; `DC-M05`; `docs/reconciliation/CHAT-VS-REPOSITORY.md` (`PL-02`). |
| Proposed answer | The current planning scope does not require persisted or binding stock reservations. When the Owner creates a production plan, the system must calculate the raw-material requirements from the menu’s applicable recipe and target quantity and compare them with the available raw-material stock. A production plan must be rejected when the available stock is insufficient to satisfy its material requirements. No independent reserved-stock balance is maintained for each plan. The current scope does not require time buckets, scheduled incoming restocks, automated purchase recommendations, procurement releases, or lot-sizing methods. Purchasing decisions remain the responsibility of the Owner. The current feature should therefore be described as a production material-requirement and stock-availability check rather than complete MRP. |
| Final answer |  |
| Answered by |  |
| Decision date |  |
| Notes | The proposed business direction is that an insufficient-stock production plan must not be accepted. This would be a binding production-plan validation rule if confirmed, even though the system does not maintain persisted stock reservations. Because multiple open plans could collectively require more stock than is physically available, the project must later verify whether the availability calculation considers requirements from other planned and processing plans. Net requirement, time-phased planning, incoming purchase receipts, lead-time planning, lot sizing, and automatic procurement recommendations are deferred. If these capabilities are introduced later, they should be treated as a separate expanded MRP phase. The audited source currently appears to calculate demand mainly for display and may not enforce the rule consistently when a plan is created; this remains an implementation and runtime-verification gap. |

### PD-PLANNING-003 — Advanced planning and commercial analytics roadmap

| Field | Value |
| --- | --- |
| Decision ID | `PD-PLANNING-003` |
| Title | Advanced planning and commercial analytics roadmap |
| Status | `DEFERRED` |
| Decision needed | Later triage purchase recommendations, weekly/monthly forecasts, coverage, EOQ/lot sizing, production recommendation, commission, and finance analytics as accepted, rejected, or out of scope. |
| What is currently known from source | Required planning, supplier, economic, and financial event data/workflows are absent or incomplete. |
| What is known from history | These ideas appeared in phased roadmaps and were explicitly deferred until reliable operational history and core modules existed. |
| Why it remains unresolved | They are historical plans, not approved current requirements, and depend on unresolved core data decisions. |
| Required decision maker or evidence source | Project owner after stock, consumption, yield, sales, and ROP/MRP baselines are stable. |
| What work it blocks | Future roadmap only; it does not block documentation of observable current implementation. |
| Safe interim documentation treatment | Keep these items under deferred/future options and do not promise delivery. |
| Source references | `SQ-O-003`; `SQ-O-004`; `VR-P3-003`; `DC-M05`; `docs/reconciliation/CHAT-VS-REPOSITORY.md` (`PL-03`; `DS-04`). |
| Proposed answer | Advanced planning and commercial analytics are deferred and are not treated as part of the current implementation scope. Purchase recommendations, weekly and monthly material forecasts, stock-coverage analysis, EOQ or other lot-sizing methods, and automated production recommendations may be reconsidered only after the system has accumulated reliable operational history for raw-material consumption, production waste, stock corrections, purchasing prices, and supplier lead times. Commercial analytics such as revenue, operating expenses, Armada commission, margin, and profit are also deferred because the current sales workflow records cumulative sold quantities rather than complete financial sale transactions. No deferred feature should be presented as committed delivery, an implemented capability, or a current acceptance requirement. Once the core inventory, production, finished-goods, distribution, return, sales-count, ROP, and material-requirement baselines are stable, the Owner must review each roadmap item individually and classify it as accepted future scope, rejected, or outside the project scope. |
| Final answer |  |
| Answered by |  |
| Decision date |  |
| Notes | Historical discussions included weekly and monthly material requirements, forecasting, purchase recommendations, possible EOQ or lot-sizing methods, production recommendations, sales reporting, expenses, revenue, and Armada commission. These discussions represent roadmap ideas rather than approved current requirements. The repository does not currently contain the reliable data and workflows required for those capabilities, including complete production-consumption history, supplier and lead-time data, safety-stock calculations, scheduled purchase receipts, procurement records, transaction-level sales, price snapshots, payment records, expense records, or commission calculations. Forecasting and procurement recommendations depend on stable stock, consumption, waste, recipe-snapshot, ROP, and purchasing data. Commercial analytics depend on a separately approved financial-sales scope. This decision should remain DEFERRED; Final answer, Answered by, and Decision date should remain blank until the Owner formally reviews the future roadmap. Deferral does not mean the ideas are rejected—it means they are intentionally excluded from the current phase and must not block documentation or completion of the existing system. |

## 8. Runtime, database, and deployment evidence

### PD-RUNTIME-001 — Database engine and applied schema baseline

| Field | Value |
| --- | --- |
| Decision ID | `PD-RUNTIME-001` |
| Title | Database engine and applied schema baseline |
| Status | `NEEDS_RUNTIME_VERIFICATION` |
| Decision needed | Establish the actual deployed/review database engine/version, applied migrations, schema/constraint drift, foreign-key/isolation settings, and supported fresh/upgrade baseline. |
| What is currently known from source | Tests configure SQLite; queries contain MySQL-specific SQL; the migration chain has static inconsistencies. Current deployed engine/schema is unknown. |
| What is known from history | MySQL was selected historically, while collaborator handovers preserve successive code states but not a definitive deployment/migration history. |
| Why it remains unresolved | Static documents cannot establish an applied database or migration table. |
| Required decision maker or evidence source | Approved read-only schema/migration evidence or disposable clone, reviewed by the technical/database owner. |
| What work it blocks | Canonical data model, supported database matrix, installation/upgrade guidance, migration-risk disposition, and reliable report/runtime claims. |
| Safe interim documentation treatment | State the audited migration schema and runtime database as unknown; do not call either MySQL deployment or SQLite compatibility verified. |
| Source references | `SQ-I-015`; `VR-P0-013`; `VR-P2-001`; `VR-P3-006`; `DC-R03`. |
| Proposed answer | MySQL is the historically selected and provisionally proposed operational database engine for StockPlan Kanawa Express. SQLite may remain in the automated-test configuration, but it is not currently proposed as a supported operational database because parts of the application use MySQL-specific behavior. All project migrations are reported as applied. A clean installation using php artisan migrate:fresh --seed and an upgrade using php artisan migrate are reported to have succeeded, and foreign-key constraints are reported as active and applied. The proposed baseline would therefore support MySQL for both fresh installation and migration-based upgrades, subject to verification. The exact proposed MySQL version range must be recorded after the active server version is checked. |
| Final answer |  |
| Answered by |  |
| Decision date |  |
| Notes | The exact MySQL version is currently unknown and must be captured using SELECT VERSION(). The table storage engine is also unconfirmed and should be checked, preferably confirming that transactional tables use InnoDB. The transaction isolation level, SQL mode, character set, and collation have not yet been recorded. The successful migration results are based on the project developer’s runtime report; command output or read-only database evidence should be preserved before closing this decision. Verification should include php artisan migrate:status, the contents of the migrations table, SHOW CREATE TABLE for critical tables, foreign-key definitions, storage-engine information, and comparison of the live schema against the migration chain. The production .env or database configuration must not be copied into documentation with credentials. MySQL is the only operational database currently proposed for support at this stage; SQLite compatibility remains unverified and should be treated only as a test-environment configuration. |

### PD-RUNTIME-002 — Automated-test baseline

| Field | Value |
| --- | --- |
| Decision ID | `PD-RUNTIME-002` |
| Title | Automated-test baseline |
| Status | `NEEDS_RUNTIME_VERIFICATION` |
| Decision needed | Establish the unchanged suite result in an isolated locked environment and define supported test engine/coverage expectations. |
| What is currently known from source | Auth/profile/example tests exist; operational modules lack automated tests; one example assertion conflicts with the `/` redirect. The audit did not run tests. |
| What is known from history | Team guidance said to test before review, but no repeatable test result or domain strategy was recorded. |
| Why it remains unresolved | Test presence and static mismatch do not establish actual suite results. |
| Required decision maker or evidence source | Future isolated unchanged test output, reviewed by the technical maintainer; no execution is authorized by this document. |
| What work it blocks | Passing-test claims, verified module status, CI guidance, and release confidence statements. |
| Safe interim documentation treatment | Report tests present/not run, domain coverage absent, and known source mismatch; do not report pass/fail. |
| Source references | `VR-P2-002`; `DC-C06`; `docs/reconciliation/CHAT-VS-REPOSITORY.md` (`TD-01`). |
| Proposed answer | No verified automated-test baseline currently exists for the project. The repository contains automated-test files, but the project developer does not have a preserved or repeatable record showing that the unchanged full test suite has been executed successfully. No automated tests are currently known to cover the main operational modules, including raw materials, recipes, production, finished goods, distribution, returns, disposal, and planning. The database engine and database-isolation behavior used by the test suite have not been personally verified by the project developer. Therefore, no claim should be made that the current suite passes, that operational workflows are covered, or that SQLite and MySQL behave equivalently under testing. A valid baseline must be established by executing the unchanged repository test suite in an isolated environment, recording the exact source commit, runtime versions, test database engine, configuration, command, passed and failed tests, and failure output. |
| Final answer |  |
| Answered by |  |
| Decision date |  |
| Notes | Static audit identified authentication, profile, and example test files, but file presence does not prove that those tests currently pass. The audit also found an apparent mismatch between an example assertion and the application’s / redirect behavior, which must be confirmed through execution rather than assumed to fail. The project developer has not knowingly performed database-specific automated testing, and no repeatable pre-review test procedure or CI result is available. Current documentation must therefore say tests present, runtime result unverified, operational-domain coverage absent or unconfirmed. Runtime verification should first run the test suite without modifying the source so the repository’s actual baseline is preserved. The result should record the commit hash, PHP version, Laravel version, dependency lock state, test command, database driver, total tests and assertions, failures, errors, skipped tests, and execution date. Any subsequent test fixes or new domain tests should be documented separately from the initial unchanged baseline. CI status remains unknown and should not be described as implemented until repository workflow files and actual CI runs are inspected. |

### PD-RUNTIME-003 — Expiry scheduler policy and operation

| Field | Value |
| --- | --- |
| Decision ID | `PD-RUNTIME-003` |
| Title | Expiry scheduler policy and operation |
| Status | `NEEDS_RUNTIME_VERIFICATION` |
| Decision needed | Confirm expiry boundary/timezone and intended cadence/owner, then establish whether the deployed scheduler actually performs the transition and how failures are monitored. |
| What is currently known from source | The command expires available rows after the expiry date and is scheduled every minute despite a daily comment. Scheduler operation was not observed. |
| What is known from history | Chilled reuse was intended, but expiry boundary, cadence, and scheduler operations were not defined. |
| Why it remains unresolved | Business timing and external process operation both lack evidence. |
| Required decision maker or evidence source | Business/quality owner for the rule; deployment operator plus safe review-environment observation for operation. |
| What work it blocks | Automatic-expiry claims, finished-goods availability, scheduler runbook, and date-boundary documentation. |
| Safe interim documentation treatment | State source scheduling and expiry condition only; mark actual automation and approved cadence unknown. |
| Source references | `SQ-L-005`; `VR-P1-011`; `VR-P2-003`; `DC-U03`; `DC-R04`. |
| Proposed answer | Finished-goods expiry is governed by a date-based rule using the application timezone Asia/Jakarta. A batch remains usable throughout its recorded expiry date and becomes expired at the beginning of the following calendar day. For example, a batch with an expiry date of 2026-08-05 remains eligible through August 5, 2026, and becomes expired at 2026-08-06 00:00 WIB. Once expired, the batch must no longer be available for new allocation or sale as ready finished goods, but its historical production and stock records must remain preserved. Because the rule is date-based, the intended scheduler cadence should be daily shortly after midnight rather than every minute. The business or quality Owner defines the expiry policy, the deployment or technical operator is responsible for ensuring the scheduler runs, and Produksi is responsible for physically handling expired goods through the rejected, waste, or disposal workflow. |
| Final answer |  |
| Answered by |  |
| Decision date |  |
| Notes | The source currently schedules the expiry command every minute even though an adjacent comment reportedly describes a daily schedule. This discrepancy is an implementation detail and does not establish the approved cadence. A daily schedule shortly after midnight in Asia/Jakarta is provisionally sufficient for a date-only expiry policy. Actual scheduler operation remains unverified: static source confirms that a command and schedule definition exist, but it does not prove that the server cron entry, scheduler worker, or deployment process is active. Runtime verification should use a safe test batch and confirm that it changes from available to expired after the configured boundary, cannot be allocated afterward, and retains its historical batch record. Verification should also preserve scheduler or command logs and confirm how failures are surfaced. As a defensive measure, finished-goods availability queries should also exclude batches whose expiry date has passed, so a scheduler delay does not allow expired stock to remain operationally selectable. The final scheduler cadence and monitoring mechanism should not be marked confirmed until the deployed runtime is inspected. |

### PD-RUNTIME-004 — Build, browser, AJAX, export, and platform evidence

| Field | Value |
| --- | --- |
| Decision ID | `PD-RUNTIME-004` |
| Title | Build, browser, AJAX, export, and platform evidence |
| Status | `NEEDS_RUNTIME_VERIFICATION` |
| Decision needed | Establish supported PHP/Node/platform/browser assumptions and verify locked build assets, layouts/toasts, report AJAX, Armada polling, XLSX output, and case-sensitive menu views in an isolated environment. |
| What is currently known from source | Wiring exists, but the audit found missing build/environment artifacts, UI error-path gaps, and a case mismatch; no build, browser, polling, AJAX, or export was executed. |
| What is known from history | Several UI/report areas were reported complete, while history itself warns that checkpoint labels are not runtime proof. |
| Why it remains unresolved | Static source presence cannot establish user-visible or platform-dependent behavior. |
| Required decision maker or evidence source | Safe runtime/build/browser/export evidence reviewed by the technical collaborator and deployment owner. |
| What work it blocks | Setup/deployment documentation, supported UI/report/export claims, and portability status. |
| Safe interim documentation treatment | Use “source-wired; not runtime-verified” and preserve known static gaps. |
| Source references | `VR-P2-004`; `VR-P2-005`; `VR-P2-008`; `VR-P2-009`; `DC-R05`; `docs/reconciliation/CHAT-VS-REPOSITORY.md` (`TD-03`; `TD-04`). |
| Proposed answer | The currently reported development environment is Windows with PHP 8.3.21, Node.js 20.19.2, and Google Chrome as the primary browser. composer install and npm install are reported to have completed successfully. The Vite development environment is reported to have run through npm run dev, with application layouts and toast notifications observed working. Report AJAX behavior and Armada polling are also reported as observed in the browser. XLSX export is reported to have produced a file that could be opened, and package-lock.json is reported as present. Google Chrome on Windows is therefore the provisionally proposed browser and platform combination for current development use. A separate successful execution of npm run build is still required before the production frontend build can be declared verified. |
| Final answer |  |
| Answered by |  |
| Decision date |  |
| Notes | npm run dev verifies the Vite development server but does not prove that npm run build successfully generates production assets. Production-build verification must run npm run build separately and confirm that Laravel loads the generated manifest and assets without missing-file or stale-asset errors. The existence and committed status of composer.lock must be checked directly; it should not be assumed from a successful composer install. package-lock.json is reported as present and should also be confirmed as committed. Layouts, toast notifications, report AJAX, Armada polling, and XLSX export have been observed working, but the tested routes, roles, browser version, console errors, failed-request behavior, and source commit have not yet been preserved as repeatable evidence. XLSX evidence should confirm expected rows, columns, values, and formatting in addition to the file opening successfully. The suspected capitalization mismatch remains unverified because Windows commonly uses a case-insensitive filesystem. It must be checked on Linux or another case-sensitive filesystem before Linux deployment compatibility is claimed. Until those checks are completed, the available runtime report is limited to the observed Windows development environment using Chrome. |

### PD-RUNTIME-005 — Migration and legacy-state rationale

| Field | Value |
| --- | --- |
| Decision ID | `PD-RUNTIME-005` |
| Title | Migration and legacy-state rationale |
| Status | `NEEDS_COLLABORATOR_CONFIRMATION` |
| Decision needed | Explain the `wasted_quantity` replacement, duplicate `allocated_by` migration, legacy `done` status references, expected upgrade/rollback paths, and any deployed baselines. |
| What is currently known from source | The audit verified stale model/migration attributes, asymmetric rollback, duplicate column migration history, and mixed status strings. |
| What is known from history | Collaborator code arrived through successive handovers without full migration rationale. |
| Why it remains unresolved | Intent and deployed sequence cannot be reconstructed safely from fresh-source migration files alone. |
| Required decision maker or evidence source | Technical collaborator, corroborated later by read-only migration/schema evidence. |
| What work it blocks | Migration-history documentation, upgrade/rollback support, legacy-data interpretation, and disposition of scaffolded fields/statuses. |
| Safe interim documentation treatment | Record the exact static inconsistencies and leave intent/deployment compatibility unknown. |
| Source references | `SQ-L-012`; `VR-P2-010`; `DC-R03`. |
| Proposed answer | Static source evidence indicates that production_items.wasted_quantity was replaced by the separate production_wastes structure. The likely intent was to move from a single waste quantity stored on a production item to more explicit waste records that can represent raw material, quantity, and related production context. However, this rationale has not been confirmed by the technical collaborator. The replacement migration does not restore wasted_quantity in its rollback path, so rollback to the previous schema is currently asymmetric and must not be described as supported without verification. The additional allocated_by migration appears to be a compatibility migration for an earlier database baseline in which the column may not have existed, while the current session-creation migration already contains the column for fresh installations. This interpretation remains unconfirmed because the deployed migration sequence is unknown. Legacy done status references should provisionally be treated as compatibility handling for an earlier production state, but it is not yet known whether deployed data contains that status or whether it should be migrated to the current source-declared completed status. Fresh migration and forward upgrade have reportedly succeeded in the current development environment, but rollback compatibility and legacy-data migration remain unverified. |
| Final answer |  |
| Answered by |  |
| Decision date |  |
| Notes | This decision requires collaborator confirmation because the intended migration history cannot be reconstructed safely from the current migration files alone. The audit found three separate concerns. First, wasted_quantity was added to production_items and later removed when production_wastes was introduced, but the reverse migration does not recreate the removed column. Second, allocated_by exists in the current Armada-session creation migration and is also handled by a later add-column migration; the later migration may have been needed for an older deployed database, but its rollback can remove a column that a fresh schema considers part of the original table. Third, some views recognize a legacy done status even though the current declared lifecycle uses other status values. The collaborator should identify the database baseline used when each migration was created, whether the project officially supports rollback or forward-only migrations, whether old databases or production data contain wasted_quantity, lack allocated_by, or use done, and whether explicit data-conversion migrations are required. Until that evidence is available, preserve the exact inconsistencies, avoid deleting apparently redundant migrations, and do not claim that rollback or legacy upgrade compatibility is safe. |

## 9. Deferred module-specific decisions

### PD-DEFERRED-001 — Single-site versus future multi-location or multi-tenant scope

| Field | Value |
| --- | --- |
| Decision ID | `PD-DEFERRED-001` |
| Title | Single-site versus future multi-location or multi-tenant scope |
| Status | `DEFERRED` |
| Decision needed | Later confirm whether the current single shared inventory remains the product boundary or what explicit trigger would justify location/tenant isolation. |
| What is currently known from source | No tenant, site, warehouse, or location partition is present in the audited domain data. |
| What is known from history | Phase 1 explicitly chose one business/single tenant; multi-tenant SaaS was a future possibility, not an immediate requirement. |
| Why it remains unresolved | Present scope is aligned, but no future trigger or multi-location business need is approved. |
| Required decision maker or evidence source | Project owner when a real multi-location/tenant requirement exists. |
| What work it blocks | Future architecture roadmap only; it does not block source-grounded current documentation if the single shared scope is stated. |
| Safe interim documentation treatment | Describe one shared operational dataset and mark multi-location/multi-tenant as deferred. |
| Source references | `SQ-O-001`; `VR-P3-004`; `docs/reconciliation/CHAT-VS-REPOSITORY.md` (`BS-02`). |
| Proposed answer | The current StockPlan Kanawa Express scope supports one business operating through one shared operational dataset and one shared inventory boundary. Raw materials, production plans, finished-goods batches, Armada allocations, returns, waste, and reports are not separated by tenant, branch, warehouse, or operating location. The current system should provisionally be documented as using one shared, unpartitioned operational dataset. Multi-location inventory and multi-tenant SaaS capabilities are deferred and are not part of the current functional or architectural requirements. They should be reconsidered only when Kanawa Express has a real operational need to maintain independently controlled stock, production, users, transactions, or reports for more than one location or business entity. |
| Final answer |  |
| Answered by |  |
| Decision date |  |
| Notes | Project history explicitly selected one Kanawa Express business and one shared inventory for the initial project phase. The audited source is consistent with this boundary because domain records do not contain tenant_id, site_id, location_id, warehouse_id, or an equivalent partition key. Multi-location and multi-tenant concepts appeared only as possible future developments and were not approved as current requirements. A future review may be triggered when the business opens an additional warehouse, production site, branch, or independently managed operating unit that requires separate stock balances, production planning, access permissions, transfers, and reports. Multi-tenant scope would require an even stronger trigger, such as offering the application to separate companies whose data must be isolated. These triggers are roadmap guidance inferred from the meaning of multi-location and multi-tenant architecture; they are not yet approved business commitments. Until such a requirement exists, current documentation should not treat tenant or location complexity as committed scope and should consistently describe a single shared operational dataset. |

### PD-DEFERRED-002 — Incomplete routes, navigation, placeholders, and unused artifacts

| Field | Value |
| --- | --- |
| Decision ID | `PD-DEFERRED-002` |
| Title | Incomplete routes, navigation, placeholders, and unused artifacts |
| Status | `NEEDS_COLLABORATOR_CONFIRMATION` |
| Decision needed | Classify empty/missing resource actions, commented production-create view, mobile-menu omissions, hard-coded Armada administration controls, and unused frontend artifacts as deliberate, superseded, planned, or abandoned. |
| What is currently known from source | The audit lists incomplete resource surfaces, disconnected UI controls/views, and installed/imported artifacts without active use. |
| What is known from history | Sidebar/UI alternatives evolved and collaborator implementation context was incomplete; no final disposition list exists. |
| Why it remains unresolved | Static non-use cannot determine whether an artifact is future work, retained scaffolding, or obsolete. |
| Required decision maker or evidence source | Technical/frontend collaborator, with owner confirmation only where menu/module scope changes. |
| What work it blocks | Accurate route/module status, navigation documentation, contributor context, and future cleanup prioritization. |
| Safe interim documentation treatment | Mark each listed artifact incomplete or unused; do not infer removal or future implementation. |
| Source references | `SQ-L-010`; `SQ-L-011`; `SQ-O-006`; `VR-P1-013`; `VR-P3-007`; `DC-S02`. |
| Proposed answer | Incomplete routes, disconnected views, navigation omissions, hard-coded controls, and unused frontend artifacts must be reviewed individually before they are implemented or removed. Empty or missing resource actions should provisionally be classified as incomplete scaffolding unless the collaborator confirms that the related operation is intentionally unsupported or has been superseded by another workflow. The commented production-create view may represent an earlier or incomplete approach to production planning; the current source-visible role split is that the Owner creates production plans and Produksi executes them, but the collaborator must confirm whether that view was replaced, deferred, or abandoned. Mobile navigation should ultimately provide access to all active functions permitted for the signed-in role, unless a specific mobile limitation is deliberately approved. Armada administration and operational controls should use database-backed Armada and account data rather than permanent hard-coded frontend values; any hard-coded values should be treated as development placeholders until their intent is confirmed. Installed, imported, or retained frontend artifacts with no active usage must not be removed solely from static non-use evidence. Each artifact must be classified as planned, superseded, abandoned, retained scaffolding, or intentionally unused after collaborator review. |
| Final answer |  |
| Answered by |  |
| Decision date |  |
| Notes | Static source analysis can identify empty actions, disconnected views, commented code, hard-coded values, missing mobile links, and unused imports or packages, but it cannot determine the original developer’s intent. Project history shows that sidebar structures, user-interface approaches, and collaborator workflows changed through multiple iterations and handovers. Therefore, an apparently unused artifact may be an unfinished feature, compatibility remnant, abandoned experiment, or future placeholder. The collaborator should provide a disposition for each audited item and identify any replacement route, view, component, or workflow. Owner confirmation is only required when the disposition changes the approved business module scope, such as permanently removing a production, inventory, or distribution capability. Until reviewed, documentation should label these items incomplete, disconnected, hard-coded, or unused, without promising future implementation or declaring them safe to delete. Cleanup should be performed in a separate technical branch after the classifications are recorded and relevant routes, builds, and workflows are regression-tested. |

## Editing Rule

Fill only the blank response fields when evidence or a human answer is actually available. A proposed answer is not final. A final answer does not become `CONFIRMED` until the approval and traceability rules in `docs/decisions/DECISION-LOG.md` are satisfied.
