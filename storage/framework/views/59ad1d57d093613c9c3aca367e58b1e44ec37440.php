

<?php $__env->startSection('content'); ?>
  <div role="main" class="main-content">
    <div class="page-content container container-plus ">
    <div class="page-header pb-2 no-print">
      <h2 class="page-title text-primary-d2 text-150">
        <a href="<?php echo e(route('ticket.index')); ?>"><?php echo e($page_name ?? ""); ?></a>
        <small class="page-info text-secondary-d2 text-nowrap">
          <i class="fa fa-angle-double-right text-80"></i>
          <?php echo e($page_info ?? ""); ?>

        </small>
      </h2>
    </div>
        <div class="row w-100">

            <div class="col-12 text-right ">
                <h2>Invoice #<?php echo e($ticket->id); ?></h2>
                <h2><?php echo e($ticket->created_at); ?></h2>
            </div>

            <div class="col-12">
                <hr>
            </div>

            <div class="col-12 w-100">
                <div class="row">
                    <div class="col-6">
                        <h4>Patient Info</h4>
                        <div>
                            <span><?php echo e($patient->first_name); ?> <?php echo e($patient->last_name); ?></span>
                        </div>
                        <div>
                            <span>P:<?php echo e($patient->home_phone); ?></span>
                        </div>
                        <div>
                            <span>C:<?php echo e($patient->cell_phone); ?></span>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4>Payment Details</h4>
                        <div>
                            <span><?php echo e($ticket->month_plan); ?> month(s) payment plan</span>
                        </div>
                        <div>
                            <span><?php echo e($ticket->month_plan); ?> Payment(s) of $<?php echo e($ticket->payment_increments); ?>/month.</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mt-2">
                <table class="table   table-striped">
                    <thead>

                    <tr>
                        <th>#</th>
                        <th>Item</th>
                        <th>Description</th>
                        <th>Total</th>
                    </tr>

                    </thead>

                    <tbody>

                        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($index+=1); ?></td>
                                <td><?php echo e($i->item); ?></td>
                                <td><?php echo e($i->description); ?></td>
                                <td><?php echo e($i->total); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </tbody>
                </table>
            </div>

            <div class="col-12 w-100">
                <div class="row">
                    <div class="col-6">
                      <div class="w-100 bg-light h-250px p-2">
                          <h5><b><?php echo e($clinic->location_name); ?></b></h5>
                          <span> <?php echo e($clinic->address); ?> </span>
                          <br>
                          <span> <?php echo e($clinic->city); ?>, <?php echo e($clinic->state); ?> </span>
                          <br>
                          <span>P: <?php echo e($clinic->phone); ?> </span>
                          <br>
                          <span> <?php echo e($clinic->website); ?> </span>
                          <br>
                          <p class="mt-5 mb-0"><b>Email</b></p>
                          <span > <?php echo e($clinic->email); ?> </span>
                      </div>
                    </div>
                    <div class="col-6">
                        <div class="w-100   h-250px">
                            <div class="w-100   h-250px p-2 text-right">

                                <span><span><b>Total amount:</b></span> $<?php echo e($ticket->total); ?> </span>
                                <br>
                                <span><span><b>Paid on visit:</b></span> $<?php echo e($ticket->amount_paid_during_office_visit); ?> </span>
                                <br>
                                <span><span><b>Balance:</b></span> $<?php echo e($ticket->balanc_during_visit); ?> </span>
                                <br>
                                <span><i>First Payment of $<?php echo e($ticket->payment_increments); ?> due <?php echo e($ticket->first_payment_due); ?></i></span>
                                <br>
                                <button type="button" class="btn btn-primary no-print" onclick="window.print()" >Print <i class="fas fa-print"></i></button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>


    </div>
  </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\GithubRepository\pryapus\resources\views/tickets/view.blade.php ENDPATH**/ ?>