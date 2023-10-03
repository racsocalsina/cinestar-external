@extends('emails.layout', ['tradeName' => $contact->trade_name])
@section('content')

<h1>Administrador</h1>
<p>Alguien quiere contactarse con nosotros, acá la información:</p>
<table cellpadding="3" cellspacing="3">
    <tr>
        <td><b>Nombres:</b></td>
        <td>{{$contact->name}}</td>
    </tr>
    <tr>
        <td><b>Apellidos:</b></td>
        <td>{{$contact->lastname}}</td>
    </tr>
    <tr>
        <td><b>Email:</b></td>
        <td><a href="mailto:{{$contact->email}}">{{$contact->email}}</a></td>
    </tr>
    <tr>
        <td><b>Distrito:</b></td>
        <td>{{$contact->district_name}}</td>
    </tr>
    <tr>
        <td><b>Mensaje:</b></td>
        <td>{{$contact->message}}</td>
    </tr>
</table>
<br>
Saludos,<br>
{{ ucfirst(strtolower($contact->trade_name)) }}

@endsection
