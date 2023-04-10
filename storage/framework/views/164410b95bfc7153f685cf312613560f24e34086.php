

<?php $__env->startSection('content'); ?>

<h4 class="text-center text-muted"><?php echo e(translate('System')); ?></h4>
<div class="row">
    <?php $__currentLoopData = $BusinessSettings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $business_settings): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6 text-center"><?php echo e(translate($business_settings['label'])); ?></h5>
                </div>
                <div class="card-body text-center">
                    <label class="aiz-switch aiz-switch-success mb-0">
                        <input type="checkbox" onchange="updateSettings(this, '<?php echo e($business_settings['type']); ?>')" <?php if(get_setting($business_settings['value'] == 1)) echo "checked";?>>
                    
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
        </div>
        
    
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
 
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
            
            $.post('<?php echo e(route('business_settings.edit')); ?>', {_token:'<?php echo e(csrf_token()); ?>', type:type, value:value}, function(data){
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
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\colebrookAdmin\resources\views/backend/setup_configurations/business_setting.blade.php ENDPATH**/ ?>