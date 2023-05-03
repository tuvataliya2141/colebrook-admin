

<?php $__env->startSection('content'); ?>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6"><?php echo e(translate('Category Information')); ?></h5>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo e(route('categories.store')); ?>" method="POST" enctype="multipart/form-data">
                	<?php echo csrf_field(); ?>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label"><?php echo e(translate('Name')); ?></label>
                        <div class="col-md-9">
                            <input type="text" placeholder="<?php echo e(translate('Name')); ?>" id="name" name="name" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label"><?php echo e(translate('Parent Category')); ?></label>
                        <div class="col-md-9">
                            <select class="select2 form-control aiz-selectpicker" onchange="checkCat(this.value);" name="parent_id" data-toggle="select2" data-placeholder="Choose ..." data-live-search="true">
                                <option value="0"><?php echo e(translate('No Parent')); ?></option>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($category->id); ?>"><?php echo e($category->getTranslation('name')); ?></option>
                                    <?php $__currentLoopData = $category->childrenCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $childCategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php echo $__env->make('categories.child_category', ['child_category' => $childCategory], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row" id="orderBy" style="display: none;">
                        <label class="col-md-3 col-form-label"><?php echo e(translate('Order By')); ?></label>
                        <div class="col-md-9">
                            
                            <select class="select2 form-control aiz-selectpicker" name="order_by" data-toggle="select2" data-placeholder="Choose ..." data-live-search="true">
                                <option value="0"><?php echo e(translate('No Order By')); ?></option>
                                <option value="By Price">By Price</option>
                                <option value="By Occasion">By Occasion</option>
                                <option value="By Type">By Type</option>
                                <option value="By Colour">By Colour</option>
                                <option value="Special Flowers">Special Flowers</option>
                                <option value="Festive Special">Festive Special</option>
                                <option value="Flower Cities">Flower Cities</option>
                                <option value="By Recipient">By Recipient</option>
                                <option value="By Flovour">By Flovour</option>
                                <option value="By Cities">By Cities</option>
                                <option value="By Theme">By Theme</option>
                                <option value="Cakes Cities">Cakes Cities</option>
                                <option value="Plants By Name">Plants By Name</option>
                                <option value="Others">Others</option>
                                <option value="Plants Cities">Plants Cities</option>
                                <option value="More Gifts">More Gifts</option>
                                <option value="For Him">For Him</option>
                                <option value="For Her">For Her</option>
                                <option value="Gift Cities">Gift Cities</option>
                                <option value="Flower Combos">Flower Combos</option>
                                <option value="Cake Combos">Cake Combos</option>
                                <option value="Exclusive Combos">Exclusive Combos</option>
                                <option value="Plant Combos">Plant Combos</option>
                                <option value="Chocolate Combos">Chocolate Combos</option>
                                <option value="By Relation">By Relation</option>
                                <option value="Same Day Delivery">Same Day Delivery</option>
                                <option value="Gift Types">Gift Types</option>
                                <option value="Digital">Digital</option>
                                <option value="Festivals">Festivals</option>
                                <option value="Special Occasions">Special Occasions</option>
                                <option value="Overseas Gifts">Overseas Gifts</option>
                            </select>
                            
                        </div>
                    </div>
                    <!-- <div class="form-group row">
                        <label class="col-md-3 col-form-label">
                            <?php echo e(translate('Ordering Number')); ?>

                        </label>
                        <div class="col-md-9">
                            <input type="number" name="order_level" class="form-control" id="order_level" placeholder="<?php echo e(translate('Order Level')); ?>">
                            <small><?php echo e(translate('Higher number has high priority')); ?></small>
                        </div>
                    </div> -->
                    <!-- <div class="form-group row">
                        <label class="col-md-3 col-form-label"><?php echo e(translate('Type')); ?></label>
                        <div class="col-md-9">
                            <select name="digital" required class="form-control aiz-selectpicker mb-2 mb-md-0">
                                <option value="0"><?php echo e(translate('Physical')); ?></option>
                                <option value="1"><?php echo e(translate('Digital')); ?></option>
                            </select>
                        </div>
                    </div> -->
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="signinSrEmail"><?php echo e(translate('Banner')); ?> <small>(<?php echo e(translate('200x200')); ?>)</small></label>
                        <div class="col-md-9">
                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium"><?php echo e(translate('Browse')); ?></div>
                                </div>
                                <div class="form-control file-amount"><?php echo e(translate('Choose File')); ?></div>
                                <input type="hidden" name="banner" class="selected-files">
                            </div>
                            <div class="file-preview box sm">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="signinSrEmail"><?php echo e(translate('Icon')); ?> <small>(<?php echo e(translate('32x32')); ?>)</small></label>
                        <div class="col-md-9">
                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium"><?php echo e(translate('Browse')); ?></div>
                                </div>
                                <div class="form-control file-amount"><?php echo e(translate('Choose File')); ?></div>
                                <input type="hidden" name="icon" class="selected-files">
                            </div>
                            <div class="file-preview box sm">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label"><?php echo e(translate('Meta Title')); ?></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="meta_title" placeholder="<?php echo e(translate('Meta Title')); ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label"><?php echo e(translate('Meta Description')); ?></label>
                        <div class="col-md-9">
                            <textarea name="meta_description" rows="5" class="form-control"></textarea>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label"><?php echo e(translate('SEO Description')); ?></label>
                        <div class="col-md-9">
                            <textarea name="seo_description" rows="5" class="aiz-text-editor"></textarea>
                        </div>
                    </div>
                    
                    <?php if(get_setting('category_wise_commission') == 1): ?>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"><?php echo e(translate('Commission Rate')); ?></label>
                            <div class="col-md-9 input-group">
                                <input type="number" lang="en" min="0" step="0.01" placeholder="<?php echo e(translate('Commission Rate')); ?>" id="commision_rate" name="commision_rate" class="form-control">
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label"><?php echo e(translate('Filtering Attributes')); ?></label>
                        <div class="col-md-9">
                            <select class="select2 form-control aiz-selectpicker" name="filtering_attributes[]" data-toggle="select2" data-placeholder="Choose ..."data-live-search="true" multiple>
                                <?php $__currentLoopData = \App\Models\Attribute::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attribute): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($attribute->id); ?>"><?php echo e($attribute->getTranslation('name')); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary"><?php echo e(translate('Save')); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>
    function checkCat(val){
        $.ajax({
            type:"POST",
            url: '<?php echo e(route('categories.checkcat')); ?>',
            data: {
                "_token": "<?php echo e(csrf_token()); ?>",
                "val": val
            },
            success: function(data){
                if(data.status == true){
                    $('#orderBy').show();
                } else {
                    $('#orderBy').hide();
                }
            }
        });
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\colebrook-admin\resources\views/backend/product/categories/create.blade.php ENDPATH**/ ?>