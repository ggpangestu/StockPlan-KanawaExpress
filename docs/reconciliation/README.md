# Repository Reconciliation

## Purpose

This directory reconciles two different evidence layers without changing either of them:

- `docs/audit/` records the implementation observable in the repository at the audited application commit. It is static source evidence, not runtime proof and not automatic approval of business policy.
- `docs/history/` records earlier requirements, reasoning, alternatives, revisions, reported completions, collaborator discoveries, and future plans. It is evidence of project evolution, not automatic proof of current implementation.
- `docs/reconciliation/` compares those layers, preserves disagreements, and identifies the evidence or decisions needed before a canonical account can be written.
- Future canonical documentation should state approved current policy and supported system behavior. It must not be created by silently promoting either source layer.

Reconciliation is documentation analysis only. It does not modify `docs/audit/`, `docs/history/`, or application source. An unresolved conflict involving stakeholder intent remains unresolved until a named human confirms it.

## Which File to Read

| Need | File |
| --- | --- |
| Understand this layer and how to navigate it | `docs/reconciliation/README.md` |
| Compare historical intent with current repository evidence topic by topic | `docs/reconciliation/CHAT-VS-REPOSITORY.md` |
| Review only meaningful conflicts, superseded choices, and undecided implementation behavior | `docs/reconciliation/DECISION-CONFLICTS.md` |
| Plan the most important human, collaborator, source, runtime, database, and process checks | `docs/reconciliation/VERIFICATION-REGISTER.md` |
| Ask stakeholders the smallest practical set of decisions | `docs/reconciliation/STAKEHOLDER-QUESTIONS-PRIORITY.md` |
| Design the future canonical documentation set and creation sequence | `docs/reconciliation/CANONICAL-DOCUMENTATION-PLAN.md` |
| Identify the repository snapshot, source directories, boundaries, commands, and created files | `docs/reconciliation/RECONCILIATION-METADATA.md` |

## Recommended Reading Order

1. `docs/reconciliation/RECONCILIATION-METADATA.md` — establish the snapshot and evidence boundary.
2. `docs/reconciliation/CHAT-VS-REPOSITORY.md` — understand the complete comparison.
3. `docs/reconciliation/DECISION-CONFLICTS.md` — focus on decisions that cannot be silently resolved.
4. `docs/reconciliation/STAKEHOLDER-QUESTIONS-PRIORITY.md` — obtain the decisions that block canonical documentation.
5. `docs/reconciliation/VERIFICATION-REGISTER.md` — gather runtime, database, source, collaborator, and process evidence safely.
6. `docs/reconciliation/CANONICAL-DOCUMENTATION-PLAN.md` — create canonical documentation only after its stated gates are resolved.

Use `docs/audit/` directly when exact route, schema, class, method, view, risk, or runtime-check evidence is needed. Use `docs/history/` directly when the sequence of an idea, rejected option, or later decision matters.

## Interpretation Rules

- Historical intent does not prove implementation.
- Repository implementation does not prove business approval.
- A conversational claim that a feature was complete is not implementation evidence unless the audit supports it.
- Static source verification is not runtime verification.
- An implementation absent from history is treated as undocumented implementation, not an approved rule.
- A newer historical statement may supersede an older statement but cannot override current repository evidence.
- Rules from `docs/audit/BUSINESS-RULES-INFERRED.md` remain observed or inferred rules until stakeholder confirmation.
- Conflicts are recorded, not silently resolved.

## Canonical Documentation Gate

This package is not canonical project documentation. The critical decisions and verification evidence identified here should be resolved first. In particular, `AGENTS.md` should not be created until the operating model, authentication policy, stock source of truth, production yield and completion lifecycle, recipe history, sales scope, return/disposal audit, and ROP/MRP scope are stable enough to guide future work safely.


## Reconciliation Completion Summary

### Classification counts

- ALIGNED: 15
- PARTIALLY_ALIGNED: 20
- IMPLEMENTED_BUT_UNDOCUMENTED: 1
- INTENDED_NOT_IMPLEMENTED: 1
- HISTORICAL_PLAN_ONLY: 2
- SUPERSEDED_HISTORY: 1
- IMPLEMENTATION_CONFLICT: 5
- BUSINESS_DECISION_REQUIRED: 4
- RUNTIME_VERIFICATION_REQUIRED: 3
- UNKNOWN: 1

### Verification counts

- Immediate stakeholder questions: 15
- P0 verification items: 15
- P1 verification items: 13

### Five most important unresolved decisions

1. Authority and approval ownership for business and technical rules.
2. Stock source of truth and production-consumption ledger.
3. Production lifecycle, idempotency, and actual yield.
4. Recipe/cost snapshot and historical costing policy.
5. Distribution custody, sales scope, returns, and disposal audit.