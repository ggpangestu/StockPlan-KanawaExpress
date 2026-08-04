# Decision Log

## Purpose

This file is the durable register for decisions that have completed review. It records what was decided, who had authority, what evidence was used, the effective scope, affected modules, required verification, and later supersession.

It does not convert source behavior, historical discussion, reconciliation recommendations, or runtime observations into approved policy. A decision entry does not authorize application changes.

## Current register

No decision entries are recorded yet. The reconciliation evidence does not currently identify an explicit human-approved decision, so no item is labeled `CONFIRMED`. No uncertain decision has been populated.

A well-supported candidate may be added later as `PROVISIONAL`, but it remains nonbinding until the confirmation requirements below are met.

## Status vocabulary

Only the following statuses may be used for decision records in this directory:

| Status | Meaning |
|---|---|
| `PENDING` | A decision question exists, but a reviewable answer has not yet been established. |
| `PROVISIONAL` | A concrete answer is recorded for review, but required human confirmation is incomplete. |
| `NEEDS_OWNER_CONFIRMATION` | The answer requires confirmation from the owner or authorized business stakeholder. |
| `NEEDS_COLLABORATOR_CONFIRMATION` | The intended design or rationale requires confirmation from the relevant collaborator. |
| `NEEDS_RUNTIME_VERIFICATION` | Runtime or database evidence is required before the matter can be resolved. |
| `DEFERRED` | An authorized person has deliberately postponed the decision. |
| `CONFIRMED` | An authorized human has explicitly approved the recorded decision and scope. |
| `REJECTED` | An authorized decision maker has explicitly rejected the proposed decision. |
| `OUT_OF_SCOPE` | An authorized decision maker has explicitly excluded the matter from the applicable scope. |
| `SUPERSEDED` | A previously confirmed decision has been replaced by a later confirmed decision. |

Evidence classifications and runtime results remain separate from these decision statuses.

## Future decision-entry template

Copy this complete template for each future entry. Do not fill an unknown with an inferred value.

### `DEC-YYYY-NNN` - `<title>`

| Field | Value |
|---|---|
| Decision ID |  |
| Title |  |
| Status |  |
| Decision |  |
| Rationale |  |
| Alternatives considered |  |
| Decision owner |  |
| Consulted parties |  |
| Decision date |  |
| Effective scope |  |
| Source evidence |  |
| Affected modules |  |
| Verification required |  |
| Supersedes |  |
| Superseded by |  |
| Review trigger |  |

## Rules for adding a confirmed decision

An entry may use `CONFIRMED` only when all applicable conditions are satisfied:

1. The decision authority for the affected scope is identified by name or by a documented governance role represented by an identified human.
2. The decision contains an explicit answer, rationale, effective scope, date, affected modules, and any relevant exceptions.
3. The decision owner explicitly approves the exact recorded wording. Required operational, technical, financial, security, or data stakeholders are consulted where their domains are affected.
4. The entry cites the relevant pending decision and exact `SQ-*`, `VR-*`, and `DC-*` identifiers, plus repository-relative source or history evidence where applicable.
5. Conflicting evidence and alternatives are retained and addressed; they are not silently discarded.
6. Source verification, human approval, implementation, and runtime verification are described separately.
7. Every template field is completed. If a field does not apply, the entry explains why rather than inventing a value.

If authority, approval, scope, or required evidence is missing, the entry must retain the applicable non-confirmed status.

## Approval requirements

- Business rules require the owner or business stakeholder authorized for that process.
- Inventory, Produksi, Armada, return, disposal, costing, revenue, privacy, or safety decisions require consultation with the affected domain representative in addition to the accountable owner when appropriate.
- Technical architecture or deployment decisions require the authorized technical decision maker; they also require business approval if they change observable business behavior or data meaning.
- Collaborator authorship, code presence, historical discussion, tests, or runtime behavior alone cannot supply approval.
- The approval evidence must identify the approver, authority, date, exact decision wording, and any conditions or exceptions.
- "I do not know," silence, or an unavailable decision maker does not count as confirmation.

## Traceability requirements

Each entry must:

- link its originating `PD-*` entry in `PENDING-DECISIONS.md`;
- link every relevant `SQ-I-*`, `SQ-L-*`, `VR-P0-*`, `VR-P1-*`, and `DC-*` identifier using the owning reconciliation document;
- cite repository files with repository-relative paths and no fabricated line numbers;
- distinguish source evidence, inferred behavior, history, collaborator statements, human approval, and runtime/database evidence;
- identify the source commit or deployed/database baseline when it matters and state when that baseline still requires verification;
- retain contrary evidence and unresolved verification items;
- state which modules and roles are affected; and
- keep `Verification required` explicit even after policy is confirmed, because confirmation is not proof of correct runtime behavior.

## Rules for superseding a decision

1. Do not rewrite or delete the earlier decision to express a materially different rule.
2. Create a new decision ID and review it independently. It remains `PROVISIONAL` or another applicable non-confirmed status until approved.
3. A new entry may replace an earlier decision only after the new entry becomes `CONFIRMED`.
4. Record the earlier ID in the new entry's **Supersedes** field. Change the earlier entry to `SUPERSEDED` and record the new ID in its **Superseded by** field.
5. State whether replacement is complete or partial, the affected scope, and the effective date. Preserve both entries and their evidence.
6. A rejected, deferred, or unconfirmed replacement does not supersede an existing confirmed decision.

## Maintenance boundary

Adding or changing an entry is a documentation action only. It must not be used as evidence that source code was changed, a migration ran, data was corrected, deployment occurred, or runtime behavior passed verification. Those activities require separately authorized work and evidence.
