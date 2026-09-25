# Sign-in, account and panel settings

UX choices for authentication and panel-wide features. Several have security consequences; the `filament-security-audit` skill covers the rest. Tags **C**, **S**, **P** as defined in `SKILL.md`.

1. **Sign-up follows `profile.signup`.** Always `login()` and `profile()`. `registration()` only when `signup = self`, and then with `emailVerification()`. **C** (GOV.UK).
2. **One password rule everywhere.** Set `Password::defaults()` once (length, `uncompromised()`, no composition rules, no expiry) and use `Password::default()` on every password field, admin user forms included. Minimum 8 characters when MFA is required, 15 when not. **C** (NIST SP 800-63B-4, GOV.UK, NCSC). Most current apps still demand symbols; NIST is better supported.
3. **Show the rule before typing.** The password rule is helper text; keep `revealablePasswords()`; never block paste. **C** (NN/g, NIST, WCAG 3.3.8).
4. **MFA follows `profile.mfa`.** Internal tools: required (`isRequired: true`), authenticator app first, `recoverable()`, recovery codes shown during set-up. Public: offered, not required. **C** (NCSC, NIST; every MFA flow on Mobbin); requiring it internally is **P**.
5. **No SMS MFA;** email codes only as a fallback. **C**.
6. **Tell the owner about account changes.** Email on password reset, MFA change, recovery-code regeneration and email change; Filament doesn't send these itself. **C** (NIST, GOV.UK).
7. **Recovery follows `profile.password_reset`.** Self-service reset requires `emailChangeVerification()`; admin-only reset is documented. **P**.
8. **The bell is for other people's events.** `databaseNotifications()` only when something sends to it; each notification has an action that opens the record and marks it read. Otherwise leave the bell off. **S** (NN/g) plus Mobbin.
9. **The user menu holds profile, theme and sign out (last).** No app features. **P**; Mobbin agrees.
10. **Error pages say what happened and what to do.** Publish 403, 404, 419 and 500 views: 403 names the missing permission and who can grant it; 419 says unsaved changes were lost and links to sign in. **C** (GOV.UK, WCAG 2.2.1; Mobbin).
11. **Brand and devices.** A short `brandName()` (authenticator apps show it) and a real `favicon()`. Hide "Remember me" when `profile.shared_devices = yes`; most B2B sign-in pages on Mobbin have none. **P**.
