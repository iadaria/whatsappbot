<?php if(session('success')): ?> 
<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Операция успешно завершена!</strong> <?php echo e(session('success')); ?>

        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
<?php endif; ?><?php /**PATH C:\OSPanel\domains\localhost\whatsappbot\resources\views/layouts/_messages.blade.php ENDPATH**/ ?>