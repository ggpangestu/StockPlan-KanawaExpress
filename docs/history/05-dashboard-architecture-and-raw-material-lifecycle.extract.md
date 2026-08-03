# History Extraction — StockPlan Dashboard Architecture

## Source metadata

- Source: `5StockPlan Dashboard Architecture.txt`
- Source type: exported project conversation
- Historical role: long implementation conversation connecting production-ready login, dashboard shell, sidebar, role routing, Raw Material lifecycle, transaction architecture, archive/toast UX, and reporting foundations
- Evidence boundary: this is a historical extraction. The final repository state must be determined from source-code audit.

## 1. Conversation purpose

The conversation began immediately after the custom login was considered production-ready. Its initial goal was to build a scalable authenticated application shell and sidebar. It later became the main implementation history for:

- dashboard and role routing;
- `layouts/app.blade.php` architecture;
- sidebar active state and persistence;
- role middleware;
- Raw Material CRUD;
- stock conversion and transaction history;
- restock and adjustment flows;
- archive/restore and toast behavior;
- transaction report foundations;
- project design principles.

## 2. Login foundation carried into this conversation

The conversation treated these as established context:

- Laravel Breeze provides authentication logic;
- login UI is custom rather than Breeze’s default appearance;
- username is the operational login identifier;
- remember-me, throttling, and feedback were already handled;
- project roles are owner, produksi, and armada;
- login should no longer be repeatedly redesigned.

## 3. Dashboard layout correction

### 3.1 Initial incorrect suggestion

A new `layouts/dashboard.blade.php` with extracted sidebar/navbar components was initially suggested.

### 3.2 Context correction

The user clarified that the existing architecture already placed the sidebar directly in:

```text
resources/views/layouts/app.blade.php
```

The recommendation was corrected.

### 3.3 Accepted application-shell architecture

```text
layouts/app.blade.php
├── mobile layout
├── desktop layout
├── floating/collapsible sidebar
├── responsive behavior
├── Alpine state
└── @yield('content')
```

Role pages use:

```blade
@extends('layouts.app')

@section('content')
    ...
@endsection
```

This made `app.blade.php` the authenticated application shell rather than a generic wrapper.

A second dashboard layout was rejected because it would duplicate layout responsibility and complicate maintenance.

## 4. Dashboard route and role-view decision

The conversation compared:

- separate routes such as `/owner/dashboard`, `/produksi/dashboard`, `/armada/dashboard`;
- one `/dashboard` entry that renders role-specific views.

Accepted direction:

```text
one login route
one dashboard route
separate role views/content
one shared application shell
```

Reason:

- producing and armada dashboards were small;
- separate layout/controller trees would duplicate logic;
- differences were primarily access and content, not separate applications.

## 5. Role authorization

### 5.1 Initial gap

Routes under `/owner/...` were initially protected only by `auth`, allowing Produksi or Armada users to access them directly.

### 5.2 Principle established

```text
URL naming is not authorization.
```

### 5.3 Role middleware

A custom role middleware was introduced or planned and registered so route groups could use:

```text
auth
role:owner
```

Equivalent groups would protect Produksi and Armada functionality.

This became the main backend authorization model for the project phase.

## 6. Sidebar architecture

### 6.1 Initial sidebar qualities

The existing sidebar was judged stronger than a simple animated menu example because it already supported:

- application content layout;
- desktop/mobile responsiveness;
- collapsible/floating behavior;
- icons;
- active route state;
- overflow handling;
- localStorage persistence;
- integration with the authenticated shell.

A separate creative burger-menu example was considered visually interesting but architecturally unsuitable as a full dashboard shell.

### 6.2 Hardcoded duplication concern

The layout contained repeated mobile/desktop links and direct role conditions. Risks:

- duplicated route/label/icon definitions;
- harder active-state maintenance;
- role sections growing inside `app.blade.php`;
- future report submenus increasing complexity.

### 6.3 Architecture alternatives

- central menu array/configuration;
- reusable menu-item component;
- role-specific sidebar partials;
- later permission-driven configuration.

After inspecting the complex animation and persistence logic, the safest intermediate choice became:

```text
app.blade.php = shell, styling, Alpine state, transitions
layouts/sidebar/{role}.blade.php = role-specific menu markup
```

Config-driven menus were deferred until menu/submenu scale justified them.

## 7. Sidebar animation and paint problems

Several sidebar problems were discussed across iterations:

- animation during refresh instead of only user interaction;
- main content shifting after Alpine hydration;
- burger state flashing;
- initial localStorage state applied too late;
- repeated transition-control flags becoming complicated.

The preferred architectural principle became:

```text
Set the persisted state before first paint.
Animate only after user interaction.
```

The main content offset needed to be initialized consistently with the sidebar state, not corrected after Alpine hydration.

Later handover sources reported this area as complete, but this conversation preserves the debugging path.

## 8. Raw Material route and controller foundation

An initial resource route was created under an owner prefix. The controller began as a generated resource controller with empty actions.

Early corrections included:

- route naming consistency under `owner.raw-materials.*`;
- correct view folder structure;
- implementation of index and create flow;
- backend role protection;
- avoiding route names that did not match generated resource names.

The recommended development sequence was:

```text
create form UI
→ validation
→ store logic
→ index/list table
→ restock
→ transaction history
```

## 9. Raw Material data model evolution

Fields discussed and implemented over time:

- image;
- name;
- category;
- purchase unit;
- base unit;
- conversion value;
- sealed stock;
- opened stock;
- minimum stock;
- latest price;
- active state;
- created by.

### 9.1 Stock formula

```text
total_stock
= opened_stock
+ sealed_stock × conversion_value
```

The conversation moved status/health logic away from `opened_stock` alone and toward normalized total stock.

### 9.2 Terminology

Backend retained:

```text
conversion_value
```

User-facing language was refined toward clearer wording such as:

```text
Package Size
1 purchase unit = N base units
```

### 9.3 Create-state revision

An early form accepted sealed/opened stock and latest price directly. This was revised:

- create stores master data;
- image file is stored and its path persisted;
- initial sealed/opened stock is zero;
- stock enters through a transaction flow;
- first purchase price can be captured during restock.

This reinforced auditability.

## 10. Raw Material UI evolution

The index page was designed around:

- a compact active-material table;
- expandable detail rows;
- total stock as the primary operational number;
- status badges;
- details for sealed/opened stock, package conversion, minimum, price, and recent activity;
- responsive behavior for tablet/laptop/desktop.

The expandable-row approach was explicitly retained.

## 11. Transaction-oriented inventory architecture

### 11.1 Raw material transaction model

The module evolved beyond direct stock mutation to preserve movement history.

Important transaction information discussed:

- raw material;
- transaction type;
- quantity normalized to base unit;
- before stock;
- after stock;
- notes;
- creator;
- purchase quantity/price/value where applicable.

### 11.2 Core principle

```text
Inventory movement should be explainable through transactions.
```

### 11.3 DB transaction

Stock mutation and history insertion were wrapped in a database transaction so they either both succeed or both roll back.

## 12. Restock workflow

Restock architecture developed as:

```text
input purchase-package quantity
→ convert to base units
→ increase sealed stock
→ update latest price when provided
→ calculate after stock
→ create restock transaction
→ return redirect or AJAX response
```

Restock was intentionally separate from material creation and from generic adjustment.

A standalone restock page was initially considered/implemented, then modal-based restock became preferred for consistency with the index workflow.

## 13. Adjustment workflow

Adjustment supports correction rather than purchase:

- add opened base-unit stock;
- reduce opened base-unit stock;
- notes/context;
- before/after snapshots;
- transaction type.

The conversation later recognized that a reason taxonomy would improve clarity, but this remained a future enhancement.

## 14. Archive, active state, and undo

Hard deletion was avoided in favor of lifecycle state:

```text
active
↔ inactive/archive
```

The UX explored:

- toggle active state;
- row removal from active list;
- archived section/view;
- restore;
- undo countdown toast;
- optimistic versus reload-based updates.

A nested Alpine toggle component caused instability in one iteration. A simpler inline fetch plus reload was proposed as a stable fallback. Later iterations pursued smoother reactive behavior.

The broader decision remained:

```text
archive, do not delete operational master records
```

## 15. Toast evolution in this conversation

The Raw Material page developed a responsive top-center toast stack.

Improvements discussed:

- responsive width by screen size;
- countdown/progress;
- multiple toast support;
- undo action for archive;
- smooth enter/leave transitions;
- avoid blocking operational flow.

The source later stated the toast stack was professional and suitable for the project stage, although another conversation continued debugging adjustment flicker.

## 16. Edit restrictions and immutability principles

As transaction history accumulated, some material fields were considered operationally unsafe to change freely.

The project direction became:

- identity/display fields may remain editable;
- purchase/base units and conversion should be frozen after transaction history exists;
- transaction history should not be rewritten casually;
- archive is preferred over deletion.

These decisions protected historical interpretation and later reporting.

## 17. Price and cost handling

Features discussed or added:

- latest purchase price;
- price required on first restock;
- optional update on later restock;
- edit restock transaction price;
- recalculate transaction total;
- use normalized conversion for later HPP.

The conversation recognized that current price and historical cost may eventually need separate accounting rules.

## 18. Report foundations developed

The transaction history created enough data for an initial report.

The report was expected to use fields such as:

- type;
- quantity;
- before/after stock;
- price and total purchase value;
- creator;
- timestamp.

The conversation separated:

- dashboard summary/alerts;
- detailed report/filter/export pages.

It also established that prediction should wait for production usage and historical data rather than being forced into the transaction report.

## 19. Stable core principles explicitly stated

The source eventually summarized the project’s stable direction:

### Inventory

- normalized stock;
- base unit as source of truth;
- transaction-oriented movement;
- immutable historical meaning;
- archive rather than delete;
- lifecycle-oriented modules.

### UX

- reactive where valuable;
- avoid reload when it does not duplicate business logic;
- optimistic UI with failure/undo considerations;
- operational and archived views separated;
- consistent modal/toast patterns.

### Architecture

- Laravel backend;
- Blade application shell;
- Alpine reactive layer;
- inventory-first foundation;
- later Recipe/BOM, production usage, analytics, forecasting, and Finished Goods.

The project was described as moving beyond a CRUD exercise into system design.

## 20. Major decision evolution

### Dashboard layout

```text
new dashboard layout suggested
→ corrected to existing app.blade shell
→ app.blade remains canonical authenticated shell
```

### Dashboard routes

```text
separate role dashboard routes considered
→ one /dashboard route with role-specific views accepted
```

### Sidebar data

```text
hardcoded links
→ central array/config proposed
→ after inspecting complex UI, role partials preferred first
```

### Raw Material creation

```text
create master with stock/price
→ create master at zero stock
→ restock/adjustment establish auditable stock movement
```

### Archive toggle

```text
nested reactive component
→ unstable behavior
→ simpler fetch/reload fallback
→ later reactive/undo refinement
```

## 21. Reported module status near later portions

The conversation reported or implied:

- authentication foundation complete;
- role middleware complete;
- application shell/sidebar substantially complete;
- Raw Material core nearly or functionally complete;
- restock, adjustment, price edit, transaction history, and archive lifecycle available;
- remaining work increasingly consisted of UX polish and business-module expansion rather than foundational uncertainty.

Next modules discussed:

- Transaction/Inventory Report;
- Recipe/BOM;
- production;
- Finished Goods;
- forecasting/ROP later.

## 22. Bugs and corrections recorded

- Incorrectly suggested a second dashboard layout → retained `layouts/app.blade.php`.
- Owner routes accessible by other roles → added role middleware.
- Resource route/view naming inconsistencies → standardize owner-prefixed route names and folders.
- Image upload did not persist a path → store file and save path.
- Create form mixed stock master and transactions → initial stock zero, separate restock.
- Status used opened stock → use normalized total stock.
- Conversion label confused users → improve UX wording.
- Nested active toggle appeared to do nothing → simplify state/fetch/reload.
- Sidebar state painted late → initialize before paint and animate only on user interaction.
- Toast widths were too rigid → responsive width and spacing.

## 23. Repository-verification notes

- This source contains many full-code replacements and intermediate versions; only repository audit can identify the final files.
- Some conversation claims describe intended architecture rather than completed implementation.
- Later audit found additional modules created by the collaborator that are not fully explained here.
- Runtime completeness, test coverage, and concurrency safety were not established by this conversation.

## 24. Handover summary

This source documents how StockPlan moved from a finished login page into a coherent inventory application shell and a transaction-oriented Raw Material lifecycle.

The persistent design formula was:

```text
shared app shell
+ role middleware
+ normalized base units
+ sealed/opened stock
+ auditable movements
+ archive lifecycle
+ restrained reactive UX
```

Those principles became the foundation for the later BOM, production, Finished Goods, distribution, and reporting modules.
