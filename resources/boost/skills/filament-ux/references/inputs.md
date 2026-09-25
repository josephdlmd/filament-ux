# Inputs

Which form component to use and how to configure it. Tags **C**, **S**, **P** as defined in `SKILL.md`.

## 1. Choose by option count
One choice from 5 or fewer: `Radio` or `ToggleButtons` (`->inline()` in dense forms). 6 to 15: `Select`. More than 15, or a relationship: `Select->searchable()`. **C** (NN/g listbox 2024, GOV.UK).

## 2. Several choices
About 15 or fewer known options: `CheckboxList` (`->columns()` in dense forms). Longer or relationship lists: `Select->multiple()`. Free-text values: `TagsInput`. **C**.

## 3. Checkbox for saved yes/no, Toggle for switches
A yes/no saved with the form is a `Checkbox` with a positive label ("Send invoice by email"). A `Toggle` is for something that applies at once (a table `ToggleColumn`) or shows and hides part of the form while you edit. **C** (NN/g, Microsoft, Fluent); the show/hide split follows Shopify. About half of current apps use toggles for saved fields; the guidance is better supported.

## 4. Pre-select only safe defaults
Pre-select a setting with a safe default ("Payment terms: 30 days", then `selectablePlaceholder(false)`); never pre-select a judgement question. **C** (GOV.UK).

## 5. Dates are typed
Use the native `DatePicker` with `minDate()`/`maxDate()`. `native(false)` only for dates near today that people pick rather than type. **C** (NN/g, GOV.UK). Popover calendars are common; typed entry is better supported for trained users.

## 6. Match the TextInput to the data
`email()`, `tel()`, `numeric()`/`integer()`, `password()->revealable()`; units and currency in `prefix()`/`suffix()`. **P**.

## 7. Size fields to the answer
TextInput for one line, Textarea for more, and the same component for a field on every screen. Character count only for a real limit. **C** (GOV.UK).

## 8. Uploads state their limits first
Every `FileUpload` sets `acceptedFileTypes()` or `image()`, `maxSize()` (and `maxFiles()` when multiple) and says both in `helperText()` ("PDF or JPG, up to 10 MB"). **C** (GOV.UK, Carbon, Filament).

## 9. Child rows by lifecycle
A few short rows saved with the parent (an Order's lines): `Repeater` with `table()` columns. Rows with their own lifecycle or more than about 10: a relation manager. Mixed content blocks: `Builder`. Pick an existing record: `Select`. **S** (MoJ Add another) plus **P**.

## 10. Pick by comparing columns
When people choose a record by comparing its columns (price, stock), use `ModalTableSelect` and scope it in `relationship(modifyQueryUsing:)`, never only in the modal's filters. **S** (Filament). `profile.users = daily` only.

## 11. No KeyValue for known keys
Model known keys as fields. When `KeyValue` is used, label it and lock the keys. **P**.

## 12. Never trust Hidden
Set ownership, prices and permissions on the server, never from a `Hidden` field. **S** (Filament).

## 13. The lightest text editor that fits
Plain notes: `Textarea`. Rendered formatted text: `RichEditor` with a reduced `toolbarButtons()`. `MarkdownEditor` and `CodeEditor` only for technical users. **P**.

## 14. No deprecated components
`Select->multiple()` not `MultiSelect`; `TextEntry` not `Placeholder`. **S** (Filament `@deprecated`).
