# StockPlan Conversation History Index

## Purpose

This folder preserves knowledge extracted from five long project conversations. These files are historical evidence of intent, discussion, revisions, reported implementation, and roadmap decisions.

They are not:

- a source-code audit;
- proof of runtime behavior;
- canonical current documentation;
- authorization to implement every discussed idea.

Use `docs/audit/` to understand the repository snapshot. Use this folder to understand why ideas and decisions emerged.

## Files

| File | Primary coverage |
| --- | --- |
| `01-inventory-rop-foundation.extract.md` | Business process, ROP/MRP, FR/use cases, stack, authentication, roles, Git, and early Raw Material design |
| `02-toast-raw-material-report-evolution.extract.md` | Toast/modal debugging, archive/undo, transaction semantics, Transaction Report, Production Report direction |
| `03-sidebar-role-and-git-workflow.extract.md` | Sidebar architecture alternatives and branch/stash/merge workflow |
| `04-inventory-handover-and-production-report.extract.md` | Successive handover states, collaborator production lifecycle, consumption ledger debate, Production Report V1 |
| `05-dashboard-architecture-and-raw-material-lifecycle.extract.md` | Application shell, role routing, sidebar, Raw Material implementation history, transaction and archive lifecycle |

## Interpretation rules

When reading these files:

1. A reported status such as “DONE” means the conversation treated it as done at that point.
2. A later conversation may supersede an earlier recommendation.
3. The repository may differ from the conversation.
4. Business intent and implemented behavior must be reconciled before creating canonical documentation.
5. The next documentation phase should produce a chat-versus-repository comparison rather than silently choosing one source.
