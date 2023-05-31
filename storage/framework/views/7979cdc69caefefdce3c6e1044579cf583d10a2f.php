

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
                    <span>Individual Reports DO NOT Include refills</span>
                </div>
                <div class="col-12 table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr class="bg-light ">
                                <th>Counselor Name</th>
                                <th>ACE %</th>
                                <th>Number of Tickets</th>
                                <th>Total Paid</th>
                                <th>AVG Down Payments</th>
                                <th>AVG Ticket Amount</th>
                                <th>Gross Sales</th>
                                <th>Collected From Balances</th>
                            </tr>
                        </thead>
                        <tbody class="">

                            <?php $__currentLoopData = $counselors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <tr>
                                <th><?php echo e(ucwords(strtolower($c->counselor_name))); ?></th>
                                <td><?php echo e(empty($c->ace) ? '0.00' : $c->ace); ?>%</td>
                                <td><?php echo e($c->number_of_tickets); ?></td>
                                <td>$<?php echo e(number_format((float) $c->total_down_payments,2)); ?></td>
                                <td>$<?php echo e(number_format((float) $c->avg_down_payments,2)); ?></td>
                                <td>$<?php echo e(number_format((float) $c->avg_ticket_amount,2)); ?></td>
                                <td>$<?php echo e(number_format((float) $c->gross_sales,2)); ?></td>
                                <td>$<?php echo e(number_format((float) $c->collected_from_balances,2)); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <th>Total</th>
                                    <td></td>
                                    <td><?php echo e($totals['number_of_tickets']); ?></td>
                                    <td>$<?php echo e(number_format((float) $totals['total_down_payments'],2)); ?></td>
                                    <td>$<?php echo e(number_format((float) $totals['avg_down_payments'],2)); ?></td>
                                    <td>$<?php echo e(number_format((float) $totals['avg_ticket_amount'],2)); ?></td>
                                    <td>$<?php echo e(number_format((float) $totals['gross_sales'],2)); ?></td>
                                    <td>$<?php echo e(number_format((float) $totals['collected_from_balances'],2)); ?></td>
                                </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-12">
                    <span>Unassigned Tickets</span>
                </div>
                <div class="col-12 table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr class="bg-light ">
                                <th>Ticket#</th>
                                <th>Date</th>
                                <th>Patient Name</th>
                                <th>Sales Counselor</th>
                                <th>Total</th>
                                <th>Balance</th>
                            </tr>
                        </thead>
                        <tbody class="">
                            <?php $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="bg-light-danger">
                                <th>
                                    <a href="<?php echo e(route('ticket.edit',['ticket'=>$t->id,'appointment' => $t->appointment->id])); ?>">#<?php echo e($t->id); ?></a>
                                </th>
                                <td><?php echo e($t->date); ?></td>
                                <td><?php echo e($t->patient->name(', ')); ?></td>
                                <td>Not Selected</td>
                                <td>$<?php echo e(number_format($t->total,2)); ?></td>
                                <td>$<?php echo e(number_format($t->balance,2)); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<script src="<?php echo e(mix('js/report.finance.js')); ?>" defer></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\GithubRepository\pryapus\resources\views/report/finance/index.blade.php ENDPATH**/ ?>