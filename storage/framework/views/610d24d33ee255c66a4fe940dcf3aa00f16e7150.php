<?php $__env->startSection('content'); ?>
<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="h3"><?php echo e(translate('Product view by total user')); ?></h1>
        </div>
    </div>
</div>
<br>
<div class="card">
    <div class="card-header row gutters-5">
        <div class="col">
            <h5 class="mb-md-0 h6"><?php echo e(translate('Product view by total user')); ?></h5>
        </div>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th><?php echo e(translate('Name')); ?></th>
                    <th data-breakpoints="sm" class="text-right"><?php echo e(translate('Options')); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>
                        <div class="row gutters-5 w-200px w-md-300px mw-100">
                            <div class="col-auto">
                                <img src="<?php echo e(uploaded_asset($product->thumbnail_img)); ?>" alt="Image" class="size-50px img-fit">
                            </div>
                            <div class="col">
                                <span class="text-muted text-truncate-2"><?php echo e($product->getTranslation('name')); ?></span>
                            </div>
                        </div>
                    </td>
                    <td class="text-right">
                        <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="<?php echo e(route('products.products_view_by_user', ['id'=>$product->id] )); ?>" title="<?php echo e(translate('View')); ?>">
                            <i class="las la-eye"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>







<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\colebrookAdmin\resources\views/backend/product/products_by_user_view/index.blade.php ENDPATH**/ ?>