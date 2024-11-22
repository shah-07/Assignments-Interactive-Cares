<?php $__env->startSection('content'); ?>

<?php echo $__env->make('partials.page-title', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<!-- Work Experience Section -->
<section id="about" class="about section">
    <div class="container section-title" data-aos="fade-up">
        <h2>Work Experience</h2>
        <?php $__currentLoopData = $workExperiences; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $workExp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="resume-item mb-5">
            <h4><?php echo e($workExp['title']); ?></h4>
            <h5><?php echo e($workExp['start year']); ?> - <?php echo e($workExp['end year']); ?></h5>
            <p><?php echo e($workExp['description']); ?></p>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</section>
<!-- /Work Experience Section -->

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Web Development\Github\Assignments-Interactive-Cares\6 Static-portfolio-site\resources\views/work experiences/experiences.blade.php ENDPATH**/ ?>