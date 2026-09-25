# Colour and accessibility

Filament's own components meet WCAG contrast and labelling; these rules cover what apps add. Tags **C**, **S**, **P** as defined in `SKILL.md`.

## 1. One colour per meaning
- `success`: done or good
- `warning`: waiting on someone, the to-do states
- `danger`: destroys data or failed (Delete buttons, failure notifications); never a resting state and never a figure
- `gray`: neutral, by choice or inactive; secondary and undo buttons
- `info`: a neutral fact flag
- `primary`: the main action only (under the Noir theme it is zinc and carries no meaning)

A badge, a button, a notification and a chart series for the same state use the same colour everywhere. In dense lists, consider `gray` for "done" so colour marks only what needs attention. **C** for consistency (GOV.UK Tag, Analysis Function; Stripe, Shopify, Vercel on Mobbin); the mapping is **P**.

## 2. State colours live on the enum
`HasColor`, `HasLabel`, and `HasIcon` where a badge stands alone; no inline `match` on colours. **P**.

## 3. Never colour alone
Every coloured state also has a word or a distinct icon shape. **C** (WCAG 1.4.1, GOV.UK).

## 4. Red buttons only for what can't be undone
Reversible actions are `gray` (or `primary` when they are the screen's main task). **C** (GOV.UK, NN/g).

## 5. Custom colours pass 3:1
Hard-coded colours (chart series) pass 3:1 against the card in light and dark (`profile.theme` card colours) and the dataviz palette validator; record the ratios in the constant's docblock. **S** (WCAG 1.4.11).
A palette that passes in both Noir modes (cards `#ffffff` and `#18181b`): amber `#d97706` for to-dos (3.19 and 5.56), green `#199e70` for done (3.41 and 5.20), grey `#71717a` for neutral (4.83 and 3.67). Filament's default amber `#f59e0b` fails 3:1 on white, and a neutral grey always fails the validator's chroma floor; record that as a deliberate exception.

## 6. Tooltips never hold the only copy
A tooltip may repeat or expand what's on screen. Anything needed for the task (a figure's working, definitions, names behind codes, abbreviations, why an action is disabled) is also visible text or on a focusable element; Filament tooltips on plain text are hover-only. **C** (WCAG 1.4.13, NN/g).

## 7. No empty labels
No `label('')` on columns; icon-only actions keep a `label()` (it becomes the `aria-label`). **S**.

## 8. Notification colour follows the outcome
`success` saved, `warning` saved but needs follow-up, `danger` nothing saved. **P**; Mobbin agrees.

## 9. Density never goes below the accessibility floor
Targets at least 24 × 24 px (WCAG 2.5.8), text still readable and nothing lost at 200% zoom (1.4.4, 1.4.10), and custom spacing that survives user text-spacing overrides (1.4.12). See `layout-and-density.md`. **C** (WCAG 2.2).
