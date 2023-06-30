

<?php $__env->startSection('content'); ?>
<style>
    .message-right {
        text-align: right;
        background-color: #f1faff;
        max-width: 400px;
        border-radius: 0.475rem;
        padding: 10px;
        margin-left: auto;
        font-size: 15px;
    }
    .message-left {
    text-align: left;
    background-color: #f8f5ff;
    max-width: 400px;
    border-radius: 0.475rem;
    padding: 10px;
    margin-right: auto;
    font-size: 15px;
}
</style>
<div class="col-lg-10 mx-auto">
    <div class="card">
        <div class="card-header row gutters-5">
            <div class="text-center text-md-left">
                <h5 class="mb-md-0 h5"><?php echo e($ticket->subject); ?> #<?php echo e($ticket->code); ?></h5>
               <div class="mt-2">
                   <span> <?php echo e($ticket->user->name); ?> </span>
                   <span class="ml-2"> <?php echo e($ticket->created_at); ?> </span>
                   <span class="badge badge-inline badge-secondary ml-2 text-capitalize"> 
                       <?php echo e(translate($ticket->status)); ?> 
                   </span>
               </div>
            </div>
        </div>
        <div class="card-body">
            <div class="pad-top">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0">
                        <div class="row">
                            <div class="col-12">
                                <div class="media">
                                    <a class="media-left" href="#">
                                        <?php if($ticket->user->avatar_original != null): ?>
                                            <span class="avatar avatar-sm mr-3"><img src="<?php echo e(uploaded_asset($ticket->user->avatar_original)); ?>"></span>
                                        <?php else: ?>
                                            <span class="avatar avatar-sm mr-3"><img src="<?php echo e(static_asset('assets/img/avatar-place.png')); ?>"></span>
                                        <?php endif; ?>
                                    </a>
                                    <div class="media-body">
                                        <div class="">
                                            <span class="text-bold h6"><?php echo e($ticket->user->name); ?></span>
                                            <p class="text-muted text-sm fs-11"><?php echo e($ticket->created_at->diffForHumans()); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="message-left">
                                    <?php echo $ticket->details; ?>
                                    <br>
                                    <?php $__currentLoopData = (explode(",",$ticket->files)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php $file_detail = \App\Models\Upload::where('id', $file)->first(); ?>
                                        <?php if($file_detail != null): ?>
                                            <a href="<?php echo e(uploaded_asset($file)); ?>" download="" class="badge badge-lg badge-inline badge-light mb-1">
                                                <i class="las la-download text-muted"><?php echo e($file_detail->file_original_name.'.'.$file_detail->extension); ?></i>
                                            </a>
                                            <br>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                    </li>
                    <?php $__currentLoopData = $ticket->replies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticketreply): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($ticketreply->user_id == Auth::user()->id): ?>
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-12">
                                        <span class="float-right">
                                            <div class="media">
                                                <div class="media-body" style="text-align: end;">
                                                    <div class="">
                                                        <span class="text-bold h6"><?php echo e($ticketreply->user->name); ?></span>
                                                        <p class="text-muted text-sm fs-11"><?php echo e($ticketreply->created_at->diffForHumans()); ?></p>
                                                    </div>
                                                </div>
                                                <a class="media-left" href="#">
                                                    <?php if($ticketreply->user->avatar_original != null): ?>
                                                        <span class="avatar avatar-sm ml-3"><img src="<?php echo e(uploaded_asset($ticketreply->user->avatar_original)); ?>"></span>
                                                    <?php else: ?>
                                                        <span class="avatar avatar-sm ml-3"><img src="<?php echo e(static_asset('assets/img/avatar-place.png')); ?>"></span>
                                                    <?php endif; ?>
                                                </a>
                                                
                                            </div>
                                            <div class="message-right">
                                                <?php echo $ticketreply->details; ?>
            
                                                <div class="mt-3">
                                                <?php $__currentLoopData = (explode(",",$ticketreply->files)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php $file_detail = \App\Models\Upload::where('id', $file)->first(); ?>
                                                    <?php if($file_detail != null): ?>
                                                        <a href="<?php echo e(uploaded_asset($file)); ?>" download="" class="badge badge-lg badge-inline badge-light mb-1">
                                                            <i class="las la-paperclip mr-2"><?php echo e($file_detail->file_original_name.'.'.$file_detail->extension); ?></i>
                                                        </a>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            </div>    
                                        </span>
                                    </div>
                                </div>
                            </li>
                        <?php else: ?>  
                            <li class="list-group-item px-0">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="media">
                                            <a class="media-left" href="#">
                                                <?php if($ticketreply->user->avatar_original != null): ?>
                                                    <span class="avatar avatar-sm mr-3"><img src="<?php echo e(uploaded_asset($ticketreply->user->avatar_original)); ?>"></span>
                                                <?php else: ?>
                                                    <span class="avatar avatar-sm mr-3"><img src="<?php echo e(static_asset('assets/img/avatar-place.png')); ?>"></span>
                                                <?php endif; ?>
                                            </a>
                                            <div class="media-body">
                                                <div class="">
                                                    <span class="text-bold h6"><?php echo e($ticketreply->user->name); ?></span>
                                                    <p class="text-muted text-sm fs-11"><?php echo e($ticketreply->created_at->diffForHumans()); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="message-left">
                                            <?php echo $ticketreply->details; ?>

                                            <div class="mt-3">
                                            <?php $__currentLoopData = (explode(",",$ticketreply->files)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php $file_detail = \App\Models\Upload::where('id', $file)->first(); ?>
                                                <?php if($file_detail != null): ?>
                                                    <a href="<?php echo e(uploaded_asset($file)); ?>" download="" class="badge badge-lg badge-inline badge-light mb-1">
                                                        <i class="las la-paperclip mr-2"><?php echo e($file_detail->file_original_name.'.'.$file_detail->extension); ?></i>
                                                    </a>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
            <form action="<?php echo e(route('support_ticket.admin_store')); ?>" method="post" id="ticket-reply-form" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="ticket_id" value="<?php echo e($ticket->id); ?>" required>
                <input type="hidden" name="status" value="<?php echo e($ticket->status); ?>" required>
                <div class="form-group">
                    <textarea class="aiz-text-editor" data-buttons='[["font", ["bold", "underline", "italic"]],["para", ["ul", "ol"]],["view", ["undo","redo"]]]' name="reply" required></textarea>
                </div>
                <div class="form-group row">
                    <div class="col-md-12">
                        <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-soft-secondary font-weight-medium"><?php echo e(translate('Browse')); ?></div>
                            </div>
                            <div class="form-control file-amount"><?php echo e(translate('Choose File')); ?></div>
                            <input type="hidden" name="attachments" class="selected-files">
                        </div>
                        <div class="file-preview box sm">
                        </div>
                    </div>
                </div>
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-sm btn-dark" onclick="submit_reply('pending')">
                        <?php echo e(translate('Submit as')); ?> 
                        <strong>
                            <span class="text-capitalize"> 
                                <?php echo e(translate($ticket->status)); ?>

                            </span>
                        </strong>
                    </button>
                    <button type="submit" class="btn btn-icon btn-sm btn-dark" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"><i class="las la-angle-down"></i></button>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="#" onclick="submit_reply('open')"><?php echo e(translate('Submit as')); ?> <strong><?php echo e(translate('Open')); ?></strong></a>
                        <a class="dropdown-item" href="#" onclick="submit_reply('solved')"><?php echo e(translate('Submit as')); ?> <strong><?php echo e(translate('Solved')); ?></strong></a>
                      </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <table class="aiz-table" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th><?php echo e(translate('Order Code')); ?></th>
                        <th data-breakpoints="md"><?php echo e(translate('Num. of Products')); ?></th>
                        <th data-breakpoints="md"><?php echo e(translate('Customer')); ?></th>
                        <th data-breakpoints="md"><?php echo e(translate('Amount')); ?></th>
                        <th data-breakpoints="md"><?php echo e(translate('Delivery Status')); ?></th>
                        <th data-breakpoints="md"><?php echo e(translate('Payment Status')); ?></th>
                        <th data-breakpoints="md"><?php echo e(translate('Option')); ?></th>
                    </tr>
                </thead>
                <tbody>
                        
                        <?php $order_detail = \App\Models\Order::where('id', $ticket->product_id)->first(); ?>
                        <?php if($order_detail != null): ?>
                            <tr>
                                <td>#<?php echo e($order_detail->code); ?></td>
                                <td><?php echo e(count($order_detail->orderDetails)); ?></td>
                                <td><?php echo e($order_detail->user->name); ?></td>
                                <td><?php echo e(single_price($order_detail->grand_total)); ?></td>
                                <td><?php
                                    $status = $order_detail->delivery_status;
                                    if($order_detail->delivery_status == 'cancelled') {
                                        $status = '<span class="badge badge-inline badge-danger">'.translate('Cancel').'</span>';
                                    }
    
                                    ?>
                                    <?php echo $status; ?>

                                </td>
                                <td> <?php if($order_detail->payment_status == 'paid'): ?>
                                    <span class="badge badge-inline badge-success"><?php echo e(translate('Paid')); ?></span>
                                    <?php else: ?>
                                    <span class="badge badge-inline badge-danger"><?php echo e(translate('Unpaid')); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><a class="btn btn-soft-primary btn-sm" href="<?php echo e(route('cancel_order', encrypt($order_detail->id))); ?>" title="<?php echo e(translate('Order Cancel')); ?>">
                                        Order Cancel
                                    </a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script type="text/javascript">
        function submit_reply(status){
            $('input[name=status]').val(status);
            if($('textarea[name=reply]').val().length > 0){
                $('#ticket-reply-form').submit();
            }
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\colebrook-admin\resources\views/backend/support/support_tickets/show.blade.php ENDPATH**/ ?>