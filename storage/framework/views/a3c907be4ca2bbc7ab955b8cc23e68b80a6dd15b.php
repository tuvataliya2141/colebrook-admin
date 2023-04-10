

<?php $__env->startSection('content'); ?>

    <div class="col-lg-6  mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6"><?php echo e(translate('Profile')); ?></h5>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo e(route('profile.update', Auth::user()->id)); ?>" method="POST" enctype="multipart/form-data">
                    <input name="_method" type="hidden" value="PATCH">
                	<?php echo csrf_field(); ?>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="name"><?php echo e(translate('Name')); ?></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="<?php echo e(translate('Name')); ?>" name="name" value="<?php echo e(Auth::user()->name); ?>" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="name"><?php echo e(translate('Email')); ?></label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" placeholder="<?php echo e(translate('Email')); ?>" name="email" value="<?php echo e(Auth::user()->email); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="new_password"><?php echo e(translate('New Password')); ?></label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" placeholder="<?php echo e(translate('New Password')); ?>" name="new_password">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="confirm_password"><?php echo e(translate('Confirm Password')); ?></label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" placeholder="<?php echo e(translate('Confirm Password')); ?>" name="confirm_password">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="signinSrEmail"><?php echo e(translate('Avatar')); ?> <small>(90x90)</small></label>
                        <div class="col-md-9">
                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium"><?php echo e(translate('Browse')); ?></div>
                                </div>
                                <div class="form-control file-amount"><?php echo e(translate('Choose File')); ?></div>
                                <input type="hidden" name="avatar" class="selected-files" value="<?php echo e(Auth::user()->avatar_original); ?>">
                            </div>
                            <div class="file-preview box sm">
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary"><?php echo e(translate('Save')); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\colebrookAdmin\resources\views/backend/admin_profile/index.blade.php ENDPATH**/ ?>