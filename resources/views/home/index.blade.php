@extends('layouts.app')

@section('title', 'Iz-Bio')

@section('content')
    <div class="container-fluid">
        @if ($personalInfo)
            <div class="row align-items-start py-5">
                <div class="col-lg-4 col-md-4 mb-4 mb-md-0 profile-sidebar">
                    @include('home.partials.profile-sidebar', ['personalInfo' => $personalInfo])
                </div>

                <div class="col-lg-8 col-md-8 ps-lg-4">
                    @include('home.partials.intro', ['personalInfo' => $personalInfo])
                    @include('home.partials.skills', ['skillGroups' => $skillGroups])
                    @include('home.partials.projects', ['projects' => $projects])
                </div>
            </div>
        @else
            <div class="py-5 text-center">
                <h1>Profile coming soon</h1>
                <p class="profile-muted">Run <code>php artisan db:seed</code> to load the profile.</p>
            </div>
        @endif
    </div>
@endsection
