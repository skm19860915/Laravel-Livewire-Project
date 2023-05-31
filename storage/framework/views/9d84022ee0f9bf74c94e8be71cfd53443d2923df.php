

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

            <a href="<?php echo e(url('/settings/create/role')); ?>" class="btn btn-success mt-1 mb-1"><i class="fa fa-plus mr-1"></i>Add New Role</a>

            <table id="patientDatatable" class="d-style w-100 table text-dark-m1 text-95 border-y-1 brc-black-tp11 collapsed">
              <thead class="sticky-nav text-secondary-m1 text-uppercase text-85">
                <tr>
                  <th class="border-0 bgc-white bgc-h-yellow-l3 shadow-sm">
                    id
                  </th>
                  <th class="border-0 bgc-white bgc-h-yellow-l3 shadow-sm">
                    role
                  </th>
                  <th class="border-0 bgc-white bgc-h-yellow-l3 shadow-sm">
                    Acitons
                  </th>
                </tr>
              </thead>
              <body>
                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($r->id); ?></td>
                        <td><?php echo e($r->role); ?></td>
                        <td>
                            <a href="<?php echo e(url('/settings/edit/role/'.$r->id)); ?>">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </body>

              <tbody class="pos-rel">

              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

  </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\GithubRepository\pryapus\resources\views/role/index.blade.php ENDPATH**/ ?>