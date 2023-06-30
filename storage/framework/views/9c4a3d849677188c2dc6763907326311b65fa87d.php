<?php $__env->startSection('content'); ?>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6"><?php echo e(translate('Reviews Information')); ?></h5>
            </div>
            <div class="card-body">
                <form id="add_form" class="form-horizontal" action="<?php echo e(route('reviews.adminStore')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="form-group row" id="customer">
                        <label class="col-md-3 col-from-label">
                            <?php echo e(translate('Customer')); ?> 
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-9">
                            <select class="form-control aiz-selectpicker" name="user_id" id="user_id" data-live-search="true" required>
                                <option value="">--</option>
                                <?php $__currentLoopData = $customer; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($val->id); ?>">
                                    <?php echo e($val->name); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row" id="product">
                        <label class="col-md-3 col-from-label">
                            <?php echo e(translate('Product')); ?> 
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-9">
                            <select class="form-control aiz-selectpicker" name="product_id" id="product_id" data-live-search="true" required>
                                <option value="">--</option>
                                <?php $__currentLoopData = $product; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($val->id); ?>">
                                    <?php echo e($val->name); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label"><?php echo e(translate('Rating')); ?>

                            <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <input type="text" maxlength="1" onkeypress="return isNumber(event)" placeholder="<?php echo e(translate('Rating')); ?>" name="rating" id="rating" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">
                            <?php echo e(translate('Comment')); ?>

                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-9">
                            <textarea name="comment" rows="5" class="form-control" placeholder="<?php echo e(translate('Comment')); ?>" required="required"></textarea>
                        </div>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary">
                            <?php echo e(translate('Save')); ?>

                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>
    function isNumber(evt){
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode < 48 || charCode > 53) {
            return false;
        }
        return true; 
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\colebrook-admin\resources\views/backend/product/reviews/create.blade.php ENDPATH**/ ?>