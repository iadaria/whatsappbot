@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
            @include('layouts._messages')
            <div class="card col-md-11">
                <div class="card-body">
                    <div class="card-title">
                        <div class="d-flex align-items-center">
                            <h2>Полученные/отправленные сообщения</h2>
                            <div class="ml-auto">
                                    <form class="form-delete" method="post" 
                                    action="{{ route('delmessages') }}">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit" 
                                        class="btn btn-sm btn-outline-danger" 
                                        onclick="return confirm('Are you sure?')">Удалить все сообщения
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <hr>
  
                    <div class="row">
                        <table  id="table"
                            class="table"
                                data-toggle="table"
                                data-search="true"
                                data-filter-control="true" 
                                data-show-export="true"
                                data-click-to-select="true">
                            <thead>
                                <tr>
                                    <th data-field="id" data-filter-control="input" data-sortable="true" scope="col-auto">ID</th>
                                    <th data-field="chatId" data-filter-control="input" data-sortable="true" scope="col-auto">chatId</th>
                                    <th data-field="created_at" data-filter-control="input" data-sortable="true" scope="col-auto">Дата отправления</th>
                                    <th data-field="type" data-filter-control="input" data-sortable="true" scope="col-auto">Тип</th>
                                    <th data-field="senderName" data-filter-control="input" data-sortable="true" scope="col-auto">Имя отправителя</th>
                                    <th data-field="command" data-filter-control="input" data-sortable=true" scope="col-auto">Ком. от польз</th>
                                    <th data-field="body" data-filter-control="input" data-sortable=true" scope="col-auto">Текст от пользователя</th>
                                    <th data-field="answerBot" data-filter-control="input" data-sortable="true" scope="col">Ответ полученный пользователем</th>
                                    <th data-field="need_send_to_email" data-filter-control="input" data-sortable="true" scope="col-auto">Нужно email</th>
                                    <th data-field="was_sent_to_email" data-filter-control="input" data-sortable="true" scope="col-auto">Отправлен email</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($messages as $message)
                                <tr>
                                    <td>{{ $message->id }}</td>
                                    <td>{!! $message->chatId !!}</td>
                                    <td>{{ $message->created_at }}</td>
                                    <td>{{ $message->type }}</td>
                                    <td>{{ $message->senderName }}</td>
                                    <td>{{ $message->command }}</td>
                                    <td>{{ $message->body }}</td>
                                    <td>{!! $message->answerBot !!}</td>
                                    <td>{{ $message->need_send_to_email }}</td>
                                    <td>{{ $message->was_sent_to_email }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>
    </div>
@endsection
<footer>
    <link href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.10.0/bootstrap-table.min.css" rel="stylesheet">
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.10.0/bootstrap-table.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.9.1/extensions/editable/bootstrap-table-editable.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.9.1/extensions/filter-control/bootstrap-table-filter-control.js"></script>
    <script type="text/javascript" src="//rawgit.com/hhurz/tableExport.jquery.plugin/master/tableExport.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.9.1/extensions/export/bootstrap-table-export.js"></script>
</footer>
