# Canonical Documentation Plan

## Purpose

This plan defines how to move from static audit evidence and noncanonical conversation history to a reviewed canonical documentation set. It does not create the canonical documents, resolve stakeholder questions, or authorize application changes.

The existing sources have different authority:

1. `docs/audit/` describes the audited repository snapshot and explicitly separates source evidence from runtime unknowns.
2. `docs/history/` preserves intent, alternatives, reported outcomes, and superseded plans; it is not proof of current behavior or approval.
3. `docs/reconciliation/VERIFICATION-REGISTER.md` records the evidence gaps and safe next verification.
4. `docs/reconciliation/STAKEHOLDER-QUESTIONS-PRIORITY.md` will hold human decisions after its blank fields are answered.
5. `docs/canonical/` becomes authoritative only after its contents are reviewed and assigned owners.

## Canonical documentation rules

- Do not copy a historical “DONE” or “complete” label into canonical current state without source and, where required, runtime evidence.
- Do not convert a current source behavior into an approved business rule without a named decision owner.
- Use only these reconciliation classifications when a canonical document compares evidence: `ALIGNED`, `PARTIALLY_ALIGNED`, `IMPLEMENTED_BUT_UNDOCUMENTED`, `INTENDED_NOT_IMPLEMENTED`, `HISTORICAL_PLAN_ONLY`, `SUPERSEDED_HISTORY`, `IMPLEMENTATION_CONFLICT`, `BUSINESS_DECISION_REQUIRED`, `RUNTIME_VERIFICATION_REQUIRED`, and `UNKNOWN`.
- Cite repository-relative files, decision IDs, and verification IDs.
- State the source baseline/commit and the latest runtime-evidence date on every canonical document that depends on code behavior.
- Keep current state, approved rules, known issues, and future roadmap in separate sections/files.
- Preserve `docs/audit/` and `docs/history/` as evidence. Canonical documents may supersede conclusions, but should not rewrite the evidence record.
- `AGENTS.md` must not be created until critical operational context is stable; premature agent instructions would amplify unresolved assumptions.

## Proposed canonical file set

### Entry points and governance

| Path | Purpose | Audience | Primary sources | Required conflicts or decisions | Safe to create now? | Blocked information | Owner | Update triggers |
|---|---|---|---|---|---|---|---|---|
| `docs/canonical/README.md` | Index the canonical set, define authority/evidence rules, ownership, versioning, and navigation. | All contributors, reviewers, stakeholders | `docs/audit/AUDIT-METADATA.md`; `docs/history/README.md`; this reconciliation plan | VR-P2-011 source baseline; SQ-I-001 decision authority | Yes, as a governance/index skeleton only | Named document owners, approval workflow, effective canonical date | Documentation owner with product/technical approval | Canonical file added/renamed; owner changes; new approved evidence baseline |
| `docs/canonical/DECISION-LOG.md` | Record approved decisions with owner, date, effective version, evidence, alternatives, exceptions, and supersession links. | Product owner, technical lead, collaborators, auditors | Blank answers in `docs/reconciliation/STAKEHOLDER-QUESTIONS-PRIORITY.md`; verification evidence | SQ-I-001 first; then every answered SQ/VR item | Yes, template only; no decision entries should be invented | Named approvers and first signed decisions | Product owner for business entries; technical lead for technical entries | Any stakeholder decision, reversal, exception, or superseding implementation |
| `docs/canonical/MODULE-STATUS.md` | Give an evidence-dated module status without mixing source presence, runtime result, and approved scope. | Project owner, team leads, reviewers | `docs/audit/MODULE-STATUS.md`; verification register/results | VR-P2-002; VR-P2-004; VR-P2-011; module-specific runtime checks | Partial: source-state column can be drafted; runtime/acceptance must remain pending | Test/build/runtime results and accepted scope | Technical lead | Merge changing module behavior; verification result; scope decision; release |
| `docs/canonical/KNOWN-ISSUES.md` | Track accepted defects/risks with severity, evidence, owner, disposition, and linkage to decisions—not every speculative audit finding. | Technical lead, product owner, maintainers | `docs/audit/KNOWN-RISKS.md`; verification results; decision log | P0 results and triage ownership; distinguish reproduced from inferred | Partial: evidence-backed intake can be drafted | Severity/acceptance owner, reproduced results, remediation authorization | Technical lead with product owner for business impact | Reproduction, fix, accepted risk, severity/owner change, release |

### Product and current-state documents

| Path | Purpose | Audience | Primary sources | Required conflicts or decisions | Safe to create now? | Blocked information | Owner | Update triggers |
|---|---|---|---|---|---|---|---|---|
| `docs/canonical/PROJECT-OVERVIEW.md` | Explain product purpose, users, scope boundaries, major workflows, and what the system is/not. | Stakeholders, new contributors, evaluators | Audited current implementation; histories 01/04 for context; approved decisions | SQ-I-001; SQ-I-010; SQ-I-011; SQ-I-014; SQ-I-015; SQ-O-001 | No final version; a clearly noncanonical outline is possible | Accepted business scope, sales boundary, ROP/MRP wording, deployment scope | Product owner | Product scope decision; major module acceptance; phase/release change |
| `docs/canonical/CURRENT-SYSTEM-STATE.md` | Describe only the observable implementation and separately list runtime evidence/status. | Developers, reviewers, operations | `docs/audit/CURRENT-IMPLEMENTATION.md`; `docs/audit/ROUTE-ROLE-MATRIX.md`; `docs/audit/DATA-MODEL-AUDIT.md`; completed verification | VR-P2-011 source baseline; VR-P0-013 schema baseline; relevant runtime checks | Partial: source-only edition can be drafted with explicit audit baseline | Current HEAD drift, deployed schema, test/build/runtime results | Technical lead | Code/migration/route merge; runtime verification; audited baseline changes |
| `docs/canonical/BUSINESS-PROCESS.md` | Define approved end-to-end processes from stock receipt through production, dispatch, sales counts, returns, and disposal. | Owner, Produksi, Armada, operations, developers | Approved SQ answers; decision log; module evidence; safe runtime observations | SQ-I-004 through SQ-I-013; VR-P0-002 through VR-P0-012; VR-P1-004 through VR-P1-012 | No | Stock authority, yield, ledger, snapshots, custody/sale meaning, return/disposal SOP | Product/operations owner | Process decision; role responsibility change; workflow implementation change |
| `docs/canonical/BUSINESS-RULES.md` | Hold atomic approved rules, formulas, invariants, units, state transitions, correction policies, and exceptions. | Product owner, developers, testers, analysts | `docs/audit/BUSINESS-RULES-INFERRED.md` as candidate evidence; approved decisions and verification | All P0/P1 business decisions; especially SQ-I-004 to SQ-I-014 | No final content; candidate-rule template only | Approval for every inferred rule and exception | Product owner; domain leads per rule | Decision log entry; validated exception; rule-changing code or migration |
| `docs/canonical/ROADMAP.md` | Record approved future phases, dependencies, explicit non-goals, and decision gates. | Product owner, collaborators, project reviewers | History roadmaps as proposals; SQ-O answers; status/current state | SQ-I-011/SQ-I-014; VR-P3-001 through VR-P3-007 | No | Which historical ideas are committed, deferred, rejected, or dependent | Product owner | Scope/priority decision; phase completion; dependency evidence; release plan |

### Technical architecture and contracts

| Path | Purpose | Audience | Primary sources | Required conflicts or decisions | Safe to create now? | Blocked information | Owner | Update triggers |
|---|---|---|---|---|---|---|---|---|
| `docs/canonical/SYSTEM-ARCHITECTURE.md` | Document the Laravel/Blade/Alpine monolith, request flow, controller/model boundaries, frontend composition, scheduling, exports, and external/runtime dependencies. | Developers, reviewers, operations | `composer.json`; `package.json`; `bootstrap/app.php`; source inventory; audit architecture sections; histories 01/03/05 | VR-P2-001; VR-P2-003; VR-P2-004; VR-P2-007/008/009; VR-P3-007 | Partial: source architecture can be drafted | Supported runtime matrix, scheduler/build operations, disposition of unused components | Technical lead | Dependency/boot/config/layout change; infrastructure decision; new service/job/API |
| `docs/canonical/DATA-MODEL.md` | Define current tables, relationships, keys, lifecycle states, units, calculated fields, and approved sources of truth. | Developers, data reviewers, analysts | `docs/audit/DATA-MODEL-AUDIT.md`; migrations/models; deployed schema evidence | VR-P0-006/007/008/013/014; VR-P1-012; VR-P2-010 | Partial: audited schema edition can be drafted but not called deployed truth | Deployed migration state, stock truth, snapshots/cost, retention, finished-good invariants | Technical/data lead | Migration/model change; schema drift finding; source-of-truth decision |
| `docs/canonical/ROLE-ACCESS-MATRIX.md` | Maintain the role, route/action, middleware, record-ownership, response/view, and deliberately unsupported-action contract. | Developers, security reviewers, testers | `docs/audit/ROUTE-ROLE-MATRIX.md`; `routes/web.php`; `routes/auth.php`; `bootstrap/app.php` | VR-P1-003; VR-P1-013; route-list/runtime check RV-151 | Partial: source route matrix can be copied only after source recheck | Action disposition, role/record ownership, installed-framework route list | Technical lead/security owner | Route/middleware/controller action change; role decision; route-list verification |
| `docs/canonical/CODE-AND-OPERATIONS.md` | Document supported PHP/Node/database versions, setup/build, environment assumptions, storage/mail/queue/scheduler, and safe operational checks. | Developers, CI, deployment/operations | Manifests/config; audit runtime checklist; completed P2 verifications | VR-P2-001 through VR-P2-004; VR-P2-009; VR-P3-006 | No final version; source prerequisites can be outlined | Supported matrix, `.env` contract, build evidence, scheduler/mail/storage ownership | Technical lead and operations owner | Dependency/runtime upgrade; environment/config procedure change; deployment incident |

### Module documentation

| Path | Purpose | Audience | Primary sources | Required conflicts or decisions | Safe to create now? | Blocked information | Owner | Update triggers |
|---|---|---|---|---|---|---|---|---|
| `docs/canonical/modules/README.md` | Define the standard module-document template and index. | Developers, module owners, reviewers | Canonical governance and audit module inventory | SQ-I-001; module ownership from VR-P1-013 | Yes, template/index only | Named module owners and acceptance states | Documentation owner | Module added/retired; template/governance change |
| `docs/canonical/modules/AUTH-AND-ROLES.md` | Document login/account lifecycle, role vocabulary, route access, record ownership, recovery, and registration. | Users, administrators, developers, security reviewers | Auth/user routes/controllers/models/tests; histories 01/05; audit questions | SQ-I-002/SQ-I-003; VR-P0-001/014; VR-P1-001/002/003 | No final rule edition | Registration, identifier/email, deletion/retention, ownership policy | Security/product owner | Auth/role route/model change; account-policy decision; security finding |
| `docs/canonical/modules/RAW-MATERIALS.md` | Document material master, units, sealed/opened stock, restock, adjustment, archive, pricing, and ledger. | Owner, inventory staff, developers, testers | Raw-material source; audit workflow/data/rules; histories 01/02/05 | SQ-I-004/SQ-I-005; VR-P0-005/006/008; VR-P1-004/005 | No final business-rule edition; source-state appendix is possible | Stock truth, price/cost policy, adjustment/precision, consumption ledger boundary | Inventory owner and technical lead | Stock formula/validation/transaction schema change; reconciliation decision |
| `docs/canonical/modules/RECIPE-BOM.md` | Document menu lifecycle, recipe quantity meaning, HPP display, snapshots/versioning, and active-state rules. | Owner, Produksi, developers, analysts | Menu models/controllers/migrations/views; audit BOM findings; histories 01/04 | SQ-I-005/SQ-I-006; VR-P0-007/008; VR-P1-006; SQ-L-004 | No final version | Snapshot time, cost basis, BOM denominator/complexity, inactive semantics | Product/Produksi owner | Recipe/schema/cost change; versioning decision; new BOM capability |
| `docs/canonical/modules/PRODUCTION.md` | Document planning, reservations, start/complete/cancel states, material use, package opening, waste, yield, and corrections. | Owner, Produksi, developers, testers | Both production controllers/models/views; audit production rules/risks; history 04 | SQ-I-006 through SQ-I-009; VR-P0-002 through VR-P0-007; VR-P1-007/008/013 | No | State machine/idempotency, actual yield, snapshots, ledger/waste, collaborator ownership | Produksi/product owner and technical lead | Production state/calculation/schema change; completion verification; rule approval |
| `docs/canonical/modules/FINISHED-GOODS.md` | Document batch creation, quantity/status/date invariants, expiry, correction, and availability. | Owner, Produksi, inventory staff, developers | Finished-good model/migration/controller/command; audit findings | SQ-I-008; SQ-L-005; VR-P0-004/007; VR-P1-011/012; VR-P2-003 | No final version | Actual output, snapshot/shelf-life point, state truth, expiry boundary/cadence | Product/Produksi owner | Batch/status/expiry logic change; scheduler verification; yield decision |
| `docs/canonical/modules/DISTRIBUTION-SALES-RETURNS.md` | Document allocation/custody, Armada sessions, sold counts, financial scope, returns, inspection, and disposal. | Owner, Armada, Produksi, finance, developers | Session/return models/controllers/views/migrations; audit findings; histories 01/04 | SQ-I-010 through SQ-I-013; VR-P0-009 through VR-P0-012/015; VR-P1-009/010 | No | Custody event, sales/revenue boundary, session invariant, return SOP, disposal history | Product/operations/finance owner | Distribution/sale/return schema or process change; concurrency evidence; SOP approval |
| `docs/canonical/modules/REPORTS-EXPORTS.md` | Document report purposes, filters, metrics, dates, data provenance, AJAX contracts, and export status. | Owner, analysts, developers, testers | Report controllers/views/export; audit report findings; histories 02/04 | VR-P2-005/006; SQ-L-008/SQ-L-009; underlying yield/snapshot/ledger decisions | Partial: Transaction Report source/history contract can be drafted; Production Report meaning remains blocked | Runtime workbook/AJAX results; output/date/BOM/cost meanings | Product analyst and technical lead | Report query/filter/metric/export change; runtime verification; metric decision |
| `docs/canonical/modules/ROP-MRP.md` | Define current alert/advisory behavior separately from approved future ROP/MRP/procurement phases. | Owner, analysts, developers, academic reviewers | Current thresholds/reservations; histories 01/02/04; audit ROP/MRP findings | SQ-I-014; VR-P3-001/002/003/005 | No final roadmap section; current source limitations can be drafted | Approved terminology, inputs, phase scope, ownership, data-readiness gates | Product owner/analyst | ROP/MRP scope decision; history threshold reached; planning implementation change |

### Repository-level entry files

| Path | Purpose | Audience | Primary sources | Required conflicts or decisions | Safe to create now? | Blocked information | Owner | Update triggers |
|---|---|---|---|---|---|---|---|---|
| `README.md` | Provide the public/project entry point: purpose, supported setup, role overview, documentation links, and verified status boundary. Existing content must be updated carefully, not blindly replaced. | New contributors, evaluators, operators | Stable canonical overview/current state/operations/status | Canonical overview, current state, operations, and status must first be approved | No | Product scope, supported environment/setup, stable canonical links and status | Project maintainer | Supported setup/product scope/release/document index changes |
| `CODEX_CONTEXT.md` | Give coding agents a compact, evidence-dated map of architecture, critical invariants, file ownership, safe commands, open decision boundaries, and canonical links. | Codex/AI coding agents and maintainers | Approved canonical architecture, rules, data model, role matrix, module docs, issues | All P0 decisions; principal P1 invariants; source baseline; safe command policy | No | Stable invariants, approved business boundaries, tested setup, file ownership | Technical lead/documentation owner | Critical invariant, architecture, workflow, setup, or safety policy changes |
| `AGENTS.md` | Give durable repository instructions for agents: scope, required verification, edit boundaries, conventions, and links to canonical context. It must contain instructions, not unresolved project speculation. | AI coding agents and human reviewers | Stable `CODEX_CONTEXT.md`; approved canonical docs; repository workflow policy | Critical context stable; P0 resolved; relevant P1 rules approved; setup/verification path established | **No. Create last only after critical context is stable.** | Any unresolved rule that could cause an agent to implement the wrong stock, production, sales, or retention behavior | Repository maintainer with product/technical approval | Durable workflow/convention/safety change; canonical context relocation; tooling policy change |

## Required creation order

### Phase 0 - establish authority and evidence baseline

1. Resolve SQ-I-001: name product, technical, operations, and documentation decision owners.
2. Complete VR-P2-011 read-only source-baseline recheck.
3. Complete VR-P0-013 through approved read-only deployed-schema evidence or explicitly declare that deployment state remains unknown.
4. Keep all P0/P1 stakeholder answer fields blank until a named owner responds.

### Phase 1 - create documentation governance

1. Create the template/index for `docs/canonical/DECISION-LOG.md`.
2. Create the skeleton for `docs/canonical/README.md`.
3. Create `docs/canonical/modules/README.md` with the standard module template.
4. Record the audit commit and reconciliation status, not runtime claims.

### Phase 2 - draft source-grounded technical current state

1. Draft `docs/canonical/CURRENT-SYSTEM-STATE.md` from the rechecked source baseline.
2. Draft source-only portions of `docs/canonical/SYSTEM-ARCHITECTURE.md`.
3. Draft audited-schema portions of `docs/canonical/DATA-MODEL.md`, clearly separating deployed-schema unknowns.
4. Draft `docs/canonical/ROLE-ACCESS-MATRIX.md` after the route source recheck.
5. Draft source-state columns in `docs/canonical/MODULE-STATUS.md` and evidence intake in `docs/canonical/KNOWN-ISSUES.md`.

### Phase 3 - resolve critical operational meaning

Resolve and log all P0 decisions before canonicalizing stock, production, distribution, sales, return, or disposal rules:

- registration/security;
- completion idempotency;
- actual yield;
- consumption ledger;
- stock source of truth;
- recipe/cost snapshots;
- historical/current price semantics;
- allocation/custody;
- sale/revenue scope;
- return duplication/idempotency;
- disposal retention;
- user-history retention; and
- sold-count atomicity/runtime outcome.

### Phase 4 - write approved business process and module rules

1. Write `docs/canonical/BUSINESS-PROCESS.md` from logged decisions.
2. Write `docs/canonical/BUSINESS-RULES.md`; each rule links to a decision and source/runtime evidence.
3. Write module documents in dependency order:
   1. `AUTH-AND-ROLES.md`
   2. `RAW-MATERIALS.md`
   3. `RECIPE-BOM.md`
   4. `PRODUCTION.md`
   5. `FINISHED-GOODS.md`
   6. `DISTRIBUTION-SALES-RETURNS.md`
   7. `REPORTS-EXPORTS.md`
   8. `ROP-MRP.md`

### Phase 5 - attach safe runtime and operations evidence

1. Execute only approved, isolated items from `docs/audit/RUNTIME-VERIFICATION-CHECKLIST.md`.
2. Complete the supported environment, build, database, scheduler, mail, storage, and test evidence.
3. Finalize `docs/canonical/CODE-AND-OPERATIONS.md`.
4. Update runtime columns in `MODULE-STATUS.md` and reproduced outcomes in `KNOWN-ISSUES.md` without relabeling static evidence as runtime-tested.

### Phase 6 - finalize scope, roadmap, and overview

1. Triage historical future ideas through SQ-I-014 and SQ-O questions.
2. Write `docs/canonical/ROADMAP.md` only from approved commitments and dependency gates.
3. Write `docs/canonical/PROJECT-OVERVIEW.md` after current scope and non-goals are stable.
4. Review the complete canonical set with product, technical, operations, and collaborator owners.

### Phase 7 - update repository entry context

1. Update root `README.md` from approved canonical documents.
2. Create `CODEX_CONTEXT.md` only when critical invariants, supported setup, and open-decision boundaries are stable.
3. Validate all links and remove duplicated claims in favor of canonical references.

### Phase 8 - create agent instructions last

Create `AGENTS.md` only after:

- every P0 business decision is resolved or explicitly marked as a prohibited implementation area;
- core P1 workflow rules have owners and approved wording;
- the source/schema baseline is known;
- safe test/build/runtime commands are verified;
- `CODEX_CONTEXT.md` and canonical links are stable; and
- product and technical owners approve the instructions.

Until then, agent work must continue to consult the audit/reconciliation evidence and must not infer unresolved business rules.

## Standard header for each future canonical document

Each canonical file should begin with:

| Field | Required value |
|---|---|
| Canonical status | Draft / Reviewed / Approved |
| Document owner | Named role/person |
| Approvers | Named role/person |
| Effective date | Date |
| Source baseline | Commit identifier |
| Runtime evidence through | Date or “none” |
| Decision-log references | Decision IDs |
| Open verification references | VR IDs |
| Supersedes | Prior canonical document/version, if any |
| Next review trigger/date | Trigger or date |

“Draft,” “Reviewed,” and “Approved” are document lifecycle states, not reconciliation classifications.

## Completion condition

The canonical set is ready to guide implementation only when:

- its source baseline is current;
- P0 rules are decided;
- relevant P1 workflows are approved;
- runtime-only claims cite captured runtime evidence;
- module status separates current implementation, accepted behavior, and roadmap;
- every canonical file has an owner and update trigger; and
- `AGENTS.md` contains only durable, approved instructions.
