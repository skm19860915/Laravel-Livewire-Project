<?php $__env->startSection('content'); ?>

<div role="main" class="main-content">

<div class="page-content container container-plus">
    <!-- page header and toolbox -->
<div class="page-header pb-2">
    <h2 class="page-title text-primary-d2 text-150">
    <a href="<?php echo e(url('/patients')); ?>" ><?php echo e($page_name ?? ""); ?></a>
    <small class="page-info text-secondary-d2 text-nowrap">
        <i class="fa fa-angle-double-right text-80"></i>
        <?php echo e($page_info ?? ""); ?>

    </small>
    </h2>
</div>

    <?php echo $__env->make('includes.alerts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="cards-container mt-3">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#overview" role="tab" aria-controls="home" aria-selected="true">Overview</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#edit_info" role="tab" aria-controls="profile" aria-selected="false">Edit Info</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#history" role="tab" aria-controls="contact" aria-selected="false">History</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="communication-tab" data-toggle="tab" href="#communication" role="tab" aria-controls="contact" aria-selected="false">Communications</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="home-tab">
            <?php echo $__env->make('patient._overview', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>

        <div class="tab-pane fade" id="edit_info" role="tabpanel" aria-labelledby="profile-tab">
            <?php echo $__env->make('patient._edit_form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="contact-tab">
            <?php echo $__env->make('patient._history', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="tab-pane fade" id="communication" role="tabpanel" aria-labelledby="communication-tab">
            <?php echo $__env->make('patient._communication', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>
    </div>
</div>
</div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(mix('js/history.js')); ?>" defer></script>
    <script src="<?php echo e(mix('js/communication.js')); ?>" defer></script>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\GithubRepository\pryapus\resources\views/patient/overview.blade.php ENDPATH**/ ?>