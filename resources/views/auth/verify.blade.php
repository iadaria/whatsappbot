@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Подтвердить email адрес</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            На ваш адрес электронной почты была отправлена новая ссылка для подтверждения
                        </div>
                    @endif
                    Прежде чем продолжить, пожалуйста, проверьте вашу электронную почту для проверки ссылки.
                    Если вы не получили письмо, <a href="{{ route('verification.resend') }}">нажмите здесь, чтобы выполнить другой запрос</a>.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
