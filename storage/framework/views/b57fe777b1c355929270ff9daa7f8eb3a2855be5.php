

<?php $__env->startSection('content'); ?>

<div role="main" class="main-content">

  <div class="page-content container container-plus ">
    <!-- page header and toolbox -->
    <div class="page-header pb-2">
      <h2 class="page-title text-primary-d2 text-150">
        <a href="#" ><?php echo e($page_name ?? ""); ?></a>
        <small class="page-info text-secondary-d2 text-nowrap">
          <i class="fa fa-angle-double-right text-80"></i>
          <?php echo e($page_info ?? ""); ?>

        </small>
      </h2>
    </div>

    <?php echo $__env->make('includes.alerts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="cards-container mt-3">
      <div class="card border-0 shadow-sm radius-0">


        <div class="card-body px-2  pb-2 border-1 brc-primary-m3 ">
                <div class="d-flex flex-row">    
                  <div class="d-flex ml-auto">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('export', Auth::user())): ?>           
                      <button id="export" class="btn btn-white border-dark shadow-sm text-dark d-block fz-12px">
                          <i class="fas fa-file-excel"></i>
                          Export
                      </button>
                    <?php endif; ?>
                  </div>
                  <div class="d-flex">
                    <button id="daterange" data-start='<?php echo e($_start); ?>' data-end='<?php echo e($_end); ?>' class="btn btn-white border-dark shadow-sm text-dark ml-2 d-block fz-12px">
                        <i class="fas fa-calendar-alt"></i>
                        <span><?php echo e($start); ?> - <?php echo e($end); ?></span>
                    </button>
                  </div>
                </div>
                <div class="col-12 table-responsive mt-3">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="bg-light ">
                                <th>Product Name</th>
                                <th>Orders</th>
                                <th>Average Duration</th>
                                <th>Average Sale</th>
                                <th>Total Sale</th>
                                <th>Total Down Payments</th>
                            </tr>
                        </thead>
                        <tbody class="">

                            <?php $__currentLoopData = $product_totals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <tr id="product_<?php echo e($row->id); ?>" class="bg-light">
                                <td><a role="button"><i class="fa fa-plus-square mr-4 row_icon"></i></a><?php echo e(ucwords(strtolower($row->name))); ?> - TOTAL</td>
                                <td><?php echo e($row->orders ?? '0'); ?></td>
                                <td><?php echo e($row->avg_duration ?? '0'); ?></td>
                                <td>$<?php echo e(number_format($row->avg_sale,2) ?? '0.00'); ?></td>
                                <td>$<?php echo e(number_format($row->total_sales,2) ?? '0.00'); ?></td>
                                <td>$<?php echo e(number_format($row->total_paid,2) ?? '0.00'); ?></td>
                            </tr>
                              <?php $__currentLoopData = $detail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detailRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($detailRow->id === $row->id): ?>
                                  <tr class="product_children_<?php echo e($row->id); ?> d-none">
                                      <td class="pl-4"><?php echo e(ucwords(strtolower($detailRow->name))); ?> 
                                          <?php if($detailRow->reorder == 1): ?> 
                                            - Reorder
                                          <?php elseif($detailRow->new_customer == 1): ?>
                                            - New Customer 
                                          <?php else: ?>
                                            - Other
                                          <?php endif; ?></td>
                                      <td><?php echo e($detailRow->orders ?? '0'); ?></td>
                                      <td><?php echo e($detailRow->avg_duration ?? '0'); ?></td>
                                      <td>$<?php echo e(number_format($detailRow->avg_sale,2) ?? '0.00'); ?></td>
                                      <td>$<?php echo e(number_format($detailRow->total_sales,2) ?? '0.00'); ?></td>
                                      <td>$<?php echo e(number_format($detailRow->total_paid,2) ?? '0.00'); ?></td>
                                  </tr>
                                <?php endif; ?>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php if($totals->isEmpty()): ?>
                              <tr class="bg-light">
                                <th>TOTAL:</th>
                                <td>0</td>
                                <td>0</td>
                                <td>$0.00</td>
                                <td>$0.00</td>
                                <td>$0.00</td>
                              </tr>
                              <?php else: ?>
                                <tr id="product_totals" class="bg-light">
                                  <?php $__currentLoopData = $totals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $total): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <th><a role="button"><i class="fa fa-plus-square mr-4 expand_totals"></i></a>TOTAL:</th>
                                    <td><?php echo e($total->orders ?? '0'); ?></td>
                                    <td><?php echo e($total->avg_duration ?? '0'); ?></td>
                                    <td>$<?php echo e(number_format($total->avg_sale,2) ?? '0.00'); ?></td>
                                    <td>$<?php echo e(number_format($total->total_sales,2) ?? '0.00'); ?></td>
                                    <td>$<?php echo e(number_format($total->total_paid,2) ?? '0.00'); ?></td>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tr>
                                <?php $__currentLoopData = $total_details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $td): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                  <tr class="product_totals_detail d-none bg-light">
                                      <td class="pl-4">
                                          <?php if($td->reorder == 1): ?> 
                                            Reorder
                                          <?php elseif($td->new_customer == 1): ?>
                                            New Customer 
                                          <?php else: ?>
                                            Other
                                          <?php endif; ?></td>
                                      <td><?php echo e($td->orders ?? '0'); ?></td>
                                      <td><?php echo e($td->avg_duration ?? '0'); ?></td>
                                      <td>$<?php echo e(number_format($td->avg_sale,2) ?? '0.00'); ?></td>
                                      <td>$<?php echo e(number_format($td->total_sales,2) ?? '0.00'); ?></td>
                                      <td>$<?php echo e(number_format($td->total_paid,2) ?? '0.00'); ?></td>
                                  </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                              <?php endif; ?>
                          
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
      </div>
    </div>

  </div>

</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(mix('js/report.sales-by-product.js')); ?>" defer></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\GithubRepository\pryapus\resources\views/report/sales-by-product/index.blade.php ENDPATH**/ ?>