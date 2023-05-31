

<?php $__env->startSection('content'); ?>

<div role="main" class="main-content">

  <div class="page-content container container-plus">
    <!-- page header and toolbox -->
    <div class="page-header pb-2">
      <h2 class="page-title text-primary-d2 text-150">
        <a href="<?php echo e(route('schedule.index')); ?>" ><?php echo e($page_name ?? ""); ?></a>
        <small class="page-info text-secondary-d2 text-nowrap">
          <i class="fa fa-angle-double-right text-80"></i>
          <?php echo e($page_info ?? ""); ?>

        </small>
      </h2>
    </div>

    <?php echo $__env->make('includes.alerts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="cards-container mt-3">


                <a href="<?php echo e(route('patient.edit',['patient'=>$appointment->patient_id])); ?>" class="btn btn-success rounded-0">
                    Edit Patient Info <i class="fas fa-edit"></i>
                </a>
                <div class="card border-0 shadow-sm radius-0 my-2">
                        <div class="card-header bgc-primary-d1">
                            <h5 class="card-title text-white ">
                                Appointment Details
                            </h5>
                        </div>
                        <div class="card-body px-2 py-2 pb-2 border-1 brc-primary-m3 border-t-0">
                            <?php echo $__env->make('schedule.appointment_alerts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <div class="row">
                                <div class="col-12 mt-2">
                                    <h4>Personal info</h4>
                                    <hr>
                                </div>
                                <div class="col-12 col-md-6 col-lg-6 ">
                                    <div class="form-group">
                                    <label for="first_name" class="mb-0">First Name</label>
                                    <span class="text-danger">*</span>
                                    <input type="text" id="first_name" value="<?php echo e($patient->first_name); ?>" name="first_name" class="form-control" disabled   />
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-6 ">
                                    <div class="form-group">
                                    <label for="last_name" class="mb-0">Last Name</label>
                                    <span class="text-danger">*</span>
                                    <input type="text" id="last_name" value="<?php echo e($patient->last_name); ?>" name="last_name" class="form-control " disabled   />
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-6 ">
                                    <div class="form-group">
                                    <label for="home_phone" class="mb-0">Home Phone</label>
                                    
                                    <input type="text" id="home_phone" value="<?php echo e($patient->home_phone); ?>" name="home_phone" class="form-control phones " disabled   />
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-6 ">
                                    <div class="form-group">
                                    <label for="cell_phone" class="mb-0">Cell Phone</label>
                                    
                                    <input type="text" id="cell_phone" value="<?php echo e($patient->cell_phone); ?>" name="cell_phone" class="form-control phones " disabled   />
                                    </div>
                                </div>
                                   </div>
                                <form class="forms w-100" action="<?php echo e(route('appointment.update',['appointment' => $appointment->id])); ?>" method="post">
                                    <?php echo csrf_field(); ?>
                                <div class="row">
                                    <div class="col-12 mt-2">
                                        <h4>Appointment Date & Time</h4>
                                        <hr>
                                    </div>
                                    <div class="col-12 col-md-4">
                                            <div class="form-group d-flex align-items-center ">
                                                <label for="date_appointment" class="mb-0" style="width:70px">Date <span class="text-danger">*</span></label>
                                                <input type="datepicker" autocomplete="0" id="date_appointment" value="<?php echo e($appointment->date); ?>" name="date_appointment" class="form-control dates "   />
                                            </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                            <div class="form-group d-flex align-items-center">
                                                <label for="time_appointment" class="mb-0" style="width:70px">Time <span class="text-danger">*</span></label>
                                                <input type="text" value="<?php echo e($appointment->time); ?>" autocomplete="0" id="time_appointment" name="time_appointment" class="form-control times"   />
                                                <span class="input-clock"><i class="far fa-clock"></i></span>
                                            </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                            <div class="form-group d-flex align-items-center">
                                            <label for="appointment_type"   class="mb-0" >Appointment Type <span class="text-danger">*</span></label>
                                            <select name="appointment_type" class="form-control" id="appointment_type" required>
                                                <?php $__currentLoopData = $appointment_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($type); ?>" <?php echo e($type == $appointment->appointment_type ? 'selected' :''); ?>><?php echo e($type); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            </div>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-12 col-md-3 col-lg-3">
                                        <span>Reorder<span class="text-danger">*</span></label></span>
                                        <input type="radio" name="reorder" value="1" id="yes" class="form-control d-inline-block ml-2" <?php echo e($appointment->reorder ? 'checked' : ''); ?>>
                                        <label for="new">Yes</label>
                                        <input type="radio"  name="reorder" value="0" id="no"  class="form-control d-inline-block ml-2" <?php echo e($appointment->reorder === 0 ? 'checked' : ''); ?>>
                                        <label for="no">No</label>
                                        <i id="reorderTooltip" class="fa fa-question-circle c-p ml-2" title="A reorder won't have an appointment confirmation sent to the patient (only applies if Zingle is enabled)."></i>
                                    </div>
                                    <div class="col-12 col-md-3 col-lg-3">
                                        <label for="prevent_zingle_confirmation">Prevent SMS Confirmation</label>
                                        <input type="checkbox" name="prevent_zingle_confirmation" value="1" id="yes" class="form-control d-inline-block ml-2" <?php echo e($appointment->prevent_zingle_confirmation === 1 ? 'checked' : ''); ?>>
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
                                            <textarea  id="appointment_note" name="appointment_note" class="form-control"  ><?php echo e($appointment->note); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="alert alert-info" style="border-left: 8px solid #2470bd;">
                                        Please Choose from option below. Please note that any changes made above will be recorded unless the appointment is deleted. Also, after the appointment has been confirmed, you will have the option to Check-in the patient as they arrive at your clinic,
                                    </div>
                                </div>
                                <div class="col-12">
                                <div class="px-2 py-2 pb-2   d-flex flex-wrap alig-items-center">
                                    <button type="submit" name="action"  value="update" class="btn btn-primary rounded-0 my-1 mx-1"><i class="fas fa-check text-white"></i> Save Changes</button>

                                    <?php if(!$cancelled): ?>
                                        <?php if(!$confirm): ?>
                                            <button type="submit" name="action" value="confirm" class="btn btn-success rounded-0 my-1 mx-1"><i class="fas fa-thumbs-up"></i> Confirm</button>
                                            <?php else: ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('patinetCheckIn', auth()->user())): ?>
                                                <?php if($hidden): ?>
                                                    <a
                                                        href='<?php echo e(route( "ticket.create" ,
                                                                            ["appointment"=>$appointment->id]
                                                                    )); ?>'
                                                        type="button"
                                                        name="action"
                                                        value="patinet-check-in"
                                                        class="btn btn-primary rounded-0 my-1 mx-1"
                                                    >
                                                        <i class="fas fa-thumbs-up"></i>
                                                        Patient Check-In
                                                    </a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <button type="submit" name="action" value="reschedule" class="btn btn-warning rounded-0 my-1 mx-1"><i class="fas fa-calendar-alt text-white"></i> Reschedule</button>
                                    <?php if(!$cancelled): ?>
                                        <button type="submit" name="action" value="cancel" class="btn btn-danger rounded-0 my-1 mx-1"><i class="fas fa-thumbs-down"></i> Cancel</button>
                                        <?php if(!$voicemail): ?>
                                            <button type="submit" name="action" value="voicemail" class="btn btn-primary bg-purble rounded-0 my-1 mx-1"><i class="fas fa-comment"></i> Voicemail</button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <button type="button" name="action" id="deleteAppointment"  value="delete" class="btn btn-dark rounded-0 my-1 mx-1"><i class="fas fa-times text-white"></i> Delete</button>

                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
            </form>
    </div>

  </div>

</div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>

<script src="<?php echo e(mix('js/schedule.js')); ?>" defer></script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\GithubRepository\pryapus\resources\views/schedule/edit.blade.php ENDPATH**/ ?>