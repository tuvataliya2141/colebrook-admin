

<?php $__env->startSection('content'); ?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
    		<div class="card-header">
    			<h1 class="h6"><?php echo e(translate('User Search Report')); ?></h1>
    		</div>
            <div class="card-body">
                <table class="table table-bordered aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><?php echo e(translate('Search By')); ?></th>
                            <th><?php echo e(translate('Number searches')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $searches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $searche): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e(($key+1) + ($searches->currentPage() - 1)*$searches->perPage()); ?></td>
                                <td><?php echo e($searche->query); ?></td>
                                <td><?php echo e($searche->count); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
                <div class="aiz-pagination mt-4">
                    <?php echo e($searches->links()); ?>

                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\colebrookAdmin\resources\views/backend/reports/user_search_report.blade.php ENDPATH**/ ?>