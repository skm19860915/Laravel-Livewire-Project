<table>
<thead>
    <tr>
        <th>id</th>
        <th>Patient</th>
        <th>visit_date</th>
        <th>Modified</th>
        <th>Modified By</th>
        <th>Sales_counselor</th>
        <th>Office_Visit_amount</th>
        <th>Applicator Amount</th>
        <th>paid_amount</th>
        <th>total_amount</th>
        <th>pay_increments</th>
        <th>Refill</th>
        <th>purchased</th>
        
    </tr>
</thead>
<tbody>
    <?php $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

        <tr>
        <th><?php echo e($t->id); ?></th>
        <th><?php echo e($t->patient_name); ?></th>
        <th><?php echo e($t->date); ?></th>
        <th><?php echo e($t->updated_at); ?></th>
        <th><?php echo e($t->modified_by); ?></th>
        <th><?php echo e($t->sales_counselor); ?></th>
        <th><?php echo e($t->officeVisit); ?></th>
        <th><?php echo e($t->applicator); ?></th>
        <th><?php echo e(number_format($t->amount_paid_during_office_visit)); ?></th>
        <th><?php echo e(number_format($t->total)); ?></th>
        <th><?php echo e($t->month_plan); ?></th>
        <th><?php echo e($t->refill); ?></th>
        <th><?php echo e($t->purchased); ?></th>
        
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</tbody>
</table>
<?php /**PATH D:\GithubRepository\pryapus\resources\views/exports/tickets.blade.php ENDPATH**/ ?>