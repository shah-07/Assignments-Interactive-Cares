@extends('layout')

@section('content')

@include('partials.page-title')

  <!-- Projects Details Section -->
  <section id="portfolio-details" class="portfolio-details section">

    <div class="container" data-aos="fade-up" data-aos-delay="100">

      <div class="row gy-4">

        <div class="col-lg-8">
          <div class="portfolio-details-slider swiper init-swiper">
            <div class="swiper-wrapper align-items-center">
                <div class="swiper-slide">
                    <img src="{{ asset('assets/img/portfolio/'.$project['image']) }}" alt="App 1">
                </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="portfolio-description" data-aos="fade-up" data-aos-delay="300">
            <h2>{{ $project['title'] }}</h2>
            <p>
                {{ $project['description'] }}
            </p>
          </div>
        </div>

      </div>

    </div>

  </section><!-- /Project Details Section -->

@endsection
