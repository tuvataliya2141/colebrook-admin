
<?php $__env->startSection('content'); ?>
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col">
			<h1 class="h3"><?php echo e(translate('Add New Size Chart')); ?></h1>
		</div>
	</div>
</div>
<div class="card">
	<form action="<?php echo e(route('size-chart.store')); ?>" method="POST" enctype="multipart/form-data">
		<?php echo csrf_field(); ?>
		<div class="card-header">
			<h6 class="fw-600 mb-0"><?php echo e(translate('Size Chart')); ?></h6>
		</div>
		<div class="card-body">
			<div class="form-group row">
				<div class="col-md-6">
					<label class="col-md-2 col-from-label"><?php echo e(translate('Category')); ?> <span class="text-danger">*</span></label>
					<div class="col-md-10">
						<select class="form-control aiz-selectpicker" name="category_id" id="category_id" data-live-search="true" required>
							<option value="">Select Category...</option>
							<?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($category->id); ?>"><?php echo e($category->getTranslation('name')); ?></option>
							<?php $__currentLoopData = $category->childrenCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $childCategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<?php echo $__env->make('categories.child_category', ['child_category' => $childCategory], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</select>
					</div>
				</div>
				<div class="col-md-6">
					<label class="col-md-3 col-form-label" for="signinSrEmail"><?php echo e(translate('Image')); ?></label>
					<div class="col-md-8">
						<div class="input-group" data-toggle="aizuploader" data-type="image">
							<div class="input-group-prepend">
								<div class="input-group-text bg-soft-secondary font-weight-medium"><?php echo e(translate('Browse')); ?></div>
							</div>
							<div class="form-control file-amount"><?php echo e(translate('Choose File')); ?></div>
							<input type="hidden" name="image" class="selected-files">
						</div>
						<div class="file-preview box sm">
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<table class="table table-bordered table-hover" id="dynamic_field">
					<tr>
						<th>Size</th>
						<th>Title</th>
						<th>Inches Value</th>
						<th>CM  Value</th>
					</tr>
					<tr>
						<td><input type="text" name="size[]" placeholder="Enter the Size like: XL" class="form-control size_list" required/></td>
						<td><input type="text" name="title[]" placeholder="Enter the  Title like: Chest" class="form-control title_list" required/></td>
						<td><input type="text" name="inches_value[]" placeholder="Enter the value in Inches" class="form-control inches_value" required/></td>
						<td><input type="text" name="cm_value[]" placeholder="Enter the value in Centimeter" class="form-control cm_value" required/></td>
						<td><button type="button" name="add" id="add" class="btn btn-primary">Add More</button></td>  
					</tr>
				</table>
			</div>
			<div class="text-right">
				<button type="submit" class="btn btn-primary"><?php echo e(translate('Save Page')); ?></button>
			</div>
		</div>
	</form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

<script type="text/javascript">
	$(document).ready(function(){
   
   		var i = 1;
		var length;
		
		$("#add").click(function(){
			i++;
			$('#dynamic_field').append('<tr id="row'+i+'"><td><input type="text" name="size[]" placeholder="Enter the Size like: XL" class="form-control size_list" required/></td><td><input type="text" name="title[]" placeholder="Enter the  Title like: Chest" class="form-control title_list" required/></td><td><input type="text" name="inches_value[]" placeholder="Enter the value in Inches" class="form-control inches_value" required/></td>	<td><input type="text" name="cm_value[]" placeholder="Enter the value in Centimeter" class="form-control cm_value" required/></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
		});
 
   		$(document).on('click', '.btn_remove', function(){  
			var button_id = $(this).attr("id");     
			$('#row'+button_id+'').remove();  
		});
	 
 
 
		$("#submit").on('click',function(event){
			var formdata = $("#add_name").serialize();
			console.log(formdata);
			
			event.preventDefault()
			
			$.ajax({
				url   :"action.php",
				type  :"POST",
				data  :formdata,
				cache :false,
				success:function(result){
					alert(result);
					$("#add_name")[0].reset();
				}
			});
	 	});
   });
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\colebrook-admin\resources\views/backend/size_chart/create.blade.php ENDPATH**/ ?>