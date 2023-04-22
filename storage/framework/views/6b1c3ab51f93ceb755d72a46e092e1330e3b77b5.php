<h1><?php echo e(translate('Ticket')); ?></h1>
<p><?php echo e($content); ?></p>
<p><b><?php echo e(translate('Sender')); ?>: </b><?php echo e($sender); ?></p>
<p>
	<b><?php echo e(translate('Details')); ?>:</b>
	<br>
	<?php echo $details; ?>
</p>
<a class="btn btn-primary btn-md" href="<?php echo e($link); ?>"><?php echo e(translate('See ticket')); ?></a>
<?php /**PATH D:\xampp\htdocs\colebrook-admin\resources\views/emails/support.blade.php ENDPATH**/ ?>