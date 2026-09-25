# UX profile template

Copy this into the app as `.ai/ux-profile.md` and fill it in. The rules read these values instead of hard-coding them; a rule that says `profile.users` means "look up `users` here".

```yaml
users: daily            # daily (trained staff, repeated tasks) | occasional (infrequent or public)
devices: desktop        # desktop | mixed
min_screen_width: 1440  # the narrowest desktop width people actually use, in px
data_volume: small      # small (fast queries, live filters) | large (deferred filters, capped bulk actions)
glossary: CONTEXT.md    # where the app's domain terms are defined
create_verb: Add        # Add | Create; applied through lang/vendor/filament-* overrides
primary_records: []     # the records people work on daily, in menu order, e.g. [Orders, Customers]
locale: en
date_format: 'M j, Y'
datetime_format: 'M j, Y g:i A'
currency: USD
timezone: UTC           # display timezone; storage stays UTC
theme:
  plugins: [noir, compact]
  primary_has_meaning: false      # Noir makes primary zinc
  card_light: '#ffffff'
  card_dark: '#18181b'
signup: admin           # admin (accounts created by an admin) | self (public sign-up)
mfa: required           # required | optional
password_reset: admin   # admin | self
shared_devices: no      # yes hides "Remember me"
```

## What changes with each value

| Setting | Rules it changes |
| --- | --- |
| `users` | Tabs per page (≤5 daily, none occasional), wizards only for rare tasks, shortcuts, "then add another", QueryBuilder, inline-edit columns, `ModalTableSelect`, form Toggles, density level |
| `devices`, `min_screen_width` | Page width, grid columns, two-column record pages, sidebar collapse |
| `data_volume` | Live vs deferred filters, pagination mode, bulk selection caps |
| `glossary`, `create_verb` | Labels, capitalisation, button verbs |
| `primary_records` | Menu order |
| `locale`, `date_format`, `currency`, `timezone` | Number and date display |
| `theme` | What `primary` means, contrast checks for custom colours |
| `signup`, `mfa`, `password_reset`, `shared_devices` | Sign-in and account features |
