

<?php $__env->startSection('content'); ?>

  <div role="main" class="main-content">

    <div class="page-content container container-plus">
      <!-- page header and toolbox -->
      <div class="page-header pb-2">
        <h2 class="page-title text-primary-d2 text-150">
          <?php echo e($page_name ?? ""); ?>

        </h2>
      </div>

      <?php echo $__env->make('includes.alerts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

      <div class="cards-container mt-3">
        <div class="card border-0 shadow-sm radius-0">
          <div class="card-header bgc-primary-d1">
            <h5 class="card-title text-white">
              <i class="fa fa-list mr-2px"></i>
                <?php echo e($card_title ?? ""); ?>

            </h5>
          </div>
            <div class="card-body bgc-transparent px-2 py-0 pb-2 border-1 brc-primary-m3 border-t-0">
             <form action="<?php echo e(route('notification.store')); ?>" method="post">
                <?php echo csrf_field(); ?>
                <textarea name="emails" id="" cols="30" rows="10" class="form-control my-2"><?php echo e($emails); ?></textarea>
                <button class="btn btn-primary" name="action" value="test">Test Notification</button>
                <button class="btn btn-primary" name="action" value="save">Save Changes</button>
            </form>
            </div>
        </div>
    </div>
</div>
</div>




<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\GithubRepository\pryapus\resources\views/notification/create.blade.php ENDPATH**/ ?>