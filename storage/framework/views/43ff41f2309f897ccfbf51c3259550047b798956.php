<?php $__env->startSection('content'); ?>
<div class="aiz-titlebar text-left mt-2 mb-3">
    <h1 class="mb-0 h6"><?php echo e(translate('Edit Product')); ?></h5>
</div>
<div class="">
    <form class="form form-horizontal mar-top" action="<?php echo e(route('products.update', $product->id)); ?>" method="POST" enctype="multipart/form-data" id="choice_form">
        <div class="row gutters-5">
            <div class="col-lg-8">
                <input name="_method" type="hidden" value="POST">
                <input type="hidden" name="id" value="<?php echo e($product->id); ?>">
                <input type="hidden" name="lang" value="<?php echo e($lang); ?>">
                <?php echo csrf_field(); ?>
                <div class="card">
                    <ul class="nav nav-tabs nav-fill border-light">
                        <?php $__currentLoopData = \App\Models\Language::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="nav-item">
                            <a class="nav-link text-reset <?php if($language->code == $lang): ?> active <?php else: ?> bg-soft-dark border-light border-left-0 <?php endif; ?> py-3" href="<?php echo e(route('products.admin.edit', ['id'=>$product->id, 'lang'=> $language->code] )); ?>">
                                <img src="<?php echo e(static_asset('assets/img/flags/'.$language->code.'.png')); ?>" height="11" class="mr-1">
                                <span><?php echo e($language->name); ?></span>
                            </a>
                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-lg-3 col-from-label"><?php echo e(translate('Product Name')); ?> <i class="las la-language text-danger" title="<?php echo e(translate('Translatable')); ?>"></i></label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" name="name" placeholder="<?php echo e(translate('Product Name')); ?>" value="<?php echo e($product->getTranslation('name', $lang)); ?>" required>
                            </div>
                        </div>
                        <div class="form-group row" id="category">
                            <label class="col-lg-3 col-from-label"><?php echo e(translate('Category')); ?></label>
                            <div class="col-lg-8">
                                <select class="form-control aiz-selectpicker" name="category_id" id="category_id" data-selected="<?php echo e($product->category_id); ?>" data-live-search="true" required>
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($category->id); ?>"><?php echo e($category->getTranslation('name')); ?></option>
                                    <?php $__currentLoopData = $category->childrenCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $childCategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php echo $__env->make('categories.child_category', ['child_category' => $childCategory], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <!-- Code cange by Tarun on 02-fab-22 CR#2 - start -->
                        <div class="form-group row" id="category">
                            <label class="col-lg-3 col-from-label"><?php echo e(translate('Additional Category')); ?></label>
                            <div class="col-lg-8">
                                <select class="form-control aiz-selectpicker" multiple data-live-search="true" name="additional_id[]" id="additional_id" data-live-search="true">
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($category->id); ?>" <?php echo e((in_array($category->id, $additional)) ? 'selected' : ''); ?>><?php echo e($category->getTranslation('name')); ?></option>
                                    <?php $__currentLoopData = $category->childrenCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $childCategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($additional): ?>
                                        <?php echo $__env->make('categories.child_category', ['child_category' => $childCategory, 'additional' => $additional], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    <?php else: ?>
                                        <?php echo $__env->make('categories.child_category', ['child_category' => $childCategory], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <!-- Code cange by Tarun on 02-fab-22 CR#2 - end -->
                        <div class="form-group row" id="brand">
                            <label class="col-lg-3 col-from-label"><?php echo e(translate('Brand')); ?></label>
                            <div class="col-lg-8">
                                <select class="form-control aiz-selectpicker" name="brand_id" id="brand_id" data-live-search="true">
                                    <option value=""><?php echo e(translate('Select Brand')); ?></option>
                                    <?php $__currentLoopData = \App\Models\Brand::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($brand->id); ?>" <?php if($product->brand_id == $brand->id): ?> selected <?php endif; ?>><?php echo e($brand->getTranslation('name')); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-from-label"><?php echo e(translate('Unit')); ?> <i class="las la-language text-danger" title="<?php echo e(translate('Translatable')); ?>"></i> </label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" name="unit" placeholder="<?php echo e(translate('Unit (e.g. KG, Pc etc)')); ?>" value="<?php echo e($product->getTranslation('unit', $lang)); ?>" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-from-label"><?php echo e(translate('Minimum Purchase Qty')); ?></label>
                            <div class="col-lg-8">
                                <input type="number" lang="en" class="form-control" name="min_qty" value="<?php if($product->min_qty <= 1): ?><?php echo e(1); ?><?php else: ?><?php echo e($product->min_qty); ?><?php endif; ?>" min="1" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-from-label"><?php echo e(translate('Tags')); ?></label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control aiz-tag-input" name="tags[]" id="tags" value="<?php echo e($product->tags); ?>" placeholder="<?php echo e(translate('Type to add a tag')); ?>" data-role="tagsinput">
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6"><?php echo e(translate('Product Images')); ?></h5>
                    </div>
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="signinSrEmail"><?php echo e(translate('Gallery Images')); ?></label>
                            <div class="col-md-8">
                                <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium"><?php echo e(translate('Browse')); ?></div>
                                    </div>
                                    <div class="form-control file-amount"><?php echo e(translate('Choose File')); ?></div>
                                    <input type="hidden" name="photos" value="<?php echo e($product->photos); ?>" class="selected-files">
                                </div>
                                <div class="file-preview box sm">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="signinSrEmail"><?php echo e(translate('Thumbnail Image')); ?> <small>(290x300)</small></label>
                            <div class="col-md-8">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium"><?php echo e(translate('Browse')); ?></div>
                                    </div>
                                    <div class="form-control file-amount"><?php echo e(translate('Choose File')); ?></div>
                                    <input type="hidden" name="thumbnail_img" value="<?php echo e($product->thumbnail_img); ?>" class="selected-files">
                                </div>
                                <div class="file-preview box sm">
                                </div>
                            </div>
                        </div>
                        
                        
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6"><?php echo e(translate('Product Videos')); ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-lg-3 col-from-label"><?php echo e(translate('Video Provider')); ?></label>
                            <div class="col-lg-8">
                                <select class="form-control aiz-selectpicker" name="video_provider" id="video_provider">
                                    <option value="youtube" <?php if ($product->video_provider == 'youtube') echo "selected"; ?> ><?php echo e(translate('Youtube')); ?></option>
                                    <option value="dailymotion" <?php if ($product->video_provider == 'dailymotion') echo "selected"; ?> ><?php echo e(translate('Dailymotion')); ?></option>
                                    <option value="vimeo" <?php if ($product->video_provider == 'vimeo') echo "selected"; ?> ><?php echo e(translate('Vimeo')); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-from-label"><?php echo e(translate('Video Link')); ?></label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" name="video_link" value="<?php echo e($product->video_link); ?>" placeholder="<?php echo e(translate('Video Link')); ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6"><?php echo e(translate('Product Variation')); ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row gutters-5">
                            <div class="col-lg-3">
                                <input type="text" class="form-control" value="<?php echo e(translate('Colors')); ?>" disabled>
                            </div>
                            <div class="col-lg-8">
                                <select class="form-control aiz-selectpicker" data-live-search="true" data-selected-text-format="count" name="colors[]" id="colors" multiple>
                                    <?php $__currentLoopData = \App\Models\Color::orderBy('name', 'asc')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option
                                        value="<?php echo e($color->code); ?>"
                                        data-content="<span><span class='size-15px d-inline-block mr-2 rounded border' style='background:<?php echo e($color->code); ?>'></span><span><?php echo e($color->name); ?></span></span>"
                                        <?php if (in_array($color->code, json_decode($product->colors))) echo 'selected' ?>
                                        ></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-lg-1">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input value="1" type="checkbox" name="colors_active" <?php if (count(json_decode($product->colors)) > 0) echo "checked"; ?> >
                                    <span></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group row gutters-5">
                            <div class="col-lg-3">
                                <input type="text" class="form-control" value="<?php echo e(translate('Attributes')); ?>" disabled>
                            </div>
                            <div class="col-lg-8">
                                <select name="choice_attributes[]" id="choice_attributes" data-selected-text-format="count" data-live-search="true" class="form-control aiz-selectpicker" multiple data-placeholder="<?php echo e(translate('Choose Attributes')); ?>">
                                    <?php $__currentLoopData = \App\Models\Attribute::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $attribute): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($attribute->id); ?>" <?php if($product->attributes != null && in_array($attribute->id, json_decode($product->attributes, true))): ?> selected <?php endif; ?>><?php echo e($attribute->getTranslation('name')); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>

                        <div class="">
                            <p><?php echo e(translate('Choose the attributes of this product and then input values of each attribute')); ?></p>
                            <br>
                        </div>

                        <div class="customer_choice_options" id="customer_choice_options">
                            <?php $__currentLoopData = json_decode($product->choice_options); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $choice_option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="form-group row">
                                <div class="col-lg-3">
                                    <input type="hidden" name="choice_no[]" value="<?php echo e($choice_option->attribute_id); ?>">
                                    <input type="text" class="form-control" name="choice[]" value="<?php echo e(optional(\App\Models\Attribute::find($choice_option->attribute_id))->getTranslation('name')); ?>" placeholder="<?php echo e(translate('Choice Title')); ?>" disabled>
                                </div>
                                <div class="col-lg-8">
                                    <select class="form-control aiz-selectpicker attribute_choice" data-live-search="true" name="choice_options_<?php echo e($choice_option->attribute_id); ?>[]" multiple>
                                        <?php $__currentLoopData = \App\Models\AttributeValue::where('attribute_id', $choice_option->attribute_id)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($row->value); ?>" <?php if( in_array($row->value, $choice_option->values)): ?> selected <?php endif; ?>>
                                            <?php echo e($row->value); ?>

                                        </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6"><?php echo e(translate('Product price + stock')); ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-lg-3 col-from-label"><?php echo e(translate('Unit price')); ?></label>
                            <div class="col-lg-6">
                                <input type="text" placeholder="<?php echo e(translate('Unit price')); ?>" name="unit_price" class="form-control" value="<?php echo e($product->unit_price); ?>" required>
                            </div>
                        </div>

                        <?php
                          $start_date = date('d-m-Y H:i:s', $product->discount_start_date);
                          $end_date = date('d-m-Y H:i:s', $product->discount_end_date);
                        ?>

                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="start_date"><?php echo e(translate('Discount Date Range')); ?></label>
                            <div class="col-sm-9">
                              <input type="text" class="form-control aiz-date-range" <?php if($product->discount_start_date && $product->discount_end_date): ?> value="<?php echo e($start_date.' to '.$end_date); ?>" <?php endif; ?> name="date_range" placeholder="<?php echo e(translate('Select Date')); ?>" data-time-picker="true" data-format="DD-MM-Y HH:mm:ss" data-separator=" to " autocomplete="off">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-from-label"><?php echo e(translate('Discount')); ?></label>
                            <div class="col-lg-6">
                                <input type="number" lang="en" min="0" step="0.01" placeholder="<?php echo e(translate('Discount')); ?>" name="discount" class="form-control" value="<?php echo e($product->discount); ?>" required>
                            </div>
                            <div class="col-lg-3">
                                <select class="form-control aiz-selectpicker" name="discount_type" required>
                                    <option value="amount" <?php if ($product->discount_type == 'amount') echo "selected"; ?> ><?php echo e(translate('Flat')); ?></option>
                                    <option value="percent" <?php if ($product->discount_type == 'percent') echo "selected"; ?> ><?php echo e(translate('Percent')); ?></option>
                                </select>
                            </div>
                        </div>

                        <div id="show-hide-div">
                            <div class="form-group row" id="quantity">
                                <label class="col-lg-3 col-from-label"><?php echo e(translate('Quantity')); ?></label>
                                <div class="col-lg-6">
                                    <input type="number" lang="en" value="<?php echo e(optional($product->stocks->first())->qty); ?>" step="1" placeholder="<?php echo e(translate('Quantity')); ?>" name="current_stock" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">
                                    <?php echo e(translate('SKU')); ?>

                                </label>
                                <div class="col-md-6">
                                    <input type="text" placeholder="<?php echo e(translate('SKU')); ?>" value="<?php echo e(optional($product->stocks->first())->sku); ?>" name="sku" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">
                                <?php echo e(translate('External link')); ?>

                            </label>
                            <div class="col-md-9">
                                <input type="text" placeholder="<?php echo e(translate('External link')); ?>" name="external_link" value="<?php echo e($product->external_link); ?>" class="form-control">
                                <small class="text-muted"><?php echo e(translate('Leave it blank if you do not use external site link')); ?></small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">
                                <?php echo e(translate('External link button text')); ?>

                            </label>
                            <div class="col-md-9">
                                <input type="text" placeholder="<?php echo e(translate('External link button text')); ?>" name="external_link_btn" value="<?php echo e($product->external_link_btn); ?>" class="form-control">
                                <small class="text-muted"><?php echo e(translate('Leave it blank if you do not use external site link')); ?></small>
                            </div>
                        </div>
                        <br>
                        <div class="sku_combination" id="sku_combination">

                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6"><?php echo e(translate('Product Description')); ?></h5>
                    </div>
                    <div class="card-body">
                        
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label"><?php echo e(translate('Bullet Points Description')); ?></label>
                            <div class="col-md-8">
                                <textarea class="aiz-text-editor" name="bullet_points_description"><?php echo e($product->bullet_points_description); ?></textarea>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label class="col-lg-3 col-from-label"><?php echo e(translate('Description')); ?> <i class="las la-language text-danger" title="<?php echo e(translate('Translatable')); ?>"></i></label>
                            <div class="col-lg-9">
                                <textarea class="aiz-text-editor" name="description"><?php echo e($product->getTranslation('description', $lang)); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php if(get_setting('personlised_gift_option') == 1): ?>
                <?php if($product->is_file_upload != 1): ?>
                    <style>
                        #hidden_content{display:none;}
                    </style>
                <?php endif; ?>   
                    <div class="card">
                        <div class="card-header">
                            
                            <h5 class="mb-0 h6"><?php echo e(translate('Personlised Gift Option')); ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label"><?php echo e(translate('Controle Type')); ?></label>
                                <div class="col-md-4 d-flex flex-row align-items-center">                                
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox"  name="is_text_box" value="1" <?php if($product->is_text_box == 1): ?> checked <?php endif; ?>>
                                        <span></span>
                                    </label>
                                    <label style="font-size: 15px;" class="col-md-8 col-from-label"><?php echo e(translate('Text Box')); ?></label>
                                </div>
                                <div class="col-md-4 d-flex flex-row align-items-center">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" onchange="showDiv(this);" name="is_file_upload" value="1" <?php if($product->is_file_upload == 1): ?> checked <?php endif; ?>>
                                        <span></span>
                                    </label>
                                    <label style="font-size: 15px;" class="col-md-8 col-from-label"><?php echo e(translate('File Upload')); ?></label>
                                </div>
                            </div>
                            <hr>
                            <div id="hidden_content">
                                <div class="row">
                                    <label class="col-md-3 mb-3 col-from-label"><?php echo e(translate('Choose File Type')); ?></label>
                                </div>
                                <?php $typeData = explode(',',$product->is_file_type); ?>
                                <div class="row">
                                    <div class="col-md-4 d-flex flex-row align-items-center">                                
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="checkbox" <?php echo e((in_array('JPG', $typeData)) ? 'checked' : ''); ?> name="is_file_type[]" value="JPG">
                                            <span></span>
                                        </label>
                                        <label style="font-size: 15px;" class="col-md-8 col-from-label"><?php echo e(translate('JPG')); ?></label>
                                    </div>
                                    <div class="col-md-4 d-flex flex-row align-items-center">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="checkbox" <?php echo e((in_array('PNG', $typeData)) ? 'checked' : ''); ?> name="is_file_type[]" value="PNG">
                                            <span></span>
                                        </label>
                                        <label style="font-size: 15px;" class="col-md-8 col-from-label"><?php echo e(translate('PNG')); ?></label>
                                    </div>
                                    <div class="col-md-4 d-flex flex-row align-items-center">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="checkbox" <?php echo e((in_array('PDF', $typeData)) ? 'checked' : ''); ?> name="is_file_type[]" value="PDF">
                                            <span></span>
                                        </label>
                                        <label style="font-size: 15px;" class="col-md-8 col-from-label"><?php echo e(translate('PDF')); ?></label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 d-flex flex-row align-items-center">                                
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="checkbox" <?php echo e((in_array('JPEG', $typeData)) ? 'checked' : ''); ?> name="is_file_type[]" value="JPEG">
                                            <span></span>
                                        </label>
                                        <label style="font-size: 15px;" class="col-md-8 col-from-label"><?php echo e(translate('JPEG')); ?></label>
                                    </div>
                                    <div class="col-md-4 d-flex flex-row align-items-center">                                
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="checkbox" <?php echo e((in_array('TFF', $typeData)) ? 'checked' : ''); ?> name="is_file_type[]" value="TFF">
                                            <span></span>
                                        </label>
                                        <label style="font-size: 15px;" class="col-md-8 col-from-label"><?php echo e(translate('TFF')); ?></label>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                

<!--                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6"><?php echo e(translate('Product Shipping Cost')); ?></h5>
                    </div>
                    <div class="card-body">

                    </div>
                </div>-->

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6"><?php echo e(translate('PDF Specification')); ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="signinSrEmail"><?php echo e(translate('PDF Specification')); ?></label>
                            <div class="col-md-8">
                                <div class="input-group" data-toggle="aizuploader">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium"><?php echo e(translate('Browse')); ?></div>
                                    </div>
                                    <div class="form-control file-amount"><?php echo e(translate('Choose File')); ?></div>
                                    <input type="hidden" name="pdf" value="<?php echo e($product->pdf); ?>" class="selected-files">
                                </div>
                                <div class="file-preview box sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <?php if($product->is_refundable == 1): ?>
                    <style>
                        #hidden_content_refund{display:none;}
                    </style>
                <?php endif; ?>  
                

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6"><?php echo e(translate('SEO Meta Tags')); ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-lg-3 col-from-label"><?php echo e(translate('Meta Title')); ?></label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" name="meta_title" value="<?php echo e($product->meta_title); ?>" placeholder="<?php echo e(translate('Meta Title')); ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-from-label"><?php echo e(translate('Description')); ?></label>
                            <div class="col-lg-8">
                                <textarea name="meta_description" rows="8" class="form-control"><?php echo e($product->meta_description); ?></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="signinSrEmail"><?php echo e(translate('Meta Images')); ?></label>
                            <div class="col-md-8">
                                <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium"><?php echo e(translate('Browse')); ?></div>
                                    </div>
                                    <div class="form-control file-amount"><?php echo e(translate('Choose File')); ?></div>
                                    <input type="hidden" name="meta_img" value="<?php echo e($product->meta_img); ?>" class="selected-files">
                                </div>
                                <div class="file-preview box sm">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"><?php echo e(translate('Slug')); ?></label>
                            <div class="col-md-8">
                                <input type="text" placeholder="<?php echo e(translate('Slug')); ?>" id="slug" name="slug" value="<?php echo e($product->slug); ?>" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6" class="dropdown-toggle" data-toggle="collapse" data-target="#collapse_2">
                            <?php echo e(translate('Shipping Configuration')); ?>

                        </h5>
                    </div>
                    <div class="card-body collapse show" id="collapse_2">
                        <?php if(get_setting('shipping_type') == 'product_wise_shipping'): ?>
                        <div class="form-group row">
                            <label class="col-lg-6 col-from-label"><?php echo e(translate('Free Shipping')); ?></label>
                            <div class="col-lg-6">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="radio" name="shipping_type" value="free" <?php if($product->shipping_type == 'free'): ?> checked <?php endif; ?>>
                                    <span></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-6 col-from-label"><?php echo e(translate('Flat Rate')); ?></label>
                            <div class="col-lg-6">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="radio" name="shipping_type" value="flat_rate" <?php if($product->shipping_type == 'flat_rate'): ?> checked <?php endif; ?>>
                                    <span></span>
                                </label>
                            </div>
                        </div>

                        <div class="flat_rate_shipping_div" style="display: none">
                            <div class="form-group row">
                                <label class="col-lg-6 col-from-label"><?php echo e(translate('Shipping cost')); ?></label>
                                <div class="col-lg-6">
                                    <input type="number" lang="en" min="0" value="<?php echo e($product->shipping_cost); ?>" step="0.01" placeholder="<?php echo e(translate('Shipping cost')); ?>" name="flat_shipping_cost" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-6 col-from-label"><?php echo e(translate('Is Product Quantity Mulitiply')); ?></label>
                            <div class="col-md-6">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="checkbox" name="is_quantity_multiplied" value="1" <?php if($product->is_quantity_multiplied == 1): ?> checked <?php endif; ?>>
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <?php else: ?>
                        <p>
                            <?php echo e(translate('Product wise shipping cost is disable. Shipping cost is configured from here')); ?>

                            <a href="<?php echo e(route('shipping_configuration.index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['shipping_configuration.index','shipping_configuration.edit','shipping_configuration.update'])); ?>">
                                <span class="aiz-side-nav-text"><?php echo e(translate('Shipping Configuration')); ?></span>
                            </a>
                        </p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6"><?php echo e(translate('Low Stock Quantity Warning')); ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="name">
                                <?php echo e(translate('Quantity')); ?>

                            </label>
                            <input type="number" name="low_stock_quantity" value="<?php echo e($product->low_stock_quantity); ?>" min="0" step="1" class="form-control">
                        </div>
                    </div>
                </div>

                <!--<div class="card">-->
                <!--    <div class="card-header">-->
                <!--        <h5 class="mb-0 h6"><?php echo e(translate('Cash On Delivery')); ?></h5>-->
                <!--    </div>-->
                <!--    <div class="card-body">-->
                <!--        <?php if(get_setting('cash_payment') == '1'): ?>-->
                <!--        <div class="form-group row">-->
                <!--            <div class="col-md-12">-->
                <!--                <div class="form-group row">-->
                <!--                    <label class="col-md-6 col-from-label"><?php echo e(translate('Status')); ?></label>-->
                <!--                    <div class="col-md-6">-->
                <!--                        <label class="aiz-switch aiz-switch-success mb-0">-->
                <!--                            <input type="checkbox" name="cash_on_delivery" value="1" <?php if($product->cash_on_delivery == 1): ?> checked <?php endif; ?>>-->
                <!--                            <span></span>-->
                <!--                        </label>-->
                <!--                    </div>-->
                <!--                </div>-->
                <!--            </div>-->
                <!--        </div>-->
                <!--        <?php else: ?>-->
                <!--            <p>-->
                <!--                <?php echo e(translate('Cash On Delivery option is disabled. Activate this feature from here')); ?>-->
                <!--                <a href="<?php echo e(route('activation.index')); ?>" class="aiz-side-nav-link <?php echo e(areActiveRoutes(['shipping_configuration.index','shipping_configuration.edit','shipping_configuration.update'])); ?>">-->
                <!--                    <span class="aiz-side-nav-text"><?php echo e(translate('Cash Payment Activation')); ?></span>-->
                <!--                </a>-->
                <!--            </p>-->
                <!--        <?php endif; ?>-->
                <!--    </div>-->
                <!--</div>-->
                <!--<div class="card">-->
                <!--    <div class="card-header">-->
                <!--        <h5 class="mb-0 h6"><?php echo e(translate('Featured')); ?></h5>-->
                <!--    </div>-->
                <!--    <div class="card-body">-->
                <!--        <div class="form-group row">-->
                <!--            <div class="col-md-12">-->
                <!--                <div class="form-group row">-->
                <!--                    <label class="col-md-6 col-from-label"><?php echo e(translate('Status')); ?></label>-->
                <!--                    <div class="col-md-6">-->
                <!--                        <label class="aiz-switch aiz-switch-success mb-0">-->
                <!--                            <input type="checkbox" name="featured" value="1" <?php if($product->featured == 1): ?> checked <?php endif; ?>>-->
                <!--                            <span></span>-->
                <!--                        </label>-->
                <!--                    </div>-->
                <!--                </div>-->
                <!--            </div>-->
                <!--        </div>-->
                <!--    </div>-->
                <!--</div>-->

                <!--<div class="card">-->
                <!--    <div class="card-header">-->
                <!--        <h5 class="mb-0 h6"><?php echo e(translate('Todays Deal')); ?></h5>-->
                <!--    </div>-->
                <!--    <div class="card-body">-->
                <!--        <div class="form-group row">-->
                <!--            <div class="col-md-12">-->
                <!--                <div class="form-group row">-->
                <!--                    <label class="col-md-6 col-from-label"><?php echo e(translate('Status')); ?></label>-->
                <!--                    <div class="col-md-6">-->
                <!--                        <label class="aiz-switch aiz-switch-success mb-0">-->
                <!--                            <input type="checkbox" name="todays_deal" value="1" <?php if($product->todays_deal == 1): ?> checked <?php endif; ?>>-->
                <!--                            <span></span>-->
                <!--                        </label>-->
                <!--                    </div>-->
                <!--                </div>-->
                <!--            </div>-->
                <!--        </div>-->
                <!--    </div>-->
                <!--</div>-->

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6"><?php echo e(translate('Estimate Shipping Time')); ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="name">
                                <?php echo e(translate('Shipping Days')); ?>

                            </label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="est_shipping_days" value="<?php echo e($product->est_shipping_days); ?>" min="1" step="1" placeholder="<?php echo e(translate('Shipping Days')); ?>">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroupPrepend"><?php echo e(translate('Days')); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6"><?php echo e(translate('Return Days')); ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="name">
                                <?php echo e(translate('Return Days')); ?>

                            </label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="return_days" value="<?php echo e($product->return_days); ?>" min="1" step="1" placeholder="<?php echo e(translate('Return Days')); ?>">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroupPrepend"><?php echo e(translate('Days')); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6"><?php echo e(translate('Replace Days')); ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="name">
                                <?php echo e(translate('Replace Days')); ?>

                            </label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="replace_days" value="<?php echo e($product->replace_days); ?>" min="1" step="1" placeholder="<?php echo e(translate('Replace Days')); ?>">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroupPrepend"><?php echo e(translate('Days')); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6"><?php echo e(translate('VAT & Tax')); ?></h5>
                    </div>
                    <div class="card-body">
                        <?php $__currentLoopData = \App\Models\Tax::where('tax_status', 1)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tax): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label for="name">
                            <?php echo e($tax->name); ?>

                            <input type="hidden" value="<?php echo e($tax->id); ?>" name="tax_id[]">
                        </label>

                        <?php
                        $tax_amount = 0;
                        $tax_type = '';
                        foreach($tax->product_taxes as $row) {
                            if($product->id == $row->product_id) {
                                $tax_amount = $row->tax;
                                $tax_type = $row->tax_type;
                            }
                        }
                        ?>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <input type="number" lang="en" min="0" value="<?php echo e($tax_amount); ?>" step="0.01" placeholder="<?php echo e(translate('Tax')); ?>" name="tax[]" class="form-control" required>
                            </div>
                            <div class="form-group col-md-6">
                                <select class="form-control aiz-selectpicker" name="tax_type[]">
                                    <option value="amount" <?php if($tax_type == 'amount'): ?> selected <?php endif; ?>>
                                        <?php echo e(translate('Flat')); ?>

                                    </option>
                                    <option value="percent" <?php if($tax_type == 'percent'): ?> selected <?php endif; ?>>
                                        <?php echo e(translate('Percent')); ?>

                                    </option>
                                </select>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6"><?php echo e(translate('Shipping Information')); ?></h5>
                    </div>
                    <div class="card-body">
                        <label for="name">
                            <?php echo e(translate('Weight')); ?>

                        </label>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <input type="number" lang="en" min="0" value="<?php echo e($product->weight); ?>" placeholder="<?php echo e(translate('Weight')); ?>" name="weight" class="form-control" required>
                            </div>
                        </div>
                        <label for="name">
                            <?php echo e(translate('SKU')); ?>

                        </label>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <input type="text" lang="en" value="<?php echo e($product->sku); ?>" placeholder="<?php echo e(translate('SKU')); ?>" name="sku" class="form-control" required>
                            </div>
                        </div>
                        <label for="name">
                            <?php echo e(translate('HSN Code')); ?>

                        </label>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <input type="number" lang="en" min="0" value="<?php echo e($product->hsn_code); ?>" placeholder="<?php echo e(translate('HSN Code')); ?>" name="hsn_code" class="form-control" required>
                            </div>
                        </div>
                        <label for="name">
                            <?php echo e(translate('Shipment Length')); ?>

                        </label>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <input type="number" lang="en" min="0" value="<?php echo e($product->shipment_length); ?>" placeholder="<?php echo e(translate('Shipment Length')); ?>" name="shipment_length" class="form-control" required>
                            </div>
                        </div>
                        <label for="name">
                            <?php echo e(translate('Shipment Width')); ?>

                        </label>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <input type="number" lang="en" min="0" value="<?php echo e($product->shipment_width); ?>" placeholder="<?php echo e(translate('Shipment Width')); ?>" name="shipment_width" class="form-control" required>
                            </div>
                        </div>
                        <label for="name">
                            <?php echo e(translate('Shipment Height')); ?>

                        </label>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <input type="number" lang="en" min="0" value="<?php echo e($product->shipment_height); ?>" placeholder="<?php echo e(translate('Shipment Height')); ?>" name="shipment_height" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-12">
                <div class="mb-3 text-right">
                    <button type="submit" name="button" class="btn btn-info"><?php echo e(translate('Update Product')); ?></button>
                </div>
            </div>
        </div>
    </form>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

<script type="text/javascript">
    // Code cange by Brijesh on 18-fab-22 CR#2 - start
    function showDiv(val) {
        if(val.checked){
            
            $("#hidden_content").slideToggle("slow");
            // $("#hidden_content").css("display", "flex");
            
        } else {
            $("#hidden_content").slideToggle("slow");
        }
        
    }
    // Code cange by Brijesh on 18-fab-22 CR#2 - end


    // Code cange by Brijesh on 23-fab-22 CR#2 - start
    function showRefundDiv(val) {
        if(val.checked){
            $("#hidden_content_refund").slideToggle("slow");
        } else {
            $("#hidden_content_refund").slideToggle("slow");
        }
    }
    // Code cange by Brijesh on 23-fab-22 CR#2 - end
    $(document).ready(function (){
        show_hide_shipping_div();
    });

    $("[name=shipping_type]").on("change", function (){
        show_hide_shipping_div();
    });

    function show_hide_shipping_div() {
        var shipping_val = $("[name=shipping_type]:checked").val();

        $(".flat_rate_shipping_div").hide();

        if(shipping_val == 'flat_rate'){
            $(".flat_rate_shipping_div").show();
        }
    }

    function add_more_customer_choice_option(i, name){
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:"POST",
            url:'<?php echo e(route('products.add-more-choice-option')); ?>',
            data:{
               attribute_id: i
            },
            success: function(data) {
                var obj = JSON.parse(data);
                $('#customer_choice_options').append('\
                <div class="form-group row">\
                    <div class="col-md-3">\
                        <input type="hidden" name="choice_no[]" value="'+i+'">\
                        <input type="text" class="form-control" name="choice[]" value="'+name+'" placeholder="<?php echo e(translate('Choice Title')); ?>" readonly>\
                    </div>\
                    <div class="col-md-8">\
                        <select class="form-control aiz-selectpicker attribute_choice" data-live-search="true" name="choice_options_'+ i +'[]" multiple>\
                            '+obj+'\
                        </select>\
                    </div>\
                </div>');
                AIZ.plugins.bootstrapSelect('refresh');
           }
       });


    }

    $('input[name="colors_active"]').on('change', function() {
        if(!$('input[name="colors_active"]').is(':checked')){
            $('#colors').prop('disabled', true);
            AIZ.plugins.bootstrapSelect('refresh');
        }
        else{
            $('#colors').prop('disabled', false);
            AIZ.plugins.bootstrapSelect('refresh');
        }
        update_sku();
    });

    $(document).on("change", ".attribute_choice",function() {
        update_sku();
    });

    $('#colors').on('change', function() {
        update_sku();
    });

    function delete_row(em){
        $(em).closest('.form-group').remove();
        update_sku();
    }

    function delete_variant(em){
        $(em).closest('.variant').remove();
    }

    function update_sku(){
        $.ajax({
           type:"POST",
           url:'<?php echo e(route('products.sku_combination_edit')); ?>',
           data:$('#choice_form').serialize(),
           success: function(data){
                $('#sku_combination').html(data);
                AIZ.uploader.previewGenerate();
                AIZ.plugins.fooTable();
                if (data.length > 1) {
                    $('#show-hide-div').hide();
                }
                else {
                    $('#show-hide-div').show();
                }
           }
        });
    }

    AIZ.plugins.tagify();

    $(document).ready(function(){
        update_sku();

        $('.remove-files').on('click', function(){
            $(this).parents(".col-md-4").remove();
        });
    });

    $('#choice_attributes').on('change', function() {
        $.each($("#choice_attributes option:selected"), function(j, attribute){
            flag = false;
            $('input[name="choice_no[]"]').each(function(i, choice_no) {
                if($(attribute).val() == $(choice_no).val()){
                    flag = true;
                }
            });
            if(!flag){
                add_more_customer_choice_option($(attribute).val(), $(attribute).text());
            }
        });

        var str = <?php echo $product->attributes ?>;

        $.each(str, function(index, value){
            flag = false;
            $.each($("#choice_attributes option:selected"), function(j, attribute){
                if(value == $(attribute).val()){
                    flag = true;
                }
            });
            if(!flag){
                $('input[name="choice_no[]"][value="'+value+'"]').parent().parent().remove();
            }
        });

        update_sku();
    });

</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u985417933/domains/colebrooknow.com/public_html/admin/resources/views/backend/product/products/edit.blade.php ENDPATH**/ ?>