@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @include('layouts._messages')
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h2>Профиль</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-auto p-5">
                        <img src="{{asset('img/avatar.png') }}" alt="" width="100">
                    </div>
                    <div class="col">
                        <div class="card-body">
                            <form action="{{ route('user.update', $user->id ) }}" method="post">
                                {{ method_field('PUT') }}
                                @csrf        
                                <div class="form-group col-md-6">
                                    <label for="name">Имя</label>
                                        <input type="text" name="name" id="name" 
                                        value="{{ old('name', isset($user) ? $user->name : '') }}"
                                        class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}">
        
                                    @if ($errors->has('name'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('name')}}</strong>
                                        </div>
                                    @endif
                                </div>

                                <div class="input-group col-md-10 mb-2">                                      
                                    <a href="{{ route('resetpasswordform') }}" 
                                            class="btn btn-primary" role="button">Сбросить пароль и логин</a>              
                                </div>
        
                                <div class="form-group col-md-10">
                                    <label for="emails_to_send">Имена почтовых ящиков для отправки писем</label>
                                    <input type="text" name="emails_to_send" id="emails_to_send" 
                                        value="{{ old('emails_to_send', isset($user) ? $user->emails_to_send : '') }}"
                                        class="form-control {{ $errors->has('emails_to_send') ? 'is-invalid' : '' }}">
        
                                    @if ($errors->has('emails_to_send'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('emails_to_send')}}</strong>
                                        </div>
                                    @endif
                                </div>
        
                                <div class="form-group col-md-6">
                                    <label for="mail_from_name">От чьего имени</label>
                                    <input type="text" name="mail_from_name" id="mail_from_name" 
                                        value="{{ old('mail_from_name', config('mail.from.name')) }}"
                                        class="form-control {{ $errors->has('mail_from_name') ? 'is-invalid' : '' }}">
        
                                    @if ($errors->has('mail_from_name'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('mail_from_name')}}</strong>
                                        </div>
                                    @endif
                                </div>
        
                                <div class="form-group col-md-10">
                                    <label for="whatsapp_api_url">Whatsapp Api Url</label>
                                    <input type="text" name="whatsapp_api_url" id="whatsapp_api_url" 
                                        value="{{ old('whatsapp_api_url', config('value.chatapiurl')) }}"
                                        class="form-control {{ $errors->has('whatsapp_api_url') ? 'is-invalid' : '' }}">
        
                                    @if ($errors->has('whatsapp_api_url'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('whatsapp_api_url')}}</strong>
                                        </div>
                                    @endif
                                </div>
                            
                                <div class="form-group col-md-10">
                                    <label for="whatsapp_token">Whatsapp token</label>
                                    <input type="text" name="whatsapp_token" id="whatsapp_token" 
                                        value="{{ old('whatsapp_token', config('value.chatapitoken')) }}"
                                        class="form-control {{ $errors->has('whatsapp_token') ? 'is-invalid' : '' }}">
        
                                    @if ($errors->has('whatsapp_token'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('whatsapp_token') }}</strong>
                                        </div>
                                    @endif
                                </div>

                                <div class="form-group col-md-10">
                                    <label for="whatsapp_token">сhatId для теста</label>
                                    <input type="text" name="chatid" id="chatid" 
                                        value="{{ old('chatid', config('value.chatid')) }}"
                                        class="form-control {{ $errors->has('chatid') ? 'is-invalid' : '' }}">
        
                                    @if ($errors->has('chatid'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('chatid') }}</strong>
                                        </div>
                                    @endif
                                </div>
        
                                <div class="d-flex col-md-10 justify-content-between">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success">Обновить</button>
                                    </div>
    
                                    <div class="form-group">
                                        <a href="{{ route('resetwebhook') }}" class="btn btn-outline-secondary">Обновить webhook</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>
@endsection