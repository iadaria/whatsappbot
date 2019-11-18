<?php echo csrf_field(); ?>
<?php ( $types = array('file', 'location') ); ?>

<div class="form-group col-6">
    <label for="answer-answer_id">ID</label>
        <input type="text" name="answer_id" id="answer-answer_id" 
            value="<?php echo e(old('answer_id', isset($answer) ? $answer->answer_id : '')); ?>"
            class="form-control <?php echo e($errors->has('answer_id') ? 'is-invalid' : ''); ?>">

    <?php if($errors->has('answer_id')): ?>
        <div class="invalid-feedback">
            <strong><?php echo e($errors->first('answer_id')); ?></strong>
        </div>
    <?php endif; ?>
</div>

<div class="form-group col-12">
    <label for="type">Выберите тип команды</label>
    <select name="type" id="type" class="form-control">
        <option value="text" <?php echo e(old('type', isset($answer) ? $answer->type : null) == 'text' ? 'selected' : ''); ?>>Текстовое сообщение</option>
        <option value="file" <?php echo e(old('type', isset($answer) ? $answer->type : null) == 'file' ? 'selected' : ''); ?>>Файл</option>
        <option value="location" <?php echo e(old('type', isset($answer) ? $answer->type : null) == 'location' ? 'selected' : ''); ?>>Локация</option>
    </select>
</div>

<div class="form-group col-12 answer-text" style="<?php echo e(!in_array(old('type', isset($answer) ? $answer->type : null), $types) ? '' : 'display: none;'); ?>">
    <label for="answer-body">Текст для отображения пользователю</label>
    <textarea name="body" id="answer-body" rows="5"
        class="form-control <?php echo e($errors->has('body') ? 'is-invalid' : ''); ?>"><?php echo e(trim(old('body', isset($answer) ? $answer->body : '' ))); ?></textarea>

    <?php if($errors->has('body')): ?>
        <div class="invalid-feedback">
            <strong><?php echo e($errors->first('body')); ?></strong>
        </div>
    <?php endif; ?>
</div>

<div class="form-group col-12 answer-file" style="<?php echo e(old('type', isset($answer) ? $answer->type : null) == 'file' ? '' : 'display: none;'); ?>">
    <div id="label-file"  class="<?php echo e($errors->has('filepath') ? 'is-invalid' : ''); ?>" >
        <strong>Загружен файл: <?php echo e(old('filename', isset($answer) ? $answer->filename : '')); ?></strong>
    </div>

    <input type="file" name="file" id="file" class="form-control mb-2 <?php echo e($errors->has('file') ? 'is-invalid' : ''); ?>">  
        <?php if($errors->has('file')): ?>
            <div class="invalid-feedback">
                <strong><?php echo e($errors->first('file')); ?></strong>
            </div>
        <?php endif; ?>  
    <label for="caption">Подпись под файлом</label>  
    <input type="text" name="caption" id="caption" class="form-control" placeholder="Подпись под файлом"
        value="<?php echo e(old('caption', isset($answer) ? $answer->caption : '')); ?>"
        class="form-control <?php echo e($errors->has('caption') ? 'is-invalid' : ''); ?>">

        <?php if($errors->has('caption')): ?>
            <div class="invalid-feedback">
                <strong><?php echo e($errors->first('caption')); ?></strong>
            </div>
        <?php endif; ?>
</div>

<div class="form-group col-12 answer-location" style="<?php echo e(old('type', isset($answer) ? $answer->type : null) == 'location' ? '' : 'display: none;'); ?>">
    <input type="text" name="lat" id="lat" class="form-control mt-2 <?php echo e($errors->has('lat') ? 'is-invalid' : ''); ?>" placeholder="Ширина *"
        value="<?php echo e(old('lat', isset($answer) ? $answer->lat : '')); ?>">

        <?php if($errors->has('lat')): ?>
            <div class="invalid-feedback">
                <strong><?php echo e($errors->first('lat')); ?></strong>
            </div>
        <?php endif; ?>

    <input type="text" name="lng" id="lng" class="form-control mt-2 <?php echo e($errors->has('lng') ? 'is-invalid' : ''); ?>" placeholder="Долгота *"
        value="<?php echo e(old('lng', isset($answer) ? $answer->lng : '')); ?>"
        class="form-control <?php echo e($errors->has('lng') ? 'is-invalid' : ''); ?>">

        <?php if($errors->has('lng')): ?>
            <div class="invalid-feedback">
                <strong><?php echo e($errors->first('lng')); ?></strong>
            </div>
        <?php endif; ?>

    <input type="text" name="address" id="address" class="form-control mt-2" placeholder="Текст под сообщением с локацией"
        value="<?php echo e(old('address', isset($answer) ? $answer->address : '')); ?>"
        class="form-control <?php echo e($errors->has('address') ? 'is-invalid' : ''); ?>">

        <?php if($errors->has('address')): ?>
            <div class="invalid-feedback">
                <strong><?php echo e($errors->first('address')); ?></strong>
            </div>
        <?php endif; ?>
</div>

<div class="form-group col answer-text"  style="<?php echo e(!in_array(old('type', isset($answer) ? $answer->type : null), $types) ? '' : 'display: none;'); ?>">
    <label for="need_send_to_email">Отправить на почту</label>
    
    <input type="checkbox"
        <?php if( old('need_send_to_email', isset($answer) ? $answer->need_send_to_email : false) == true ): ?>
            checked
        <?php endif; ?>
        id="need_send_to_email" 
        name="need_send_to_email"
        value="1"
        >
</div>

<div class="form-group col-6 answer-text" style="<?php echo e(!in_array(old('type', isset($answer) ? $answer->type : null), $types) ? '' : 'display: none;'); ?>">
    <label for="answer-command">Команда</label>
    <input type="text" name="command" id="answer-command" 
        value="<?php echo e(old('command', isset($answer) ? $answer->command : '')); ?>"
        class="form-control <?php echo e($errors->has('command') ? 'is-invalid' : ''); ?>">

    <?php if($errors->has('command')): ?>
        <div class="invalid-feedback">
            <strong><?php echo e($errors->first('command')); ?></strong>
        </div>
    <?php endif; ?>                              
</div>

<div class="form-group col-12 answer-text" style="<?php echo e(!in_array(old('type', isset($answer) ? $answer->type : null), $types) ? '' : 'display: none;'); ?>">
    <label for="answer-answers">ID ответов</label>
        <input type="text" name="answers" id="answer-answers" 
        value="<?php echo e(old('answers', isset($answer) ? $answer->answers : '')); ?>"
        class="form-control <?php echo e($errors->has('answers') ? 'is-invalid' : ''); ?>">

    <?php if($errors->has('answers')): ?>
        <div class="invalid-feedback">
            <strong><?php echo e($errors->first('answers')); ?></strong>
        </div>
    <?php endif; ?>                              
</div>

<div class="form-group d-flex justify-content-center mt-3">
    <button type="submit" class="btn btn-outline-primary btn-lg"><?php echo e($buttonText); ?></button>
</div><?php /**PATH C:\OSPanel\domains\localhost\whatsappbot\resources\views/answers/_form.blade.php ENDPATH**/ ?>