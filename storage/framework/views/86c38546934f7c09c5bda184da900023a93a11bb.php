

<?php $__env->startSection('content'); ?>

<div class="card">
    <form class="" id="sort_support" action="" method="GET">
        <div class="card-header row gutters-5">
            <div class="col text-center text-md-left">
                <h5 class="mb-md-0 h6"><?php echo e(translate('Support Desk')); ?></h5>
            </div>
            <div class="col-md-2">
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" id="search" name="search"<?php if(isset($sort_search)): ?> value="<?php echo e($sort_search); ?>" <?php endif; ?> placeholder="<?php echo e(translate('Type ticket code & Enter')); ?>">
                </div>
            </div>
        </div>
    </from>

    <div class="card-body">
        <table class="aiz-table" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th data-breakpoints="lg"><?php echo e(translate('Ticket ID')); ?></th>
                    <th data-breakpoints="lg"><?php echo e(translate('Sending Date')); ?></th>
                    <th data-breakpoints="lg"><?php echo e(translate('User')); ?></th>
                    <th data-breakpoints="lg"><?php echo e(translate('Status')); ?></th>
                    <th data-breakpoints="lg"><?php echo e(translate('Last reply')); ?></th>
                    <th class="text-right"><?php echo e(translate('Options')); ?></th>
                </tr>
            </thead>
            <tbody>
                    <?php $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($ticket->user != null): ?>
                        <tr>
                            <td>#<?php echo e($ticket->code); ?></td>
                            <td><?php echo e($ticket->created_at); ?> <?php if($ticket->viewed == 0): ?> <span class="badge badge-inline badge-info"><?php echo e(translate('New')); ?></span> <?php endif; ?></td>
                            <td><?php echo e($ticket->user->name); ?></td>
                            <td>
                                <?php if($ticket->status == 'pending'): ?>
                                    <span class="badge badge-inline badge-danger"><?php echo e(translate('Pending')); ?></span>
                                <?php elseif($ticket->status == 'open'): ?>
                                    <span class="badge badge-inline badge-secondary"><?php echo e(translate('Open')); ?></span>
                                <?php else: ?>
                                    <span class="badge badge-inline badge-success"><?php echo e(translate('Solved')); ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if(count($ticket->ticketreplies) > 0): ?>
                                    <?php echo e($ticket->ticketreplies->last()->created_at); ?>

                                <?php else: ?>
                                    <?php echo e($ticket->created_at); ?>

                                <?php endif; ?>
                            </td>
                            <td class="text-right">
                                <a href="<?php echo e(route('support_ticket.admin_show', encrypt($ticket->id))); ?>" class="btn btn-soft-primary btn-icon btn-circle btn-sm" title="<?php echo e(translate('View Details')); ?>">
                                    <i class="las la-eye"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <div class="clearfix">
            <div class="pull-right">
                <?php echo e($tickets->appends(request()->input())->links()); ?>

            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\colebrookAdmin\resources\views/backend/support/support_tickets/index.blade.php ENDPATH**/ ?>