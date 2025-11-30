<?php ($title = '404 - Halaman tidak ditemukan'); ?>


<?php $__env->startSection('content'); ?>
  <div class="text-center py-12">
    <h1 class="text-3xl font-semibold mb-4">404 - Halaman tidak ditemukan</h1>
    <p class="text-gray-600 dark:text-gray-300 mb-6">Maaf, halaman yang Anda cari tidak tersedia.</p>
    <a href="<?php echo e(route('dashboard')); ?>" class="btn-primary">Kembali ke Dashboard</a>
  </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\TODOLIST\TODOLIST\resources\views/errors/404.blade.php ENDPATH**/ ?>