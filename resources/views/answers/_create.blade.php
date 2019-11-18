<div class="row mt-4">
        <div class="col-md-12">
            <div class="position-fixed">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            <h3>Текст строки бота</h3>
                        </div>
                        <hr>
                        <form action="{{ route('answers.store') }}" method="POST">
                                @csrf
                        
                                <div class="form-group col-6">
                                    <label for="answer-answer_id">ID</label>
                                        <input type="text" name="answer_id" id="answer-answer_id" 
                                            class="form-control {{ $errors->has('answer_id') ? 'is-invalid' : '' }}">
                                
                                    @if ($errors->has('answer_id'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('answer_id')}}</strong>
                                        </div>
                                    @endif                              
                                </div>
    
                                <div class="form-group col-12">
                                    <label for="answer-body">Текст для отображения пользователю</label>
                                    <textarea name="body" id="answer-body" rows="5" class="form-control {{ $errors->has('body') ? 'is-invalid' : ''}}">
                                    </textarea>
                                
                                    @if ($errors->has('body'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('body')}}</strong>
                                        </div>
                                    @endif
                                </div>
    
                                
                                <div class="form-group col-6">
                                    <label for="answer-command">Команда</label>
                                        <input type="text" name="command" id="answer-command" 
                                        class="form-control {{ $errors->has('command') ? 'is-invalid' : '' }}">
                                
                                    @if ($errors->has('command'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('command')}}</strong>
                                        </div>
                                    @endif                              
                                </div>
    
                                <div class="form-group col-6">
                                    <label for="answer-answers">ID ответов</label>
                                        <input type="text" name="answers" id="answer-answers" 
                                        class="form-control {{ $errors->has('answers') ? 'is-invalid' : '' }}">
                                
                                    @if ($errors->has('answers'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('answers')}}</strong>
                                        </div>
                                    @endif                              
                                 </div>
    
                                <div class="form-group col">
                                    <button type="submit" class="btn btn-outline-primary btn-lg">{{ $buttonText }}</button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>