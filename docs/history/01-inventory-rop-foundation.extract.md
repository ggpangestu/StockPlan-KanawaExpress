# History Extraction — Telaah Inventory Control ROP

## Source metadata

- Source: `1TelaahInventoryROP.pdf`
- Source type: exported project conversation
- Historical role: foundational conversation for business requirements, ROP/MRP direction, authentication, roles, project setup, and the early Raw Material module
- Evidence boundary: this file records what was discussed, proposed, reported as completed, revised, or planned in the conversation. It does not prove the current repository state.

## 1. Conversation purpose

The conversation began by evaluating whether Reorder Point (ROP) and Material Requirement Planning (MRP) were appropriate for a mobile coffee business. It later expanded into the project’s business requirements, roles, architecture, technology stack, authentication, Git workflow, dashboard routing, authorization, and the initial implementation of Raw Material inventory.

Main objectives developed during the conversation:

- translate the coffee business process into a functional information system;
- distinguish minimum-stock warnings from a real ROP calculation;
- define a simplified MRP approach appropriate for an UMKM-scale operation;
- build a usable project within a roughly 2–3 month academic timeline;
- establish a Laravel foundation that could grow into inventory, production, distribution, sales, and reporting;
- begin implementing authentication, role access, dashboard routing, and Raw Material management.

## 2. Business context established

### 2.1 Operating model

The business was described as a mobile express coffee operation:

- coffee is prepared at a production house before selling;
- drinks are pre-mixed per cup according to a fixed menu recipe;
- the field seller mainly adds ice and serves the customer;
- sales are carried out using modern mobile carts/armada;
- three armada were mentioned;
- each armada typically carries approximately 20–30 cups per day;
- morning production occurs before business hours;
- purchasing may happen daily or weekly because demand fluctuates;
- unsold products may be stored in a chiller and identified for reuse if still suitable;
- operational recording was initially manual or spreadsheet-based.

### 2.2 Information the owner needs

The system was expected to help answer questions such as:

- how many cups are sold per menu;
- which menu is most popular;
- how much of each material is needed per cup;
- how many cups can be produced from one purchase package;
- how much should be produced for the next operating period;
- when raw material should be purchased again;
- how much material is needed weekly or monthly;
- how products are distributed to each armada;
- how much is sold, returned, or wasted;
- how commission and reports can eventually be calculated.

### 2.3 Example recipe context

The discussion used examples in which a menu consumes fixed amounts of materials such as:

- coffee/espresso;
- UHT or full-cream milk;
- liquid palm sugar;
- creamer;
- cups/packaging.

The central calculation was:

```text
menu production quantity
× recipe quantity per cup
= material requirement
```

This became the conceptual basis for Recipe/BOM, production material deduction, MRP-like planning, and stock forecasting.

## 3. ROP analysis and evolution

### 3.1 Initial request

The initial requirement proposed color indicators:

- red: stock has reached the minimum and must be purchased;
- yellow: stock is thinning and must be monitored;
- green: stock is safe.

### 3.2 Important conceptual correction

The conversation distinguished two different concepts:

```text
Static minimum-stock alert
≠
Statistical Reorder Point
```

A full ROP was described with the general concept:

```text
ROP = average usage × lead time + safety stock
```

A true ROP therefore requires:

- historical usage or demand;
- supplier lead time;
- safety stock or an agreed buffer;
- a consistent recipe and unit basis.

A color threshold based only on manually entered `minimum_stock` was recognized as a useful feature, but not a complete ROP engine.

### 3.3 Where ROP applies

ROP was intended for raw materials, not cups as an abstract product count.

Example logic discussed:

```text
average cups sold
× ingredient quantity per cup
= average daily material use
```

That material usage can then be multiplied by lead time and combined with safety stock.

### 3.4 Data limitation and phased implementation

No reliable pre-digital historical dataset existed. The user proposed approximately one month of operational testing/data collection.

The conversation accepted a phased strategy:

#### Phase 1 — Data collection

Capture at least:

- initial stock;
- incoming stock;
- outgoing/used stock;
- transaction dates;
- daily production/sales usage;
- supplier delivery time where available.

Expected output:

- average daily usage;
- an initial view of demand variation;
- initial supplier lead-time observations.

#### Phase 2 — Simple or semi-ROP

After an initial baseline, use a simple calculation such as:

```text
average daily usage × lead time
```

A temporary conservative safety percentage could be used while data remains limited.

#### Phase 3 — More mature planning

After a longer history, potentially introduce:

- moving average or weighted moving average;
- variation-based safety stock;
- a more defensible reorder quantity;
- more accurate weekly/monthly breakdowns.

One month was considered enough for a baseline, but three to six months was discussed as more appropriate for mature forecasting.

### 3.5 ROP status at the end of this source

- The concept was accepted.
- A minimum-stock visual alert was suitable for early implementation.
- Full ROP remained a later phase dependent on operational history and lead-time data.
- It was not appropriate to claim that a complete ROP engine already existed.

## 4. MRP analysis and evolution

### 4.1 Initial request

The requested MRP-related output included:

- recommended purchase quantity;
- monthly material requirements;
- weekly breakdown;
- calculations based on stock and historical usage;
- reduced overstock, repeated purchasing, and material leakage.

### 4.2 Academic clarification

A strict industrial MRP normally requires:

- Master Production Schedule;
- Bill of Materials;
- inventory on hand;
- lead times;
- time-phased requirements and planned orders.

The coffee operation did not initially have multi-level assemblies or an industrial production structure. Therefore, the safer academic framing was:

> The system adopts ROP principles and a simplified MRP approach adapted to an UMKM-scale mobile coffee business.

### 4.3 Simplified MRP accepted direction

The business was still suitable for a simplified form because it has:

- planned cup output;
- fixed recipes;
- stock on hand;
- expected sales or distribution quantities;
- a need to calculate raw-material requirements before production.

The conceptual flow became:

```text
planned output per menu
→ expand recipe/BOM
→ calculate gross material requirement
→ compare with available stock
→ calculate shortage/net requirement
→ later recommend purchase quantity
```

### 4.4 EOQ and forecasting discussion

EOQ was discussed as an option only if economic inputs exist, such as ordering cost and holding cost. Without those inputs, a quantity should not be described as economically optimal.

Forecasting options discussed:

- moving average;
- weighted moving average;
- linear regression;
- simple averages during the early data-collection phase.

### 4.5 MRP status at the end of this source

- simplified MRP remained an accepted long-term direction;
- the first implementation priority was data capture, BOM, production planning, and stock movement;
- advanced purchase recommendations were explicitly deferred until data and production foundations were stable.

## 5. Functional scope and system workflow discussed

The conversation gradually defined a mini-ERP flow:

```text
Authentication
→ Raw Material Inventory
→ Recipe/BOM
→ Production Planning
→ Production Execution
→ Finished Goods
→ Distribution to Armada
→ Sales/Returns
→ Commission
→ Reporting and Analytics
```

### 5.1 Owner responsibilities discussed

- manage raw materials;
- record raw-material purchases/restock;
- manage menu recipes;
- view raw-material and finished-goods stock;
- identify reusable previous products;
- create armada distribution plans;
- review production requirements;
- approve or change production plans;
- set armada commission percentages;
- view sales, popular-menu, material-usage, waste, and optional profit reports.

### 5.2 Produksi responsibilities discussed

- receive production requirements;
- view planned production;
- perform production;
- input production results;
- support urgent additional production when armada requests more stock.

### 5.3 Armada responsibilities discussed

- receive distributed finished products;
- record sold quantity;
- input daily expenses where required;
- return unsold products;
- access role-specific information.

### 5.4 Automated system responsibilities discussed

- calculate required production from distribution and usable existing stock;
- calculate material requirements from recipes;
- calculate net requirements;
- generate later purchase recommendations;
- notify low stock;
- calculate sales income and commission.

### 5.5 Important workflow decision

A key flow was established:

```text
Owner sets distribution target per armada
→ system calculates additional production requirement
→ existing usable finished goods are considered
→ production fulfils the remaining requirement
```

“Usable previous product” was treated as part of the Finished Goods lifecycle rather than a completely separate main module.

## 6. Architecture and technology decisions

### 6.1 Delivery model

Two future models were considered:

- SaaS/multi-tenant;
- self-hosted or one system per business.

The accepted current direction was:

```text
Phase 1: single business / single tenant
```

Future multi-tenant readiness was discussed as an architectural possibility, but adding `business_id` to every domain table was not treated as an immediate implementation requirement.

### 6.2 Technology stack selected

The initial recommended stack was:

- Laravel;
- Laravel Breeze authentication;
- Blade;
- Tailwind CSS;
- Alpine.js;
- MySQL;
- Vite.

Vue/React full SPA was intentionally deferred because:

- it would introduce an API/auth/state-management layer;
- the project timeline was short;
- most interfaces were forms, tables, modals, and reports;
- Blade + Alpine was sufficient for the expected interaction level;
- project effort should focus on inventory and production logic.

### 6.3 General architectural direction

- Laravel monolith for the initial project;
- session authentication;
- role-based authorization;
- one shared application shell;
- server-rendered pages with Alpine for targeted reactivity;
- avoid premature abstraction and complex frontend frameworks.

## 7. Authentication and user-model development

### 7.1 Breeze usage

Breeze was kept as the authentication foundation, while the login user interface was customized rather than using the default appearance.

### 7.2 Username login decision

For the internal business context, username-based login was preferred over requiring email.

Changes discussed included:

- add `username` and `role` to `users`;
- make email optional or remove it from the operational login contract;
- update `LoginRequest` validation and `Auth::attempt()` to use username;
- include username in the rate-limiter key;
- preserve remember-me behavior;
- seed test accounts.

### 7.3 User model correction

The conversation rejected an unusual attribute-style fillable definition and recommended standard Laravel properties:

```php
protected $fillable = [
    'name',
    'username',
    'email',
    'password',
    'role',
];
```

The motivation was ecosystem consistency, easier maintenance, and reliable mass assignment for username and role.

### 7.4 Seeder and test users

A `UserSeeder` was created or planned for:

- owner;
- produksi;
- armada.

A development password such as `123456` was used for test accounts.

A missing `Hash` import in the seeder was identified and corrected using `Illuminate\Support\Facades\Hash`.

### 7.5 Login UI status reported

The login was repeatedly reviewed and eventually described in the conversation as a completed production foundation for an internal application, including:

- responsive custom layout;
- remember me;
- invalid credential feedback;
- rate limiting/lockout feedback;
- password visibility interaction;
- loading/disabled states;
- custom visual identity.

The conversation explicitly recommended freezing the login design and moving to roles/dashboard rather than continuing micro-polish.

## 8. Role and dashboard architecture

### 8.1 Roles established

The active role vocabulary discussed was:

```text
owner
produksi
armada
```

### 8.2 One login and one dashboard route

The preferred design was:

- one `/login` entry;
- one `/dashboard` route;
- a controller reads the authenticated role;
- separate dashboard views are rendered for owner, produksi, and armada;
- unsupported roles receive HTTP 403.

This avoided duplicated dashboard routes and layouts while still allowing role-specific content.

### 8.3 DashboardController evolution

Several equivalent access styles were discussed:

- `auth()->user()->role`;
- `Auth::user()->role`;
- `request()->user()->role`.

An “undefined method user” warning was identified as primarily an IDE/Intelephense typing issue rather than necessarily a Laravel runtime problem. The important final concept was role-based dispatch, not one particular helper spelling.

### 8.4 Authorization bug discovered

A produksi account could initially access `/owner/raw-materials` because routes had only `auth` middleware. The conversation emphasized:

```text
URL prefix is not authorization.
```

A custom role middleware was introduced so owner routes require both:

```text
auth
role:owner
```

This became an important security baseline.

## 9. Raw Material domain design

### 9.1 Main data model developed

The conversation evolved toward a `raw_materials` structure containing:

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
- creator.

### 9.2 Sealed and opened stock semantics

- `sealed_stock`: whole unopened purchase packages;
- `opened_stock`: active quantity expressed in the base unit;
- `conversion_value`: base-unit amount contained in one purchase package.

The central formula was:

```text
total_stock = opened_stock + sealed_stock × conversion_value
```

`total_stock` was intended as computed backend logic/accessor rather than an additional database column.

### 9.3 Base unit as canonical quantity

The conversation repeatedly established the base unit as the normalized internal quantity for:

- transaction quantities;
- recipe/BOM quantities;
- total stock;
- production consumption;
- reporting;
- future prediction.

This was one of the strongest architectural decisions in the source.

### 9.4 Create versus restock separation

An early version allowed stock and price to be entered during raw-material creation. This was revised.

Accepted direction:

```text
Create material master
→ initial stock = 0
→ stock enters through restock or adjustment
```

Reasons:

- cleaner audit trail;
- master-data creation is different from stock movement;
- purchase history should be captured as a transaction;
- inventory state should not appear without an explanatory movement.

### 9.5 Image upload correction

The initial controller risked saving the uploaded-file object rather than a stored path. The revised direction used public storage and persisted the resulting file path.

### 9.6 Restock architecture

Restock was separated into its own operation:

- quantity entered in purchase packages;
- converted to the base unit;
- added to sealed stock;
- optional/current purchase price updated;
- before/after total stock recorded;
- transaction history created;
- stock update and transaction creation wrapped in `DB::transaction()`.

The reason for a database transaction was explicit: stock state and audit history must remain synchronized and roll back together if either step fails.

### 9.7 Adjustment concept

Adjustment was treated separately from restock:

```text
Restock = purchase/acquisition event
Adjustment = correction to inventory state
```

This distinction later supported adjustment add and adjustment reduce without automatically assigning purchase value.

### 9.8 Raw Material UI direction

The preferred list interface evolved toward:

- compact primary table;
- expandable row details;
- primary visibility of total stock and status;
- expanded information for sealed stock, conversion, minimum, latest price, and transaction activity;
- clearer UI wording such as “Package Size” while preserving `conversion_value` in backend naming.

## 10. Git and collaboration workflow

The conversation documented a team workflow:

```text
main = stable/production

dev = integration/development

feature and fix branches start from updated dev
```

Typical feature flow:

```bash
git checkout dev
git pull origin dev
git checkout -b fitur/nama-fitur
# work
git add .
git commit -m "feat: ..."
git push origin fitur/nama-fitur
# open pull request to dev
```

Bug fixes were also expected to use a new branch from `dev`.

Other decisions:

- do not push `.env`, `vendor`, or `node_modules`;
- use meaningful commit messages;
- do not push directly to `main`;
- test before creating a pull request;
- CRLF/LF warnings on Windows were treated as normal line-ending notices rather than code errors.

## 11. Major decisions recorded

### Active or accepted in this conversation

- Use Blade + Alpine rather than a full SPA for the initial project.
- Use Breeze as the authentication foundation with a custom login UI.
- Use username-based operational login.
- Use roles `owner`, `produksi`, and `armada`.
- Use one dashboard route with role-specific views.
- Protect owner functionality with backend role middleware.
- Normalize inventory quantities into a base unit.
- Separate raw-material master creation from stock movement.
- Represent unopened packages and opened base-unit stock separately.
- Record restock and adjustment as inventory transactions.
- Use a phased ROP/MRP implementation based on collected history.
- Treat the first release as single-business/single-tenant.

### Discussed but deferred

- full statistical ROP;
- EOQ-based optimal purchasing;
- mature demand forecasting;
- full industrial MRP;
- multi-tenant SaaS implementation;
- Vue/React SPA architecture;
- complex permission packages.

## 12. Bugs and corrections discussed

- Seeder failed to resolve `Hash` → import the Hash facade.
- Login still used email/default Breeze assumptions → update LoginRequest and UI for username.
- IDE reported `user()` as undefined → recognized as an IDE typing issue; alternative access patterns discussed.
- Role-protected URL was accessible by any authenticated user → add role middleware.
- Raw-material image handling saved the wrong value → store the file and persist its path.
- Raw-material creation mixed master data and initial stock → move stock entry to restock/adjustment.
- Status initially considered only opened stock → calculate status from normalized total stock.
- Conversion wording was ambiguous → keep backend name but improve user-facing terminology.

## 13. Status reported near the end of this conversation

Reported as stable or substantially completed:

- custom authentication/login foundation;
- remember-me and role redirect foundation;
- role middleware;
- dashboard dispatch;
- application stack and branch workflow;
- initial Raw Material data model and CRUD foundation;
- normalized total-stock concept.

Still active or next:

- complete and polish Raw Material create/edit/list workflow;
- build restock and transaction history cleanly;
- continue dashboard/sidebar architecture;
- later implement Recipe/BOM, production, distribution, sales, reporting, ROP, and simplified MRP.

## 14. Important unresolved or verification items

- Exact final Laravel version cannot be established from this conversation alone; Laravel 11 was discussed during setup.
- The final treatment of email in the user table requires repository verification.
- The exact final login helper implementation should be verified in source.
- Reported login quality was a conversation assessment and should not replace runtime/security verification.
- Multi-tenancy was conceptual only.
- ROP/MRP algorithms were not reported as fully implemented.
- Raw Material code evolved during the conversation; final file contents require repository comparison.

## 15. Handover summary

This conversation created the project’s conceptual and technical foundation. The strongest persistent ideas were:

```text
fixed recipes
+ normalized base-unit stock
+ transaction-oriented inventory
+ role-separated operations
+ phased data collection
= foundation for production planning, ROP, simplified MRP, and reporting
```

It should be read as the origin of project intent and early implementation decisions, not as a current source-code audit.
