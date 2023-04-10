

<?php $__env->startSection('content'); ?>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6"><?php echo e(translate('Default Language')); ?></h5>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo e(route('env_key_update.update')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="form-group row">
                        <div class="col-lg-3">
                            <label class="col-from-label"><?php echo e(translate('Default Language')); ?></label>
                        </div>
                        <input type="hidden" name="types[]" value="DEFAULT_LANGUAGE">
                        <div class="col-lg-6">
                            <select class="form-control demo-select2-placeholder" name="DEFAULT_LANGUAGE">
                                <?php $__currentLoopData = \App\Models\Language::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($language->code); ?>" <?php if(env('DEFAULT_LANGUAGE') == $language->code) echo 'selected'?> ><?php echo e($language->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <button type="submit" class="btn btn-info"><?php echo e(translate('Save')); ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6"><?php echo e(translate('Import App Translations')); ?></h5>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo e(route('app-translations.import')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="form-group row">
                        <div class="col-lg-3">
                            <label class="col-from-label"><?php echo e(translate('English Trasnlation File')); ?></label>
                        </div>
                        <div class="col-lg-6">
                            <div class="custom-file">
                                <label class="custom-file-label">
                                    <input type="file" id="lang_file" name="lang_file"  class="custom-file-input" required>
                                    <span class="custom-file-name"><?php echo e(translate('Choose app_en.arb file')); ?></span>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <button type="submit" class="btn btn-info"><?php echo e(translate('Import')); ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="align-items-center">
		<div class="text-md-right">
			<a href="<?php echo e(route('languages.create')); ?>" class="btn btn-circle btn-info">
				<span><?php echo e(translate('Add New Language')); ?></span>
			</a>
		</div>
	</div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6"><?php echo e(translate('Language')); ?></h5>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th data-breakpoints="lg">#</th>
                    <th><?php echo e(translate('Name')); ?></th>
                    <th data-breakpoints="lg"><?php echo e(translate('Code')); ?></th>
                    <th data-breakpoints="lg"><?php echo e(translate('Flutter App Lang Code')); ?></th>
                    <th data-breakpoints="lg"><?php echo e(translate('RTL')); ?></th>
                    <th class="text-right" width="15%"><?php echo e(translate('Options')); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $i = 1;
                ?>
                <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e(($key+1) + ($languages->currentPage() - 1)*$languages->perPage()); ?></td>
                        <td><?php echo e($language->name); ?></td>
                        <td><?php echo e($language->code); ?></td>
                        <td><?php echo e($language->app_lang_code); ?></td>
                        <td><label class="aiz-switch aiz-switch-success mb-0">
                            <input onchange="update_rtl_status(this)" value="<?php echo e($language->id); ?>" type="checkbox" <?php if($language->rtl == 1) echo "checked";?> >
                            <span class="slider round"></span></label>
                        </td>
                        <td class="text-right">
                            <a class="btn btn-soft-info btn-icon btn-circle btn-sm" href="<?php echo e(route('languages.show', $language->id)); ?>" title="<?php echo e(translate('Translation')); ?>">
                                <i class="las la-language"></i>
                            </a>
                            <a class="btn btn-soft-warning btn-icon btn-circle btn-sm" href="<?php echo e(route('app-translations.show', $language->id)); ?>" title="<?php echo e(translate('App Translation')); ?>">
                                <i class="las la-language"></i>
                            </a>
                            <a class="btn btn-soft-success btn-icon btn-circle btn-sm" href="<?php echo e(route('app-translations.export', $language->id)); ?>" title="<?php echo e(translate('arb File Export')); ?>" download>
                                <i class="las la-download"></i>
                            </a>
                            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="<?php echo e(route('languages.edit', $language->id)); ?>" title="<?php echo e(translate('Edit')); ?>">
                                <i class="las la-edit"></i>
                            </a>
                            <?php if($language->code != 'en'): ?>
                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="<?php echo e(route('languages.destroy', $language->id)); ?>" title="<?php echo e(translate('Delete')); ?>">
                                    <i class="las la-trash"></i>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php
                        $i++;
                    ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <div class="aiz-pagination">
            <?php echo e($languages->appends(request()->input())->links()); ?>

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('modal'); ?>
    <?php echo $__env->make('modals.delete_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('script'); ?>
    <script type="text/javascript">
        function update_rtl_status(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('<?php echo e(route('languages.update_rtl_status')); ?>', {_token:'<?php echo e(csrf_token()); ?>', id:el.value, status:status}, function(data){
                if(data == 1){
                    location.reload();
                }
                else{
                    AIZ.plugins.notify('danger', '<?php echo e(translate('Something went wrong')); ?>');
                }
            });
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\colebrookAdmin\resources\views/backend/setup_configurations/languages/index.blade.php ENDPATH**/ ?>