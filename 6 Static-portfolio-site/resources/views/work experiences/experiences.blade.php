@extends('layout')

@section('content')

@include('partials.page-title')

<!-- Work Experience Section -->
<section id="about" class="about section">
    <div class="container section-title" data-aos="fade-up">
        <h2>Work Experience</h2>
        @foreach($workExperiences as $workExp)
        <div class="resume-item mb-5">
            <h4>{{ $workExp['title'] }}</h4>
            <h5>{{ $workExp['start year'] }} - {{ $workExp['end year'] }}</h5>
            <p>{{ $workExp['description'] }}</p>
        </div>
        @endforeach
    </div>
</section>
<!-- /Work Experience Section -->

@endsection
