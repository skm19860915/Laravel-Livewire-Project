<table>
<thead>
    <tr>
        <th>id</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Date of Birth</th>
        <th>Address</th>
        <th>City</th>
        <th>State</th>
        <th>Zip</th>
        <th>Home Phone</th>
        <th>Cell Phone</th>
        <th>Email</th>
        <th>note</th>
        <th>HBP</th>
        <th>Cholesterol</th>
        <th>Diabetes</th>
        <th>Visits</th>
        <th>Total Sales</th>
        <th>Paid</th>
        <th>First Visit</th>
        <th>Last Visit</th>
        <th>Lead Source</th>
        <th>Payments</th>
    </tr>
</thead>
<tbody>
    <?php $__currentLoopData = $patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($p->id); ?></td>
            <td><?php echo e($p->first_name); ?></td>
            <td><?php echo e($p->last_name); ?></td>
            <td><?php echo e($p->date_of_birth); ?></td>
            <td><?php echo e($p->address); ?></td>
            <td><?php echo e($p->city); ?></td>
            <td><?php echo e($p->state); ?></td>
            <td><?php echo e($p->zip); ?></td>
            <td><?php echo e($p->home_phone); ?></td>
            <td><?php echo e($p->cell_phone); ?></td>
            <td><?php echo e($p->email); ?></td>
            <td><?php echo e($p->patient_note); ?></td>
            <td><?php echo e($p->high_blood_pressure); ?></td>
            <th><?php echo e($p->high_cholesterol); ?></th>
            <th><?php echo e($p->diabetes); ?></th>
            <td><?php echo e($p->visits); ?></td>
            <td><?php echo e($p->total_sales); ?></td>
            <td><?php echo e($p->paid); ?></td>
            <td><?php echo e($p->first_visit); ?></td>
            <td><?php echo e($p->last_visit); ?></td>
            <td><?php echo e($p->lead_source); ?></td>
            <td><?php echo e($p->payments); ?></td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</tbody>
</table>
<?php /**PATH D:\GithubRepository\pryapus\resources\views/exports/patients.blade.php ENDPATH**/ ?>