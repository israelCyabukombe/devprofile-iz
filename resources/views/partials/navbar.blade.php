<nav class="navbar navbar-expand-lg site-navbar sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">Israel Cyabu</a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#siteNavbar"
            aria-controls="siteNavbar"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="siteNavbar">
            <ul class="navbar-nav mx-auto">

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">About</a>
                </li>

                <li class="nav-item">
                    <a
                        class="nav-link"
                        href="{{ asset(rawurlencode('ISRAEL CYABUKOMBE RESUME.pdf')) }}"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        Résumé
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <button
                        class="nav-link dropdown-toggle"
                        type="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >
                        Links
                    </button>

                    <ul class="dropdown-menu">
                        <li>
                            <a
                                class="dropdown-item"
                                href="https://github.com/israelCyabukombe"
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                My GitHub
                            </a>
                        </li>
                        <li>
                            <a
                                class="dropdown-item"
                                href="https://www.linkedin.com/in/israel-cyabukombe-974ba2aa/"
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                My LinkedIn
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
            <x-theme-toggle />
        </div>
    </div>
</nav>