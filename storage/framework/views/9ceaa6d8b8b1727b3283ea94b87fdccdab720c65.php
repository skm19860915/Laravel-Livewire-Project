<?php $__env->startSection('content'); ?>

    <!-- Main content -->
    <section class="w-100 p-2   no-print">

        <?php echo $__env->make('includes.alerts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="page-header pb-2" id="page_header" data-ticket="<?php echo e(json_encode($ticket)); ?>">
            <h2 class="page-title text-primary-d2 text-150">
                <a href="<?php echo e(route('ticket.index')); ?>"><?php echo e($page_title); ?></a>
                <small class="page-info text-secondary-d2 text-nowrap">
                    <i class="fa fa-angle-double-right text-80"></i>
                    <?php echo e($card_title); ?>

                </small>
            </h2>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-body p-2">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('converBackToAppt', auth()->user())): ?>
                            <a href="<?php echo e(route('ticket.delete', ['ticket' => $ticket->id])); ?>" class="btn btn-success my-1">Convert Back to Appointment <i class="fas fa-sign-out-alt"></i></a>
                        <?php endif; ?>
                        <a href="<?php echo e(route('ticket.view', ['ticket' => $ticket->id])); ?>" class="btn btn-success my-1">View Invoice Format <i class="fas fa-external-link-alt"></i></a>
                        <a class="btn btn-success apply-payments my-1 <?php echo e(!(float) $ticket->payment_increments && !(float) $ticket->month_plan ? 'disabled' : ''); ?>" href="<?php echo e(route('payment.create', ['ticket' => $ticket->id])); ?>">Apply Payment
                            <i class="fas fa-dollar-sign"></i></a>
                        <a class="btn btn-success payments-history my-1 <?php echo e(!(float) $ticket->payment_increments && !(float) $ticket->month_plan ? 'disabled' : ''); ?>" href="<?php echo e(route('payment.history', ['ticket' => $ticket->id])); ?>">Payments
                            History <i class="fas fa-bars"></i></a>
                        <?php if(!$ticket->revisit): ?>
                            <?php if(!$showRevisitButton): ?>
                                <a href="<?php echo e(route('ticket.revisit', ['ticket' => $ticket->id])); ?>" class="btn btn-success my-1 mark-for-revisit-btn ">Mark for Revisit <i class="fas fa-exclamation-circle"></i></a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php if($ticket->revisit): ?>
                <div class="col-12  mt-2">
                    <div class="alert alert-info" style="border-left: 8px solid #2470bd;">
                        NOTE: This ticket has been marked for a
                        'Revisit'. The purpose of a 'Revisit' is
                        to omit a customer's initial 'No Sale' or
                        'ACE' visit from reporting in the event they
                        change their mind and go through with the purchase
                        at a later date. In order to log the new appointment/ticket,
                        please create a separate appointment from the Schedule
                        screen. You will be unable to make any edits to
                        this ticket while marked as a Revisit.
                        If you need to undo this process,
                        select the 'UNDO REVISIT' button below.
                        <br>
                        <a href="<?php echo e(route('ticket.undoRevisit', ['ticket' => $ticket->id])); ?>" class="btn btn-danger d-block ml-auto w-150px">UNDO REVISIT</a>
                    </div>
                </div>
            <?php endif; ?>
            <div class="col-12 mt-2">
                <div class="card border-0 shadow-sm radius-0 my-2">
                    <div class="card-header bgc-primary-d1">
                        <h5 class="card-title text-white ">
                            <i class="fas fa-file"></i>
                            <?php echo e($card_title); ?>

                        </h5>
                    </div>
                    <div class="card-body px-2 py-2 pb-2 border-1 brc-primary-m3 border-t-0">

                        <form id="ticket_form" action="" data-action="<?php echo e(route('ticket.update', ['ticket' => $ticket->id, 'appointment' => $appointment->id])); ?>" class="forms" enctype="multipart/form-data" method="post">
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
                                            <input type="datepicker" id="ticket_date" name="date" value="<?php echo e($ticket->date); ?>" class="form-control w-200px dates">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Sales Counselor</label>
                                        <div class="col-sm-6">
                                            <select name="sales_counselor" class="form-control">
                                                <option value="">----</option>
                                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if(!isset($u->deleted_at)): ?>
                                                        <option value="<?php echo e($u->id); ?>" <?php echo e($u->id == $ticket->user_id ? 'selected' : ''); ?>><?php echo e($u->name()); ?></option>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                    <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label"><?php echo e($s->name); ?></label>
                                            <div class="col-sm-6 d-flex align-items-center">
                                                <input type="checkbox" class="services" id="service_<?php echo e($s->id); ?>" name="services" value="<?php echo e($s->id); ?>" data-deleteabel="<?php echo e($s->deleteable); ?>"
                                                    <?php echo e(in_array($s->id, $ticket_services_ids) ? 'checked' : ''); ?> data-price="<?php echo e($s->price); ?>" data-receivable="<?php echo e($s->receivable); ?>" class="form-control">
                                                <?php if($s->receivable): ?>
                                                    <input type="text" placeholder="$0.00"
                                                        value="<?php echo e(in_array($s->id, $ticket_services_ids)? number_format($ticket_services->where('service_id', $s->id)->where('ticket_id', $ticket->id)->first()->custom_price,2,'.',''): null); ?>"
                                                        id="service_<?php echo e($s->id); ?>_price" class="w-100px text-center services_price" <?php echo e(in_array($s->id, $ticket_services_ids) ? '' : 'disabled'); ?>>
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
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="products-rows" data-edit="true" data-products='<?php echo e(json_encode($products)); ?>' data-store='<?php echo e(route('product.store')); ?>' data-token="<?php echo e(csrf_token()); ?>">

                                                    <?php $__empty_1 = true; $__currentLoopData = $ticket_products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                        <tr id="product_<?php echo e($p->id); ?>">
                                                            <td>
                                                                <select name="product_<?php echo e($p->id); ?>_select" id="product_<?php echo e($p->id); ?>_select" class="form-control">
                                                                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p2): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <option value="<?php echo e($p2->id); ?>" <?php echo e($p2->id == $p->product_id ? 'selected' : ''); ?>>
                                                                            <?php echo e($p2->name); ?>

                                                                        </option>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </select>
                                                            </td>
                                                            <td class="amount-node">
                                                                <input type="text" value="<?php echo e((int) $p->custom_amount); ?>" name="product_<?php echo e($p->id); ?>_amount" id="product_<?php echo e($p->id); ?>_amount" class="form-control numeric" />
                                                            </td>
                                                            <td class="price-node">
                                                                <input type="text" value=" $<?php echo e(number_format($p->custom_price, 2)); ?>" name="product_<?php echo e($p->id); ?>_price" id="product_<?php echo e($p->id); ?>_price" class="form-control currency" />

                                                            </td>
                                                            <td><a class="text-primary c-p link" id="delete_<?php echo e($p->id); ?>">Delete</a></td>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                        <tr class="bg-light" id="empty_row">
                                                            <td colspan="5"><small>No Sold Items dectected, Press the 'Add' button below to get started</small></td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>

                                            <button type="button" class="d-block btn btn-success ml-auto" id="addNewFormRowForProductsTable"><i class="fas fa-plus"></i> Add</button>
                                        </div>
                                    </div>

                                    <div class="form-group row <?php echo e($ticket_products->count() > 0 ? 'd-none' : ''); ?>" id="recommended_treatment">
                                        <label class="col-sm-2 col-form-label mt-0">Recommended Treatment</label>
                                        <div class="col-sm-10">
                                            <?php $__currentLoopData = $treatment_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="form-group mr-4 mb-0">
                                                    <input type="radio" id="treatment_type-<?php echo e($tt->id); ?>" name="treatment_type" value="<?php echo e($tt->id); ?>" class="form-control d-inline-block"
                                                        <?php echo e($tt->id == $ticket->treatment_type_id || ($tt->description === 'None' && !$ticket->treatment_type_id)? 'checked': ''); ?>>
                                                    <label for="treatment_type-<?php echo e($tt->id); ?>"><?php echo e($tt->description); ?></label>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php $__currentLoopData = $treatment_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if(($tt->description === 'None')): ?>
                                                    <input type="hidden" id="selected_tt_id" value="<?php echo e($tt->id); ?>" />
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>

                                    <div class="form-group row" id="treatment_end_date_row">
                                        <label class="col-sm-2 col-form-label mt-0">Treatment End Date</label>
                                        <div class="col-sm-6">
                                            <input type="datepicker" id="treatment_end_date" value="<?php echo e($ticket->treatment_end_date); ?>" name="treatment_end_date" class="dates form-control w-300px" />
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
                                        <label class="col-sm-2 col-form-label">Total Due</label>
                                        <div class="col-sm-6">
                                            <input name="total" id="total" type="text" value="$<?php echo e(number_format($ticket->total, 2)); ?>" readonly class="form-control w-200px currency">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Amount Paid During office visit</label>
                                        <div class="col-sm-6">
                                            <input id="apdov" type="text" placeholder="$<?php echo e(number_format($ticket->amount_paid_during_office_visit, 2)); ?>" value="$<?php echo e(number_format($ticket->amount_paid_during_office_visit, 2)); ?>" autocomplete="off"
                                                name="amount_paid_during_office_visit" class="form-control w-200px currency">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Balance during visit</label>
                                        <div class="col-sm-6">
                                            <input type="text" id="bdv" placeholder="$<?php echo e(number_format($ticket->balanc_during_visit, 2)); ?>" value="$<?php echo e(number_format($ticket->balanc_during_visit, 2)); ?>" id="bdv" name="balanc_during_visit" readonly
                                                class="form-control w-200px currency" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Pay Increments</label>
                                        <div class="col-sm-6">
                                            <select name="payment_increments" id="payment_increments" class="form-control w-300px" data-update='<?php echo e(json_encode($ticket)); ?>'>
                                                
                                            </select>
                                            <small class="text-muted font-italic">Increments do not include today's visit as payment date. (i.e The month plan includes both today's payment and the following month's payment)</small>
                                        </div>
                                    </div>
                                    <div class="form-group row firstPaymentDue">
                                        <label class="col-sm-2 col-form-label">First Payment Due</label>
                                        <div class="col-sm-6">
                                            <input type="datepicker" id="firstPaymentDueDate" autocomplete="off" value="<?php echo e($ticket->first_payment_due); ?>" name="first_payment_due" class="dates form-control w-300px" />
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

<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\GithubRepository\pryapus\resources\views/tickets/edit.blade.php ENDPATH**/ ?>