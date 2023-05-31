

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
      <div class="card border-0 shadow-sm radius-0">
        <div class="card-header bgc-primary-d1">
          <h5 class="card-title text-white">
            <i class="fa fa-edit mr-2px"></i>
            <?php echo e($card_title ?? ""); ?>

          </h5>
        </div>

        <div class="card-body px-2 py-0 pb-2 border-1 brc-primary-m3 border-t-0">
            <?php echo $__env->make('patient._edit_form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
      </div>
    </div>

  </div>

</div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>

<script src="<?php echo e(mix('js/patients.js')); ?>" defer></script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\GithubRepository\pryapus\resources\views/patient/edit.blade.php ENDPATH**/ ?>