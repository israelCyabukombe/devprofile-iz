# Career Page Roadmap

> **Resume here:** Phase 5 — Polish (IN PROGRESS). Responsive polish is done (branch `phase-5-responsive`). Next: pick the next open Phase 5 item with the user (contact page, VM database port forward, or deployment), on a new branch from an up-to-date `master`.

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
- If the page suddenly has **no styling** (for example the photo fills the whole page), a stopped `npm run dev` left `public/hot` behind, so Laravel loads CSS from a dev server that isn't running. Fix: delete `public/hot` and run `npm run build` (or start `npm run dev` again).

---

## Phase 3: Home page, left column (profile) — `DONE`

Branch: `phase-3-home-profile`

- [x] `HomeController@index` replaces `Route::view` (keep the route name `home`) and loads PersonalInfo with its education
- [x] Two-column Bootstrap layout: `col-md-4` sticky sidebar + `col-md-8` content (empty until Phase 4)
- [x] Left column: round profile photo (copied `IMG_0002.jpg` to `public/images/`), name, job title, GitHub / LinkedIn icons (`<x-icons.github />`, `<x-icons.linkedin />`), Education
- [x] Partial: `resources/views/home/partials/profile-sidebar.blade.php`; page view is `home/index.blade.php` (old `welcome.blade.php` removed)
- [x] "Profile coming soon" message when the database isn't seeded yet
- [x] Pest test: `HomePageProfileTest` covers the seeded profile, photo, social links, hidden empty fields, education order and the empty state
- [x] Pause → review → merge

**Notes from Phase 3:**

- Tests now compile Blade views into `storage/framework/testing/views` (`VIEW_COMPILED_PATH` in `phpunit.xml`). Before that, running tests on Windows wrote compiled views into the shared folder, and the VM's web server failed with `touch(): Utime failed` (HTTP 500). Fixed for good in Phase 5 (Apache now compiles views outside the synced folder).
- `public/images/IMG_0002.jpg` is 7.2 MB (copied as-is from BioWeb). Resize/compress it in Phase 5 polish.

---

## Phase 4: Home page, right column (intro, skills, projects) — `DONE`

Branch: `phase-4-home-content`

- [ ] Replace the placeholder projects in `ProjectSeeder` with real data from the user (title, description, live URL, repo URL, sort order, skills). **Deferred to Phase 5.**
- [x] Intro: job title and summary text (`home/partials/intro.blade.php`)
- [x] Skills grouped by `SkillCategory` in `HomeController::groupSkillsByCategory()` (enum order, empty categories left out), rendered with `<x-skill-badge />` (`home/partials/skills.blade.php`)
- [x] Projects ordered by `sort_order` (ascending; BioWeb sorted descending), using `<x-project-card />` with repo / live links (`<x-icons.github />`, `<x-icons.globe />`) and a Bootstrap collapse "Tech Stack" (`home/partials/projects.blade.php`)
- [x] Empty state: "No projects yet." (no spinner or retry needed, because the page is rendered on the server)
- [x] Pest tests: `HomePageContentTest` covers intro, skills under the right category in order, empty categories hidden, projects in order with their tech stack, links shown/hidden, and the empty state
- [x] Pause → review → merge

**Notes from Phase 4:**

- `PersonalInfo::skills()` now orders by `id`, so skills show in the order the seeder lists them.
- `HomeController::findSkillsInCategory()` uses a plain `foreach` instead of `filter(function ...)`, for readability.
- The Tech Stack button has a layers icon (`<x-icons.layers />`); only the arrow (`.icon-chevron`) flips when the stack opens.
- The VM `touch(): Utime failed` 500 error came back. Cause: Laravel calls `touch()` when a `.blade.php` file has a newer timestamp than its compiled copy but the same content, which happens after `git checkout` / `git pull` / merges rewrite files. The VM's web server can't set timestamps on the synced folder. Fixed in Phase 5: Apache now compiles views into `/var/cache/devprofile-Iz/views` on the VM's own disk (see Phase 5).

---

## Phase 5: Polish — `IN PROGRESS`

One branch per item (or small group of items), each with pause → review → merge.

**Follow-ups from earlier phases:**

- [x] Replace the placeholder projects in `ProjectSeeder` with the real ones: DocSearch, QualityTracking, My Bio Web (copied from the live site; branch `phase-5-real-projects`). Added MSSQL (Back End, Intermediate) to `SkillSeeder`. The seeder deletes old placeholder rows and stops with an error on an unknown skill name.
- [x] Project order: highest `sort_order` first (like BioWeb). `HomeController::findProjects()` uses `orderByDesc('sort_order')`. `ProjectSeeder` renumbered DocSearch 3, QualityTracking 2, My Bio Web 1, so the live site's order stays the same. After pulling, re-seed with `php artisan db:seed --class=ProjectSeeder` (branch `phase-5-order-and-gap`).
- [x] Skill badge gap: `.skill-badges` gap is now `1.5rem` (was `0.75rem`) in `resources/css/app.css`.
- [x] Permanent fix for the VM `touch(): Utime failed` error. Done Nov 22 in Lamp-project (commit `24328cf`): Apache's compiled views now live in `/var/cache/devprofile-Iz/views` on the VM's own disk, owned by `www-data` (Salt state `devprofile-view-cache` + `SetEnv VIEW_COMPILED_PATH` in the Apache site config). Verified: touching a Blade file no longer causes a 500.
- [ ] Make the VM database reachable from Windows: Windows has its own MySQL 8.0 (`MySQL80` service) on port 3306, so forward the VM's MariaDB to another port (e.g. `guest: 3306, host: 33060`) and run `DB_PORT=33060 php artisan ...` on Windows. Needs MariaDB to accept connections from the host (Salt change). Until then, run database commands inside the VM.
- [x] Resize / compress `public/images/IMG_0002.jpg`: now 600×900, 89 KB (was 3168×4752, 7.2 MB), resized with PowerShell's built-in .NET image library at JPEG quality 82. The `<img>` has `width`/`height` so the layout doesn't jump (branch `phase-5-seo-and-photo`).

**Original polish items:**

- [x] Responsive polish (branch `phase-5-responsive`): columns sit side by side only from 992px (`col-lg-*`), so tablets get the stacked layout and the sidebar is sticky whenever there are two columns. The page uses `container` (lines up with the navbar). The right column shares one `max-width: 720px` (`.home-content`). On phones: badge gap `0.75rem` (`1.5rem` from 576px), card padding `p-3 p-sm-4`, and the intro's job title is hidden while stacked (the sidebar shows it). Card hover lift only on `@media (hover: hover)`. Tested in `ResponsiveLayoutTest`.
  - Tip: check widths with headless Edge by loading the page in an `<iframe>` of the target width. Headless Edge has a minimum window width, so `--window-size=375,...` alone gives a wrong, cropped phone view.
  - Not changed: at 1024px the name wraps onto two lines in the sidebar (reads fine).
- [ ] Contact page
- [x] SEO meta tags (branch `phase-5-seo-and-photo`): title is "Name — Job title", plus meta description (summary cut to 160 characters), canonical URL, Open Graph + Twitter card, and JSON-LD `Person`. `HomeController::buildSeoTags()` prepares the values, `<x-seo-meta>` prints them, the layout has an `@yield('meta')` slot. Tested in `SeoMetaTest`.
  - Note for deployment: set `APP_URL` to the real domain. After going live, check link previews with LinkedIn's Post Inspector and the JSON-LD with Google's Rich Results Test (neither works on `localhost`).
- [ ] Deployment
  - Note: BioWeb hard-coded its skills in `src/data/Skills.ts` because its Azure SQL database had slow cold starts (the "Database cold start detected…" messages in `About.tsx`). This site renders on the server, so a sleeping database would delay the whole page. Use a database that doesn't sleep, or cache the home page data with Laravel's cache (`Cache::remember`). Keep the database as the single source of truth (no hard-coded copy like `Skills.ts`).
