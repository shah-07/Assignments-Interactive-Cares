@extends('layout')

@section('content')

<!-- Hero Section -->
<section id="hero" class="hero section dark-background">
    <img src="assets/img/hero-bg.jpg" alt="" data-aos="fade-in" class="">

    <div class="container" data-aos="fade-up" data-aos-delay="100">
        <h2>Shahbaj</h2>
        <p>I'm
            <span class="typed" data-typed-items="Designer, Developer, Freelancer, Photographer">Designer</span>
            <span class="typed-cursor typed-cursor--blink" aria-hidden="true"></span>
            <span class="typed-cursor typed-cursor--blink" aria-hidden="true"></span>
        </p>
    </div>
</section>
<!-- /Hero Section -->

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

<!-- Projects Section -->
<section id="portfolio" class="portfolio section light-background">
    <div class="container section-title" data-aos="fade-up">
        <h2>Projects</h2>
        <p>
            Magnam dolores commodi suscipit. Necessitatibus eius consequatur ex aliquid fuga eum quidem. Sit sint consectetur velit. Quisquam quos quisquam cupiditate. Et nemo qui impedit suscipit alias ea. Quia fugiat sit in iste officiis commodi quidem hic quas.
        </p>
    </div>

    <div class="container">
        <div class="isotope-layout" data-default-filter="*" data-layout="masonry" data-sort="original-order">

            <div class="row gy-4 isotope-container" data-aos="fade-up" data-aos-delay="200">
                @foreach($projects as $project)
                    <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-app">
                        <div class="portfolio-content h-100">
                            <img src="assets/img/portfolio/{{ $project['image'] }}" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>Project {{ $project['id'] }}</h4>
                                <p>{{ $project['title'] }}</p>
                                <a href="{{ route('project.show', $project['id']) }}" title="More Details" class="details-link"><i class="bi bi-link-45deg"></i></a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
<!-- /Projects Section -->

@endsection
