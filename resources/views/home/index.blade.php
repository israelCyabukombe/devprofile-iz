@extends('layouts.app')

@section('title', $seo['title'])

@section('meta')
    <x-seo-meta :seo="$seo" />
@endsection

@section('content')
    <div class="container">
        @if ($personalInfo)
            {{-- Two columns from 992px (lg) up; below that the sidebar stacks on top. --}}
            <div class="row align-items-start py-4 py-lg-5">
                <div class="col-lg-4 mb-4 mb-lg-0 profile-sidebar">
                    @include('home.partials.profile-sidebar', ['personalInfo' => $personalInfo])
                </div>

                <div class="col-lg-8 ps-lg-4">
                    <div class="home-content">
                        @include('home.partials.intro', ['personalInfo' => $personalInfo])
                        @include('home.partials.skills', ['skillGroups' => $skillGroups])
                        @include('home.partials.projects', ['projects' => $projects])
                    </div>
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
