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
                        <h2>Полученные/отправленные сообщения</h2>
                        <div class="ml-auto d-flex align-items-center">
                            <form class="form-show-all mr-2" 
                                action="{{ route('showmessages') }}">
                                @method('POST')
                                @csrf
                                <button type="submit" 
                                    class="btn btn-sm btn-outline-secondary">Показать все сообщения
                                </button>
                            </form>
                            @if (isset($messages) && count($messages) > 0)
                            <form class="form-show-all mr-2" 
                                action="{{ route('exportexcel') }}">
                                @method('GET')
                                @csrf
                                <button type="submit" 
                                    class="btn btn-sm btn-outline-success">Excel
                                </button>
                            </form>

                            <form class="form-delete" method="post" 
                                action="{{ route('delmessages') }}">
                                @method('DELETE')
                                @csrf
                                <button type="submit" 
                                    class="btn btn-sm btn-outline-danger" 
                                    onclick="return confirm('Удалить все сообщения?')">Удалить все сообщения
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <form id="form-search" action="{{ route('search') }}" method="post"
                        autocomplete="off"
                        enctype="multipart/form-data">
                        @csrf
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
                            {{--    <input type="hidden" name="_token" value="{!! csrf_field() !!}"> --}}
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
                                @if (isset($messages))
                                @foreach ($messages as $message)
                                <tr>
                                    <td>{{ $message->id }}</td>
                                    <td>{{ $message->chatId }}</td>
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
                            {{ $messages->links()}}
                        </div>
                        @endif
                    </form>                   
                </div>       
            </div>
        </div>
    </div>
</div>
@endsection
