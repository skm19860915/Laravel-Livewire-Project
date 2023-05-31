

<?php $__env->startSection('content'); ?>

<!-- Main content -->
<section class="w-100 p-2   no-print">
    <div class="page-header pb-2">
        <h2 class="page-title text-primary-d2 text-150">
          <a href="<?php echo e(route('schedule.index')); ?>">Schedule</a>
          
        </h2>
      </div>
    <?php echo $__env->make('includes.alerts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="row">
          <div class="col-12">
            <div class="card card-primary ">
                <div class="card-body p-2">
                  <a href="<?php echo e(route('appointment.create')); ?>" class="btn btn-success my-1"><i class="fas fa-plus"></i> Add New Appointment</a>
                  <a href="<?php echo e(route('block.create')); ?>" class="btn appt-block my-1 text-white"><i class="fas fa-times"></i> Block Calendar</a>
                  <a   class="btn btn-success my-1" onclick="window.print()"><i class="fas fa-print"></i> Print Daily Appointment</a>
                <!-- THE CALENDAR -->
                <div id="calendar"  ></div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
          <div class="col-12">
                <div class="card border-0 shadow-sm radius-0 my-2 w-100 ">
                    <div class="card-header bgc-primary-d1">
                        <h5 class="card-title text-white">
                            <i class="fa fa-key mr-2px"></i>
                            Color Key
                        </h5>
                    </div>
                    <div class="card-body px-2 py-2 pb-2 border-1 brc-primary-m3 border-t-0 d-flex flex-wrap alig-items-center">

                        <button class="btn appt-success text-white rounded-0 my-1 mx-1">Confirmed/Ticket</button>
                        <button class="btn appt-confirm-no-show text-white rounded-0 my-1 mx-1">Confirmed No Show</button>
                        <button class="btn appt-cancelled text-white rounded-0 my-1 mx-1">Cancelled</button>
                        <button class="btn appt-reschedule text-white rounded-0 my-1 mx-1">Rescheduled</button>
                        <button class="btn appt-voicemail text-white bg-purble rounded-0 my-1 mx-1">Voicemail</button>
                        <button class="btn appt-no-sales text-white rounded-0 my-1 mx-1">No Sale/Rescheduled during office/Marked for Revisit</button>
                        <span class="my-2"><i class="fas fa-star-of-life"></i> - New Customer(1st visit)</span>
                        <span class="my-2  mx-1"><i class="fas fa-plus-square"></i> - Procedure</span>
                        <span class="my-2  mx-1"><i class="far fa-circle"></i> - Other</span>
                    </div>
                </div>
          </div>



        </div>
        <!-- /.row -->
    </section>
    
<section>
    <table class="table table-bordered  show-table-in-print  ">
        <thead class="text-center">
            <tr>
                <th colspan="6">Appointments  <?php echo e(now()->format('m/d/Y')); ?></th>
            </tr>
            <tr>
                <th>#</th>
                <th>Patient Name</th>
                <th>Home Phone</th>
                <th>Cell Phone</th>
                <th>Appointment Time</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $appointmentsToday; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($index+1); ?></td>
                <td><?php echo e($a->last_name.',  '.$a->first_name); ?></td>
                <td><?php echo e($a->home_phone); ?></td>
                <td><?php echo e($a->cell_phone); ?></td>
                <td><?php echo e($a->time); ?></td>
                <td><?php echo e($a->scheduleType->description); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

<script src="<?php echo e(mix('js/schedule.js')); ?>" defer></script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\GithubRepository\pryapus\resources\views/schedule/index.blade.php ENDPATH**/ ?>