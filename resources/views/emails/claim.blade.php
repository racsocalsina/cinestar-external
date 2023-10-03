@php
    $typeName = null;
    if($claim->claim_type_id == \App\Enums\ClaimType::RECLAMO){
        $typeName = 'un reclamo';
    } else {
        $typeName = 'una queja';
    }
@endphp

@extends('emails.layout', ['tradeName' => $claim->trade_name])
@section('content')

<h1>Administrador</h1>
<p>Un cliente acaba de registrar {{$typeName}} mediante el libro de reclamaciones de nuestra web, ver el archivo adjunto para mas detalles.</p>
<br>
Saludos,<br>
{{ ucfirst(strtolower($claim->trade_name)) }}

@endsection
