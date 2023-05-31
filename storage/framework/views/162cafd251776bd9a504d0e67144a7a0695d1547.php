<div role="main" class="main-content">
  <div class="page-content container container-plus">
    <div class="page-header pb-2">
      <h2 class="page-title text-primary-d2 text-150">
        <a href="<?php echo e(url('settings/emails')); ?>" >Email Journeys</a>
        <small class="page-info text-secondary-d2 text-nowrap"><i class="fa fa-angle-double-right text-80"></i>&nbsp;Edit Email Journey</small>
      </h2>
    </div>

    <?php if($updated == 'success'): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        The template has been updated successfully.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php elseif($updated == 'failed'): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        The template could not be updated. Try again.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php endif; ?>

    <div class="cards-container mt-3">
      <div class="card border-0 shadow-sm radius-0">
        <div class="card-header bgc-primary-d1">
          <h5 class="card-title text-white">Email Journey Template</h5>
        </div>
        <div class="card-body px-2 py-4 border-1 brc-primary-m3 border-t-0">
            <div class="row">
              <div class="col12 col-md-6">
                <div class="form-group">
                    <label for="name" class="mb-0">Name</label>
                    <input type="text" wire:model.debounce.500ms="name" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red  <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" />
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
              </div>
              <div class="col12 col-md-6">
                <div class="form-group">
                    <label for="subject" class="mb-0">Subject</label>
                    <input type="text" wire:model.debounce.500ms="subject" class="form-control <?php $__errorArgs = ['subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red  <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" />
                    <?php $__errorArgs = ['subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col12 col-md-12">
                <div class="form-group">
                    <label for="body" class="mb-0">Body</label>
                    <textarea id="body" wire:model.debounce.500ms="body" rows="10" class="form-control <?php $__errorArgs = ['body'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red  <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"></textarea>
                    <?php $__errorArgs = ['body'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col6 col-md-6 text-left">
                <div class="form-group">
                    <label for="trigger_date_type" class="col-form-label">Trigger Date:</label>
                    <select wire:model.debounce.500ms="trigger_date_type" id="trigger_date_type" class="form-control">
                        <option value="">Select Trigger Date</option>
                        <option value="1">Ticket Date</option>
                        <option value="2">Appointment Date</option>
                        <option value="3">Treatment End Date</option>
                    </select>
                    <?php $__errorArgs = ['trigger_date_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
              </div>
              <div class="col6 col-md-6 text-left">
                <div class="form-group">
                    <label for="treatment_type_id" class="col-form-label">Treatment Type:</label>
                    <select wire:model.debounce.500ms="treatment_type_id" id="treatment_type_id" class="form-control">
                        <option value="">Select Treatment Type</option>
                        <?php $__currentLoopData = $treatments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $treatment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($treatment->id); ?>"><?php echo e($treatment->description); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <option value="0">ACE</option>
                    </select>
                    <?php $__errorArgs = ['treatment_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col6 col-md-12 text-left">
                <div class="form-inline">
                    <label class="col-form-label">Send Email&nbsp;&nbsp;
                    <input type="text" class="form-control" wire:model.debounce.500ms="days" id="days">&nbsp;&nbsp;
                    Day(s) from Trigger Date</label>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col12 col-md-12 text-left pt-2">
                <button class="btn btn-primary px-4 submit-buttons" wire:click="updateTemplate(<?php echo e($edit_id); ?>, <?php echo e($status); ?>)">Update</button>
              </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php $__env->startSection('script'); ?>
<script src="https://cdn.tiny.cloud/1/rmknmjxd061rk4pinyl8kgvksqfw5jv8p9nxo7z3eecj4073/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({ selector: '#body' });
</script>
<?php $__env->stopSection(); ?>





<?php /**PATH D:\GithubRepository\pryapus\resources\views/livewire/edit-email.blade.php ENDPATH**/ ?>