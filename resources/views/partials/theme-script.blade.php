{{--
    Runs before the page paints, so the saved theme is applied without a flash of the wrong colors.
    First visit: follow the visitor's operating system setting. After a click on the toggle,
    resources/js/app.js saves the choice under the same "theme" key.
--}}
<script>
    (function applyInitialTheme() {
        var savedTheme = null;

        try {
            savedTheme = localStorage.getItem('theme');
        } catch (error) {
            savedTheme = null;
        }

        var theme = savedTheme;

        if (theme !== 'light' && theme !== 'dark') {
            var systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            if (systemPrefersDark) {
                theme = 'dark';
            } else {
                theme = 'light';
            }
        }

        document.documentElement.setAttribute('data-theme', theme);
        document.documentElement.setAttribute('data-bs-theme', theme);
    })();
</script>
