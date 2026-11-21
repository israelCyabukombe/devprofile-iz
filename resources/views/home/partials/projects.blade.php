{{-- Right column: project cards, in sort_order. HomeController prepares $projects. --}}
<section class="home-projects mb-4">
    <h2 class="fw-bold mb-4">Project Samples</h2>

    @forelse ($projects as $project)
        <x-project-card :project="$project" />
    @empty
        <p class="profile-muted">No projects yet.</p>
    @endforelse
</section>
