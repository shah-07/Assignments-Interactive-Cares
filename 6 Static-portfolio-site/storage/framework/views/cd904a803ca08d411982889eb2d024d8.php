<?php $__env->startSection('content'); ?>

<?php echo $__env->make('partials.page-title', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

  <!-- Projects Details Section -->
  <section id="portfolio-details" class="portfolio-details section">

    <div class="container" data-aos="fade-up" data-aos-delay="100">

      <div class="row gy-4">

        <div class="col-lg-8">
          <div class="portfolio-details-slider swiper init-swiper">
            <div class="swiper-wrapper align-items-center">
                <div class="swiper-slide">
                    <img src="<?php echo e(asset('assets/img/portfolio/'.$project['image'])); ?>" alt="App 1">
                </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="portfolio-description" data-aos="fade-up" data-aos-delay="300">
            <h2><?php echo e($project['title']); ?></h2>
            <p>
                <?php echo e($project['description']); ?>

            </p>
          </div>
        </div>

      </div>

    </div>

  </section><!-- /Project Details Section -->

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Web Development\Github\Assignments-Interactive-Cares\6 Static-portfolio-site\resources\views/projects/project-details.blade.php ENDPATH**/ ?>