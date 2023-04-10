    

    <?php $__env->startSection('content'); ?>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6 "><?php echo e(translate('Paypal Credential')); ?></h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="<?php echo e(route('payment_method.update')); ?>" method="POST">
                        <input type="hidden" name="payment_method" value="paypal">
                        <?php echo csrf_field(); ?>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="PAYPAL_CLIENT_ID">
                            <div class="col-md-4">
                                <label class="col-from-label"><?php echo e(translate('Paypal Client Id')); ?></label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="PAYPAL_CLIENT_ID" value="<?php echo e(env('PAYPAL_CLIENT_ID')); ?>" placeholder="<?php echo e(translate('Paypal Client ID')); ?>" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="PAYPAL_CLIENT_SECRET">
                            <div class="col-md-4">
                                <label class="col-from-label"><?php echo e(translate('Paypal Client Secret')); ?></label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="PAYPAL_CLIENT_SECRET" value="<?php echo e(env('PAYPAL_CLIENT_SECRET')); ?>" placeholder="<?php echo e(translate('Paypal Client Secret')); ?>" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label class="col-from-label"><?php echo e(translate('Paypal Sandbox Mode')); ?></label>
                            </div>
                            <div class="col-md-8">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input value="1" name="paypal_sandbox" type="checkbox" <?php if(get_setting('paypal_sandbox') == 1): ?>
                                        checked
                                    <?php endif; ?>>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary"><?php echo e(translate('Save')); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6 "><?php echo e(translate('Stripe Credential')); ?></h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="<?php echo e(route('payment_method.update')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="payment_method" value="stripe">
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="STRIPE_KEY">
                            <div class="col-md-4">
                                <label class="col-from-label"><?php echo e(translate('Stripe Key')); ?></label>
                            </div>
                            <div class="col-md-8">
                            <input type="text" class="form-control" name="STRIPE_KEY" value="<?php echo e(env('STRIPE_KEY')); ?>" placeholder="<?php echo e(translate('STRIPE KEY')); ?>" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="STRIPE_SECRET">
                            <div class="col-md-4">
                                <label class="col-from-label"><?php echo e(translate('Stripe Secret')); ?></label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="STRIPE_SECRET" value="<?php echo e(env('STRIPE_SECRET')); ?>" placeholder="<?php echo e(translate('STRIPE SECRET')); ?>" required>
                            </div>
                        </div>
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary"><?php echo e(translate('Save')); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6 "><?php echo e(translate('RazorPay Credential')); ?></h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="<?php echo e(route('payment_method.update')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="payment_method" value="razorpay">
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="RAZOR_KEY">
                            <div class="col-md-4">
                                <label class="col-from-label"><?php echo e(translate('RAZOR KEY')); ?></label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="RAZOR_KEY" value="<?php echo e(env('RAZOR_KEY')); ?>" placeholder="<?php echo e(translate('RAZOR KEY')); ?>" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="RAZOR_SECRET">
                            <div class="col-md-4">
                                <label class="col-from-label"><?php echo e(translate('RAZOR SECRET')); ?></label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="RAZOR_SECRET" value="<?php echo e(env('RAZOR_SECRET')); ?>" placeholder="<?php echo e(translate('RAZOR SECRET')); ?>" required>
                            </div>
                        </div>
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary"><?php echo e(translate('Save')); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\colebrookAdmin\resources\views/backend/setup_configurations/payment_method.blade.php ENDPATH**/ ?>