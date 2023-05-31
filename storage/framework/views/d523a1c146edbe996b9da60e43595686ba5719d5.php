

<?php $__env->startSection('content'); ?>

<div role="main" class="main-content">

  <div class="page-content container container-plus ">
    <!-- page header and toolbox -->
    <div class="page-header pb-2">
      <h2 class="page-title text-primary-d2 text-150">
        <a href="#" ><?php echo e($page_name ?? ""); ?></a>
        <small class="page-info text-secondary-d2 text-nowrap">
          <i class="fa fa-angle-double-right text-80"></i>
          <?php echo e($page_info ?? ""); ?>

        </small>
      </h2>
    </div>

    <?php echo $__env->make('includes.alerts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="cards-container mt-3">
      
      <div class="card border-0 shadow-sm radius-0">


        <div class="card-body px-2  pb-2 border-1 brc-primary-m3 ">
            <div class="row w-100">
                <div class="col-12 d-flex flex-row justify-content-end">
                    <div class="d-flex">
                        <input type="radio" name="range" value="WoW" id="WoW" class="form-control" <?php if(app('request')->input('type') == 'WoW'): ?> checked="" <?php endif; ?>>
                        <label for="WoW">Week Over Week</label>
                        <input type="radio" name="range" value="MoM" id="MoM" class="form-control ml-3" <?php if(app('request')->input('type') == 'MoM'): ?> checked="" <?php endif; ?>>
                        <label for="MoM">Month Over Month</label>
                    </div>
                </div>
     
                <div class="col-12 table-responsive mt-3">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="bg-light text-secondary">
                                <th>Source</th>
                                <th>Appointments Booked Between <?php echo e($priorPeriodStart); ?> and <?php echo e($thisPeriodStart); ?></th>
                                <th>Appointments Booked since <?php echo e($thisPeriodStart); ?></th>
                                <th>Trend</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $sources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $source): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                              <?php if($source->trend < 0): ?>
                                <tr class="table-danger text-secondary">
                              <?php elseif($source->trend >= 100.00): ?>
                                <tr class="table-success  text-secondary">
                              <?php else: ?>
                                <tr class="text-secondary">
                              <?php endif; ?>
                                <td><?php echo e($source->description); ?></td>
                                <td><?php echo e($source->priorPeriod); ?></td>
                                <td><?php echo e($source->thisPeriod); ?></td>
                                <td>
                                  <?php if($source->trend < 0): ?>
                                    <span class="text-secondary"><?php echo e($source->trend); ?>% <i class="nav-icon fa fa-arrow-down"></i></span>
                                  <?php elseif($source->trend > 100.00): ?>
                                  <span class="text-secondary"><?php echo e($source->trend); ?>% <i class="nav-icon fa fa-arrow-up"></i></span>
                                  <?php else: ?> 
                                    <?php echo e($source->trend); ?>%
                                  <?php endif; ?>  
                                </td>
                              </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                              <tr><td colspan="5">No data found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
      </div>
    </div>

  </div>

</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(mix('js/report.marketing-trend.js')); ?>" defer></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\GithubRepository\pryapus\resources\views/report/marketing-trend/index.blade.php ENDPATH**/ ?>