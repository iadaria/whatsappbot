<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <?php echo $__env->make('layouts._messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h2>Профиль</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-auto p-5">
                        <img src="<?php echo e(asset('img/avatar.png')); ?>" alt="" width="100">
                    </div>
                    <div class="col">
                        <div class="card-body">
                            <form action="<?php echo e(route('user.update', $user->id )); ?>" method="post">
                                <?php echo e(method_field('PUT')); ?>

                                <?php echo csrf_field(); ?>        
                                <div class="form-group col-md-6">
                                    <label for="name">Имя</label>
                                        <input type="text" name="name" id="name" 
                                        value="<?php echo e(old('name', isset($user) ? $user->name : '')); ?>"
                                        class="form-control <?php echo e($errors->has('name') ? 'is-invalid' : ''); ?>">
        
                                    <?php if($errors->has('name')): ?>
                                        <div class="invalid-feedback">
                                            <strong><?php echo e($errors->first('name')); ?></strong>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="input-group col-md-10 mb-2">                                      
                                    <a href="<?php echo e(route('resetpasswordform')); ?>" 
                                            class="btn btn-primary" role="button">Сбросить пароль и логин</a>              
                                </div>
        
                                <div class="form-group col-md-10">
                                    <label for="emails_to_send">Имена почтовых ящиков для отправки писем</label>
                                    <input type="text" name="emails_to_send" id="emails_to_send" 
                                        value="<?php echo e(old('emails_to_send', isset($user) ? $user->emails_to_send : '')); ?>"
                                        class="form-control <?php echo e($errors->has('emails_to_send') ? 'is-invalid' : ''); ?>">
        
                                    <?php if($errors->has('emails_to_send')): ?>
                                        <div class="invalid-feedback">
                                            <strong><?php echo e($errors->first('emails_to_send')); ?></strong>
                                        </div>
                                    <?php endif; ?>
                                </div>
        
                                <div class="form-group col-md-6">
                                    <label for="mail_from_name">От чьего имени</label>
                                    <input type="text" name="mail_from_name" id="mail_from_name" 
                                        value="<?php echo e(old('mail_from_name', config('mail.from.name'))); ?>"
                                        class="form-control <?php echo e($errors->has('mail_from_name') ? 'is-invalid' : ''); ?>">
        
                                    <?php if($errors->has('mail_from_name')): ?>
                                        <div class="invalid-feedback">
                                            <strong><?php echo e($errors->first('mail_from_name')); ?></strong>
                                        </div>
                                    <?php endif; ?>
                                </div>
        
                                <div class="form-group col-md-10">
                                    <label for="whatsapp_api_url">Whatsapp Api Url</label>
                                    <input type="text" name="whatsapp_api_url" id="whatsapp_api_url" 
                                        value="<?php echo e(old('whatsapp_api_url', config('value.chatapiurl'))); ?>"
                                        class="form-control <?php echo e($errors->has('whatsapp_api_url') ? 'is-invalid' : ''); ?>">
        
                                    <?php if($errors->has('whatsapp_api_url')): ?>
                                        <div class="invalid-feedback">
                                            <strong><?php echo e($errors->first('whatsapp_api_url')); ?></strong>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            
                                <div class="form-group col-md-10">
                                    <label for="whatsapp_token">Whatsapp token</label>
                                    <input type="text" name="whatsapp_token" id="whatsapp_token" 
                                        value="<?php echo e(old('whatsapp_token', config('value.chatapitoken'))); ?>"
                                        class="form-control <?php echo e($errors->has('whatsapp_token') ? 'is-invalid' : ''); ?>">
        
                                    <?php if($errors->has('whatsapp_token')): ?>
                                        <div class="invalid-feedback">
                                            <strong><?php echo e($errors->first('whatsapp_token')); ?></strong>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group col-md-10">
                                    <label for="whatsapp_token">сhatId для теста</label>
                                    <input type="text" name="chatid" id="chatid" 
                                        value="<?php echo e(old('chatid', config('value.chatid'))); ?>"
                                        class="form-control <?php echo e($errors->has('chatid') ? 'is-invalid' : ''); ?>">
        
                                    <?php if($errors->has('chatid')): ?>
                                        <div class="invalid-feedback">
                                            <strong><?php echo e($errors->first('chatid')); ?></strong>
                                        </div>
                                    <?php endif; ?>
                                </div>
        
                                <div class="d-flex col-md-10 justify-content-between">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success">Обновить</button>
                                    </div>
    
                                    <div class="form-group">
                                        <a href="<?php echo e(route('resetwebhook')); ?>" class="btn btn-outline-secondary">Обновить webhook</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\localhost\whatsappbot\resources\views/auth/profile.blade.php ENDPATH**/ ?>