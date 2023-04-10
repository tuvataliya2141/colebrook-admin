

<?php $__env->startSection('content'); ?>
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col">
			<h1 class="h3"><?php echo e(translate('Edit Home Card')); ?></h1>
		</div>
	</div>
</div>
<div class="card">
	<form class="p-4" action="<?php echo e(route('website.home_card.update')); ?>" method="POST" enctype="multipart/form-data">
		<?php echo csrf_field(); ?>
		
		<input type="hidden" name="card_id" value="<?php echo e($card->id); ?>">
		
		<div class="card-header px-0">
			<h6 class="fw-600 mb-0"><?php echo e(translate('Home Card')); ?></h6>
		</div>
		<div class="card-body px-0">
			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="name"><?php echo e(translate('Home Card Title')); ?></label>
				<div class="col-sm-10">
					<input type="text" class="form-control" placeholder="<?php echo e(translate('Title')); ?>" name="title" value="<?php echo e($card->title); ?>">
				</div>
			</div>
			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="name"><?php echo e(translate('Home Card URL')); ?></label>
				<div class="col-sm-10">
					<input type="text" class="form-control" placeholder="<?php echo e(translate('URL')); ?>" name="url" value="<?php echo e($card->url); ?>">
				</div>
			</div>
			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="name"><?php echo e(translate('Home Card Image')); ?></label>
				<div class="col-sm-10">
					<div class="input-group " data-toggle="aizuploader" data-type="image">
						<div class="input-group-prepend">
							<div class="input-group-text bg-soft-secondary font-weight-medium"><?php echo e(translate('Browse')); ?></div>
						</div>
						<div class="form-control file-amount"><?php echo e(translate('Choose File')); ?></div>
						<input type="hidden" name="image" class="selected-files" value="<?php echo e($card->image); ?>">
					</div>
					<div class="file-preview">
					</div>
				</div>
			</div>
			<div class="text-right">
				<button type="submit" class="btn btn-primary"><?php echo e(translate('Update Home Card')); ?></button>
			</div>
		</div>
	</form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\colebrookAdmin\resources\views/backend/website_settings/home_card/edit.blade.php ENDPATH**/ ?>