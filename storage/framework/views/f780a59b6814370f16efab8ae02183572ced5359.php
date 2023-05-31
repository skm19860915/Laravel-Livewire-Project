

<?php $__env->startSection('content'); ?>


  <div role="main" class="main-content">

    <div class="page-content container container-plus">
      <!-- page header and toolbox -->
      <div class="page-header pb-2">
        <h2 class="page-title text-primary-d2 text-150">
          Users
        </h2>
      </div>

      <?php echo $__env->make('includes.alerts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

      <div class="cards-container mt-3">
        <div class="card border-0 shadow-sm radius-0">
          <div class="card-header bgc-primary-d1">
            <h5 class="card-title text-white">
              <i class="fa fa-list mr-2px"></i>
              Staff/User List
            </h5>
          </div>

          <div class="card-body bgc-transparent px-2 py-0 pb-2 border-1 brc-primary-m3 border-t-0 table-responsive">

            <a href="<?php echo e(url('settings/create-user')); ?>" class="btn btn-success mt-1 mb-1"><i class="fa fa-plus mr-1"></i>Add Staff/User</a>

            <table id="usersDatatable" class="d-style w-100 table text-dark-m1 text-95 border-y-1 brc-black-tp11 collapsed">
              <thead class="text-secondary-m1 text-uppercase text-85">
                <tr>
                  <th class="border-0 bgc-white bgc-h-yellow-l3 shadow-sm">
                    Name
                  </th>
                  <th class="border-0 bgc-white bgc-h-yellow-l3 shadow-sm">
                    Username
                  </th>
                  <th class="border-0 bgc-white bgc-h-yellow-l3 shadow-sm">
                    Role
                  </th>
                  <th class="border-0 bgc-white bgc-h-yellow-l3 shadow-sm">
                    Email
                  </th>
                  <th class="border-0 bgc-white bgc-h-yellow-l3 shadow-sm">
                    Status
                  </th>
                  <th class="border-0 bgc-white shadow-sm w-2">
                  Actions
                  </th>
                </tr>
              </thead>

              <tbody class="pos-rel">
              <?php if($users->isNotEmpty()): ?>
                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <tr class="d-style bgc-h-default-l4">
                    <td><a href="<?php echo e(url('/settings/users')); ?>/<?php echo e($user->id); ?>"><?php echo e($user->last_name); ?>, <?php echo e($user->first_name); ?></a></td>
                    <td><?php echo e($user->username); ?></td>
                    <td><?php echo e($user->role->role); ?></td>
                    <td><?php echo e($user->email); ?></td>
                    <td>
                      <?php if($user->deleted_at): ?>
                        <span class="badge badge-danger badge-rounded">Disabled</span>
                      <?php else: ?>
                        <span class="badge badge-success badge-rounded">Active</span>
                      <?php endif; ?>
                    </td>
                    <td class="align-middle">
                      <a  title="Edit" href="<?php echo e(url('/settings/users')); ?>/<?php echo e($user->id); ?>" >
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

    <?php $__env->startSection('script'); ?>
        <script src="<?php echo e(mix('js/users.js')); ?>" defer></script>
    <?php $__env->stopSection(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\GithubRepository\pryapus\resources\views/users/index.blade.php ENDPATH**/ ?>