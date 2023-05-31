<table id="emailCommunicationsTable" data-patient="<?php echo e($patient->id); ?>" class="d-style  w-100 table text-dark-m1 text-95 border-y-1 brc-black-tp11 collapsed">
    <thead class="sticky-nav text-secondary-m1 text-uppercase text-85">
        <tr>
            <th class="border-0 bgc-white bgc-h-yellow-l3 shadow-sm">Email Name</th>
            <th class="border-0 bgc-white bgc-h-yellow-l3 shadow-sm">Send Date</th>
            <th class="border-0 bgc-white bgc-h-yellow-l3 shadow-sm">Status</th>
            <th class="border-0 bgc-white bgc-h-yellow-l3 shadow-sm">Actions</th>
        </tr>
    </thead>
    <tbody class="pos-rel">
        <?php $__currentLoopData = $emails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr class="d-style bgc-h-default-l4">
            <td><?php echo e($e->name); ?></td>
            <td><?php echo e($e->updated_at); ?></td>
            <td>
            <?php if($e->status == 0): ?>
                <div class="btn btn-primary radius-4" style="cursor:unset;">Queued</div>
            <?php elseif($e->status == -1): ?>
                <div class="btn btn-danger radius-4" style="cursor:unset;">Failed</div>
            <?php else: ?>
                <div class="btn btn-success radius-4" style="cursor:unset;">Sent</div>
            <?php endif; ?>
            </td>
            <td class="align-middle" style="display:flex;">
                <?php if($e->status == 0): ?>
                    <a id="<?php echo e($e->id); ?>" class="btn btn-success btn-status">Remove</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>

<?php /**PATH D:\GithubRepository\pryapus\resources\views/patient/_communication.blade.php ENDPATH**/ ?>