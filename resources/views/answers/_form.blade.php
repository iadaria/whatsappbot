@csrf
@php ( $types = array('file', 'location') )

<div class="form-group col-6">
    <label for="answer-answer_id">ID</label>
        <input type="text" name="answer_id" id="answer-answer_id" 
            value="{{ old('answer_id', isset($answer) ? $answer->answer_id : '') }}"
            class="form-control {{ $errors->has('answer_id') ? 'is-invalid' : '' }}">

    @if ($errors->has('answer_id'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('answer_id')}}</strong>
        </div>
    @endif
</div>

<div class="form-group col-12">
    <label for="type">Выберите тип команды</label>
    <select name="type" id="type" class="form-control">
        <option value="text" {{ old('type', isset($answer) ? $answer->type : null) == 'text' ? 'selected' : ''}}>Текстовое сообщение</option>
        <option value="file" {{ old('type', isset($answer) ? $answer->type : null) == 'file' ? 'selected' : ''}}>Файл</option>
        <option value="location" {{ old('type', isset($answer) ? $answer->type : null) == 'location' ? 'selected' : ''}}>Локация</option>
    </select>
</div>

<div class="form-group col-12 answer-text" style="{{ !in_array(old('type', isset($answer) ? $answer->type : null), $types) ? '' : 'display: none;' }}">
    <label for="answer-body">Текст для отображения пользователю</label>
    <textarea name="body" id="answer-body" rows="5"
        class="form-control {{ $errors->has('body') ? 'is-invalid' : '' }}">{{trim(old('body', isset($answer) ? $answer->body : '' ))}}</textarea>

    @if ($errors->has('body'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('body')}}</strong>
        </div>
    @endif
</div>

<div class="form-group col-12 answer-file" style="{{ old('type', isset($answer) ? $answer->type : null) == 'file' ? '' : 'display: none;' }}">
    <div id="label-file"  class="{{ $errors->has('filepath') ? 'is-invalid' : '' }}" >
        <strong>Загружен файл: {{ old('filename', isset($answer) ? $answer->filename : '') }}</strong>
    </div>
{{--     @if ($errors->has('filepath'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('filepath')}}</strong>
        </div>
    @endif   --}}
    <input type="file" name="file" id="file" class="form-control mb-2 {{ $errors->has('file') ? 'is-invalid' : '' }}">  
        @if ($errors->has('file'))
            <div class="invalid-feedback">
                <strong>{{ $errors->first('file')}}</strong>
            </div>
        @endif  
    <label for="caption">Подпись под файлом</label>  
    <input type="text" name="caption" id="caption" class="form-control" placeholder="Подпись под файлом"
        value="{{ old('caption', isset($answer) ? $answer->caption : '') }}"
        class="form-control {{ $errors->has('caption') ? 'is-invalid' : '' }}">

        @if ($errors->has('caption'))
            <div class="invalid-feedback">
                <strong>{{ $errors->first('caption')}}</strong>
            </div>
        @endif
</div>

<div class="form-group col-12 answer-location" style="{{ old('type', isset($answer) ? $answer->type : null) == 'location' ? '' : 'display: none;' }}">
    <input type="text" name="lat" id="lat" class="form-control mt-2 {{ $errors->has('lat') ? 'is-invalid' : '' }}" placeholder="Ширина *"
        value="{{ old('lat', isset($answer) ? $answer->lat : '') }}">

        @if ($errors->has('lat'))
            <div class="invalid-feedback">
                <strong>{{ $errors->first('lat')}}</strong>
            </div>
        @endif

    <input type="text" name="lng" id="lng" class="form-control mt-2 {{ $errors->has('lng') ? 'is-invalid' : '' }}" placeholder="Долгота *"
        value="{{ old('lng', isset($answer) ? $answer->lng : '') }}"
        class="form-control {{ $errors->has('lng') ? 'is-invalid' : '' }}">

        @if ($errors->has('lng'))
            <div class="invalid-feedback">
                <strong>{{ $errors->first('lng')}}</strong>
            </div>
        @endif

    <input type="text" name="address" id="address" class="form-control mt-2" placeholder="Текст под сообщением с локацией"
        value="{{ old('address', isset($answer) ? $answer->address : '') }}"
        class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}">

        @if ($errors->has('address'))
            <div class="invalid-feedback">
                <strong>{{ $errors->first('address')}}</strong>
            </div>
        @endif
</div>

<div class="form-group col answer-text"  style="{{ !in_array(old('type', isset($answer) ? $answer->type : null), $types) ? '' : 'display: none;' }}">
    <label for="need_send_to_email">Отправить на почту</label>
    {{-- <input type="hidden" name="need_send_to_email" value="0"> --}}
    <input type="checkbox"
        @if ( old('need_send_to_email', isset($answer) ? $answer->need_send_to_email : false) == true )
            checked
        @endif
        id="need_send_to_email" 
        name="need_send_to_email"
        value="1"
        >
</div>

<div class="form-group col-6 answer-text" style="{{ !in_array(old('type', isset($answer) ? $answer->type : null), $types) ? '' : 'display: none;' }}">
    <label for="answer-command">Команда</label>
    <input type="text" name="command" id="answer-command" 
        value="{{ old('command', isset($answer) ? $answer->command : '') }}"
        class="form-control {{ $errors->has('command') ? 'is-invalid' : '' }}">

    @if ($errors->has('command'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('command')}}</strong>
        </div>
    @endif                              
</div>

<div class="form-group col-12 answer-text" style="{{ !in_array(old('type', isset($answer) ? $answer->type : null), $types) ? '' : 'display: none;' }}">
    <label for="answer-answers">ID ответов</label>
        <input type="text" name="answers" id="answer-answers" 
        value="{{ old('answers', isset($answer) ? $answer->answers : '') }}"
        class="form-control {{ $errors->has('answers') ? 'is-invalid' : '' }}">

    @if ($errors->has('answers'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('answers')}}</strong>
        </div>
    @endif                              
</div>

<div class="form-group d-flex justify-content-center mt-3">
    <button type="submit" class="btn btn-outline-primary btn-lg">{{ $buttonText }}</button>
</div>