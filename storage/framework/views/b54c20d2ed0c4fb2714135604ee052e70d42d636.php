<div role="main" class="main-content">
    <div class="page-content container container-plus">
        <div class="page-header pb-2">
            <h2 class="page-title text-primary-d2 text-150">Email Journeys</h2>
        </div>

        <div id="email_action" style="display:none" class="alert alert-danger alert-dismissible fade show" role="alert">
            This template can not be deactivated because it is already used.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="cards-container mt-3">
            <div class="card border-0 shadow-sm radius-0">
                <div class="card-header bgc-primary-d1">
                    <h5 class="card-title text-white"><i class="fa fa-list mr-2px"></i>Email Journeys</h5>
                </div>
                <div class="card-body bgc-transparent px-2 py-0 pb-2 border-1 brc-primary-m3 border-t-0">
                    <a href="<?php echo e(url('settings/create-email')); ?>" class="btn btn-success mt-1 mb-1"><i class="fa fa-plus mr-1"></i>Add New Email Journey</a>
                    <table id="emailsDatatable" class="d-style w-100 table text-dark-m1 text-95 border-y-1 brc-black-tp11 collapsed">
                        <thead class="sticky-nav text-secondary-m1 text-uppercase text-85">
                        <tr>
                            <th class="border-0 bgc-white bgc-h-yellow-l3 shadow-sm">Email Journey Name</th>
                            <th class="border-0 bgc-white bgc-h-yellow-l3 shadow-sm">Treatment Type</th>
                            <th class="border-0 bgc-white bgc-h-yellow-l3 shadow-sm">Status</th>
                            <th class="border-0 bgc-white shadow-sm w-2">Actions</th>
                        </tr>
                        </thead>
                        <tbody class="pos-rel">
                            <?php $__currentLoopData = $email_journeys_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $email_journeys): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="d-style bgc-h-default-l4">
                                <td><?php echo e($email_journeys->name); ?></td>
                                <td><?php echo e(empty($email_journeys->description) ? "ACE" : $email_journeys->description); ?></td>
                                <td>
                                <?php if($email_journeys->status): ?>
                                    Activate
                                <?php else: ?>
                                    Deactivate
                                <?php endif; ?>
                                </td>
                                <td class="align-middle" style="display:flex;">
                                    <a title="Edit" href="<?php echo e(url('/settings/emails')); ?>/<?php echo e($email_journeys->id); ?>" class="btn btn-primary mr-1">Edit</a>
                                    <?php if($email_journeys->status): ?>
                                    <a id="<?php echo e($email_journeys->id); ?>" class="btn btn-success btn-status">Deactivate</a>
                                    <?php else: ?>
                                    <a id="<?php echo e($email_journeys->id); ?>" class="btn btn-success btn-status">Activate</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('js/emails.js')); ?>" defer></script>
<?php $__env->stopSection(); ?>
<?php /**PATH D:\GithubRepository\pryapus\resources\views/livewire/email-list.blade.php ENDPATH**/ ?>