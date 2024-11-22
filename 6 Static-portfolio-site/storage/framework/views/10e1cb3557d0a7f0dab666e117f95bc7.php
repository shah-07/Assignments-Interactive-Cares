<?php $__env->startSection('content'); ?>

<?php echo $__env->make('partials.page-title', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

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
                <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-app">
                        <div class="portfolio-content h-100">
                            <img src="assets/img/portfolio/<?php echo e($project['image']); ?>" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>Project <?php echo e($project['id']); ?></h4>
                                <p><?php echo e($project['title']); ?></p>
                                <a href="<?php echo e(route('project.show', $project['id'])); ?>" title="More Details" class="details-link"><i class="bi bi-link-45deg"></i></a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</section>
<!-- /Projects Section -->

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Web Development\Github\Assignments-Interactive-Cares\6 Static-portfolio-site\resources\views/projects/projects.blade.php ENDPATH**/ ?>