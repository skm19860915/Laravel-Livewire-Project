

<?php $__env->startSection('content'); ?>

<div role="main" class="main-content">

  <div class="page-content container container-plus">
    <!-- page header and toolbox -->
    <div class="page-header pb-2">
      <h2 class="page-title text-primary-d2 text-150">
        <a href="<?php echo e(url('settings/schedule-types')); ?>" ><?php echo e($page_name ?? ""); ?></a>
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
            <i class="fa fa-plus mr-2px"></i>
            <?php echo e($card_title ?? ""); ?>

          </h5>
        </div>

        <div class="card-body px-2 py-0 pb-2 border-1 brc-primary-m3 border-t-0">
          <form class="mt-lg-3 forms" method="POST" action="<?php echo e(url('settings/create/schedule-type')); ?>">
            <?php echo csrf_field(); ?>
            <div class="row">
              <div class="col-12 ">
                <div class="form-group">
                  <label for="location_name" class="mb-0">Description</label>
                  <span class="text-danger">*</span>
                  <input
                  type="text"
                  id="description"
                  name="description"
                  class="form-control"
                  required />
                </div>
              </div>

            </div>

         <div class="row">
              <div class="col-12 ">
                <div class="form-group">
                  <label for="alert_type" class="mb-0">Alert Type</label>
                  <span class="text-danger">*</span>
                  <input
                  type="text"
                  id="alert_type"
                  name="alert_type"
                  value="dark"
                  class="form-control"
                  required />
                </div>
              </div>
            </div>


            <div class="row mt-4">
              <div class="col12 col-md-12 text-left">
                <button class="btn btn-primary px-4 submit-buttons"  type="submit">
                  <i class="fa fa-check mr-1"></i>
                  Add Schedule Type
                </button>
              </div>
            </div>

          </form>
        </div>
      </div>
    </div>

  </div>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\GithubRepository\pryapus\resources\views/schedule_type/create.blade.php ENDPATH**/ ?>