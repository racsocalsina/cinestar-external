@php
    $img = asset('assets/img/unlock.png');
@endphp

@extends('emails.layout', ['tradeName' => $data['trade_name']])
@section('content')

    <div style="text-align: center">

        <img src="{{$img}}" width="100px" height="100px" />
        <h1 style="text-align: center">¿Olvidaste tu contraseña?</h1>
        <p style="text-align: center">No hay necesidad de preocuparse, {{$data['params_user']}}! Para restablecer su contraseña, haga clic en el botón de abajo.</p>
        <br>

        @if(strtolower($data['trade_name']) == strtolower(\App\Enums\TradeName::CINESTAR))
            <a href="{{ $data['params_url'] }}" class="button" style="background-color: #d9000d; border-bottom: 8px solid #d9000d; border-left: 18px solid #d9000d; border-right: 18px solid #d9000d; border-top: 8px solid #d9000d;">Restablecer Contraseña</a>
        @else
            <a href="{{ $data['params_url'] }}" class="button" style="background-color: #171198; border-bottom: 8px solid #171198; border-left: 18px solid #171198; border-right: 18px solid #171198; border-top: 8px solid #171198;">Restablecer Contraseña</a>
        @endif
    </div>
@endsection

