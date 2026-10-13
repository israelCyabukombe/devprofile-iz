import 'bootstrap/dist/js/bootstrap.bundle.min.js';

/*
 * Light / dark theme toggle.
 * The first theme is set by the inline script in resources/views/partials/theme-script.blade.php,
 * which reads the same localStorage key before the page paints.
 */

const themeStorageKey = 'theme';

function getCurrentTheme() {
    return document.documentElement.getAttribute('data-theme');
}

function getOppositeTheme(theme) {
    if (theme === 'dark') {
        return 'light';
    }

    return 'dark';
}

/* Remember the choice. Storage can be blocked (private windows), and the theme should still switch. */
function saveTheme(theme) {
    try {
        localStorage.setItem(themeStorageKey, theme);
    } catch (error) {
        // Nothing to do: the theme still applies to this page.
    }
}

/* Screen readers announce what the button will do, so the label names the theme it switches to. */
function updateToggleLabels(theme) {
    const nextTheme = getOppositeTheme(theme);
    const toggleButtons = document.querySelectorAll('[data-theme-toggle]');

    toggleButtons.forEach((button) => {
        button.setAttribute('aria-label', `Switch to ${nextTheme} theme`);
    });
}

/* data-bs-theme makes Bootstrap's own parts (dropdowns, mobile menu) switch colors too. */
function applyTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    document.documentElement.setAttribute('data-bs-theme', theme);
    updateToggleLabels(theme);
}

function toggleTheme() {
    const nextTheme = getOppositeTheme(getCurrentTheme());

    applyTheme(nextTheme);
    saveTheme(nextTheme);
}

function setUpThemeToggle() {
    const toggleButtons = document.querySelectorAll('[data-theme-toggle]');

    toggleButtons.forEach((button) => {
        button.addEventListener('click', toggleTheme);
    });

    updateToggleLabels(getCurrentTheme());
}

setUpThemeToggle();
