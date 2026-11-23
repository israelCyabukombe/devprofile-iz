{{--
    Right column, top: job title and summary.
    The job title is hidden while the columns are stacked, because the sidebar right above already shows it.
--}}
<section class="home-intro mb-4">
    <h4 class="mb-3 d-none d-lg-block">{{ $personalInfo->job_title }}</h4>
    <p class="home-intro-text">{{ $personalInfo->summary_text }}</p>
</section>
