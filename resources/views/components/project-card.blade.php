{{--
    One project: title, description, repo / live links, and a "Tech Stack" button
    that opens a Bootstrap collapse with the project's skills.
--}}
@props(['project'])

<article class="project-card p-4 mb-4">
    <h5 class="fw-bold mb-2">{{ $project->title }}</h5>
    <p class="mb-3">{{ $project->description }}</p>

    <div class="d-flex align-items-center gap-3 mt-2">
        @if ($project->repo_url)
            <a
                class="project-link"
                href="{{ $project->repo_url }}"
                target="_blank"
                rel="noopener noreferrer"
                aria-label="Source code for {{ $project->title }}"
            >
                <x-icons.github />
            </a>
        @endif

        @if ($project->live_url)
            <a
                class="project-link"
                href="{{ $project->live_url }}"
                target="_blank"
                rel="noopener noreferrer"
                aria-label="Live site for {{ $project->title }}"
            >
                <x-icons.globe />
            </a>
        @endif

        @if ($project->skills->isNotEmpty())
            <button
                class="btn btn-sm btn-outline-secondary ms-auto project-tech-stack-toggle"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#project-{{ $project->id }}-tech-stack"
                aria-expanded="false"
                aria-controls="project-{{ $project->id }}-tech-stack"
            >
                <x-icons.layers />
                Tech Stack
                <x-icons.chevron-down />
            </button>
        @endif
    </div>

    @if ($project->skills->isNotEmpty())
        <div class="collapse" id="project-{{ $project->id }}-tech-stack">
            <div class="skill-badges project-tech-stack mt-3 pt-3">
                @foreach ($project->skills as $skill)
                    <x-skill-badge :name="$skill->name" />
                @endforeach
            </div>
        </div>
    @endif
</article>
