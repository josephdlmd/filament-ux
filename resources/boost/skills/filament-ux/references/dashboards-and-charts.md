# Dashboards and charts

A dense tool's dashboard answers "what needs me now?" first. Tags **C**, **S**, **P** as defined in `SKILL.md`.

1. **To-dos first, then charts.** The first row holds counts of work waiting for the viewing role, each linking to the exact filtered list or status tab; charts follow. No role sees a half-empty row (`getColumnSpan()` adapts). **C** (NN/g dashboards, Carbon 2026) plus **P**.
2. **The to-do row holds counts only.** No trends, sparklines or polling, at most four tiles per role. Deltas on empty accounts are noise. **P**; Mobbin shows the noise.
3. **A count matches the list it opens.** Counts and charts query through the same named scopes as the list's filters, and labels come from the same settings. Assert it in tests. **P**.
4. **Pick the form from the question.** A stat tile for one number to act on, a bar chart for comparisons; no pies, dual axes or one-bar charts. **C** (NN/g, dataviz).
5. **Bars start at zero,** with integer ticks for counts (`beginAtZero`, `precision: 0`). **C** (Analysis Function, ONS; Mobbin agrees).
6. **Natural order first** (bands low to high, groups A–Z), otherwise rank by value. **S**.
7. **Say what is counted.** `getDescription()` with one line (it is also the canvas `aria-label`) and a y-axis title with the unit. **C**.
8. **Legend for two or more series,** none for one; a series' total may sit in its legend. The to-do segment sits on the baseline; at most four stack categories. **C**.
9. **Charts use the colour mapping** (`colour-and-accessibility.md` rule 1). If one series is the story, it gets the accent and the rest go gray. **C**.
10. **Empty state:** override `isEmpty()` so a chart with nothing counted shows a heading and a next step, not empty axes. **C** (dataviz, NN/g, Carbon).
11. **Tooltips add detail but never hold the only copy.** **S**.
