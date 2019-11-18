<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <?php echo $__env->make('layouts._messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div class="card">
                <div class="card-header d-flex">
                    Сбросить пароль и !логин
                    <div class="ml-auto">
                        <a href="<?php echo e(url()->previous()); ?>" class="btn btn-outline-secondary">Отмена</a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo e(route('resetpassword')); ?>">
                        <?php echo csrf_field(); ?>

                       

                        <div class="form-group row align-items-center">
                            <label for="email" class="col-md-4 col-form-label text-md-right">
                                Укажите e-mail адрес в качестве логина
                            </label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control
                                 <?php if ($errors->has('email')) :
if (isset($message)) { $messageCache = $message; }
$message = $errors->first('email'); ?> is-invalid <?php unset($message);
if (isset($messageCache)) { $message = $messageCache; }
endif; ?>" 
                                    name="email" value="<?php echo e(old('email', isset($user) ? $user->email : '')); ?>"
                                    oninvalid="this.setCustomValidity('Введите корректный email')">

                                <?php if ($errors->has('email')) :
if (isset($message)) { $messageCache = $message; }
$message = $errors->first('email'); ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                <?php unset($message);
if (isset($messageCache)) { $message = $messageCache; }
endif; ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">
                                Новый пароль
                            </label>

                            <div class="col-md-6">
                                <input id="new_password" type="password" class="form-control 
                                    <?php if ($errors->has('new_password')) :
if (isset($message)) { $messageCache = $message; }
$message = $errors->first('new_password'); ?> is-invalid <?php unset($message);
if (isset($messageCache)) { $message = $messageCache; }
endif; ?>" 
                                    name="new_password">

                                <?php if ($errors->has('new_password')) :
if (isset($message)) { $messageCache = $message; }
$message = $errors->first('new_password'); ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                <?php unset($message);
if (isset($messageCache)) { $message = $messageCache; }
endif; ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">
                                Повторите пароль
                            </label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" 
                                name="new_password_confirmation">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">Сбросить пароль и логин</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\localhost\whatsappbot\resources\views/auth/passwords/reset.blade.php ENDPATH**/ ?>