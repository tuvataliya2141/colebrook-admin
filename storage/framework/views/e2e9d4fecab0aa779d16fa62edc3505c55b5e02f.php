<?php $__env->startSection('content'); ?>

<h4 class="text-center text-muted mt-4"><?php echo e(translate('Feature Activation')); ?></h4>
<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header text-center bord-btm">
                <h3 class="mb-0 h6 text-center"><?php echo e(translate('Paypal Payment Activation')); ?></h3>
            </div>
            <div class="card-body">
                <div class="clearfix">
                    <img class="float-left" src="<?php echo e(static_asset('assets/img/cards/paypal.png')); ?>" height="30">
                    <label class="aiz-switch aiz-switch-success mb-0 float-right">
                        <input type="checkbox" onchange="updateSettings(this, 'paypal_payment')" <?php if(get_setting('paypal_payment') == 1) echo "checked";?>>
                        <span class="slider round"></span>
                    </label>
                </div>
                <div class="alert text-center" style="color: #004085;background-color: #cce5ff;border-color: #b8daff;margin-bottom:0;margin-top:10px;">
                    <?php echo e(translate('You need to configure Paypal correctly to enable this feature')); ?>. <a href="<?php echo e(route('payment_method.index')); ?>"><?php echo e(translate('Configure Now')); ?></a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0 h6 text-center"><?php echo e(translate('Stripe Payment Activation')); ?></h3>
            </div>
            <div class="card-body text-center">
                <div class="clearfix">
                    <img   class="float-left" src="<?php echo e(static_asset('assets/img/cards/stripe.png')); ?>" height="30">
                    <label class="aiz-switch aiz-switch-success mb-0 float-right">
                        <input type="checkbox" onchange="updateSettings(this, 'stripe_payment')" <?php if(get_setting('stripe_payment') == 1) echo "checked";?>>
                        <span class="slider round"></span>
                    </label>
                </div>
                <div class="alert" style="color: #004085;background-color: #cce5ff;border-color: #b8daff;margin-bottom:0;margin-top:10px;">
                    You need to configure Stripe correctly to enable this feature. <a href="<?php echo e(route('payment_method.index')); ?>">Configure Now</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0 h6 text-center"><?php echo e(translate('Razor Pay Activation')); ?></h3>
            </div>
            <div class="card-body text-center">
                <div class="clearfix">
                    <img class="float-left" src="<?php echo e(static_asset('assets/img/cards/rozarpay.png')); ?>" height="30">
                    <label class="aiz-switch aiz-switch-success mb-0 float-right">
                        <input type="checkbox" onchange="updateSettings(this, 'razorpay')" <?php if(get_setting('razorpay') == 1) echo "checked";?>>
                        <span class="slider round"></span>
                    </label>
                </div>
                <div class="alert" style="color: #004085;background-color: #cce5ff;border-color: #b8daff;margin-bottom:0;margin-top:10px;">
                    <?php echo e(translate('You need to configure Razor correctly to enable this feature')); ?>. <a href="<?php echo e(route('payment_method.index')); ?>"><?php echo e(translate('Configure Now')); ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script type="text/javascript">
        function updateSettings(el, type){
            if($(el).is(':checked')){
                var value = 1;
            }
            else{
                var value = 0;
            }
            
            $.post('<?php echo e(route('business_settings.update.activation')); ?>', {_token:'<?php echo e(csrf_token()); ?>', type:type, value:value}, function(data){
                if(data == '1'){
                    AIZ.plugins.notify('success', '<?php echo e(translate('Settings updated successfully')); ?>');
                }
                else{
                    AIZ.plugins.notify('danger', 'Something went wrong');
                }
            });
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\colebrookAdmin\resources\views/backend/setup_configurations/activation.blade.php ENDPATH**/ ?>