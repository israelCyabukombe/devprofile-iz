# Career Page Roadmap

> **Resume here:** Phase 3 — Home page, left column (IN PROGRESS, not started). Phase 2 is reviewed and the user is merging `home-navbar`. Once it's on `master`, create `phase-3-home-profile` from `master` and plan Phase 3.

**Status legend:** `TODO` · `IN PROGRESS` · `DONE`

Every phase ends with **pause → review → merge** (see `CLAUDE.md`).

---

## Phase 0: Planning files — `DONE`

Branch: `phase-0-planning`

- [x] Create `CLAUDE.md` (working rules, auto-loaded every session)
- [x] Create `ROADMAP.md` (this file)
- [x] Reviewed and merged into `master`

---

## Phase 1: Database migrations, models, seeders — `DONE`

Branch: `phase-1-database` (from `master`)

- [x] Create models with migrations, factories and seeders (`php artisan make:model -mfs --no-interaction`):
  - [x] `personal_infos`: first_name, last_name, email, job_title, summary_text (text), profile_photo_path, resume_path, github_url, linkedin_url
  - [x] `education` (Laravel treats "education" as uncountable): personal_info_id (FK), degree, school, minor (nullable), sort_order
  - [x] `skill_levels`: description
  - [x] `skills`: personal_info_id (FK), skill_level_id (FK), name, category
  - [x] `projects`: title, description (text), live_url (nullable), repo_url (nullable), sort_order, timestamps
  - [x] `project_skill` pivot: project_id, skill_id
- [x] `App\Enums\SkillCategory` enum (`FrontEnd`, `BackEnd`, `Framework`, `Other`) with a `label()` for headings, cast on `Skill`
- [x] Relationships:
  - PersonalInfo hasMany Skill and Education
  - Skill belongsTo PersonalInfo and SkillLevel
  - Project belongsToMany Skill
- [x] Seeders use the real content from BioWeb (`src/data/Skills.ts`, `src/data/PersonalInfo.ts`, `About.tsx`, last skills migration)
- [x] Projects are **placeholders** (`ProjectSeeder`). Replace them with real data before Phase 4.
- [x] Pest tests: relationships work, and seeders create the expected rows
- [x] Migrations run cleanly against MariaDB 11.8.6 (run inside the Vagrant VM)
- [x] Pause → review → merge

**Review checklist (Phase 1):**

- [x] Migrations: the 6 `database/migrations/2026_11_16_*` files (columns, foreign keys, unique project/skill pair)
- [x] Enum: `app/Enums/SkillCategory.php` (categories and heading labels)
- [x] Models: `PersonalInfo`, `Education`, `SkillLevel`, `Skill`, `Project` in `app/Models/` (relationships)
- [x] Seeders: `SkillSeeder` (skill list vs BioWeb) and `ProjectSeeder` (placeholder projects)
- [x] Tests: `tests/Feature/ProfileModelRelationshipsTest.php` and `tests/Feature/ProfileSeederTest.php`
- [x] In the VM: `php artisan db:seed` to load the data into MariaDB
- [x] Full suite: `php artisan test --compact`

---

## Phase 2: Navbar with Light / Dark mode — `DONE`

Branch: `home-navbar` (rebased on `master` after Phase 1)

- [x] Keep the existing markup in `resources/views/partials/navbar.blade.php` (brand, About, Résumé, Links dropdown)
- [x] Replace the 3 theme buttons with **one Light / Dark toggle** (`<x-theme-toggle />`, inline SVG sun / moon, `aria-label`)
- [x] Put the theme on `<html data-theme="...">` and also set Bootstrap's `data-bs-theme`
- [x] Save the choice in `localStorage` (wrapped in try/catch)
- [x] Inline script in `<head>` (`partials/theme-script.blade.php`) applies the saved theme before the page paints. First visit follows the OS setting.
- [x] Button attribute is `data-theme-toggle`, so it doesn't clash with the `[data-theme]` selector
- [x] CSS: only `light` and `dark` tokens (BioWeb's colors), unused `.nav-inner` / `.nav-links` rules removed
- [x] Copy the résumé PDF from `BioWeb/bioweb.client/public/` into `public/` (link URL-encoded)
- [x] Pest test: `NavbarTest` covers the links, the PDF, the single toggle and the head script
- [x] Pause → review → merge

**Notes for later phases:**

- Tailwind and Bootstrap both define a `collapse` class. Tailwind's (`visibility: collapse`) was hiding the navbar links, so `app.css` has `@source not inline('collapse')`. Watch for other Bootstrap/Tailwind class-name clashes (for example `container`, `hidden`, `visible`) when adding markup.
- The site runs in the Vagrant VM (`http://localhost:8080`), which syncs this folder. For live CSS/JS, run `npm run dev` on Windows. Hard-refresh (Ctrl+Shift+R) if the browser shows an old copy.

---

## Phase 3: Home page, left column (profile) — `IN PROGRESS`

Branch: `phase-3-home-profile`

- [ ] `HomeController@index` replaces `Route::view` (keep the route name `home`) and loads PersonalInfo with its educations
- [ ] Two-column Bootstrap layout: `col-md-4` sticky sidebar + `col-md-8` content
- [ ] Left column: round profile photo (copy `IMG_0002.jpg` to `public/images/`), name, job title, GitHub / LinkedIn icons, Education
- [ ] Partial: `resources/views/home/partials/profile-sidebar.blade.php`
- [ ] Pest test: the page shows the seeded name, job title and education
- [ ] Pause → review → merge

---

## Phase 4: Home page, right column (intro, skills, projects) — `TODO`

Branch: `phase-4-home-content`

- [ ] Intro: job title and summary text
- [ ] Skills grouped by `SkillCategory` (grouping done in the controller), rendered with a reusable `skill-badge` component
- [ ] Projects ordered by `sort_order`, using a `project-card` component with repo / live links and a Bootstrap collapse "Tech Stack" showing the project's skills
- [ ] Empty state when there are no projects (no spinner or retry needed, because the page is rendered on the server)
- [ ] Pest tests: skills appear under the right category, and projects appear in order with their skills
- [ ] Pause → review → merge

---

## Phase 5: Polish (optional, later) — `TODO`

- [ ] Responsive polish
- [ ] Contact page
- [ ] SEO meta tags
- [ ] Deployment
