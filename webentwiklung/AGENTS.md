# AI Agent Instructions for this Project

This is a small static website project with HTML, CSS, and assets only. There is no JavaScript framework, build tool, or package manifest in the repository.

## Project scope
- `index.html` is the main landing page.
- `login.html` and `registrieren.html` are authentication pages.
- `styles.css` contains the site styling.
- `logo.png`, `images-2.png`, and `musik.mp3` are static assets used by the pages.

## What the agent should do
- Keep the implementation simple and static unless the user explicitly asks for a backend or JavaScript-based authentication flow.
- Prefer editing the existing HTML/CSS files rather than introducing new frameworks.
- Preserve the project structure and link paths as-is.
- If adding interactivity, use plain JavaScript in the current folder or inline in the HTML.

## Known project conventions
- HTML pages are linked by relative paths (`login.html`, `registrieren.html`).
- Styling is centralized in `styles.css` and applied via classes like `.button`, `.mitte`, and `.text-links`.
- The project is currently a static prototype, not a production app.

## Special notes
- `login.html` is currently empty and likely needs page content or form layout.
- No build/test commands are present. Treat this as content and styling work unless new tooling is introduced.

## When in doubt
- Ask the user before adding a backend, external library, or build tooling.
- Ask the user for design or UX preferences if new pages or flows are added.
