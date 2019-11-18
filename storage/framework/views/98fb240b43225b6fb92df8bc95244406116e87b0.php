<?php $__env->startSection('content'); ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-text-bot">
                <div class="card-body">
                    <div class="card-title">
                        <h3>Текст бота</h3>
                    </div>
                    <hr>
                    <form action="<?php echo e(route('answers.store')); ?>" method="POST" enctype="multipart/form-data">  
                        <?php echo $__env->make('answers._form', [
                            'buttonText' => "Добавить команду бота", 
                            'newID' => ++$lastID], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </form>
                </div>
            </div>
        
            <?php echo $__env->make('layouts._messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div class="commands">
                <table class="table table-answers">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Текст</th>
                            <th scope="col">Команда</th>
                            <th scope="col">email</th>
                            <th scope="col">Ответы</th>
                            <th scope="col">Изменить/Удалить</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $__currentLoopData = $answers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $answer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><strong><?php echo e($answer->answer_id); ?></strong></td>
                            <td><?php echo $answer->body_html; ?></td>
                            <td><strong><?php echo e($answer->command); ?></strong></td>
                            <td><?php echo e($answer->need_send_to_email); ?></td>
                            <td><?php echo e($answer->answers); ?></td>
                            <td class="d-flex flex-row">
                                <a href="<?php echo e(route('answers.edit', $answer->id)); ?>" 
                                    class="btn btn-sm btn-outline-info mr-2">Изменить</a>


                                <form class="form-delete" method="post" 
                                    action="<?php echo e(route('answers.destroy', $answer->id)); ?>" id="form_delete_<?php echo e($answer->id); ?>">
                                    <?php echo method_field('DELETE'); ?>
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" 
                                        class="btn btn-sm btn-outline-danger" 
                                        form="form_delete_<?php echo e($answer->id); ?>"
                                        onclick="return confirm('Удалить команду с ID <?php echo e($answer->answer_id); ?> ?')">Удалить
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\localhost\whatsappbot\resources\views/answers/index.blade.php ENDPATH**/ ?>