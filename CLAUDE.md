# devprofile-Iz — Working Agreement

@AGENTS.md
@ROADMAP.md

## Goal

Rebuild the career / profile page from https://www.icyabu.com/ in this Laravel app.
The original is the **BioWeb** project (.NET API + React) at `c:\Users\israe\source\repos\BioWeb`.
Use BioWeb as the reference for **look and content**, not for code structure. This app is server-rendered
Blade + Bootstrap 5.3 (Tailwind is also available), with data coming from the database.

## Before every task

1. Read `ROADMAP.md` and find the phase marked **IN PROGRESS**.
2. Work only inside that phase's scope. If a request falls outside it, say so before doing anything.
3. Check the matching BioWeb files in the reference map below.

## Phase workflow: pause → review → merge

1. Each phase gets its own branch named `phase-N-short-name`, created from an up-to-date `master`.
2. Make a plan and get approval. Then build it, write Pest feature tests, and run
   `vendor/bin/pint --dirty --format agent` and the narrowest tests that cover the change.
3. **STOP.** Summarize what changed and how to check it in the browser.
   Do **not** commit, push or merge until the user says so.
4. After the user approves: commit, push, and open a PR to `master`. **The user merges it.**
5. Update `ROADMAP.md`: tick the phase's checkboxes, mark it **DONE**, mark the next phase
   **IN PROGRESS**, and update the "Resume here" line.

## Code style: readability first

Readable code beats short or clever code. When in doubt, write the longer, clearer version.

- **No dense one-liners.** No nested ternaries and no long chained inline expressions.
  Break the work into named variables and steps.
- **Descriptive names**: `$frontEndSkills`, not `$fe`. `isFeatured()`, not `feat()`.
- **Small methods** that each do one job.
- **Controllers prepare data; views display it.** Blade views hold no logic heavier than
  `@foreach` / `@if`. Do grouping, sorting and filtering in the controller or model.
- **Split repeated markup** into Blade partials or components (for example `skill-badge`, `project-card`).
- **CSS** lives in `resources/css/app.css`. Theme colors are CSS variables. No inline `style=""`.
- **JavaScript**: plain, named functions with a short comment explaining *why*.
- **PHP**: PHPDoc with array shapes instead of inline comments, explicit types everywhere (see AGENTS.md).

## Reference map (BioWeb → Laravel)

| BioWeb (reference) | devprofile-Iz (target) |
| --- | --- |
| `BioWeb.Server/Models/*.cs` | `app/Models/*.php` + `database/migrations` |
| `BioWeb.Server/Migrations/*SeedPersonalInfo*`, `*AddProjectAndProjectSkill*` | `database/seeders` |
| `bioweb.client/src/data/Skills.ts`, `PersonalInfo.ts` | `database/seeders` |
| `bioweb.client/src/components/Navbar.tsx`, `Navbar.css` | `resources/views/partials/navbar.blade.php` |
| `bioweb.client/src/context/ThemeProvider.tsx` | `resources/js/app.js` + inline script in `layouts/app.blade.php` |
| `bioweb.client/src/components/About.tsx`, `About.css` | `resources/views/home/*` + `resources/css/app.css` |
| `bioweb.client/public/images/IMG_0002.jpg`, résumé PDF | `public/images/`, `public/` |
