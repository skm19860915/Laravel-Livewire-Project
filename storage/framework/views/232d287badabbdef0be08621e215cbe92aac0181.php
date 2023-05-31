

<?php $__env->startSection('content'); ?>

<div role="main" class="main-content">
  <div class="page-content container container-plus">
    <!-- page header and toolbox -->
    <div class="page-header pb-2">
      <h2 class="page-title text-primary-d2 text-150">
        <a href="<?php echo e(url('/schedule')); ?>" ><?php echo e($page_name ?? ""); ?></a>
        <small class="page-info text-secondary-d2 text-nowrap">
          <i class="fa fa-angle-double-right text-80"></i>
          <?php echo e($page_info ?? ""); ?>

        </small>
      </h2>
    </div>

    <?php echo $__env->make('includes.alerts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="cards-container mt-3">
    <form action="<?php echo e(url('create/appointment')); ?>" method="post" class="forms">
        <?php echo csrf_field(); ?>
      <div class="card border-0 shadow-sm radius-0 my-2">
        <div class="card-header bgc-primary-d1">
          <h5 class="card-title text-white">
            
             Current or New Patient ?
          </h5>
        </div>

        <div class="card-body px-2 py-2 pb-2 border-1 brc-primary-m3 border-t-0 d-flex">
            <input type="radio" checked  name="currentOrnew" value="new" id="new" class="form-control ">
            <label for="new">New Patient </label>

            <input type="radio"  name="currentOrnew" value="current" id="current"  class="form-control ml-2">
            <label for="current">Current Patient</label>
        </div>
      </div>
      <div class="card border-0 shadow-sm radius-0 my-2" id="new-patient">
        <div class="card-header bgc-primary-d1">
          <h5 class="card-title text-white">
            
             New Patient Info
          </h5>
        </div>

        <div class="card-body px-2 py-0 pb-2 border-1 brc-primary-m3 border-t-0">

                <?php echo $__env->make('schedule._create_patient_form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        </div>
      </div>
      <div class="card border-0 shadow-sm radius-0 my-2 d-none" id="current-patient">
        <div class="card-header bgc-primary-d1">
          <h5 class="card-title text-white">
            
             Find Customer
          </h5>
        </div>

        <div class="card-body px-2 py-2 pb-2 border-1 brc-primary-m3 border-t-0">
                <div class="row">
                    <div class="col-12 col-md-4 col-lg-4">

                        <label for="current_patient" class="mb-0">Start Typing  in the patient's name <span class="text-danger">*</span></label>

                    </div>
                    <div class="col-12 col-md-5 col-lg-5">
                        <select type="text" id="current_patient" name="current_patient" class="form-control " style="width:400px"  >
                            <option value=""></option>
                                
                        </select>

                    </div>
                </div>


        </div>
      </div>
      <div class="card border-0 shadow-sm radius-0 my-2">
        <div class="card-header bgc-primary-d1">
          <h5 class="card-title text-white ">
            
             Appointment Date & Time
          </h5>
        </div>

        <div class="card-body px-2 py-2 pb-2 border-1 brc-primary-m3 border-t-0">
            <div class="row">
                <div class="col-12 col-md-3 col-lg-3">
                    <div class="form-group d-flex align-items-center ">
                        <label for="date_appointment" class="mb-0" style="width:60px">Date <span class="text-danger">*</span></label>
                        <input type="datepicker" autocomplete="0" id="date_appointment" name="date_appointment" value="<?php echo e(old('date_appointment')); ?>" class="form-control dates"  required />
                    </div>
                </div>
                <div class="col-12 col-md-3 col-lg-3">
                    <div class="form-group d-flex align-items-center">
                        <label for="time_appointment" class="mb-0" style="width:60px">Time <span class="text-danger">*</span></label>
                        <input type="text" autocomplete="0" id="time_appointment" name="time_appointment" value="<?php echo e(old('time_appointment')); ?>" class="form-control times"   required/>
                    </div>
                </div>
                <div class="col-12 col-md-3 col-lg-3">
                    <div class="form-group d-flex align-items-center">
                        <label for="appointment_type" class="mb-0" >Appointment Type <span class="text-danger">*</span></label>
                        <select name="appointment_type" class="form-control" id="appointment_type" required>
                            <?php $__currentLoopData = $appointment_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($type); ?>"><?php echo e($type); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
              </div>
                <div class="row mt-4">
                    <div class="col-12 col-md-3 col-lg-3">
                        <span>Reorder<span class="text-danger">*</span></label></span>
                        <input type="radio" name="reorder" value="1" id="yes" class="form-control d-inline-block ml-2">
                        <label for="new">Yes</label>
                        <input type="radio"  name="reorder" value="0" id="no"  class="form-control d-inline-block ml-2" checked="checked">
                        <label for="no">No</label>
                        <i id="reorderTooltip" class="fa fa-question-circle c-p ml-2" title="A reorder won't have an appointment confirmation sent to the patient (only applies if Zingle is enabled)."></i>
                    </div>
                    <div class="col-12 col-md-3 col-lg-3">
                        <label for="prevent_zingle_confirmation">Prevent SMS Confirmation</label>
                        <input type="checkbox" name="prevent_zingle_confirmation" value="1" id="yes" class="form-control d-inline-block ml-2">
                        <i id="preventSMSConfirmationTooltip" class="fa fa-question-circle c-p ml-2" title="Check this to prevent Zingle from sending an SMS confirmation to the patient (only applies if Zingle is enabled)."></i>  
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <h4>Appointment Note</h4>
                        <hr>
                    </div>
                    <div class="col-12 ">
                        <div class="form-group">
                            <textarea  id="appointment_note"   name="appointment_note" class="form-control"  ><?php echo e(old('appointment_note')); ?></textarea>
                            <span class="error" ></span>
                        </div>
                    </div>
                  </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary submit-buttons"><i class="fas fa-check"></i> Create Appointment </button>

      </div>
        </form>
    </div>

  </div>

</div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>

 <script src="<?php echo e(mix('js/schedule.js')); ?>" defer></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\GithubRepository\pryapus\resources\views/schedule/create.blade.php ENDPATH**/ ?>