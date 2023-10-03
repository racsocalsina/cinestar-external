@php
    $backgroundColor = null;
    $logo = null;

    if(strtolower($data->trade_name) == strtolower(\App\Enums\TradeName::CINESTAR))
    {
        $backgroundColor = '#e60f3d';
        $logo = public_path('assets/mails/logo.png');
    } else {
        $backgroundColor = '#171198';
        $logo = public_path('assets/mails/movietime-logo.png');
    }
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Constancia de Registro</title>
    <style type="text/css">

        *{
            margin: 0;
            padding: 0;
        }

        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        a {
            color: #0087C3;
            text-decoration: none;
        }

        body {
            margin: 0 auto;
            color: #555555;
            background: #FFFFFF;
            font-size: 14px;
        }

        main{
            margin: 0 50px;
        }

        header {
            background: {{$backgroundColor}};
            padding: 10px 0;
            margin-bottom: 20px;
        }

        #logo {
            float: left;
            padding-left: 20px;
        }

        #logo img {
            height: 50px;
            width: 170px;
        }

        #company {
            padding-top: 10px;
            color: #ffffff;
            text-align: right;
            padding-right: 20px;
        }

        .details {
            margin-bottom: 20px;
        }

        .section {
            width: 100%;
            padding-left: 6px;
            border-left: 6px solid #0087C3;
            float: left;
        }

        .section div{
            margin: 10px;
        }

        .subtitle{
            color: {{$backgroundColor}};
            font-size: 16px;
            font-weight: bold;
        }

        .item{
            font-size: 15px;
            font-weight: bold;
        }

    </style>
</head>
<body>
<header class="clearfix">
    <div id="logo">
        <img src="{{$logo}}" />
    </div>

    <div id="company">
        <div><b>CONSTANCIA DE REGISTRO</b></div>
        <div style="font-size: 18px"><b># {{$data->code}}</b></div>
    </div>

</header>
<main>
    <br>
    <div class="details clearfix">
        <div class="section">
            <div class="subtitle"><b>Zona donde registró el caso:</b></div>
            <div>
                <span class="item">Departamento:</span>
                <label>{{ $data->sede_district ? ($data->sede_district->department ? $data->sede_district->department->name : null) : null }}</label>
            </div>
            <div>
                <span class="item">Provincia:</span>
                <label>{{ $data->sede_district ? ($data->sede_district->province ? $data->sede_district->province->name : null) : null }}</label>
            </div>
            <div>
                <span class="item">Distrito:</span>
                <label>{{ $data->sede_district ? $data->sede_district->name : null }}</label>
            </div>
        </div>
    </div>

    <div class="details clearfix">
        <div class="section">
            <div class="subtitle">Detalle de su reclamo:</div>
            <div>
                <span class="item">Tipo:</span>
                <label>{{$data->type->name . ' - '. $data->type->description}}</label>
            </div>
            <div>
                <span class="item">Detalle:</span>
                <label> {{$data->detail}}</label>
            </div>
        </div>
    </div>

    <div class="details clearfix">
        <div class="section">
            <div class="subtitle">Identificación del bien contratado:</div>
            <div>{{ $data->identification_type->name }}</div>
        </div>
    </div>

    <div class="details clearfix">
        <div class="section">
            <div class="subtitle">Monto del reclamo:</div>
            <div>{{ $data->amount ? "S/ ". $data->amount : "No especificado" }}</div>
        </div>
    </div>

    <div class="details clearfix">
        <div class="section">

            <div class="subtitle">Identificación del consumidor reclamante:</div>
            <div>
                <span class="item">Nombres:</span>
                <label>{{$data->name}}</label>
            </div>
            <div>
                <span class="item">Apellidos:</span>
                <label> {{$data->lastname}}</label>
            </div>
            <div>
                <span class="item">Mayor de edad:</span>
                <label> {{ \App\Helpers\CastNameHelper::getConditionalName($data->older) }}</label>
            </div>
            <div>
                <span class="item">Tipo de Documento de Identidad:</span>
                <label> {{$data->document_type->name}}</label>
            </div>
            <div>
                <span class="item">Número de Documento:</span>
                <label> {{$data->document_number}}</label>
            </div>
            <div>
                <span class="item">Nombre del representante:</span>
                <label> {{$data->representative_name}}</label>
            </div>
        </div>
    </div>

    <div class="details clearfix">
        <div class="section">
            <div class="subtitle">Dirección del reclamante:</div>
            <div>
                <span class="item">Dirección:</span>
                <label> {{$data->address}}</label>
            </div>
            <div>
                <span class="item">Departamento:</span>
                <label>{{ $data->person_district ? ($data->person_district->department ? $data->person_district->department->name : null) : null }}</label>
            </div>
            <div>
                <span class="item">Provincia:</span>
                <label>{{ $data->person_district ? ($data->person_district->province ? $data->person_district->province->name : null) : null }}</label>
            </div>
            <div>
                <span class="item">Distrito:</span>
                <label>{{ $data->person_district ? $data->person_district->name : null }}</label>
            </div>
            <div>
                <span class="item">Teléfono de contacto:</span>
                <label>{{$data->cellphone}}</label>
            </div>
            <div>
                <span class="item">Correo:</span>
                <label>
                    <a href="mailto:{{$data->email}}">{{$data->email}}</a>
                </label>
            </div>
        </div>
    </div>

</main>
</body>
</html>
