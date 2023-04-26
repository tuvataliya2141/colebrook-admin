
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css">
<style>
	.accordion-list .row {
		align-items: center;
	}
	.accordion-list li h3 {
		margin-bottom: 0;
	}
	.accordion-list li .btn-icon {
		display: flex;
		align-items: center;
		justify-content: center;
		margin: 0 5px;
	}
	ul.accordion-list {
		position: relative;
		display: block;
		width: 100%;
		height: auto;
		padding: 20px;
		margin: 0;
		list-style: none;
		background-color: #f9f9fA;
	}
	ul.accordion-list li {
		position: relative !important;
		display: block;
		width: 100%;
		height: auto;
		background-color: #FFF;
		padding: 20px;
		margin: 0 auto 15px auto;
		border: 1px solid #eee;
		border-radius: 5px;
		cursor: pointer;
	}		
	li.active .btn-soft-success{
		transform: rotate(45deg);
	}
	h3 {
		font-weight: 700;
		position: relative;
		display: block;
		width: 100%;
		height: auto;
		padding: 0 0 0 0;
		margin: 0;
		font-size: 15px;
		letter-spacing: 0.01em;
		cursor: pointer;
	}
	div.answer {
		position: relative;
		display: block;
		width: 100%;
		height: auto;
		margin: 0;
		padding: 0;
		cursor: pointer;
	}
  	

</style>

<?php $__env->startSection('content'); ?>
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col">
			<h1 class="h3"><?php echo e(translate('Size Chart')); ?></h1>
		</div>
	</div>
</div>

<div class="card">
	<div class="card-header">
		<h6 class="mb-0 fw-600"><?php echo e(translate('Size Chart')); ?></h6>
		<a href="<?php echo e(route('size-chart.add')); ?>" class="btn btn-primary"><?php echo e(translate('Add New Size')); ?></a>
	</div>
	<div class="card-body">
		<ul class="accordion-list">
			<?php $__currentLoopData = $list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<li>
					<div class="row">
						<div class="col-md-6">
							<h3 style="font-size: 15px;"><?php echo e($val->name); ?></h3>
						</div>
						<div class="col-md-6 text-right d-flex justify-content-end">
							<a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="<?php echo e(route('size-chart.edit', $val->id)); ?>" title="<?php echo e(translate('Edit')); ?>">
								<i class="las la-edit"></i>
							</a>
							<a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="<?php echo e(route('size-chart.destroy', $val->id)); ?>" title="<?php echo e(translate('Delete')); ?>">
								<i class="las la-trash"></i>
							</a>
							<a href="#" class="btn btn-soft-success btn-icon btn-circle btn-sm">
								<i class="las la-plus"></i>
							</a>
						</div>
					</div>
					<div class="answer">
						<table class="table aiz-table mb-0">
							<thead>
								<tr>
									<th><?php echo e(translate('Size')); ?></th>
									<th><?php echo e(translate('Title')); ?></th>
									<th><?php echo e(translate('Value in inches')); ?></th>
									<th><?php echo e(translate('Value in CM')); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php $size = json_decode($val->size_values) ?>
								<?php $__currentLoopData = $size; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<tr>
									<td><?php echo e($val->size); ?></td>
									<td><?php echo e($val->title); ?></td>
									<td><?php echo e($val->inches_value); ?></td>
									<td><?php echo e($val->cm_value); ?></td>
								</tr>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							</tbody>
						</table>
					</div>
				</li>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		</ul>
		
    </table>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('modal'); ?>
    <?php echo $__env->make('modals.delete_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

<script type="text/javascript">
	$(document).ready(function(){
		$('.accordion-list > li > .answer').hide();
		$('.accordion-list > li .btn-soft-success').click(function() {
			if ($(this).parent().parent().parent().hasClass("active")) {
				$(this).parent().parent().parent().removeClass("active").find(".answer").slideUp();
			} else {
				$(".accordion-list > li.active .answer").slideUp();
				$(".accordion-list > li.active").removeClass("active");
				$(this).parent().parent().parent().addClass("active").find(".answer").slideDown();
			}
			return false;
		});
		
	});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\colebrook-admin\resources\views/backend/size_chart/index.blade.php ENDPATH**/ ?>