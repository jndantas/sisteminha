@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Verifique seu e-mail') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('Um link de verificação está sendo enviado para seu e-mail') }}
                        </div>
                    @endif

                    {{ __('Por favor verifique seu e-mail.') }}
                    {{ __('Se não recebeu o link.') }}, <a href="{{ route('verification.resend') }}">{{ __('clique aqui para enviar novamente') }}</a>.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
