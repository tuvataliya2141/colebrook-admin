<?php $__env->startSection('content'); ?>
<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="h3"><?php echo e(translate('User List')); ?></h1>
        </div>
    </div>
</div>
<br>
<div class="card">
    <div class="card-header row gutters-5">
        <div class="col">
            <h5 class="mb-md-0 h6"><?php echo e(translate('User List')); ?></h5>
        </div>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th><?php echo e(translate('Name')); ?></th>
                    <th><?php echo e(translate('Email')); ?></th>
                    <th><?php echo e(translate('Phone')); ?></th>
                    <th><?php echo e(translate('Total visit')); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>
                        <span class="text-muted text-truncate-2"><?php echo e($user->name); ?></span>
                    </td>
                    <td>
                        <span class="text-muted text-truncate-2"><?php echo e($user->email); ?></span>
                    </td>
                    <td>
                        <span class="text-muted text-truncate-2"><?php echo e($user->phone); ?></span>
                    </td>
                    <td>
                        <span class="text-muted text-truncate-2"><?php echo e($user->total_visit); ?></span>    
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>







<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\colebrook-admin\resources\views/backend/product/products_by_user_view/user_list.blade.php ENDPATH**/ ?>