<?php $__env->startSection('content'); ?>

    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6"><?php echo e(translate('Coupon Information Adding')); ?></h5>
            </div>
            <div class="card-body">
              <form class="form-horizontal" action="<?php echo e(route('coupon.store')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="form-group row">
                    <label class="col-lg-3 col-from-label" for="name"><?php echo e(translate('Coupon Type')); ?></label>
                    <div class="col-lg-9">
                        <select name="coupon_type" id="coupon_type" class="form-control aiz-selectpicker" onchange="coupon_form()" required>
                            <option value=""><?php echo e(translate('Select One')); ?></option>
                            <option value="product_base"><?php echo e(translate('For Products')); ?></option>
                            <option value="cart_base"><?php echo e(translate('For Total Orders')); ?></option>
                        </select>
                    </div>
                </div>

                <div id="coupon_form">

                </div>
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-primary"><?php echo e(translate('Save')); ?></button>
                </div>
              </from>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>

<script type="text/javascript">

    function coupon_form(){
        var coupon_type = $('#coupon_type').val();
		$.post('<?php echo e(route('coupon.get_coupon_form')); ?>',{_token:'<?php echo e(csrf_token()); ?>', coupon_type:coupon_type}, function(data){
            $('#coupon_form').html(data);
		});
    }

</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\colebrook-admin\resources\views/backend/marketing/coupons/create.blade.php ENDPATH**/ ?>