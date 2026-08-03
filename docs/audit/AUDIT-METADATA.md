# Audit Metadata

## Repository Identity

| Field | Value |
| --- | --- |
| Repository name | `StockPlan-KanawaExpress` |
| Current branch | `docs/repository-audit` |
| Audited commit hash | `7343e9e1194ffe32e2aa607be509ff0149d86675` |
| Latest commit date | `2026-08-03T16:25:18+07:00` |
| Latest commit subject | `Merge pull request #23 from ggpangestu/improve/report-production` |
| Audit date | `2026-08-03` (Asia/Jakarta) |
| Audit type | Static, source-code-only, read-only |

The application source represented by the audited commit was inspected statically. The audit did not establish that the same behavior occurs in a running application or against any particular database or deployment environment.

## Audit Boundaries

The audit did not execute any of the following:

- Application runtime
- Migrations
- Seeders
- Automated tests
- Vite or production builds
- Browser workflows
- Scheduler or application commands
- Exports
- Database queries
- Concurrency tests

No runtime, browser, database, queue, filesystem-export, or concurrent-user result should therefore be inferred from these documents.

## Evidence Meaning

| Label | Meaning in this audit |
| --- | --- |
| `SOURCE_VERIFIED` | Directly observed in the repository source at the audited commit, such as a route declaration, class, method, validation rule, query, relationship, migration, view, component, script, or test definition. This label verifies source evidence only. |
| `INFERRED_FROM_CODE` | A behavior or business rule derived by tracing and interpreting source-code paths, but not explicitly declared as a requirement and not observed at runtime. |
| `NEEDS_RUNTIME_VERIFICATION` | Source inspection alone cannot establish the behavior; confirmation requires a safe runtime, browser, database, integration, export, scheduler, or concurrency check. |
| `PARTIALLY_IMPLEMENTED` | A recognizable implementation exists, but one or more expected paths, actions, validations, integrations, persistence steps, views, or tests are incomplete or absent. |
| `SCAFFOLDED_ONLY` | Structural or placeholder code exists, but the reviewed source does not establish a complete usable implementation. |
| `UNUSED` | Code, schema, or another artifact is present but no active reference or reachable use was found during static tracing. Dynamic or external use remains possible unless separately disproved. |
| `UNKNOWN` | The available repository evidence is absent, ambiguous, conflicting, or insufficient to classify the behavior more precisely. |

`SOURCE_VERIFIED` is not runtime verification. It does not mean a path was executed, a UI was opened, a query succeeded, a test passed, or a business outcome was validated.

## Audit Documents

The audit set under `docs/audit/` consists of:

| File | Purpose |
| --- | --- |
| `docs/audit/INITIAL-REPOSITORY-AUDIT.md` | Preserves the full initial static repository audit and its evidence-backed findings. |
| `docs/audit/CURRENT-IMPLEMENTATION.md` | Describes only the implementation observable in the reviewed source snapshot. |
| `docs/audit/MODULE-STATUS.md` | Summarizes each module's source status, runtime status, test evidence, primary files, and important gaps. |
| `docs/audit/DATA-MODEL-AUDIT.md` | Records tables, columns, model relationships, stock fields, calculations, sources of truth, unused fields, and migration inconsistencies. |
| `docs/audit/ROUTE-ROLE-MATRIX.md` | Maps HTTP routes to names, controller actions, middleware, roles, responses or views, and incomplete resource actions. |
| `docs/audit/BUSINESS-RULES-INFERRED.md` | Catalogs source-observed or inferred business rules with evidence, confidence, affected modules, and test coverage. |
| `docs/audit/KNOWN-RISKS.md` | Separates directly verified defects from concurrency, data-integrity, portability, and maintainability risks. |
| `docs/audit/RUNTIME-VERIFICATION-CHECKLIST.md` | Converts runtime unknowns into reviewable checks without recording them as executed. |
| `docs/audit/HUMAN-QUESTIONS.md` | Collects decisions and domain questions that require confirmation from a project owner or collaborator. |
| `docs/audit/AUDIT-METADATA.md` | Identifies the audited repository snapshot, audit boundaries, evidence vocabulary, document set, and repository state. |

## Repository State

At metadata capture:

- The tracked application source was unchanged relative to audited commit `7343e9e1194ffe32e2aa607be509ff0149d86675`.
- The audit documentation was uncommitted and appeared as an untracked `docs/` directory.
- The application commit that was audited is the commit hash above; the uncommitted files under `docs/audit/` are documentation created from that static audit and are not part of that commit.
- No tracked application-code modification was reported by Git.

The read-only `git status --short` output was:

```text
?? docs/
```

Git summarizes an entirely untracked directory at its directory path, so that output does not enumerate each audit file individually. The document table above records the files observed under `docs/audit/`.

Reproducibility is limited in the following ways:

- Commit `7343e9e1194ffe32e2aa607be509ff0149d86675` identifies the application source snapshot, but the uncommitted audit documents cannot be recovered from that commit alone.
- The audit records static interpretation of the repository at the stated date; it does not capture or validate runtime configuration, installed dependency state, environment variables, external services, browser behavior, database contents, or deployed infrastructure.
- No executable verification was performed, so runtime-only, data-dependent, timing-dependent, and concurrency-dependent outcomes remain outside the evidence boundary.
- Branch name and worktree status describe the local repository at metadata capture and may change independently of the audited commit.

## Important Disclaimer

These audit files are:

- Repository evidence derived from a static review of the identified source snapshot.
- Not approved business specifications.
- Not canonical project documentation.
- Not proof that runtime behavior is correct.
- Not authorization to fix all listed findings.

Any business-rule adoption, remediation scope, migration, data correction, or implementation change requires separate review and authorization.
