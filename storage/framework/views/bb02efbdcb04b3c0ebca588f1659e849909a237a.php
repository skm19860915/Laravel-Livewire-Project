

<?php $__env->startSection('content'); ?>
    <!-- Main content -->
    <section class="w-100 p-2   no-print">
        <?php echo $__env->make('includes.alerts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="page-header pb-2">
            <h2 class="page-title text-primary-d2 text-150">
                <a href="<?php echo e(route('ticket.index')); ?>"><?php echo e($page_title ?? ''); ?></a>
                <small class="page-info text-secondary-d2 text-nowrap">
                    <i class="fa fa-angle-double-right text-80"></i>
                    <?php echo e($page_info ?? ''); ?>

                </small>
            </h2>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-primary ">
                    <div class="card-body p-2">
                        <a href="<?php echo e(route('appointment.new.create', ['appointment' => $appointment->id])); ?>" class="btn btn-success my-1 d-none" id="reschedule_cancellation_btn">Reschedule / Cancellation</a>
                        <a href="<?php echo e(route('appointment.edit', ['appointment' => $appointment->id])); ?>" class="btn btn-success my-1">Convert Back to Appointment <i class="fas fa-sign-out-alt"></i></a>
                        
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <div class="col-12 mt-2">
                <div class="card border-0 shadow-sm radius-0 my-2">
                    <div class="card-header bgc-primary-d1">
                        <h5 class="card-title text-white ">
                            <i class="fas fa-file"></i>
                            <?php echo e($card_title ?? ''); ?>

                        </h5>
                    </div>
                    <div class="card-body px-2 py-2 pb-2 border-1 brc-primary-m3 border-t-0">

                        <form id="ticket_form" action="" data-action="<?php echo e(route('ticket.store', ['appointment' => $appointment->id])); ?>" class="forms" enctype="multipart/form-data" method="post">
                            <?php echo csrf_field(); ?>
                            <div class="d-flex flex-column justify-content-start align-items-center">

                                <div class="col-12 d-flex align-items-end mt-2">
                                    <h4 class="m-0 mr-2">Purchase Details</h4>

                                </div>
                                <div class="col-12">
                                    <hr>
                                </div>

                                <div class="col-12">
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Date</label>
                                        <div class="col-sm-6">
                                            <input type="datepicker" id="ticket_date" name="date" value="<?php echo e(now()->format('m/d/Y')); ?>" class="form-control w-200px dates">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Sales Counselor</label>
                                        <div class="col-sm-6">
                                            <select name="sales_counselor" class="form-control">
                                                <option value="">----</option>
                                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($u->id); ?>"><?php echo e($u->name()); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                    <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label"><?php echo e($s->name); ?></label>
                                            <div class="col-sm-6 d-flex align-items-center">
                                                <input type="checkbox" class="services" id="service_<?php echo e($s->id); ?>" name="services" value="<?php echo e($s->id); ?>" data-deleteabel="<?php echo e($s->deleteable); ?>" data-price="<?php echo e($s->price); ?>"
                                                    data-receivable="<?php echo e($s->receivable); ?>" class="form-control">
                                                <?php if($s->receivable): ?>
                                                    <input type="text" placeholder="$<?php echo e(number_format($s->price, 2)); ?>" value="$<?php echo e(number_format($s->price, 2)); ?>" id="service_<?php echo e($s->id); ?>_price" class="w-100px text-center services_price"
                                                        disabled>
                                                <?php endif; ?>
                                                <span class="text-muted font-italic"><?php echo e(trim($s->note) ? "($s->note)" : ''); ?></span>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Select Package(s) Sold</label>
                                        <div class="col-sm-9 ">

                                            <table class="table table-bordered table-hover  w-100">
                                                <thead>
                                                    <tr>
                                                        <th>Product</th>
                                                        <th>Duration in Months (or doses for ESWT)</th>
                                                        <th>Price</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="products-rows" data-edit="false" data-products='<?php echo e(json_encode($products)); ?>' data-store='<?php echo e(route('product.store')); ?>' data-token="<?php echo e(csrf_token()); ?>">
                                                    <tr class="bg-light">
                                                        <td colspan="5"><small>No Sold Items dectected, Press the 'Add' button below to get started</small></td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                            <button type="button" class="d-block btn btn-success ml-auto" id="addNewFormRowForProductsTable"><i class="fas fa-plus"></i> Add</button>
                                        </div>
                                    </div>

                                    <div class="form-group row " id="recommended_treatment">
                                        <label class="col-sm-2 col-form-label mt-0">Recommended Treatment</label>
                                        <div class="col-sm-10">

                                            <?php $__currentLoopData = $treatment_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="form-group mr-4 mb-0">
                                                    <input type="radio" id="treatment_type-<?php echo e($tt->id); ?>" name="treatment_type" value="<?php echo e($tt->id); ?>" class="form-control d-inline-block" <?php echo e($tt->description === 'None' ? 'checked' : ''); ?>>
                                                    <label for="treatment_type-<?php echo e($tt->id); ?>"><?php echo e($tt->description); ?></label>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>

                                    <div class="form-group row" id="treatment_end_date_row">
                                        <label class="col-sm-2 col-form-label mt-0">Treatment End Date</label>
                                        <div class="col-sm-6">
                                            <input type="datepicker" id="treatment_end_date" autocomplete="off" value="<?php echo e(now()->format('m/d/Y')); ?>" name="treatment_end_date" class="dates form-control w-300px" />
                                            <span class="text-muted font-italic"><small>Defaults to the longest package purchased, or 3 months if only ESWT package purchased</small></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 d-flex align-items-end mt-2">
                                    <h4 class="m-0 mr-2">Payment Info</h4>
                                </div>
                                <div class="col-12">
                                    <hr>
                                </div>

                                <div class="col-12">
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Total</label>
                                        <div class="col-sm-6">
                                            <input name="total" id="total" type="text" value="0.00" placeholder="0.00" readonly class="form-control w-200px currency">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Amount Paid During office visit</label>
                                        <div class="col-sm-6">
                                            <input type="text" id="apdov" autocomplete="off" value="0.00" name="amount_paid_during_office_visit" value="" class="form-control w-200px currency">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Balance during visit</label>
                                        <div class="col-sm-6">
                                            <input type="text" value="0.00" id="bdv" name="balanc_during_visit" readonly value="" placeholder="0.00" class="form-control w-200px currency" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Pay Increments</label>
                                        <div class="col-sm-6">
                                            <select name="payment_increments" id="payment_increments" class="form-control w-300px">

                                            </select>
                                            <small class="text-muted">Increments do not include today's visit as payment date. (i.e The month plan includes both today's payment and the following month's payment)</small>
                                        </div>
                                    </div>
                                    <div class="form-group row d-none firstPaymentDue">
                                        <label class="col-sm-2 col-form-label">First Payment Due</label>
                                        <div class="col-sm-6">
                                            <input type="datepicker" id="firstPaymentDueDate" value="<?php echo e(now()->addMonth()->format('m/d/Y')); ?>" name="first_payment_due" class="dates form-control w-300px" />
                                        </div>
                                    </div>




                                </div>

                                <div class="col-12 mt-2">
                                    <button type="submit" class="btn btn-primary submit-buttons">Save Changes</button>
                                </div>

                        </form>

                        <div class="col-6 align-self-start mt-4" id="form_errors">
                        </div>

                    </div>
                </div>
                <!-- /.card -->
            </div>
            <div class="col-12 mt-2 p-0">
                <div class="alert alert-info" style="border-left: 8px solid #2470bd;">
                    <b>Patient Name:</b> <span><?php echo e($appointment->patient->first_name); ?> <?php echo e($appointment->patient->last_name); ?></span><br>
                    <b>Patient Notes:</b> <span><?php echo e($appointment->patient->patient_note); ?></span><br>
                    <b>Appointment Notes:</b> <span><?php echo e($appointment->note); ?></span><br>
                    <b>Appointment Was Created:</b> <span><?php echo e($appointment->getDate($appointment, 'created_at')); ?></span><br>
                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(mix('js/tickets.js')); ?>" defer></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\GithubRepository\pryapus\resources\views/tickets/create.blade.php ENDPATH**/ ?>