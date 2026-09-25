# Numbers, money and dates

Tags **C**, **S**, **P** as defined in `SKILL.md`. Currency, locale, date format and timezone come from `profile.*`.

1. **Right-align numbers.** Money, percentages and quantities and their headers are `->alignEnd()`, including Repeater `TableColumn` headers. Text and dates stay left. **C** (GOV.UK, Microsoft; Stripe, Mercury, Xero on Mobbin).
2. **One money formatter.** `->money(profile.currency)` in columns and entries, and one helper backed by `Number::currency()` in prose and tooltips. Never `number_format()` for money. **P**.
3. **Money always shows its decimals.** Currency symbol before the number, thousands separators, `.00` kept, so decimals line up. **C** for separators; keeping `.00` is **P** (GOV.UK drops it); Mobbin agrees.
4. **Negative amounts keep their sign, in the normal text colour.** Never red: status badges carry the warning. **P**; Stripe, Mercury and Xero on Mobbin agree. A true minus sign (−) is nicer where the formatter allows it.
5. **A percentage sits beside the amount it comes from,** to one decimal, with `%` and no space, and is sortable. Round in the direction that never falsely passes a threshold. **C** for format (Microsoft, GOV.UK) plus **P**.
6. **Differences between percentages are "points".** "3.4 points under target", never "3.4% under". **S** (Microsoft) plus **P**.
7. **Quantities read as numbers.** Thousands separators, no trailing zeros, a space before the unit ("1,000 g"), plural units ("3 days"). **C**.
8. **One date format,** `profile.date_format`, and date-times with AM/PM or 24-hour per locale, set once as panel defaults (`Table::configureUsing`, `Schema::configureUsing`). No ISO dates in the UI. **P**.
9. **Absolute dates for facts and logs, relative only for recency hints.** Business dates (Invoiced on, Due) show the date, with `sinceTooltip()`. Activity and audit logs show the date and time with the timezone. Relative time ("3 days ago") only in notification feeds and "last updated" hints, with `dateTimeTooltip()`. **P**; Mobbin shows every current audit log with absolute times.
10. **Convert timestamps to `profile.timezone`** before formatting, including hand-formatted strings; date-only columns are never converted. Test at 23:30 UTC. **S** (Filament).
11. **No totals across unrelated records.** No `Sum`/`Average` of prices across different items; use `Count`, or `Range` within one item. **P**.
