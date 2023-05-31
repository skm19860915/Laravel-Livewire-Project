<?php $__env->startComponent('mail::message'); ?>
<h1>Dear <?php echo e($name); ?></h1>
<hr>
<span><?php echo e($body); ?></span>
<br>
Thanks,<br>
<?php echo e($location_name); ?>

<?php echo $__env->renderComponent(); ?>
<?php /**PATH D:\GithubRepository\pryapus\resources\views/mail/contact-email.blade.php ENDPATH**/ ?>