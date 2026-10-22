# Security and Accessibility Checklist
Mark items only after testing the real application.

## Security
- [ ] Protected pages check login/session.
- [ ] Admin-only actions check the user's role on the server.
- [ ] Passwords are hashed and not stored as plain text.
- [ ] Prepared statements are used for user-supplied SQL values.
- [ ] Server-side validation is applied to forms and API requests.
- [ ] User-provided output is escaped before rendering.
- [ ] Uploads validate file type and size.
- [ ] State-changing actions use POST and appropriate CSRF protection where applicable.
- [ ] Errors do not reveal passwords, SQL, or sensitive server paths.
- [ ] Secrets and private data are excluded from GitHub.

## Accessibility
- [ ] Inputs have associated labels.
- [ ] Links and buttons have meaningful text.
- [ ] Keyboard focus is visible.
- [ ] Text and controls have sufficient contrast.
- [ ] Tables have clear headings.
- [ ] Validation errors explain how to correct input.
- [ ] Layout works at desktop and narrow/mobile widths.
