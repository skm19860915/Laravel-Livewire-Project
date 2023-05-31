<!doctype html>
<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1">

    <title><?php echo e(config('app.name')); ?></title>


    <!-- <link rel="stylesheet" type="text/css" href="/css/ace-font.css"> -->

    <link href="<?php echo e(mix('css/app.css')); ?>" rel="stylesheet">

    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo e(asset('/apple-touch-icon.png')); ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo e(asset('/favicon-32x32.png')); ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo e(asset('/favicon-16x16.png')); ?>">
    <link rel="manifest" href="<?php echo e(asset('/site.webmanifest')); ?> ">

  </head>

  <body>

    <main>
      <?php echo $__env->yieldContent('content'); ?>
    </main>

    <script src="<?php echo e(mix('/js/manifest.js')); ?>" defer></script>
    <script src="<?php echo e(mix('/js/vendor.js')); ?>" defer></script>
    <script src="<?php echo e(mix('/js/app.js')); ?>" defer></script>

  </body>

</html>
<?php /**PATH D:\GithubRepository\pryapus\resources\views/layouts/guest.blade.php ENDPATH**/ ?>