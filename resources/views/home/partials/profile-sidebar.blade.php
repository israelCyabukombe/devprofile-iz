{{-- Left column of the home page: photo, name, job title, social links and education. --}}
<div class="d-flex flex-column align-items-center text-center">
    @if ($personalInfo->profile_photo_path)
        <img
            class="profile-photo"
            src="{{ asset($personalInfo->profile_photo_path) }}"
            alt="Portrait of {{ $personalInfo->fullName() }}"
        >
    @endif

    <h1 class="mb-1">{{ $personalInfo->fullName() }}</h1>
    <h4 class="mb-0">{{ $personalInfo->job_title }}</h4>

    <div class="d-flex justify-content-center gap-3 mt-2">
        @if ($personalInfo->github_url)
            <a
                class="profile-social-link"
                href="{{ $personalInfo->github_url }}"
                target="_blank"
                rel="noopener noreferrer"
                aria-label="GitHub"
            >
                <x-icons.github />
            </a>
        @endif

        @if ($personalInfo->linkedin_url)
            <a
                class="profile-social-link"
                href="{{ $personalInfo->linkedin_url }}"
                target="_blank"
                rel="noopener noreferrer"
                aria-label="LinkedIn"
            >
                <x-icons.linkedin />
            </a>
        @endif
    </div>

    @if ($personalInfo->education->isNotEmpty())
        <section class="profile-education mt-4">
            <h3 class="profile-section-title">Education</h3>

            @foreach ($personalInfo->education as $education)
                <div class="profile-education-item">
                    <p class="profile-education-degree">{{ $education->degree }}</p>
                    <p class="profile-muted">{{ $education->school }}</p>

                    @if ($education->minor)
                        <p class="profile-muted profile-education-minor">Minor in {{ $education->minor }}</p>
                    @endif
                </div>
            @endforeach
        </section>
    @endif
</div>
