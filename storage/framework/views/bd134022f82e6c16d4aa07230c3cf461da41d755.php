

<?php $__env->startSection('content'); ?>
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col">
			<h1 class="h3"><?php echo e(translate('Home Card')); ?></h1>
		</div>
	</div>
</div>

<div class="card">
	<div class="card-body">
		<table class="table aiz-table mb-0">
        <thead>
            <tr>
                <th data-breakpoints="lg">#</th>
                <th><?php echo e(translate('Title')); ?></th>
                <th data-breakpoints="md"><?php echo e(translate('URL')); ?></th>
                <th class="text-right"><?php echo e(translate('Actions')); ?></th>
            </tr>
        </thead>
        <tbody>
        	<?php $__currentLoopData = $list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        	<tr>
        		<td><?php echo e($val->id); ?></td>
        		<td><?php echo e($val->title); ?></td>
        		<td><?php echo e($val->url); ?></td>
        		<td class="text-right">
					<a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="<?php echo e(route('website.home_card_edit', ['id'=>$val->id, 'lang'=>env('DEFAULT_LANGUAGE')] )); ?>" title="<?php echo e(translate('Edit')); ?>">
						<i class="las la-edit"></i>
					</a>
				</td>
        	</tr>
        	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
	</div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\colebrookAdmin\resources\views/backend/website_settings/home_card/index.blade.php ENDPATH**/ ?>