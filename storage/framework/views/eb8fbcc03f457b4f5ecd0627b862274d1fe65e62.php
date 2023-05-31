<div class="table-responsive">
  <table class="table table-bordered table-hover">
      <thead>
          <tr>
              <th>Service Name</th>
              <th>Price</th>
              <th>Receivable</th>
              <th>Description</th>
              <th>Note</th>
              <th></th>
              <th></th>
          </tr>
      </thead>
      <tbody id="services-rows" data-store='<?php echo e(route('service.store')); ?>' data-token="<?php echo e(csrf_token()); ?>">
          <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <tr
              data-id="<?php echo e($s->id); ?>"
              data-delete="<?php echo e(route('service.enable',[ 'service' => $s->id ])); ?>"
              data-update="<?php echo e(route('service.update',[ 'service' => $s->id ])); ?>"
              data-object="<?php echo e(json_encode($s)); ?>"
          >
              <td><?php echo e($s->name); ?></td>
              <td>$<?php echo e(number_format($s->price,2,'.','')); ?></td>
              <td><?php echo e($s->receivable ? "Yes" : "No"); ?></td>
              <td><?php echo e($s->description); ?></td>
              <td><?php echo e($s->note); ?></td>
              <td><a href=""></a></td>
              <td><a href=""></a></td>
          </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </tbody>
  </table>
</div>
<button class="d-block btn btn-success ml-auto" id="addNewFormRowForServicesTable"><i class="fas fa-plus"></i> Add</button>
<?php /**PATH D:\GithubRepository\pryapus\resources\views/pricing/services.blade.php ENDPATH**/ ?>