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
                        <h2>Полученные/отправленные сообщения</h2>
                        <div class="ml-auto d-flex align-items-center">
                            <form class="form-show-all mr-2" 
                                action="<?php echo e(route('showmessages')); ?>">
                                <?php echo method_field('POST'); ?>
                                <?php echo csrf_field(); ?>
                                <button type="submit" 
                                    class="btn btn-sm btn-outline-secondary">Показать все сообщения
                                </button>
                            </form>
                            <?php if(isset($messages) && count($messages) > 0): ?>
                            <form class="form-show-all mr-2" 
                                action="<?php echo e(route('exportexcel')); ?>">
                                <?php echo method_field('GET'); ?>
                                <?php echo csrf_field(); ?>
                                <button type="submit" 
                                    class="btn btn-sm btn-outline-success">Excel
                                </button>
                            </form>

                            <form class="form-delete" method="post" 
                                action="<?php echo e(route('delmessages')); ?>">
                                <?php echo method_field('DELETE'); ?>
                                <?php echo csrf_field(); ?>
                                <button type="submit" 
                                    class="btn btn-sm btn-outline-danger" 
                                    onclick="return confirm('Удалить все сообщения?')">Удалить все сообщения
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <form id="form-search" action="<?php echo e(route('search')); ?>" method="post"
                        autocomplete="off"
                        enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
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
                            
                                <tr>                      
                                    <td><input type="text" name="message_id"></td>
                                    <td><input type="text" name="chatId"></td>
                                    <td><input type="text" name="created_at"></td>
                                    <td><input type="text" name="type"></td>
                                    <td><input type="text" name="senderName"></td>
                                    <td><input type="text" name="command"></td>
                                    <td><input type="text" name="body"></td>
                                    <td>
                                        <input type="text" name="answerBot" placeholder="Введите список ID ответов бота через запятую, например: 1, 2">
                                    </td>
                                    <td><input type="text" name="was_sent_to_email"></td>
                                    <td class="align-middle">
                                        <button type="submit" form="form-search"
                                            class="bnt btn-search">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php if(isset($messages)): ?>
                                <?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($message->id); ?></td>
                                    <td><?php echo e($message->chatId); ?></td>
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
                            <?php echo e($messages->links()); ?>

                        </div>
                        <?php endif; ?>
                    </form>                   
                </div>       
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\localhost\whatsappbot\resources\views/messages/index.blade.php ENDPATH**/ ?>