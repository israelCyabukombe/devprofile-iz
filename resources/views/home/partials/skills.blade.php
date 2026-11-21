{{-- Right column: skills grouped by category. HomeController prepares $skillGroups. --}}
@if (count($skillGroups) > 0)
    <section class="home-skills mb-4">
        <h2 class="fw-bold mb-4">Skills</h2>

        <div class="skill-groups">
            @foreach ($skillGroups as $skillGroup)
                <div class="skill-group">
                    <h6 class="skill-group-title">{{ $skillGroup['label'] }}</h6>

                    <div class="skill-badges">
                        @foreach ($skillGroup['skills'] as $skill)
                            <x-skill-badge :name="$skill->name" />
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endif
