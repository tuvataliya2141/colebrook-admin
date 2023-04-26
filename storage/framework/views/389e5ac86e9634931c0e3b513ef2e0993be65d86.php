

<?php $__env->startSection('content'); ?>
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col-md-6">
			<h1 class="h3"><?php echo e(translate('All Coupons')); ?></h1>
		</div>
		<div class="col-md-6 text-md-right">
			<a href="<?php echo e(route('coupon.create')); ?>" class="btn btn-circle btn-info">
				<span><?php echo e(translate('Add New Coupon')); ?></span>
			</a>
		</div>
	</div>
</div>

<div class="card">
  <div class="card-header">
      <h5 class="mb-0 h6"><?php echo e(translate('Coupon Information')); ?></h5>
  </div>
  <div class="card-body">
      <table class="table aiz-table p-0">
            <thead>
                <tr>
                    <th data-breakpoints="lg">#</th>
                    <th><?php echo e(translate('Code')); ?></th>
                    <th data-breakpoints="lg"><?php echo e(translate('Type')); ?></th>
                    <th data-breakpoints="lg"><?php echo e(translate('Start Date')); ?></th>
                    <th data-breakpoints="lg"><?php echo e(translate('End Date')); ?></th>
                    <th width="10%"><?php echo e(translate('Options')); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $coupons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $coupon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($key+1); ?></td>
                        <td><?php echo e($coupon->code); ?></td>
                        <td><?php if($coupon->type == 'cart_base'): ?>
                                <?php echo e(translate('Cart Base')); ?>

                            <?php elseif($coupon->type == 'product_base'): ?>
                                <?php echo e(translate('Product Base')); ?>

                        <?php endif; ?></td>
                        <td><?php echo e(date('d-m-Y', $coupon->start_date)); ?></td>
                        <td><?php echo e(date('d-m-Y', $coupon->end_date)); ?></td>
						<td class="text-right">
                            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="<?php echo e(route('coupon.edit', encrypt($coupon->id) )); ?>" title="<?php echo e(translate('Edit')); ?>">
                                <i class="las la-edit"></i>
                            </a>
                            <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="<?php echo e(route('coupon.destroy', $coupon->id)); ?>" title="<?php echo e(translate('Delete')); ?>">
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
    <?php echo $__env->make('modals.delete_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\colebrook-admin\resources\views/backend/marketing/coupons/index.blade.php ENDPATH**/ ?>