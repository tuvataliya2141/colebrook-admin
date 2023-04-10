

<?php $__env->startSection('content'); ?>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6"><?php echo e(translate('All Subscribers')); ?></h5>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th data-breakpoints="lg">#</th>
                    <th><?php echo e(translate('Email')); ?></th>
                    <th data-breakpoints="lg"><?php echo e(translate('Date')); ?></th>
                    <th data-breakpoints="lg" class="text-right"><?php echo e(translate('Options')); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $subscribers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $subscriber): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <tr>
                      <td><?php echo e(($key+1) + ($subscribers->currentPage() - 1)*$subscribers->perPage()); ?></td>
				              <td><div class="text-truncate"><?php echo e($subscriber->email); ?></div></td>
                      <td><?php echo e(date('d-m-Y', strtotime($subscriber->created_at))); ?></td>
                      <td class="text-right">
                          <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="<?php echo e(route('subscriber.destroy', $subscriber->id)); ?>" title="<?php echo e(translate('Delete')); ?>">
                              <i class="las la-trash"></i>
                          </a>
                      </td>
                  </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <div class="clearfix">
            <div class="pull-right">
                <?php echo e($subscribers->appends(request()->input())->links()); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('modal'); ?>
    <?php echo $__env->make('modals.delete_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\colebrookAdmin\resources\views/backend/marketing/subscribers/index.blade.php ENDPATH**/ ?>