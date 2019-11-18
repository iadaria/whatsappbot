{{-- @extends('layouts.app')

@section('content') --}}
<div class="card">
    <div class="card-body">
        <div class="card-title">
            <h3>Редактирование команды</h3>
        </div>
        <hr>
        <form action="{{ route('answers.update', $answer->id) }}" method="post">
                @csrf
                @method('PATCH') 
                <div class="form-group col-3">
                    <label for="answer-answer_id">Идентификатор текста</label>
                        <input type="text" name="answer_id" id="answer-answer_id" 
                        value="{{ old('answer_id', $answer->answer_id) }}"
                        class="form-control {{ $errors->has('answer_id') ? 'is-invalid' : '' }}">
                
                    @if ($errors->has('answer_id'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('answer_id')}}</strong>
                        </div>
                    @endif
                </div>
                
                <div class="form-group col-12">
                    <label for="answer-body">Текст для отображения пользователю</label>
                    <textarea name="body" id="answer-body"
                        class="form-control {{ $errors->has('body') ? 'is-invalid' : '' }}">{{trim(old('body', $answer->body))}}</textarea>
                
                    @if ($errors->has('body'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('body')}}</strong>
                        </div>
                    @endif
                </div>
                
                <div class="form-group col-6">
                    <label for="answer-answers">ID ответов</label>
                        <input type="text" name="answers" id="answer-answers" 
                        value="{{ old('answer_answers', $answer->answer_answers) }}"
                        class="form-control {{ $errors->has('answers') ? 'is-invalid' : '' }}">
                
                    @if ($errors->has('answers'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('answers')}}</strong>
                        </div>
                    @endif                              
                </div>

                <div class="form-group col">
                    <button type="submit" class="btn btn-outline-primary btn-lg">Обновить</button>
                </div>
        </form>
    </div>
</div>
{{-- @endsection --}}