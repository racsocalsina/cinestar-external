@extends('emails.layout', ['tradeName' => $data->trade_name])
@section('content')

<h1>Administrador</h1>
<p>Alguien ha enviado una solicitud para trabajar con nosotros, acá los detalles y el curriculum vitae adjunto:</p>
<table cellpadding="3" cellspacing="3">
    <tr>
        <td><b>Nombres:</b></td>
        <td>{{$data->name}}</td>
    </tr>
    <tr>
        <td><b>Apellidos:</b></td>
        <td>{{$data->lastname}}</td>
    </tr>
    <tr>
        <td><b>Email:</b></td>
        <td><a href="mailto:{{$data->email}}">{{$data->email}}</a></td>
    </tr>
    <tr>
        <td><b>Dirección:</b></td>
        <td>{{$data->address}}</td>
    </tr>
    <tr>
        <td><b>Número de documento:</b></td>
        <td>{{$data->document_number}}</td>
    </tr>
    <tr>
        <td><b>Distrito:</b></td>
        <td>{{$data->district_name}}</td>
    </tr>
    <tr>
        <td><b>Fecha de nacimiento:</b></td>
        <td>{{$data->birth_date->format('d/m/Y')}}</td>
    </tr>
    <tr>
        <td><b>Nivel de Educación:</b></td>
        <td>{{$data->education_level}}</td>
    </tr>
</table>
<br>
Saludos,<br>
{{ ucfirst(strtolower($data->trade_name)) }}

@endsection
