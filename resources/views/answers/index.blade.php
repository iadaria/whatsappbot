@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-text-bot">
                <div class="card-body">
                    <div class="card-title">
                        <h3>Текст бота</h3>
                    </div>
                    <hr>
                    <form action="{{ route('answers.store') }}" method="POST" enctype="multipart/form-data">  
                        @include ('answers._form', [
                            'buttonText' => "Добавить команду бота", 
                            'newID' => ++$lastID])
                    </form>
                </div>
            </div>
        
            @include('layouts._messages')
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
                    @foreach ($answers as $answer)
                        <tr>
                            <td><strong>{{ $answer->answer_id }}</strong></td>
                            <td>{!! $answer->body_html !!}</td>
                            <td><strong>{{ $answer->command }}</strong></td>
                            <td>{{ $answer->need_send_to_email }}</td>
                            <td>{{ $answer->answers }}</td>
                            <td class="d-flex flex-row">
                                <a href="{{ route('answers.edit', $answer->id) }}" 
                                    class="btn btn-sm btn-outline-info mr-2">Изменить</a>
{{--                                 <form class="form-change" action ="{{ route('answers.edit', $answer->id) }}" method="post" id="form_change">
                                    <button type="submit" 
                                        class="btn btn-sm btn-outline-info" 
                                        form="form_change">Изменить
                                    </button>
                                </from>     --}}

                                <form class="form-delete" method="post" 
                                    action="{{ route('answers.destroy', $answer->id) }}" id="form_delete_{{ $answer->id }}">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit" 
                                        class="btn btn-sm btn-outline-danger" 
                                        form="form_delete_{{ $answer->id }}"
                                        onclick="return confirm('Удалить команду с ID {{ $answer->answer_id }} ?')">Удалить
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
