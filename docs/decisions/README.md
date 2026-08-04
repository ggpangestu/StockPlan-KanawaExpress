# Decision Management Layer

## Purpose

This directory is the project's working layer for unresolved decisions and reviewed decisions. It connects repository evidence and reconciliation records to later canonical documentation without treating code, history, or recommendations as approved policy.

The files have distinct roles:

- `PENDING-DECISIONS.md` is the editable register for questions that still need an answer, evidence, review, or confirmation.
- `DECISION-LOG.md` is the durable register for reviewed decisions. It begins with no decision entries because the available evidence does not identify an explicit human-approved decision.
- `README.md` defines how information moves between those registers.

This layer does not create business rules, authorize implementation work, or prove runtime behavior.

## Evidence, pending decisions, and confirmed decisions

| Concept | Meaning |
|---|---|
| Evidence | A traceable source observation, historical statement, stakeholder or collaborator response, or runtime/database observation. Evidence keeps its original meaning and classification. It can support a decision but does not make the decision. |
| Pending decision | A material question whose answer, authority, evidence, or review is incomplete. Its record remains in `PENDING-DECISIONS.md` under an allowed non-confirmed status. |
| Confirmed decision | A reviewed decision with an explicit answer from an identified human who has authority for the stated scope. It is recorded in `DECISION-LOG.md` with status `CONFIRMED`. Confirmation of policy is separate from source implementation and runtime verification. |

`SOURCE_VERIFIED` means that something was observed in source code; it is not runtime verification and is not stakeholder approval. Repository behavior is therefore not automatically an approved decision. Historical discussion records prior intent or context; it is not automatically current policy.

## Allowed statuses

Use only these decision statuses in this directory:

- `PENDING`
- `PROVISIONAL`
- `NEEDS_OWNER_CONFIRMATION`
- `NEEDS_COLLABORATOR_CONFIRMATION`
- `NEEDS_RUNTIME_VERIFICATION`
- `DEFERRED`
- `CONFIRMED`
- `REJECTED`
- `OUT_OF_SCOPE`
- `SUPERSEDED`

Do not use `CONFIRMED` unless an authorized human has explicitly approved the decision. Evidence labels such as `SOURCE_VERIFIED`, `INFERRED_FROM_CODE`, and `UNKNOWN` are evidence classifications, not decision statuses.

## Where to write an answer

Write the first response directly in the relevant entry in `PENDING-DECISIONS.md`:

- Put the response being considered in **Proposed answer**.
- Record the responder in **Answered by** and the response date in **Decision date**.
- Use **Notes** for qualifications, missing evidence, exceptions, or an explicit "I do not know."
- Leave **Final answer** blank until review is complete.
- After review, place the agreed wording in **Final answer** and update the status only to the applicable allowed status.

Do not overwrite the evidence, history, unresolved reason, or source references already recorded in the entry. If an answer cannot be confirmed, keep it in the pending register. Do not copy it into the decision log as an approved rule.

## Confirmation workflow

Use this sequence:

```text
Question identified
→ recorded in PENDING-DECISIONS.md
→ evidence or human answer collected
→ decision reviewed
→ confirmed entry added to DECISION-LOG.md
→ later canonical documentation updated
```

1. Identify the question and retain its `SQ-*`, `VR-*`, or `DC-*` traceability.
2. Record it in `PENDING-DECISIONS.md` with the status that accurately describes what is missing.
3. Collect the human answer or required collaborator, source, runtime, or database evidence. Record who supplied it and when.
4. Review the answer for decision authority, scope, exceptions, contradictions, and evidence boundaries.
5. If an authorized human explicitly confirms it, copy the reviewed decision into `DECISION-LOG.md` with status `CONFIRMED` and complete traceability. Otherwise, keep it pending, mark it `PROVISIONAL`, `DEFERRED`, `REJECTED`, or `OUT_OF_SCOPE` only when that treatment is explicitly supported.
6. Update later canonical documentation only after the confirmed log entry exists. A separate task is required for any application change.

An answer of "I do not know" is a valid current state. Record it honestly with the responder and date; do not turn it into a guessed answer or an implicit approval. The decision remains unresolved under the applicable allowed status.

## Who should answer

| Answer source | Appropriate questions | Boundary |
|---|---|---|
| Owner or business stakeholder | Business policy, authority, roles, inventory meaning, costing, sales, returns, disposal, and ROP/MRP scope. | Confirms business decisions only within explicitly established authority. |
| Project developer or system analyst | Current source behavior, technical dependencies, calculations, report meaning, and implementation feasibility. | Supplies source analysis; cannot convert implementation into business approval. |
| Technical collaborator | Rationale and intended behavior behind collaborator-designed workflows, migrations, and technical choices. | Supplies design-intent evidence; authorship alone is not owner approval. |
| Produksi or Armada operational representative | Actual operating steps, yield capture, repeat completion, custody, sales counts, returns, waste, and operational exceptions. | Supplies operational evidence; cross-module policy still needs the appropriate owner. |
| Runtime or database verifier | Deployed schema, constraints, HTTP/UI behavior, concurrency, jobs, exports, and before/after data evidence in an approved environment. | Supplies observed evidence only; runtime results do not approve business policy. |

Questions spanning more than one boundary should name all required contributors and one accountable decision owner.

## Evidence sources

The primary inputs for this layer are:

- `docs/reconciliation/STAKEHOLDER-QUESTIONS-PRIORITY.md`
- `docs/reconciliation/DECISION-CONFLICTS.md`
- `docs/reconciliation/VERIFICATION-REGISTER.md`
- `docs/reconciliation/CHAT-VS-REPOSITORY.md`
- `docs/reconciliation/RECONCILIATION-METADATA.md`

Use repository-relative references. Preserve whether each statement is source-verified, inferred, historical, runtime-dependent, unresolved, or explicitly human-confirmed.
