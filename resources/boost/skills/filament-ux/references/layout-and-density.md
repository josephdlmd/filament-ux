# Layout and density

How to lay out an information-dense business tool with native Filament components and the Noir and Compact themes. Density means more useful information per screen for people who come back daily, never less legibility: the accessibility floor in rule 26 always wins. Tags **C**, **S**, **P** as defined in `SKILL.md`.

## What the themes do (Filament 5.x)

- **Compact** (from 640px wide): table rows about 36px instead of about 52px (cell padding 8px), icon buttons 32px (28px at xs/sm), page headings and stat values one size smaller. Below 640px, including at 400% zoom, Filament's default sizes return.
- **Noir** changes colours, surfaces and corner radius only, never sizes. It makes `primary` zinc, so `primary` carries no meaning.
- **Filament defaults** the rules override: content capped at 80rem, sidebar 20rem, tables 10 rows per page.

## The panel

1. **Lists and record pages use the full width.** `maxContentWidth(Width::Full)` on the panel; settings pages and long forms cap their own width with `getMaxContentWidth()`. **C** (Carbon, Fluent, Atlassian) plus **P**.
2. **A slim, collapsible sidebar.** `sidebarWidth('16rem')` and `sidebarCollapsibleOnDesktop()` (icons stay visible). **P**.
3. **Daily work first in the menu.** `profile.primary_records` at the top in that order; everything else in navigation groups with an explicit `$navigationSort`. A role never sees an item it can't use. Counts appear on queue items only (`getNavigationBadge()` for "To send", "Overdue"), never on plain record lists. **S** (NN/g menus) plus **P**; queue counts follow Shopify and Linear on Mobbin.
4. **Navigation groups by default; clusters for peers.** A Cluster for peer pages people switch between sideways (settings, reference lists); record sub-navigation for three or more pages about one record (`SubNavigationPosition::Top` up to 5 items, `Start` beyond). **S** plus **P**.

## The page

5. **One primary action per screen,** chosen for the role viewing it. Other header actions are `gray` or in an `ActionGroup` ("…"). **C** (GOV.UK, NN/g; Mobbin).
6. **Every page names its record.** Every resource sets a record title (identifier and name), so headings and breadcrumbs name the record; a subheading is at most one line of status. **C** (NN/g, GOV.UK breadcrumbs).
7. **A state that needs action is a Callout at the top,** conditional, with its fixing action. Field guidance is helper text; outcomes are notifications. **C** (GOV.UK banner, inset and warning text).
8. **Pop-up size fits the task.** Confirmations and one to three fields: a modal (`Width::Medium`). A form that needs the list or record beside it: `slideOver()`. Long edits with uploads: a full page. An action opens the same way everywhere. **S** (NN/g modals) plus **P**.
9. **Simple resources for flat reference lists.** A few short fields, no uploads, no related lists: `ManageRecords` with modal create and edit. Anything with related records or uploads gets full pages. **S** plus **P**.

## Record pages

10. **Two columns on wide screens.** Main content (two thirds: the working fields and related records) and a details aside (one third: identifiers, status, owner, dates) using a `Grid` with column spans, collapsing to one column below `lg`. **P** (Mobbin: Stripe, Shopify, Linear, Attio).
11. **A figures strip under the title.** Up to five key figures (totals, balances, counts), larger than body text, in one row. **P** (Mobbin).
12. **View page when records are looked up more than changed,** or roles differ; otherwise the row opens Edit. `profile.users = daily`: allow per-section edit actions on the View page for frequent small changes. Relation managers stay read-only on View unless the View page is the working surface. **P**; Mobbin shows both.
13. **Related data by lifecycle.** `Select` to pick one; `Repeater` for a few owned rows saved with the parent; relation manager for independent records with their own actions; `ManageRelatedRecords` when that list is a daily task in itself. **P**.
14. **Relation managers most used first;** group rarely used ones with `RelationGroup` from four; never combine them with the details tab on View pages, so the summary stays visible. **S** plus **P**.
15. **Previous and next on records people work through in order** (a queue): header actions that keep the list's filters. **P** (Mobbin).

## Sections, tabs and grids

16. **Scrolling Sections by default.** Tabs only for a few short-labelled groups where the first is what most people need and nobody compares across them: at most 5 for `profile.users = daily`, none for occasional users. Tabs are horizontal, one row, never nested, and call `persistTabInQueryString()`; never disable a tab. Tabs on record pages usually hold related records, with the summary kept visible. **C** (NN/g, GOV.UK, Fluent; Mobbin).
17. **Fieldset, Section and Grid mean different things.** Fieldset binds inputs that answer one question (an address). Section is a topic with a heading. Grid, Group, Flex and FusedGroup are layout with no heading. Collapse only rarely used Sections, with `persistCollapsed()`, never one holding a required field, and never more than one collapsed level. **C**.
18. **Dense inside a group, space between groups.** Use `Section->compact()` and `aside()` to tighten, never nest more than one level of bordered containers, and separate topics with Section spacing rather than extra wrappers. **C** (Carbon, Atlassian spacing).
19. **Read-only grids widen with the screen.** Infolists: 1 column, 2 from `lg`, 3 from `2xl` (`columns(['default' => 1, 'lg' => 2, '2xl' => 3])`). Only short, related fields share a row. **C** (Carbon, Fluent grids).
20. **Forms are one column.** Only short, related inputs share a row (City · Postcode). **C** (NN/g forms, GOV.UK).
21. **Same fields, same order, everywhere.** Create, Edit and View build from shared schema methods in one order. **S** (NN/g heuristic 4).
22. **Inline labels only in the read-only details aside** (`inlineLabel()` on entries). Forms keep labels above their fields. **C** (NN/g forms, Pajamas).

## Tables

23. **Twenty-five rows per page by default** (options 25, 50, 100), set once with `Table::configureUsing()`. **C** (Pajamas, Carbon; the most common default on Mobbin).
24. **A column budget.** At most 8 visible columns plus the action column at `profile.min_screen_width` 1440px (6 at laptop widths). The identifier comes first, then the columns people decide on; the rest are `toggleable(isToggledHiddenByDefault: true)`. `striped()` from 6 columns. A second line goes in `description()` rather than a new column. At most one inline row action; the row click opens the record. **C** (NN/g data tables, Carbon; Mobbin).
25. **Wrap names, clamp free text only when the full text is one click away.** Identifiers and names `wrap()`. Notes and descriptions use `limit()`/`lineClamp()` only when every role that sees the column can open the full text (the View page or an action), because a tooltip can only repeat it. Badge lists use `limitList()`. **C** (WCAG 1.4.13, NN/g).

## The floor and the limits

26. **Density never breaks the accessibility floor.** Targets at least 24px (2.5.8), no loss at 200% text zoom (1.4.4), reflow at 400% (1.4.10), and no fixed-height text boxes (1.4.12). Compact already meets this; custom CSS must too. **C** (WCAG 2.2).
27. **Three text sizes per section.** Body `sm`, secondary `xs` at the smallest, figures `lg`; weight and colour carry the rest of the hierarchy. **C** (Carbon, Atlassian type scales).
28. **Compact is for trained daily users.** Use the Compact theme when `profile.users = daily`. Rare or long forms keep default spacing, and a Section with a validation error stays open. **C** (MDC density guidance, Carbon productive vs expressive) plus **P**.
