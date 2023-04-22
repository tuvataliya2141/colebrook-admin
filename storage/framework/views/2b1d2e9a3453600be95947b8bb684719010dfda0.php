

<?php $__env->startSection('content'); ?>

<div class="card">
    <div class="card-header">
        <h1 class="h2 fs-16 mb-0"><?php echo e(translate('Order Details')); ?></h1>
    </div>
    <div class="card-body">
        <div class="row gutters-5 mb-20">
            <?php
                $delivery_status = $order->delivery_status;
                $payment_status = $order->payment_status;
            ?>

            <div class="col-md-3 ml-auto">
                <label for="update_payment_status"><?php echo e(translate('Payment Status')); ?></label>
                <select class="form-control aiz-selectpicker"  data-minimum-results-for-search="Infinity" id="update_payment_status">
                    <option value="unpaid" <?php if($payment_status == 'unpaid'): ?> selected <?php endif; ?>><?php echo e(translate('Unpaid')); ?></option>
                    <option value="paid" <?php if($payment_status == 'paid'): ?> selected <?php endif; ?>><?php echo e(translate('Paid')); ?></option>
                </select>
            </div>
            <div class="col-md-3 ml-auto">
                <label for="update_delivery_status"><?php echo e(translate('Delivery Status')); ?></label>
                <?php if($delivery_status != 'delivered' && $delivery_status != 'cancelled'): ?>
                    <select class="form-control aiz-selectpicker"  data-minimum-results-for-search="Infinity" id="update_delivery_status">
                        <option value="pending" <?php if($delivery_status == 'pending'): ?> selected <?php endif; ?>><?php echo e(translate('Pending')); ?></option>
                        <option value="confirmed" <?php if($delivery_status == 'confirmed'): ?> selected <?php endif; ?>><?php echo e(translate('Confirmed')); ?></option>
                        <option value="picked_up" <?php if($delivery_status == 'picked_up'): ?> selected <?php endif; ?>><?php echo e(translate('Picked Up')); ?></option>
                        <option value="on_the_way" <?php if($delivery_status == 'on_the_way'): ?> selected <?php endif; ?>><?php echo e(translate('On The Way')); ?></option>
                        <option value="delivered" <?php if($delivery_status == 'delivered'): ?> selected <?php endif; ?>><?php echo e(translate('Delivered')); ?></option>
                        <option value="cancelled" <?php if($delivery_status == 'cancelled'): ?> selected <?php endif; ?>><?php echo e(translate('Cancel')); ?></option>
                    </select>
                <?php else: ?>
                    <input type="text" class="form-control" value="<?php echo e($delivery_status); ?>" disabled>
                <?php endif; ?>
            </div>
            <div class="col-md-3 ml-auto">
                <label for="update_tracking_code"><?php echo e(translate('Tracking Code (optional)')); ?></label>
                <input type="text" class="form-control" id="update_tracking_code" value="<?php echo e($order->tracking_code); ?>">
            </div>
            <div class="col text-center text-md-left">
            </div>
        </div>
        <div class="mb-3">
            
        </div>

        <!-- added and modifed by Arun on 04-Dec-21-->
        <div class="row gutters-5 mt-20">
            <div class="col-md-6 text-center text-md-left">
                <table width=100%>
                    <tr>
                        <td>
                            <address>
                                <strong class="text-main">Recipient Info</strong><br><br>
                                <strong class="text-main"><?php echo e(json_decode($order->shipping_address)->name); ?></strong><br>
                                <?php echo e(json_decode($order->shipping_address)->email); ?><br>
                                <?php echo e(json_decode($order->shipping_address)->phone); ?><br>
                                <?php echo e(json_decode($order->shipping_address)->address); ?>, <?php echo e(json_decode($order->shipping_address)->city); ?>, <?php echo e(json_decode($order->shipping_address)->postal_code); ?><br>
                                <?php echo e(json_decode($order->shipping_address)->country); ?>

                            </address>
                        </td>
                        <td></td>
                        <td></td>
                        <td valign=top>
                            <strong class="text-main">Sender Info</strong><br><br>
                            <strong class="text-main">Kingoodie</strong><br>
                            <?php echo e(get_setting('contact_address')); ?><br>
                        </td>
                        
                    </tr>
                </table>
                
                
            </div>
            <div class="col-md-4">
                <table width=80%>
                    <tbody>
                        <tr>
                            <td class="text-main text-bold"><?php echo e(translate('Order #')); ?></td>
                            <td class="text-right text-info text-bold">	<?php echo e($order->code); ?></td>
                        </tr>
                        <tr>
                            <td class="text-main text-bold"><?php echo e(translate('Order Status')); ?></td>
                            <td class="text-right">
                                <?php if($delivery_status == 'delivered'): ?>
                                <span class="badge badge-inline badge-success"><?php echo e(translate(ucfirst(str_replace('_', ' ', $delivery_status)))); ?></span>
                                <?php else: ?>
                                <span class="badge badge-inline badge-info"><?php echo e(translate(ucfirst(str_replace('_', ' ', $delivery_status)))); ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-main text-bold"><?php echo e(translate('Order Date')); ?>	</td>
                            <td class="text-right"><?php echo e(date('d-m-Y h:i A', $order->date)); ?></td>
                        </tr>
                        <tr>
                            <td class="text-main text-bold">
                                <?php echo e(translate('Total amount')); ?>

                            </td>
                            <td class="text-right">
                                <?php echo e(single_price($order->grand_total)); ?>

                            </td>
                        </tr>
                        <tr>
                            <td class="text-main text-bold"><?php echo e(translate('Payment method')); ?></td>
                            <td class="text-right"><?php echo e(ucfirst(str_replace('_', ' ', $order->payment_type))); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <hr class="new-section-sm bord-no">
        <div class="row">
            <div class="col-lg-12 table-responsive">
                <table class="table table-bordered aiz-table invoice-summary">
                    <thead>
                        <tr class="bg-trans-dark">
                            <th data-breakpoints="lg" class="min-col">#</th>
                            <th width="10%"><?php echo e(translate('Photo')); ?></th>
                            <th class="text-uppercase"><?php echo e(translate('Description')); ?></th>
                            <th data-breakpoints="lg" class="text-uppercase"><?php echo e(translate('Delivery Type')); ?></th>
                            <th data-breakpoints="lg" class="min-col text-center text-uppercase"><?php echo e(translate('Qty')); ?></th>
                            <th data-breakpoints="lg" class="min-col text-center text-uppercase"><?php echo e(translate('Price')); ?></th>
                            <th data-breakpoints="lg" class="min-col text-right text-uppercase"><?php echo e(translate('Total')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $order->orderDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $orderDetail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($key+1); ?></td>
                            <td>
                                <?php if($orderDetail->product != null): ?>
                                    <img height="50" src="<?php echo e(uploaded_asset($orderDetail->product->thumbnail_img)); ?>">                              
                                <?php else: ?>
                                    <strong><?php echo e(translate('N/A')); ?></strong>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($orderDetail->product != null): ?>
                                    <strong><?php echo e($orderDetail->variation); ?> </strong>
                                                                   
                                <?php else: ?>
                                    <strong><?php echo e(translate('Product Unavailable')); ?></strong>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($orderDetail->shipping_type != null && $orderDetail->shipping_type == 'home_delivery'): ?>
                                <?php echo e(translate('Home Delivery')); ?>

                                <?php elseif($orderDetail->shipping_type == 'pickup_point'): ?>

                                <?php if($orderDetail->pickup_point != null): ?>
                                <?php echo e($orderDetail->pickup_point->getTranslation('name')); ?> (<?php echo e(translate('Pickup Point')); ?>)
                                <?php else: ?>
                                <?php echo e(translate('Pickup Point')); ?>

                                <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td class="text-center"><?php echo e($orderDetail->quantity); ?></td>
                            <td class="text-center"><?php echo e(single_price($orderDetail->price/$orderDetail->quantity)); ?></td>
                            <td class="text-center"><?php echo e(single_price($orderDetail->price)); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="clearfix float-right">
            <table class="table">
                <tbody>
                    <tr>
                        <td>
                            <strong class="text-muted"><?php echo e(translate('Sub Total')); ?> :</strong>
                        </td>
                        <td>
                            <?php echo e(single_price($order->orderDetails->sum('price'))); ?>

                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong class="text-muted"><?php echo e(translate('Tax')); ?> :</strong>
                        </td>
                        <td>
                            <?php echo e(single_price($order->orderDetails->sum('tax'))); ?>

                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong class="text-muted"><?php echo e(translate('Shipping')); ?> :</strong>
                        </td>
                        <td>
                            <?php echo e(single_price($order->orderDetails->sum('shipping_cost'))); ?>

                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong class="text-muted"><?php echo e(translate('Coupon')); ?> :</strong>
                        </td>
                        <td>
                            <?php echo e(single_price($order->coupon_discount)); ?>

                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong class="text-muted"><?php echo e(translate('TOTAL')); ?> :</strong>
                        </td>
                        <td class="text-muted h5">
                            <?php echo e(single_price($order->grand_total)); ?>

                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="text-right no-print">
                <a href="<?php echo e(route('invoice.download', $order->id)); ?>" type="button" class="btn btn-icon btn-light"><i class="las la-print"></i></a>
            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script type="text/javascript">
        $('#update_delivery_status').on('change', function(){
            var order_id = <?php echo e($order->id); ?>;
            var status = $('#update_delivery_status').val();
            $.post('<?php echo e(route('orders.update_delivery_status')); ?>', {
                _token:'<?php echo e(@csrf_token()); ?>',
                order_id:order_id,
                status:status
            }, function(data){
                AIZ.plugins.notify('success', '<?php echo e(translate('Delivery status has been updated')); ?>');
            });
        });

        $('#update_payment_status').on('change', function(){
            var order_id = <?php echo e($order->id); ?>;
            var status = $('#update_payment_status').val();
            $.post('<?php echo e(route('orders.update_payment_status')); ?>', {_token:'<?php echo e(@csrf_token()); ?>',order_id:order_id,status:status}, function(data){
                AIZ.plugins.notify('success', '<?php echo e(translate('Payment status has been updated')); ?>');
            });
        });

        $('#update_tracking_code').on('change', function(){
            var order_id = <?php echo e($order->id); ?>;
            var tracking_code = $('#update_tracking_code').val();
            $.post('<?php echo e(route('orders.update_tracking_code')); ?>', {_token:'<?php echo e(@csrf_token()); ?>',order_id:order_id,tracking_code:tracking_code}, function(data){
                AIZ.plugins.notify('success', '<?php echo e(translate('Order tracking code has been updated')); ?>');
            });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\colebrook-admin\resources\views/backend/sales/all_orders/show.blade.php ENDPATH**/ ?>