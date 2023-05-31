<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Product Type</th>
                <th>Duration in Months (or doses for ESWT)</th>
                <th>Price</th>
                <th>Description</th>
                <th>Note</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="products-rows" data-token="<?php echo e(csrf_token()); ?>" data-product-types='<?php echo e(json_encode($product_types)); ?>'>

            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <form action="" id="form-<?php echo e($p->id); ?>">
                    <tr id="<?php echo e($p->id); ?>" data-delete="<?php echo e(route('product.delete', ['product' => $p->id])); ?>" data-restore="<?php echo e(route('product.restore', ['product' => $p->id])); ?>"
                        data-update="<?php echo e(route('product.update', ['product' => $p->id])); ?>">
                        <td class="editable">
                            <input class="form-control text" value="<?php echo e($p->name); ?>" id="name-<?php echo e($p->id); ?>" name="name-<?php echo e($p->id); ?>" data-original-value="<?php echo e($p->name); ?>" readonly />
                        </td>
                        <td>
                            <select class="product_type_select form-control" id="product_type_select-<?php echo e($p->id); ?>" data-original-value="<?php echo e($p->product_type_id); ?>" disabled>
                                <?php if(!$p->product_type_id): ?>
                                    <option value="0" name="Not Set" value="Not Set">Not Set</option>
                                <?php endif; ?>
                                <?php $__currentLoopData = $product_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option id="product_type_<?php echo e($type->id); ?>" value="<?php echo e($type->id); ?>" name="<?php echo e($type->description); ?>" <?php echo e($type->id === $p->product_type_id ? 'selected' : ''); ?>>
                                        <?php echo e($type->description); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </td>
                        <td class="editable">
                            <input class="form-control numeric" value="<?php echo e($p->amount); ?>" id="amount-<?php echo e($p->id); ?>" name="amount-<?php echo e($p->id); ?>" data-original-value="<?php echo e($p->amount); ?>" readonly />
                        </td>
                        <td class="editable">
                            <input class="form-control currency" value="$<?php echo e(number_format($p->price, 2, '.', '')); ?>" name="price-<?php echo e($p->id); ?>" id="price-<?php echo e($p->id); ?>" data-original-value="<?php echo e($p->price); ?>" readonly />
                        </td>
                        <td class="editable">
                            <input class="form-control text" value="<?php echo e($p->description); ?>" id="description-<?php echo e($p->id); ?>" name="description-<?php echo e($p->id); ?>" data-original-value="<?php echo e($p->description); ?>" readonly />
                        </td>
                        <td class="editable">
                            <input class="form-control text" value="<?php echo e($p->note); ?>" id="note-<?php echo e($p->id); ?>" name="note-<?php echo e($p->id); ?>" data-original-value="<?php echo e($p->note); ?>" readonly />
                        </td>
                        <td id="status-<?php echo e($p->id); ?>">
                            <?php if($p->deleted_at): ?>
                                <span class="badge badge-pill badge-danger mt-2">Disabled</span>
                            <?php else: ?>
                                <span class="badge badge-pill badge-success mt-2">Active</span>
                            <?php endif; ?>
                        </td>
                        <td class="actions">
                            <div class="edit-disable-btns mt-2">
                                <a role="button" id="edit-<?php echo e($p->id); ?>" class="edit" data-toggle="tooltip" data-placement="top" title="Edit Product"><span class="material-icons md-18">edit</span></a>
                                <a role="button" id="<?php echo e($p->deleted_at ? 'restore' : 'disable'); ?>-<?php echo e($p->id); ?>" class="<?php echo e($p->deleted_at ? 'restore' : 'disable'); ?> text-<?php echo e($p->deleted_at ? 'success' : 'danger'); ?>"
                                    data-toggle="tooltip" data-placement="top" title="<?php echo e($p->deleted_at ? 'Restore' : 'Disable'); ?> Product"><span class="material-icons md-18">power_settings_new</span></a>
                            </div>
                            <div class="save-cancel-btns" style="display: none;">
                                <a role="button" id="save-<?php echo e($p->id); ?>" class="save" data-toggle="tooltip" data-placement="top" title="Save Product"><span class="material-icons md-18">save</span></a>
                                <a role="button" id="cancel-<?php echo e($p->id); ?>" class="cancel" data-toggle="tooltip" data-placement="top" title="Cancel"><span class="material-icons md-18">cancel</span></a>
                            </div>
                        </td>
                    </tr>
                </form>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


            <form action="" id="form-new">
                <tr id="new_product" style="display:none;" data-store='<?php echo e(route('product.store')); ?>'>
                    <td class="editable">
                        <input class="form-control text required" value="" id="name" name="name" />
                    </td>
                    <td>
                        <select class="product_type_select form-control" id="product_type_select">
                            <?php $__currentLoopData = $product_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option id="product_type_<?php echo e($type->id); ?>" value="<?php echo e($type->id); ?>" name="<?php echo e($type->description); ?>" <?php echo e($loop->index == 1 ? 'selected' : ''); ?>>
                                    <?php echo e($type->description); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </td>
                    <td class="editable">
                        <input class="form-control numeric required" value="" id="amount" name="amount" />
                    </td>
                    <td class="editable">
                        <input class="form-control currency required" value="" name="price" id="price" />
                    </td>
                    <td class="editable">
                        <input class="form-control text" value="" id="description" name="description" />
                    </td>
                    <td class="editable">
                        <input class="form-control text" value="" id="note" name="note" />
                    </td>
                    <td>
                    </td>
                    <td class="actions">
                        <div class="save-cancel-btns mt-2">
                            <a role="button" id="save-new" class="save" data-toggle="tooltip" data-placement="top" title="Save Product"><span class="material-icons md-18">save</span></a>
                            <a role="button" id="cancel-new" class="cancel" data-toggle="tooltip" data-placement="top" title="Cancel"><span class="material-icons md-18">cancel</span></a>
                        </div>
                    </td>
                </tr>
            </form>

        </tbody>
    </table>
</div>

<button class="d-block btn btn-success ml-auto" id="addProduct"><i class="fas fa-plus"></i>
    Add</button>
<?php /**PATH D:\GithubRepository\pryapus\resources\views/pricing/products.blade.php ENDPATH**/ ?>