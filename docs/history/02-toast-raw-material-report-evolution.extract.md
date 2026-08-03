# History Extraction — Toast, Raw Material UX, and Report Evolution

## Source metadata

- Source: `2masalahpadatoast.txt`
- Source type: exported project conversation
- Historical role: continuation of Raw Material UI/UX debugging, transaction semantics, sidebar refactoring, Transaction Report implementation, and early Production Report planning
- Evidence boundary: records discussion history and reported outcomes. It does not override repository evidence.

## 1. Conversation purpose

The conversation opened with a concrete UI defect: archive and adjustment notifications had become unstable after repeated changes. It then expanded into a long refinement session covering:

- toast architecture;
- modal and reload timing;
- archive/restore and undo behavior;
- restock and adjustment semantics;
- responsive modal design;
- role sidebar structure;
- transaction reporting, AJAX filtering, pagination, charting, and export;
- alignment of reports with functional requirements;
- Production Report and material-consumption roadmap.

The dominant pattern was iterative debugging followed by architectural simplification when layered workarounds became fragile.

## 2. Initial toast problem

### 2.1 Observed symptoms

- success toast animations were too fast and not smooth;
- adjustment toast appeared to blink twice and disappear;
- modal closure, reload, data refresh, and success notification happened in an unnatural order;
- archive and adjustment flows did not provide consistent feedback.

Desired user flow:

```text
submit succeeds
→ modal closes smoothly
→ page/data reflects the change
→ short pause
→ success toast appears
```

### 2.2 First root cause identified

Two toast systems were active at the same time:

- global Alpine `toastManager` in `resources/js/app.js`;
- Laravel session-success markup in `resources/views/layouts/app.blade.php`.

After an AJAX operation and reload, both systems could render feedback, causing double appearance/flicker.

### 2.3 Initial correction

The proposed correction was to standardize success feedback through the toast manager and remove the duplicate session-success rendering path.

Animation timing was also adjusted so the element removal delay did not cut off the leave transition.

## 3. Reload versus no-reload evolution

### 3.1 No-reload proposal

A fully reactive flow was considered:

```text
AJAX success
→ update opened stock
→ recalculate total stock
→ recalculate health badge
→ append transaction
→ close modal
→ show toast
```

Advantages discussed:

- deterministic UI;
- no sessionStorage bridge;
- no full Alpine reinitialization;
- no visual flicker from page reload.

### 3.2 Complexity discovered

Adjustment affects multiple derived areas:

- opened stock;
- normalized total stock;
- stock health;
- recent transaction history;
- potentially dashboard counts and future reorder information.

Updating all of these in JavaScript would duplicate backend business logic, especially stock-health rules.

### 3.3 Hybrid/reload decision for the current stage

The conversation moved back toward a controlled reload flow:

```text
server commits adjustment
→ modal closes
→ short delay for close animation
→ reload
→ new server-rendered data appears
→ success toast appears from sessionStorage
```

This was preferred for the current Laravel + Blade + Alpine architecture because the server remained the source of derived inventory logic.

### 3.4 Later source status

The source later treated the toast system as sufficiently mature and recommended locking it rather than continuing repeated redesign. However, the later repository audit must determine which exact version exists in code.

## 4. Toast design principles developed

The desired notification system became:

- one global toast stack;
- simple and business-focused;
- responsive width;
- top-centered placement;
- progress/countdown where appropriate;
- optional undo for reversible archive actions;
- not excessively noisy;
- usable on tablet, laptop, desktop, and mobile.

Responsive width discussed:

```text
mobile: almost full width with side margin
tablet: moderate fixed width
desktop: bounded width, not modal-sized
```

Archive and success notifications were expected to preserve clear lifecycle feedback.

## 5. Modal architecture and UX

### 5.1 Adjustment and restock modals

The restock modal was intentionally based on the visual structure of the adjustment modal so that interactions remained consistent.

Restock modal data included:

- material identity;
- current total/sealed stock;
- purchase unit;
- latest purchase price;
- restock quantity;
- price;
- notes.

The implementation was intentionally staged:

```text
layout and open event
→ state design
→ validation
→ submit/fetch
→ toast/reload
```

This avoided repeating the earlier toast refactor problem.

### 5.2 Responsive scrolling

A usability problem was found on shorter tablet/laptop screens: a modal could technically scroll, but users did not realize more fields existed below.

A custom visible scrollbar and a scrollable modal body were introduced while keeping header and footer fixed.

Reported status after the fix:

- Adjustment Modal: sufficiently mature;
- Restock Modal: sufficiently mature;
- base modal pattern: safe to stop modifying unless a new requirement appears.

## 6. Raw Material transaction semantics

### 6.1 Canonical transaction quantity

The strongest architectural conclusion was:

```text
raw_material_transactions.quantity
must be stored in the canonical base unit
```

This supported consistent:

- before/after snapshots;
- reporting;
- comparison across purchase packages;
- later production consumption;
- future forecasting.

### 6.2 Restock versus adjustment

The distinction was preserved:

```text
Restock
= purchase/acquisition of stock

Adjustment Add/Reduce
= correction to recorded inventory
```

An adjustment increase is not necessarily a purchase. Therefore a missing price on an adjustment was not automatically a bug.

Examples of adjustment-add reasons discussed:

- initial stock correction;
- stock-opname correction;
- manual correction;
- newly discovered inventory.

Potential future enhancement:

- add `reason` or `adjustment_reason`;
- optionally record estimated cost for valuation without treating the event as a purchase.

### 6.3 Adjustment Reduce ambiguity

Adjustment reductions can represent many meanings:

- damage;
- expiration;
- loss;
- stock-opname correction;
- unrecorded usage;
- waste.

The conversation recognized that raw-material waste and finished-good waste should not be silently conflated with generic adjustment reduction.

### 6.4 Restock price editing

The module added or discussed:

- first-restock price validation;
- optional price update on later restock;
- editing a restock-history price;
- recalculating transaction total;
- maintaining `latest_price`.

The conversation noted that historical price correction and current price are different accounting concepts and may need clearer policy later.

## 7. Archive, restore, and undo lifecycle

Instead of hard deletion, Raw Material records evolved toward an active/inactive lifecycle:

```text
active operational list
↔ archive/inactive list
```

Desired UX included:

- toggle/archive action;
- removal from the active table;
- restore path;
- undo queue/toast;
- separation of operational and archived records.

This supported the project principle:

```text
archive > delete
```

Several implementations were considered:

- nested Alpine component;
- inline fetch followed by reload;
- optimistic removal with undo.

When state complexity caused unstable behavior, a simpler inline fetch/reload approach was proposed for reliability. Later iterations moved toward more reactive lifecycle behavior.

## 8. Raw Material module maturity reported

The conversation eventually described the module as more than CRUD and listed the following capabilities:

- raw-material create/edit/list;
- image, category, purchase/base units, conversion, minimum stock;
- sealed/opened stock model;
- normalized total-stock calculation;
- restock;
- adjustment add/reduce;
- transaction history/recent activity;
- latest price and historical restock-price editing;
- active/inactive archive lifecycle;
- restore/undo notification;
- responsive modal interactions.

Core feature status was often described as complete or nearly complete, while visual polish and edge-case verification remained.

## 9. Sidebar structure discussion inside this source

The sidebar had become tightly coupled to:

- floating layout;
- Alpine open/close state;
- localStorage persistence;
- animated dimensions;
- active route styling;
- mobile/desktop variants.

Several structures were considered:

### Option A — `config/sidebar.php`

Move menu data to configuration while keeping renderer, style, and animation in Blade.

### Option B — role-specific partials

```text
resources/views/layouts/sidebar/
├── owner.blade.php
├── produksi.blade.php
└── armada.blade.php
```

### Evolved recommendation

For the current project size, role-specific partials were preferred first:

```text
app.blade.php = shell, renderer, and animation
role partials = role menu markup
```

`config/sidebar.php` was deferred until menu/submenu scale justified it, such as multiple report submenus or many roles.

One-level submenu depth was preferred for the narrow floating sidebar.

## 10. Report information architecture

### 10.1 Reports should be separated by domain

Instead of one large report page:

```text
Reports
├── Transactions / Inventory
├── Production
├── Finished Goods
└── Armada/Sales
```

### 10.2 Dashboard versus reports

Dashboard purpose:

- summary;
- alerts;
- a small number of KPIs;
- short trends.

Report purpose:

- analysis;
- detailed history;
- filters;
- pagination;
- export.

This prevented the dashboard from becoming a collection of every chart.

## 11. Transaction Report purpose

A major conceptual decision was:

> Transaction Report is primarily for audit and investigation, not forecasting.

Questions it should answer:

- when was a material restocked;
- who performed an adjustment;
- how much was purchased during a period;
- why did stock change;
- what was the purchase price;
- what were before/after stock values.

Forecast, days remaining, and purchase recommendation were assigned to later Inventory/ROP/MRP analysis rather than the transaction ledger page.

## 12. Transaction Report implementation evolution

### 12.1 Initial report scope

- summary/KPI cards;
- transaction table;
- search;
- transaction-type filter;
- timeframe/year/month filters;
- purchase-value chart;
- transaction composition chart;
- Excel export.

### 12.2 AJAX architecture

The report evolved toward:

- Alpine state for filters;
- native `fetch`;
- server-rendered table and KPI partials;
- AJAX pagination;
- URL synchronization through `history.replaceState()`;
- chart data updates without re-creating the chart instance;
- export using active filters.

### 12.3 Pagination fixes

The pagination handler was stored so it would not be registered repeatedly if the Alpine component was rebuilt.

Tests discussed included:

- page changes without full reload;
- filters preserved while changing pages;
- URL reflects filters/page;
- refresh restores filter state.

### 12.4 Chart lifecycle bugs encountered

Problems mentioned across iterations:

- chart container width zero;
- double render;
- triple render;
- duplicate chart instance;
- unstable Y-axis;
- animation not smooth;
- pie/donut overlap;
- chart grid escaping container;
- Lucide icons not reinitializing in replaced AJAX markup.

Corrections included:

- one Alpine component/source of state;
- update existing ApexCharts instance instead of rendering a new one;
- replace icons in AJAX partials with inline SVG where practical;
- separate fetch/update methods;
- ensure chart containers have valid dimensions.

These chart problems were later reported as resolved.

## 13. Filter-semantics evolution

Several alternatives were debated:

### Early unified-filter position

Make search/type/time filters affect KPI, table, chart, and export so the whole page appears synchronized.

Problem:

- Purchase Value chart uses restock transactions;
- selecting `Adjustment Reduce` would produce an empty or misleading purchase chart.

### Alternative special-case position

- KPI/table/export follow full filters;
- purchase chart follows search/time, not type;
- composition chart provides type-oriented analysis.

### Later locked handover decision

The source ultimately recorded the following as final for Transaction Report:

```text
Table and export filters:
- Search
- Transaction Type
- Year
- Month

Chart filters:
- Year
- Month
```

Reason given:

- search and transaction type are table/audit filters;
- purchase trend and transaction composition answer broader period questions.

This final decision should remain visible even though earlier alternatives are preserved as part of the evolution.

## 14. Transaction Report visual philosophy

The conversation repeatedly rejected adding visuals merely to make the dashboard look advanced.

Locked principle:

```text
Simple > Fancy
Informative > Decorative
Business Value > Number of Visuals
```

The final page was expected to keep a small number of visuals where each answered a different question, especially:

- Purchase Trend;
- Transaction Composition.

Transaction Report was eventually described as functionally complete, including filters, AJAX pagination, charts, responsive layout, and export.

## 15. FR/NFR alignment discoveries

The discussion compared implementation ideas with project functional requirements.

Key conclusions:

- a raw-material transaction report was a useful operational feature but was not necessarily stated explicitly in the initial FR;
- material-usage reporting was clearly aligned with the requirements;
- low-stock/ROP notification had stronger direct operational priority than adding decorative transaction charts;
- the stated waste requirement more clearly referred to unsuitable finished products than raw-material adjustment waste;
- Production Report is the more natural place for material consumption and production waste analytics.

## 16. Production Report direction

Production Report was separated from Transaction Report.

Expected content:

- production output trend;
- top produced menu;
- material consumption;
- most consumed material;
- usage per menu;
- waste summary;
- production history table;
- later production cost if a defensible cost basis exists.

The report was intended to answer:

```text
what was produced
what materials were consumed
what was wasted
what drives production volume/cost
```

rather than purchase-history questions.

## 17. `production_usage` discussion

A `production_usage` transaction type existed conceptually in reports, but stable consumption history was not yet available in this conversation.

The source recognized two possible approaches:

- dynamically calculate consumption from Production + BOM + Waste;
- persist a dedicated material-consumption ledger when production completes.

The more detailed decision continued in the handover conversation, but this source already established that usage belongs to production analytics, not purchase transaction analysis.

## 18. Decisions recorded

### Accepted/current within the conversation

- one global toast manager rather than duplicate systems;
- modal and server-rendered data may use a controlled reload for current-stage reliability;
- base-unit quantity is canonical;
- restock and adjustment remain semantically distinct;
- archive/restore is preferred over deletion;
- role-specific sidebar partials are a safe intermediate architecture;
- reports are split by domain;
- Transaction Report is audit-oriented, not predictive;
- Transaction Report remains visually restrained;
- Production Report owns material-consumption and waste insights;
- ROP alert is operationally more important than extra chart decoration.

### Deferred or optional

- no-reload update of every derived inventory element;
- `adjustment_reason` and optional estimated cost;
- full menu configuration in `config/sidebar.php`;
- mature forecast, coverage days, and purchase recommendation;
- consumption ledger implementation;
- production costing until a reliable cost model is defined.

## 19. Status reported near the end of the source

Reported as complete or sufficiently stable:

- Raw Material lifecycle core;
- adjustment/restock modal base;
- toast UX for the current phase;
- archive/restore/undo direction;
- Transaction Report functional scope;
- chart lifecycle bugs;
- responsive filters/table/chart layout.

Reported next focus:

- Production Report V1;
- material-consumption analysis;
- later ROP/coverage/procurement planning;
- keep reviewing FR/NFR so new features do not drift from the original purpose.

## 20. Verification warnings

- The conversation contains many successive versions; repository source is required to identify the exact surviving implementation.
- “Complete” is a reported conversational status, not runtime proof.
- Earlier filter recommendations were superseded by the later table-versus-chart filter decision.
- Toast strategy moved between no-reload and controlled reload; the final repository behavior must be verified.
- Some recommendations were intentionally future roadmap items and should not be treated as implemented.
