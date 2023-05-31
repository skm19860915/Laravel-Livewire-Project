

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

            <a href="<?php echo e(url('settings/create/marketing-source')); ?>" class="btn btn-success mt-1 mb-1"><i class="fa fa-plus mr-1"></i>Add New Marketing Source</a>

            <table id="locationsDatatable" class="d-style w-100 table text-dark-m1 text-95 border-y-1 brc-black-tp11 collapsed">
              <thead class="sticky-nav text-secondary-m1 text-uppercase text-85">
                <tr>
                  <th class="border-0 bgc-white bgc-h-yellow-l3 shadow-sm">
                    description
                  </th>
                  <th class="border-0 bgc-white bgc-h-yellow-l3 shadow-sm">
                    ACTIONS
                  </th>
                </tr>
              </thead>

              <tbody class="pos-rel">
              <?php if($MarketingSources->isNotEmpty()): ?>
                <?php $__currentLoopData = $MarketingSources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ms): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <tr class="d-style bgc-h-default-l4">
                    <td><?php echo e($ms->description); ?></td>

                    <td class="align-middle d-flex align-items-center">
                      <a  title="Edit" href="<?php echo e(route('marking-source.edit',['marketingSource'=>$ms->id])); ?>" >
                        <i class="fa fa-edit text-blue-m1 text-120 mx-2"></i>
                      </a>
                      <?php if($ms->disable): ?>
                      <a class="btn btn-success px-4 mt-2 " href="<?php echo e(route('marking-source.toggelDisable',['marketingSource'=>$ms->id])); ?>"  type="submit">
                          <i class="fa fa-play-circle mr-1"></i>
                          Activate
                      </a>
                      <?php else: ?>
                          <a class="btn btn-danger px-4 mt-2 disable" href="<?php echo e(route('marking-source.toggelDisable',['marketingSource'=>$ms->id])); ?>"  type="submit">
                              <i class="fa fa-pause-circle mr-1"></i>
                              Disable
                          </a>
                      <?php endif; ?>
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
<script src="<?php echo e(mix('js/marketing-sources.js')); ?>" defer></script>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\GithubRepository\pryapus\resources\views/marketing/index.blade.php ENDPATH**/ ?>