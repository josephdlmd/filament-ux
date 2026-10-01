---
name: filament-ux
description: "Sourced UX rules for information-dense Filament business tools. Use when building, changing or reviewing any Filament screen: panel navigation, resource pages, forms, tables, filters, bulk actions, import and export, dashboards and charts, notifications and copy, sign-in and account settings, AI suggestions."
---

# Filament UX for information-dense business tools

These rules make a Filament panel carry more useful information per screen for people who use it daily, using native components and the Noir and Compact themes only. Every rule is backed by published guidance (NN/g, GOV.UK, WCAG 2.2, NIST, Microsoft, Carbon, Atlassian, Fluent), checked against current SaaS practice on Mobbin, and tagged:

- **C** consensus: two or more independent sources agree
- **S** single source
- **P** project call: a deliberate choice; follow it, but don't present it as industry practice

Sources and their dates: `references/sources.md`.

## Steps

1. **Read the app's profile.** Open `.ai/ux-profile.md`. Rules written as `profile.users`, `profile.currency` and so on take their values from it. If it doesn't exist, copy `references/profile-template.md` there, fill in what the codebase tells you (currency, timezone, glossary file, theme plugins in the panel provider), and ask the user for the rest (`users`, `data_volume`, `signup`, `mfa`, `shared_devices`).
2. **Read the app's own rules.** App-specific rules in `.ai/rules/` override these where they conflict; say so when one does.
3. **Load the reference for every area the change touches:**

| Touching | Read |
| --- | --- |
| Panel provider, navigation, page structure, record pages, sections, tabs, grids, table layout, density | `references/layout-and-density.md` |
| Any resource page, custom page or dashboard, or auditing screens: its intent per role (Work queue, Workbench, Directory, Reference), which template expresses it (Record page, List page, Manage page, Dashboard page, Settings page), the Identity bar, Figures, Main column, Details aside, Related lists, `RecordLayout::make()` and the building blocks | `references/page-templates.md` (read alongside `layout-and-density.md`) |
| Any label, button, heading, helper text, description, error, confirmation, notification, empty state | `references/copy-and-feedback.md` |
| Form fields, uploads, editors, repeaters | `references/inputs.md` |
| Tables, search, filters, tabs on lists, bulk actions, import, export, shortcuts | `references/tables-and-finding.md` |
| Badges, colours, tooltips, icons, contrast, accessibility | `references/colour-and-accessibility.md` |
| Money, percentages, quantities, dates, times, timezones | `references/numbers-and-dates.md` |
| Dashboards, stats, charts, widgets | `references/dashboards-and-charts.md` |
| Sign-in, passwords, MFA, profile, user menu, database notifications, error pages | `references/auth-and-panel.md` |
| Bulk actions, row links, remembered filters with tabs, date tooltips, global search hints, phone inputs, upload limits, chart empty states, session expiry, lookups of admin-editable records | `references/filament-traps.md` (read alongside the area's reference) |
| Any AI or model judgment shown to people: suggestions, duplicate warnings, similar records, ranked matches, auto-fill | `references/ai-suggestions.md` |

4. **Apply every rule in those references to every screen you touch.** Use Laravel Boost `search-docs` before relying on a Filament method's exact behaviour.
5. **Finish with a rule check.** For a build, list each rule that shaped the change. For a review, list each rule the screen breaks with `file:line` and the rule's number. The step is done when every rule in every loaded reference has been either applied or noted as not applicable.

## With Filament Blueprint

`reviewing-filament-plans` treats the Blueprint as the whole acceptance contract and imports no outside rules, so these rules reach a review only through the plan:

- **Planning (`planning-filament`):** write each applicable rule into the Blueprint as a requirement, citing it as `filament-ux <reference> <number>` (for example `filament-ux layout-and-density 24`), and record any deliberate exception as an agreed amendment.
- **Reviewing (`reviewing-filament-plans`):** report Blueprint conformance exactly as that skill says. Report breaches of rules the Blueprint didn't include in a separate "UX rules" list after the review, never as Blueprint deviations.

## When the rules conflict

The accessibility floor (`layout-and-density.md` rule 26, `colour-and-accessibility.md` rule 9) beats density. An app's `.ai/rules/` beats this package. A **C** rule beats a **P** rule. Common practice in other apps never beats a **C** rule on its own: error wording, placeholder hints and password composition rules are common and still wrong.
