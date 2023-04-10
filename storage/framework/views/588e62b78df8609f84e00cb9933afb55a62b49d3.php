

<?php $__env->startSection('content'); ?>

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3"><?php echo e(translate('All Taxes')); ?></h1>
        </div>
        <div class="col-md-6 text-md-right">
            <a href="#" data-target="#add-tax" data-toggle="modal" class="btn btn-circle btn-info">
                <span><?php echo e(translate('Add New Tax')); ?></span>
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header row gutters-5">
        <div class="col text-center text-md-left">
            <h5 class="mb-md-0 h6"><?php echo e(translate('All Taxes')); ?></h5>
        </div>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th><?php echo e(translate('Tax Type')); ?></th>
                    <th><?php echo e(translate('Status')); ?></th>
                    <th class="text-right"><?php echo e(translate('Options')); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $all_taxes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $tax): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($loop->iteration); ?></td>
                    <td><?php echo e($tax->name); ?></td>
                    
                    <td>
                        <label class="aiz-switch aiz-switch-success mb-0">
                            <input onchange="update_tax_status(this)" value="<?php echo e($tax->id); ?>" type="checkbox" <?php if ($tax->tax_status == 1) echo "checked"; ?> >
                            <span class="slider round"></span>
                        </label>
                        
                    </td>
                    <td class="text-right">
                        <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="<?php echo e(route('tax.edit', $tax->id )); ?>" title="<?php echo e(translate('Edit')); ?>">
                            <i class="las la-edit"></i>
                        </a>
                        <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="<?php echo e(route('tax.destroy', $tax->id)); ?>" title="<?php echo e(translate('Delete')); ?>">
                            <i class="las la-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('modal'); ?>
    <!-- Tax Add Modal -->
    <div id="add-tax" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom" role="document">
            <div class="modal-content">
                <div class="modal-header bord-btm">
                    <h4 class="modal-title h6"><?php echo e(translate('Add New Tax')); ?></h4>
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                </div>
                
                <form class="form-horizontal"  action="<?php echo e(route('tax.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        
                        <div class="form-group">
                            <div class=" row">
                                <label class="col-sm-3 control-label" for="name">
                                    <?php echo e(translate('Tax Name')); ?>

                                </label>
                                <div class="col-sm-9">
                                    <input type="text" placeholder="<?php echo e(translate('Name')); ?>" id="name" name="name" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-styled btn-base-3" data-dismiss="modal">
                            <?php echo e(translate('Close')); ?>

                        </button>
                        <button type="submit" class="btn btn-primary btn-styled btn-base-1">
                            <?php echo e(translate('Save')); ?>

                        </button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
    
    <?php echo $__env->make('modals.delete_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script type="text/javascript">
        function sort_pickup_points(el){
            $('#sort_pickup_points').submit();
        }
        
        function update_tax_status(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('<?php echo e(route('taxes.tax-status')); ?>', {_token:'<?php echo e(csrf_token()); ?>', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '<?php echo e(translate('Tax status updated successfully')); ?>');
                }
                else{
                    AIZ.plugins.notify('danger', '<?php echo e(translate('Something went wrong')); ?>');
                }
            });
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\colebrookAdmin\resources\views/backend/setup_configurations/tax/index.blade.php ENDPATH**/ ?>