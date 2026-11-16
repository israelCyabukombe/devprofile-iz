# Career Page Roadmap

> **Resume here:** Phase 1 — Database (IN PROGRESS, not started). Create branch `phase-1-database` from `master`.

**Status legend:** `TODO` · `IN PROGRESS` · `DONE`

Every phase ends with **pause → review → merge** (see `CLAUDE.md`).

---

## Phase 0: Planning files — `DONE`

Branch: `phase-0-planning`

- [x] Create `CLAUDE.md` (working rules, auto-loaded every session)
- [x] Create `ROADMAP.md` (this file)
- [x] Reviewed and merged into `master`

---

## Phase 1: Database migrations, models, seeders — `IN PROGRESS`

Branch: `phase-1-database` (from `master`)

- [ ] Create models with migrations, factories and seeders (`php artisan make:model -mfs --no-interaction`):
  - [ ] `personal_infos`: first_name, last_name, email, job_title, summary_text (text), profile_photo_path, resume_path, github_url, linkedin_url
  - [ ] `educations`: personal_info_id (FK), degree, school, minor (nullable), sort_order
  - [ ] `skill_levels`: description
  - [ ] `skills`: personal_info_id (FK), skill_level_id (FK), name, category
  - [ ] `projects`: title, description (text), live_url (nullable), repo_url (nullable), sort_order, timestamps
  - [ ] `project_skill` pivot: project_id, skill_id
- [ ] `App\Enums\SkillCategory` enum (`FrontEnd`, `BackEnd`, `Framework`, `Other`), cast on `Skill`
- [ ] Relationships:
  - PersonalInfo hasMany Skill and Education
  - Skill belongsTo PersonalInfo and SkillLevel
  - Project belongsToMany Skill
- [ ] Seeders use the real content from BioWeb (`SeedPersonalInfo` / `AddProjectAndProjectSkill` migrations, `src/data/Skills.ts`, `src/data/PersonalInfo.ts`)
- [ ] Pest tests: relationships work, and seeders create the expected rows
- [ ] `php artisan migrate:fresh --seed` runs cleanly against the MariaDB set in `.env`
- [ ] Pause → review → merge

---

## Phase 2: Navbar with Light / Dark mode — `TODO`

Branch: `home-navbar` (already has the Bootstrap navbar markup; rebase on `master` first)

- [ ] Keep the existing markup in `resources/views/partials/navbar.blade.php` (brand, About, Résumé, Links dropdown)
- [ ] Replace the 3 theme buttons with **one Light / Dark toggle** (sun / moon icon, `aria-label`)
- [ ] Put the theme on `<html data-theme="...">` and also set Bootstrap's `data-bs-theme`
- [ ] Save the choice in `localStorage` (wrapped in try/catch)
- [ ] Add a small inline script in the layout `<head>` that applies the saved theme before the page paints, so it doesn't flash
- [ ] Rename the button attribute to `data-theme-toggle`, so it doesn't clash with the `[data-theme]` selector
- [ ] CSS: keep only `light` and `dark` tokens, and remove the unused `.nav-inner` / `.nav-links` rules
- [ ] Copy the résumé PDF from `BioWeb/bioweb.client/public/` into `public/`
- [ ] Pest test: the home page renders the navbar links and the theme toggle
- [ ] Pause → review → merge

---

## Phase 3: Home page, left column (profile) — `TODO`

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
