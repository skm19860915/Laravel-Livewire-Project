

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
                <div class="col-12">

                    <button id="daterange" data-start='<?php echo e($_start); ?>' data-end='<?php echo e($_end); ?>' class="btn btn-white border-dark shadow-sm text-dark  d-block ml-auto fz-12px">
                        <i class="fas fa-calendar-alt"></i>
                        <span><?php echo e($start); ?> - <?php echo e($end); ?></span>
                    </button>
                </div>
                <div class="col-12">
                    <span>Please note that Tickets marked for 'Revisit' are not included in AVG reports however WILL be included within the 'Paid Amount' column</span>
                </div>
                <div class="col-12 table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr class="bg-light ">
                                <th>Source</th>
                                <th>Paid Amount</th>
                                <th>AVG Paid Amount</th>
                                <th>AVG Total Amount</th>
                                <th>AVG Age</th>
                                <th>Booked</th>
                                <th>Reschedule</th>
                                <th>Cancel</th>
                                <th>Confirm</th>
                                <th>Shows</th>
                                <th>Trimix - Tickets/Doses</th>
                                <th>Sub - Tickets/Doses</th>
                                <th>T - Tickets/Doses</th>
                            </tr>
                        </thead>
                        <tbody class="">

                            <?php $__currentLoopData = $marketing_sources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                              <td><?php echo e($m->description); ?></td>
                              <td>$<?php echo e(number_format($m->paid_amount,2)); ?></td>
                              <td>$<?php echo e($m->avg_paid_amount); ?></td>
                              <td>$<?php echo e($m->avg_total_amount); ?></td>
                              <td><?php echo e(number_format($m->avg_age,0)); ?></td>
                              <td><?php echo e($m->booked); ?></td>
                              <td><?php echo e($m->reschedule); ?></td>
                              <td><?php echo e($m->cancel); ?></td>
                              <td><?php echo e($m->confirm); ?></td>
                              <td><?php echo e($m->shows); ?></td>
                              <td><?php echo e($m->trimix); ?>/<?php echo e(number_format($m->doses_trimix,0)); ?></td>
                              <td><?php echo e($m->sublingual); ?>/<?php echo e(number_format($m->doses_sublingual,0)); ?></td>
                              <td><?php echo e($m->testosterones); ?>/<?php echo e(number_format($m->doses_testosterones,0)); ?></</td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <th>Total</th>
                                    <td>$<?php echo e(number_format($totals['paid_amount'],2)); ?></td>
                                    <td>$<?php echo e(number_format($totals['avg_paid_amount'],2)); ?></td>
                                    <td>$<?php echo e(number_format($totals['avg_total_amount'],2)); ?></td>
                                    <td><?php echo e(number_format($totals['avg_age'],0)); ?></td>
                                    <td><?php echo e(($totals['booked'])); ?></td>
                                    <td><?php echo e(($totals['reschedule'])); ?></td>
                                    <td><?php echo e(($totals['cancel'])); ?></td>
                                    <td><?php echo e(($totals['confirm'])); ?></td>
                                    <td><?php echo e(($totals['shows'])); ?></td>
                                    <td><?php echo e($totals['trimix']); ?>/<?php echo e(number_format($totals['doses_trimix'],0)); ?></td>
                                    <td><?php echo e($totals['sublingual']); ?>/<?php echo e(number_format($totals['doses_sublingual'],0)); ?></td>
                                    <td><?php echo e($totals['testosterones']); ?>/<?php echo e(number_format($totals['doses_testosterones'],0)); ?></td>
                                </tr>
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

    <script src="<?php echo e(mix('js/report.marketing.js')); ?>" defer></script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\GithubRepository\pryapus\resources\views/report/marketing/index.blade.php ENDPATH**/ ?>