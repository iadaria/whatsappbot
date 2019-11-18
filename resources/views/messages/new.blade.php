@extends('layouts.app')
<style>
    td input {
        width: 100%;
    }
    .fa {
        cursor: pointer;
    }

</style>
@section('content')

<div class="container-fluid">
    <div class="row justify-content-md-center">
        <div class="col-lg-12 col-xl-10 ">
            @include('layouts._messages')
            <div class="card">                              
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h2>Необработанные сообщения</h2>
                        @if( isset($newmessages) && count($newmessages) > 0)
                        <div class="ml-auto d-flex align-items-center">
                            <form class="form-show-all mr-2" 
                                action="{{ route('savemessages') }}">
                                @csrf
                                <button type="submit"
                                    class="btn btn-sm btn-outline-secondary">Перенести в архив
                                </button>
                            </form>
                            <form class="form-delete" method="POST"
                                action="{{ route('delnewmessages') }}">
                                @method('DELETE')
                                @csrf
                                <button type="submit" 
                                    class="btn btn-sm btn-outline-danger" 
                                    onclick="return confirm('Удалить сообщения?')">Удалить все сообщения
                                </button>
                            </form>
                        </div>
                        @endif
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
                            @if (isset($newmessages))
                            @foreach ($newmessages as $message)
                            <tr>
                                <td>{{ $message->id }}</td>
                                <td>{!! $message->chatId !!}</td>
                                <td>{{ $message->created_at }}</td>
                                <td>{{ $message->type }}</td>
                                <td>{{ $message->senderName }}</td>
                                <td>{{ $message->command }}</td>
                                <td>{!! $message->body_html !!}</td>
                                <td>{!! $message->answerBot !!}</td>
                                <td>{{ $message->was_sent_to_email }}</td>
                                <td>&nbsp;</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
                        {{ $newmessages->links()}}
                    </div>
                    @endif               
                </div>       
            </div>
        </div>
    </div>
</div>
@endsection
