

<?php $__env->startSection('content'); ?>

<div role="main" class="main-content">

  <div class="page-content container container-plus">
    <!-- page header and toolbox -->
    <div class="page-header pb-2">
      <h2 class="page-title text-primary-d2 text-150">
        <a href="<?php echo e(url('/patients')); ?>" ><?php echo e($page_name ?? ""); ?></a>
        <small class="page-info text-secondary-d2 text-nowrap">
          <i class="fa fa-angle-double-right text-80"></i>
          <?php echo e($page_info ?? ""); ?>

        </small>
      </h2>
    </div>

    <?php echo $__env->make('includes.alerts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="cards-container mt-3">
      <div class="card border-0 shadow-sm radius-0">
        <div class="card-header bgc-primary-d1">
          <h5 class="card-title text-white">
            <i class="fa fa-plus mr-2px"></i>
            <?php echo e($card_title ?? ""); ?>

          </h5>
        </div>

        <div class="card-body px-2 py-0 pb-2 border-1 brc-primary-m3 border-t-0">
          <form class="mt-lg-3 forms" method="POST" action="<?php echo e(url('/create/patient')); ?>">
            <?php echo csrf_field(); ?>
            <div class="row">
              <div class="col-12">
                <h4>Personal info</h4>
                <hr>
              </div>
              <div class="col-6 ">
                <div class="form-group">
                  <label for="first_name" class="mb-0">First Name</label>
                  <span class="text-danger">*</span>
                  <input type="text" id="first_name" name="first_name" value="<?php echo e(old('first_name')); ?>" class="form-control"    required />
                </div>
              </div>
              <div class="col-6 ">
                <div class="form-group">
                  <label for="last_name" class="mb-0">Last Name</label>
                  <span class="text-danger">*</span>
                  <input type="text" id="last_name" name="last_name" value="<?php echo e(old('last_name')); ?>" class="form-control"  required  />
                </div>
              </div>
              <div class="col-6 ">
                <div class="form-group">
                  <label for="home_phone" class="mb-0">Home Phone</label>
                  
                  <input type="text" id="home_phone" name="home_phone" value="<?php echo e(old('home_phone')); ?>" class="form-control phones"   />
                </div>
              </div>
              <div class="col-6 ">
                <div class="form-group">
                  <label for="cell_phone" class="mb-0">Cell Phone</label>
                  
                  <input type="text" id="cell_phone" name="cell_phone" value="<?php echo e(old('cell_phone')); ?>" class="form-control phones"   />
                </div>
              </div>
              <div class="col-6 ">
                <div class="form-group">
                  <label for="email" class="mb-0">Email</label>
                  
                  <input type="email" id="email" name="email" value="<?php echo e(old('email')); ?>" class="form-control"   />
                </div>
              </div>
              <div class="col-6 ">
                <div class="form-group">
                  <label for="date_of_birth" class="mb-0">Date of Birth</label>
                  
                  <input type="input" autocomplete="0" id="date_of_birth" name="date_of_birth" value="<?php echo e(old('date_of_birth')); ?>" class="form-control dates"   />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <h4>Address info</h4>
                <hr>
              </div>
              <div class="col-12 ">
                <div class="form-group">
                  <label for="address" class="mb-0">Address</label>
                  
                  <input type="text" id="address" name="address" value="<?php echo e(old('address')); ?>" class="form-control"   />
                </div>
              </div>
              <div class="col-6 ">
                <div class="form-group">
                  <label for="city" class="mb-0">City</label>
                  
                  <input type="text" id="city" name="city" value="<?php echo e(old('city')); ?>" class="form-control"   />
                </div>
              </div>
              <div class="col-6 ">
                <div class="form-group">
                  <label for="state" class="mb-0">State</label>
                  
                  <select type="text" id="state" name="state"   data-value=<?php echo e(old('state',session('current_location')->state)); ?> class="form-control">
                  </select>
                </div>
              </div>
              <div class="col-6 ">
                <div class="form-group">
                  <label for="zip" class="mb-0">Zip</label>
                  
                  <input type="text" id="zip" name="zip" class="form-control zips " value="<?php echo e(old('zip')); ?>"   />
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-12">
                <h4>Existing Health Conditions</h4>
                <hr>
              </div>
              <div class="col-3 ">
                <div class="form-group d-flex">
                    <input type="checkbox" value="1" id="high_blood_pressure" name="high_blood_pressure" <?php echo e(old('high_blood_pressure') ? 'checked' : ''); ?>  class="form-control"   />
                    <label for="high_blood_pressure" class="mb-0">High Blood Pressure</label>
                </div>
              </div>
              <div class="col-3 ">
                <div class="form-group d-flex">
                    <input type="checkbox" value="1" id="high_cholesterol" name="high_cholesterol" class="form-control" <?php echo e(old('high_cholesterol') ? 'checked' : ''); ?>   />
                    <label for="high_cholesterol" class="mb-0">High Cholesterol</label>
                </div>
              </div>
              <div class="col-3 ">
                <div class="form-group d-flex">
                    <input type="checkbox" value="1" id="diabetes" name="diabetes" class="form-control" <?php echo e(old('diabetes') ? 'checked' : ''); ?>    />
                    <label for="diabetes" class="mb-0">Diabetes</label>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <h4>Marketing Info</h4>
                <hr>
              </div>
              <div class="col-12 ">
                <div class="form-group">
                    <label for="how_did_hear_about_clinic" class="mb-0">How Did This Patient hear about your clinic ? <span class="text-danger">*</span> </label>
                    <select  id="how_did_hear_about_clinic" name="how_did_hear_about_clinic" class="form-control" required>
                    <option value="">Select Marketing Source</option>
                       <?php $__currentLoopData = $marketing_source; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ms): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                         <option value="<?php echo e($ms->id); ?>" <?php echo e(old('how_did_hear_about_clinic') == $ms->id ? 'selected' : ''); ?>><?php echo e($ms->description); ?></option>

                       <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <h4>Patient Note</h4>
                <hr>
              </div>
              <div class="col-12 ">
                <div class="form-group">
                     <textarea  id="patient_note" name="patient_note"   class="form-control"  ><?php echo e(old('patient_note')); ?></textarea>
                </div>
              </div>
            </div>

            <div class="row mt-4">
              <div class="col12 col-md-12 text-left">
                <button class="btn btn-primary px-4 submit-buttons"  type="submit">
                  <i class="fa fa-check mr-1"></i>
                  Add Patient
                </button>
              </div>
            </div>

          </form>
        </div>
      </div>
    </div>

  </div>

</div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>

<script src="<?php echo e(mix('js/patients.js')); ?>" defer></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\GithubRepository\pryapus\resources\views/patient/create.blade.php ENDPATH**/ ?>