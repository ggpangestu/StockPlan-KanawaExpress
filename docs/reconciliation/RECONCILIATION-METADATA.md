# Reconciliation Metadata

## Repository Identity

| Field | Value |
| --- | --- |
| Repository name | `StockPlan-KanawaExpress` |
| Current branch | `docs/repository-audit` |
| Current commit | `7343e9e1194ffe32e2aa607be509ff0149d86675` |
| Original audited application commit | `7343e9e1194ffe32e2aa607be509ff0149d86675` |
| Audit documentation commit | Not available. `docs/audit/` is untracked and is not part of the current commit. |
| Reconciliation date | `2026-08-03` (Asia/Jakarta) |
| Reconciliation type | Static documentation-to-documentation evidence reconciliation; documentation-only and read-only with respect to application source and both evidence layers |

The current branch head is the same commit identified in `docs/audit/AUDIT-METADATA.md`. Therefore this reconciliation did **not** occur against audit documentation referring to an older application commit. If the branch advances after this metadata capture, the comparison must be rechecked before it is used as current evidence.

## Source Directories

| Directory | Role in reconciliation | Authority boundary |
| --- | --- | --- |
| `docs/audit/` | Static source-derived evidence for the implementation observable at the audited commit | Not runtime proof and not approved business policy |
| `docs/history/` | Extracted historical evidence of requirements, alternatives, revisions, reported completion, collaborator discovery, and future plans | Not proof of current implementation and not automatically active intent |
| `docs/reconciliation/` | Comparison, conflicts, verification priorities, stakeholder decision prompts, and canonical-documentation planning | Not canonical project documentation and not remediation authorization |

All six files under `docs/history/` and all ten files under `docs/audit/` were included in the evidence inventory. No additional application-source check was needed to resolve the comparison; application paths cited in this package are paths already established by the audit.

## Files Created

- `docs/reconciliation/README.md`
- `docs/reconciliation/CHAT-VS-REPOSITORY.md`
- `docs/reconciliation/DECISION-CONFLICTS.md`
- `docs/reconciliation/VERIFICATION-REGISTER.md`
- `docs/reconciliation/STAKEHOLDER-QUESTIONS-PRIORITY.md`
- `docs/reconciliation/CANONICAL-DOCUMENTATION-PLAN.md`
- `docs/reconciliation/RECONCILIATION-METADATA.md`

No canonical file and no `AGENTS.md` was created.

## Commands Executed

Only safe, read-only inspection commands were used. The command forms included:

```text
rg --files docs\audit docs\history
rg -n "^#{1,4} " docs\history docs\audit
Get-ChildItem -LiteralPath docs\audit,docs\history -File
Get-ChildItem -LiteralPath docs\reconciliation -File
Get-Content -Raw -LiteralPath <documentation-file>
git branch --show-current
git rev-parse HEAD
git log -1 --format="%cI|%s"
git status --short
git ls-files docs\audit docs\history
git diff --stat
```

Additional read-only `rg`, `Get-Content`, and PowerShell counting/validation commands were used to check headings, classifications, required fields, file inventory, and totals. Documentation files were added or corrected with the patch-editing tool.

The reconciliation did not run the application, migrations, seeders, tests, builds, browser workflows, schedulers, application commands, exports, or database queries.

## Repository State and Modification Boundary

- Application source modified: **No**.
- Files under `docs/audit/` modified: **No**.
- Files under `docs/history/` modified: **No**.
- Files created or modified by this task: only the seven files listed under `docs/reconciliation/`.
- `git status --short` reported `?? docs/` before reconciliation creation because the entire documentation tree was untracked. It remains the expected compact status while all documentation is untracked.
- `git ls-files docs\audit docs\history` returned no paths, so neither evidence layer has a documentation commit available in this worktree.
- A normal `git diff --stat` produces no tracked-file output for untracked documentation; an explicit untracked-file stat is required to summarize these new files.

The application commit is reproducible from Git. The uncommitted audit, history, and reconciliation documents are not reproducible from that commit alone and must be preserved separately or committed in a later authorized workflow.

## Evidence Vocabulary

### Audit evidence labels retained

| Label | Meaning |
| --- | --- |
| `SOURCE_VERIFIED` | Directly observed in repository source at the audited commit; not executed. |
| `INFERRED_FROM_CODE` | A likely behavior or rule derived from static tracing, not runtime-observed and not automatically approved. |
| `NEEDS_RUNTIME_VERIFICATION` | Source/doc comparison cannot establish the result without a safe runtime, browser, database, scheduler, export, integration, or concurrency check. |
| `PARTIALLY_IMPLEMENTED` | A recognizable source path exists, but an important expected part is absent, disconnected, or incomplete. |
| `SCAFFOLDED_ONLY` | A placeholder, field, route surface, enum value, or structure exists without a complete usable workflow. |
| `UNUSED` | An artifact exists but no active reference/use was found during static tracing; dynamic/external use is not disproved. |
| `UNKNOWN` | Available evidence is absent, ambiguous, conflicting, or insufficient. |

`SOURCE_VERIFIED` is explicitly not runtime verification.

### Reconciliation classifications

| Classification | Meaning |
| --- | --- |
| `ALIGNED` | Historical intent and repository implementation materially agree. |
| `PARTIALLY_ALIGNED` | Some historical intent is implemented, but important parts are absent, different, or incomplete. |
| `IMPLEMENTED_BUT_UNDOCUMENTED` | Repository functionality exists without meaningful supporting historical intent/decision evidence. |
| `INTENDED_NOT_IMPLEMENTED` | History contains an accepted or repeatedly stated requirement that repository evidence does not implement. |
| `HISTORICAL_PLAN_ONLY` | History discusses a future idea or option without establishing an accepted current requirement. |
| `SUPERSEDED_HISTORY` | An older historical approach was replaced by a later historical decision or clearly newer implementation. |
| `IMPLEMENTATION_CONFLICT` | Current source materially conflicts with stated historical intent. |
| `BUSINESS_DECISION_REQUIRED` | Code selects a behavior, but the evidence does not establish stakeholder approval. |
| `RUNTIME_VERIFICATION_REQUIRED` | The comparison cannot be completed without executed or applied-environment evidence. |
| `UNKNOWN` | Evidence remains insufficient or ambiguous after comparing both layers. |

## Limitations

- The audit and history evidence layers are uncommitted local documentation, so their exact contents are not identified by the application commit.
- The history files are extracts from earlier conversations rather than the full original sessions; extraction may omit context, and some source-export text contains encoding artifacts.
- Historical “complete” or “done” statements were not independently validated.
- The audit was static and source-only; this reconciliation inherits every runtime unknown recorded in `docs/audit/RUNTIME-VERIFICATION-CHECKLIST.md`.
- No stakeholder, collaborator, business-process, deployment, database, or runtime evidence was collected during reconciliation.
- Application paths are cited from the audit; no repeated full source audit or new source interpretation was performed.
- Current implementation cannot by itself establish approved policy, and historical intent cannot by itself establish current behavior.
- The verification register is a plan, not evidence that any check has passed.

## Reconciliation Disclaimer

This package preserves evidence differences and decision boundaries. It is not canonical documentation, a business specification, proof of runtime correctness, approval of collaborator choices, or authorization to change application behavior.

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