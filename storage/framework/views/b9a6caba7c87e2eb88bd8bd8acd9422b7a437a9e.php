

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

    <div class="cards-container mt-3 table-responsive">
        <h4>$ Due Today</h4>
        <hr>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th></th>
                    <th>Patient</th>
                    <th>Ticket #</th>
                    <th>Date Due</th>
                    <th>Payment Owed</th>
                    <th>Balance</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                 <?php $__currentLoopData = $todaydue; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <tr class="bg-light-danger">
                    <td>#<?php echo e($index+=1); ?>.</td>
                    <td>
                        <a
                        target="_blank"
                        href="<?php echo e(route('patient.overview',['patient' => $p->patient_id])); ?>">
                            <?php echo e($p->patient_last_name.', '.$p->patient_first_name); ?>

                        </a>
                    </td>
                    <td>
                        <a
                        target="_blank"
                        href="<?php echo e(route('ticket.edit',['ticket'=> $p->ticket_id,'appointment'=>$p->schedule_id])); ?>" >#<?php echo e($p->ticket_id); ?></a>
                    </td>
                    <td><?php echo e($p->due); ?></td>
                    <td>$<?php echo e($p->payment_owed); ?></td>
                    <td>$<?php echo e($p->balance); ?></td>
                    <td>
                        <a
                            
                            
                            href="<?php echo e(route('payment.create',['ticket' => $p->ticket_id])); ?>"
                            class="btn btn-light  brc-danger-m3 border-0 border-l-3 rounded-0"
                            >
                            Proccess Payment
                        </a>
                    </td>
                </tr>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        
        <h4><i class="fas fa-exclamation-circle"></i> Overdue</h4>
        <hr>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th></th>
                    <th>Patient</th>
                    <th>Ticket #</th>
                    <th>Date Due</th>
                    <th>Payment Owed</th>
                    <th>Balance</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>

                 <?php $__currentLoopData = $overdue; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <tr class="bg-light-danger">
                    <td>#<?php echo e($index+=1); ?>.</td>
                    <td>
                        <a
                        target="_blank"
                        href="<?php echo e(route('patient.overview',['patient' => $p->patient_id])); ?>">
                            <?php echo e($p->patient_last_name.', '.$p->patient_first_name); ?>

                        </a>
                    </td>
                    <td>
                        <a
                        target="_blank"
                        href="<?php echo e(route('ticket.edit',['ticket'=> $p->ticket_id,'appointment'=>$p->schedule_id])); ?>" >#<?php echo e($p->ticket_id); ?></a>
                    </td>
                    <td><?php echo e($p->due); ?></td>
                    <td>$<?php echo e($p->payment_owed); ?></td>
                    <td>$<?php echo e($p->balance); ?></td>
                    <td>
                        <a
                            
                            
                            href="<?php echo e(route('payment.create',['ticket' => $p->ticket_id])); ?>"
                            class="btn btn-light  brc-danger-m3 border-0 border-l-3 rounded-0"
                            >
                            Proccess Payment
                        </a>
                    </td>
                </tr>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
           </tbody>
        </table>
    </div>
    </div>

  </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

<script src="<?php echo e(mix('js/receivable.js')); ?>" defer></script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\GithubRepository\pryapus\resources\views/receivable/index.blade.php ENDPATH**/ ?>