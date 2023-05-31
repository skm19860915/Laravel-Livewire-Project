          <form class="mt-lg-3 forms" method="POST" action="<?php echo e(url('/edit/patient/'.$patient->id)); ?>">
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
                  <input type="text" id="first_name" value="<?php echo e($patient->first_name); ?>" name="first_name" class="form-control" required />
                </div>
              </div>
              <div class="col-6 ">
                <div class="form-group">
                  <label for="last_name" class="mb-0">Last Name</label>
                  <span class="text-danger">*</span>
                  <input type="text" id="last_name" value="<?php echo e($patient->last_name); ?>"  name="last_name" class="form-control" required />
                </div>
              </div>
              <div class="col-6 ">
                <div class="form-group">
                  <label for="home_phone" class="mb-0">Home Phone</label>
                  
                  <input type="text" id="home_phone" value="<?php echo e($patient->home_phone); ?>"  name="home_phone" class="form-control phones"   />
                </div>
              </div>
              <div class="col-6 ">
                <div class="form-group">
                  <label for="cell_phone" class="mb-0">Cell Phone</label>
                  
                  <input type="text" id="cell_phone" value="<?php echo e($patient->cell_phone); ?>"  name="cell_phone" class="form-control phones"   />
                </div>
              </div>
              <div class="col-6 ">
                <div class="form-group">
                  <label for="email" class="mb-0">Email</label>
                  
                  <input type="email" id="email" value="<?php echo e($patient->email); ?>"  name="email" class="form-control"   />
                </div>
              </div>
              <div class="col-6 ">
                <div class="form-group">
                  <label for="date_of_birth" class="mb-0">Date of Birth</label>
                  
                  <input type="datepicker" id="date_of_birth" value="<?php echo e($patient->date_of_birth); ?>"  name="date_of_birth" class="form-control dates"   />
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
                  
                  <input type="text" id="address" value="<?php echo e($patient->address); ?>"  name="address" class="form-control"   />
                </div>
              </div>
              <div class="col-6 ">
                <div class="form-group">
                  <label for="city" class="mb-0">City</label>
                  
                  <input type="text" id="city" value="<?php echo e($patient->city); ?>"  name="city" class="form-control"   />
                </div>
              </div>
              <div class="col-6 ">
                <div class="form-group">
                  <label for="state" class="mb-0">State</label>
                  <select type="text" id="state" name="state" data-value="<?php echo e($patient->state); ?>" class="form-control">
                  </select>
                </div>
              </div>
              <div class="col-6 ">
                <div class="form-group">
                  <label for="zip" class="mb-0">Zip</label>
                  
                  <input type="text" id="zip" value="<?php echo e($patient->zip); ?>"  name="zip" class="form-control zips"   />
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
                    <input type="checkbox" value="1"  <?php echo e($patient->high_blood_pressure ? "checked" :""); ?>   id="high_blood_pressure" name="high_blood_pressure" class="form-control"   />
                    <label for="high_blood_pressure" class="mb-0">High Blood Pressure</label>
                </div>
              </div>
              <div class="col-3 ">
                <div class="form-group d-flex">
                    <input type="checkbox" value="1" <?php echo e($patient->high_cholesterol ? "checked" :""); ?>  id="high_cholesterol" name="high_cholesterol" class="form-control"   />
                    <label for="high_cholesterol" class="mb-0">High Cholesterol</label>
                </div>
              </div>
              <div class="col-3 ">
                <div class="form-group d-flex">
                    <input type="checkbox" value="1" <?php echo e($patient->diabetes ? "checked" :""); ?>  id="diabetes" name="diabetes" class="form-control"   />
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

                         <option value="<?php echo e($ms->id); ?>" <?php echo e($ms->id == $patient->how_did_hear_about_clinic ? "selected" : ""); ?>><?php echo e($ms->description); ?></option>

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
                     <textarea  id="patient_note" name="patient_note" class="form-control"  ><?php echo e($patient->patient_note); ?></textarea>
                </div>
              </div>
            </div>

            <div class="row mt-4">
              <div class="col12 col-md-12 text-left">
                <button class="btn btn-primary px-4 submit-buttons"  type="submit">
                  <i class="fa fa-check mr-1"></i>
                  Save Changes
                </button>
              </div>
            </div>

          </form>
<?php /**PATH D:\GithubRepository\pryapus\resources\views/patient/_edit_form.blade.php ENDPATH**/ ?>