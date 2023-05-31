

<?php $__env->startSection('content'); ?>


  <div role="main" class="main-content">

    <div class="page-content container container-plus">
      <!-- page header and toolbox -->
      <div class="page-header pb-2">
        <h2 class="page-title text-primary-d2 text-150">
          Locations
        </h2>
      </div>

      <?php echo $__env->make('includes.alerts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

      <div class="cards-container mt-3">
        <div class="card border-0 shadow-sm radius-0">
          <div class="card-header bgc-primary-d1">
            <h5 class="card-title text-white">
              <i class="fa fa-list mr-2px"></i>
              Locations List
            </h5>
          </div>

          <div class="card-body bgc-transparent px-2 py-0 pb-2 border-1 brc-primary-m3 border-t-0">

            <a href="<?php echo e(url('settings/create-location')); ?>" class="btn btn-success mt-1 mb-1"><i class="fa fa-plus mr-1"></i>Add New Location</a>

            <table id="locationsDatatable" class="d-style w-100 table text-dark-m1 text-95 border-y-1 brc-black-tp11 collapsed">
              <thead class="sticky-nav text-secondary-m1 text-uppercase text-85">
                <tr>
                  <th class="border-0 bgc-white bgc-h-yellow-l3 shadow-sm">
                    Name
                  </th>
                  <th class="border-0 bgc-white bgc-h-yellow-l3 shadow-sm">
                    Address
                  </th>
                  <th class="border-0 bgc-white bgc-h-yellow-l3 shadow-sm">
                    City
                  </th>
                  <th class="border-0 bgc-white bgc-h-yellow-l3 shadow-sm">
                    State
                  </th>
                  <th class="border-0 bgc-white bgc-h-yellow-l3 shadow-sm">
                    Zip
                  </th>
                  <th class="border-0 bgc-white bgc-h-yellow-l3 shadow-sm">
                    Phone
                  </th>
                  <th class="border-0 bgc-white shadow-sm w-2">
                  Actions
                  </th>
                </tr>
              </thead>

              <tbody class="pos-rel">
              <?php if($locations->isNotEmpty()): ?>
                <?php $__currentLoopData = $locations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <tr class="d-style bgc-h-default-l4">
                    <td><a href="<?php echo e(url('/settings/locations')); ?>/<?php echo e($location->id); ?>"><?php echo e($location->location_name); ?></a></td>
                    <td><?php echo e($location->address); ?></td>
                    <td><?php echo e($location->city); ?></td>
                    <td><?php echo e($location->state); ?></td>
                    <td><?php echo e($location->zip); ?></td>
                    <td><?php echo e($location->phone); ?></td>

                    <td class="align-middle">
                      <a  title="Edit" href="<?php echo e(url('/settings/locations')); ?>/<?php echo e($location->id); ?>" >
                        <i class="fa fa-edit text-blue-m1 text-120"></i>
                      </a>
                    </td>
                  </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

  </div>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('js/locations.js')); ?>" defer></script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\GithubRepository\pryapus\resources\views/locations/index.blade.php ENDPATH**/ ?>