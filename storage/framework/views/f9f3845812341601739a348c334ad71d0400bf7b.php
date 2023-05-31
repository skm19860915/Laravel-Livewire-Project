

<?php $__env->startSection('content'); ?>
    <div role="main" class="main-content">

        <div class="page-content container container-plus">
            <!-- page header and toolbox -->
            <div class="page-header pb-2">
                <h2 class="page-title text-primary-d2 text-150">
                    <?php echo e($page_name ?? ''); ?>

                </h2>
            </div>

            <?php echo $__env->make('includes.alerts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


            <ul class="nav nav-tabs mt-3" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#products" role="tab" aria-controls="home" aria-selected="true">Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#services" role="tab" aria-controls="profile" aria-selected="false">Services</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="products" role="tabpanel" aria-labelledby="home-tab">

                    <?php echo $__env->make('pricing.products', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                </div>
                <div class="tab-pane fade" id="services" role="tabpanel" aria-labelledby="profile-tab">

                    <?php echo $__env->make('pricing.services', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                </div>
            </div>
        </div>

    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(mix('js/pricing.js')); ?>" defer></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\GithubRepository\pryapus\resources\views/pricing/index.blade.php ENDPATH**/ ?>