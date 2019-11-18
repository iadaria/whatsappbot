<style>
    td input {
        width: 100%;
    }
    .fa {
        cursor: pointer;
    }

</style>
<?php $__env->startSection('content'); ?>

<div class="container-fluid">
    <div class="row justify-content-md-center">
        <div class="col-lg-12 col-xl-10 ">
            <?php echo $__env->make('layouts._messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div class="card">                              
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h2>Необработанные сообщения</h2>
                        <?php if( isset($newmessages) && count($newmessages) > 0): ?>
                        <div class="ml-auto d-flex align-items-center">
                            <form class="form-show-all mr-2" 
                                action="<?php echo e(route('savemessages')); ?>">
                                <?php echo csrf_field(); ?>
                                <button type="submit"
                                    class="btn btn-sm btn-outline-secondary">Перенести в архив
                                </button>
                            </form>
                            <form class="form-delete" method="POST"
                                action="<?php echo e(route('delnewmessages')); ?>">
                                <?php echo method_field('DELETE'); ?>
                                <?php echo csrf_field(); ?>
                                <button type="submit" 
                                    class="btn btn-sm btn-outline-danger" 
                                    onclick="return confirm('Удалить сообщения?')">Удалить все сообщения
                                </button>
                            </form>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card-body">
                    <table id="table" class="table">
                        <thead>
                            <tr> 
                                <th scope="col">ID</th>
                                <th scope="col">chatId</th>
                                <th scope="col">Дата отправления</th>
                                <th scope="col">Тип</th>
                                <th scope="col">Имя отправителя</th>
                                <th scope="col">Команда от польз</th>
                                <th scope="col">Текст от пользователя</th>
                                <th scope="col">Ответ полученный пользователем</th>
                                <th scope="col">Отправлен email</th>
                                <th scope="col">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(isset($newmessages)): ?>
                            <?php $__currentLoopData = $newmessages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($message->id); ?></td>
                                <td><?php echo $message->chatId; ?></td>
                                <td><?php echo e($message->created_at); ?></td>
                                <td><?php echo e($message->type); ?></td>
                                <td><?php echo e($message->senderName); ?></td>
                                <td><?php echo e($message->command); ?></td>
                                <td><?php echo $message->body_html; ?></td>
                                <td><?php echo $message->answerBot; ?></td>
                                <td><?php echo e($message->was_sent_to_email); ?></td>
                                <td>&nbsp;</td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
                        <?php echo e($newmessages->links()); ?>

                    </div>
                    <?php endif; ?>               
                </div>       
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\localhost\whatsappbot\resources\views/messages/new.blade.php ENDPATH**/ ?>