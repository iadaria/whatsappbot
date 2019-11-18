@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="position-fixed">
                <div class="card">
                        <div class="card-body">
                            <div class="card-title">
                                <div class="d-flex align-items-center">
                                    <h3>Редактирование</h3>
                                    <div class="ml-auto">
                                        <a href="{{ route('answers.index') }}" class="btn btn-outline-secondary">Отмена</a>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <form action="{{ route('answers.update', $answer->id) }}" method="POST" enctype="multipart/form-data">
                                {{ method_field('PUT') }}                           
                {{--                 {{ method_field('POST') }} --}}
                                @include (
                                    'answers._form', [
                                    'buttonText' => "Обновить команду"])  
                            </form>
                        </div>
                    </div>
                </div>
            <div class="commands">
                <table class="table">
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
                                <form class="form-delete_{{ $answer->id }}" method="post" 
                                    action="{{ route('answers.destroy', $answer->id) }}">
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