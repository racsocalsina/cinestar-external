@php
    $backgroundColor = null;
    $logo = null;

    if(strtolower($tradeName) == strtolower(\App\Enums\TradeName::CINESTAR))
    {
        $backgroundColor = '#e60f3d';
        $logo = asset('assets/mails/logo.png');
    } else {
        $backgroundColor = '#171198';
        $logo = asset('assets/mails/movietime-logo.png');
    }

    $purchase = $data['data'];
    $movieVersion = null;

    if(isset($purchase->ticket_data->movie->version->short))
    {
        $movieVersion = " / {$purchase->ticket_data->movie->version->short}";
    }

@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Voucher de compra</title>
    <style type="text/css">
        h5 {
            color: {{$backgroundColor}};
        }

        h6 {
            color: #0a0302;
            font-size: 25px;
        }
    </style>
</head>
<body>
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr style="background-color: {{$backgroundColor}}">
            <td align="center">
                <table class="header"  width="100%" cellpadding="8" cellspacing="8">
                    <tr>
                        <td style="text-align: center">
                            <img src="{{$logo}}" alt="" width="200"/>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td>
                <table cellpadding="0" cellspacing="0" border="0" width="100%" style="color: #727272">

                    <tr>
                        <td class="bg-whitesmoke px-1">
                            <table  cellspacing="0" border="0" width="100%">

                                <tr>
                                    <td style="padding-top: 20px;" colspan="10">
                                    </td>
                                </tr>


                                <!--====================================================-->
                                <!-- Boleto -->
                                <!--====================================================-->
                                @if(isset($purchase->ticket_data))
                                    <tr>
                                        <td style="padding-right: 30px;" valign="top">
                                            <img src="{{route('render.purchase-voucher-ticket.qr', isset($purchase->guid) ? $purchase->guid : 'no-guid')}}" width="250" height="250">
                                        </td>

                                        <td width="1000px;" style="vertical-align: top;">
                                            <table cellpadding="0" cellspacing="5" border="0" width="100%">

                                                <!-------------------------------------->
                                                <!-- Pelicula -->
                                                <!-------------------------------------->
                                                <tr>
                                                    <td colspan="10">
                                                        <h6 style="margin-bottom: 0; margin-top: 0px;"><strong>{{$purchase->ticket_data->movie->name}}</strong></h6>
                                                    </td>
                                                </tr>

                                                <!-------------------------------------->
                                                <!-- Butacas y salas -->
                                                <!-------------------------------------->
                                                <tr>
                                                    <td colspan="7" class="bg-whitesmoke px-1">
                                                        <h5 class="color-scarlet" style="font-size: 0.9rem; margin-bottom: 0; margin-left: 0px; margin-top: 10px;">Butacas</h5>
                                                    </td>

                                                    <td colspan="3" class="bg-whitesmoke px-1">
                                                        <h5 class="color-scarlet" style="font-size: 0.9rem; margin-bottom: 0; margin-left: 0px;margin-top: 10px;">Sala</h5>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td colspan="7" class="bg-whitesmoke px-1">
                                                        {{$purchase->ticket_data->seats}}
                                                    </td>
                                                    <td colspan="3" class="bg-whitesmoke px-1">
                                                        {{$purchase->ticket_data->room}}
                                                    </td>
                                                </tr>

                                                <!-------------------------------------->
                                                <!-- Cantidad y tipos de entradas -->
                                                <!-------------------------------------->
                                                <tr>
                                                    <td colspan="10" class="bg-whitesmoke px-1">
                                                        <h5 class="color-scarlet" style="font-size: 0.9rem; margin-bottom: 0; margin-left: 0px; margin-top: 20px"> Cantidad y tipo de entradas</h5>
                                                    </td>
                                                </tr>

                                                @foreach($purchase->ticket_data->ticket_types as $item)
                                                    <tr>
                                                        <td colspan="10" class="bg-whitesmoke px-1">
                                                            {{$item->quantity . ' ' . $item->name}}
                                                        </td>
                                                    </tr>
                                                @endforeach

                                                <!-------------------------------------->
                                                <!-- Fecha y hora -->
                                                <!-------------------------------------->
                                                <tr>
                                                    <td colspan="7" class="bg-whitesmoke px-1">
                                                        <h5 class="color-scarlet" style="font-size: 0.9rem; margin-bottom: 0; margin-left: 0px;margin-top: 20px">Fecha</h5>
                                                    </td>

                                                    <td colspan="3" class="bg-whitesmoke px-1">
                                                        <h5 class="color-scarlet" style="font-size: 0.9rem; margin-bottom: 0; margin-left: 0px;margin-top: 20px">Hora</h5>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td colspan="7" class="bg-whitesmoke px-1" >
                                                        {{$purchase->ticket_data->start_at_name}}
                                                    </td>
                                                    <td colspan="3" class="bg-whitesmoke px-1" >
                                                        {{\App\Helpers\Helper::dateTimeByFormat($purchase->ticket_data->start_at, null, 'h:i A'). $movieVersion}}
                                                    </td>
                                                </tr>


                                                <!-------------------------------------->
                                                <!-- Puntos -->
                                                <!-------------------------------------->
                                                <tr>
                                                    <td colspan="7" class="bg-whitesmoke px-1">
                                                        <h5 class="color-scarlet" style="font-size: 0.9rem; margin-bottom: 0; margin-left: 0px;margin-top: 20px">N° Comprobante:</h5>
                                                    </td>

                                                    <td colspan="3" class="bg-whitesmoke px-1">
                                                        <h5 class="color-scarlet" style="font-size: 0.9rem; margin-bottom: 0; margin-left: 0px; margin-top: 20px">Ptos acumulados</h5>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td colspan="7" class="bg-whitesmoke px-1" >
                                                        {{$purchase->ticket_data->voucher_number}}
                                                    </td>
                                                    <td colspan="3" class="bg-whitesmoke px-1" >
                                                        {{$purchase->ticket_data->points}} puntos
                                                    </td>
                                                </tr>

                                            </table>
                                        </td>
                                    </tr>
                                @endif

                                <tr>
                                    <td style="padding-top: 20px;" colspan="10">
                                    </td>
                                </tr>

                                <!--====================================================-->
                                <!-- Dulceria -->
                                <!--====================================================-->
                                @if(isset($purchase->sweet_data))
                                    <tr>
                                        <td style="padding-right: 30px;" valign="top">
                                            <img src="{{route('render.purchase-voucher-sweet.qr', isset($purchase->guid) ? $purchase->guid : 'no-guid')}}" width="250" height="250">
                                        </td>

                                        <td width="1000px;" style="vertical-align: top;">
                                            <table cellpadding="0" cellspacing="10" border="0" width="100%">

                                                <!-------------------------------------->
                                                <!-- Title -->
                                                <!-------------------------------------->
                                                <tr>
                                                    <td colspan="10" class="bg-whitesmoke px-1">
                                                        <h6 class="my-0" style="margin-bottom: 0; margin-left: 0px; margin-top: 0px"><strong>Compra en chocolatería</strong></h6>
                                                    </td>
                                                </tr>

                                                <!-------------------------------------->
                                                <!-- Fecha y puntos -->
                                                <!-------------------------------------->
                                                <tr>
                                                    <td colspan="6" class="bg-whitesmoke px-1">
                                                        <h5 class="color-scarlet" style="font-size: 1rem; margin-bottom: 0; margin-left: 0px; margin-top: 0px">Fecha de recojo:</h5>
                                                    </td>
                                                    <td colspan="6" class="bg-whitesmoke px-1">
                                                        <h5 class="color-scarlet" style="font-size: 0.9rem; margin-bottom: 0; margin-left: 0px; margin-top: 20px">N° Comprobante:</h5>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td colspan="6" class="bg-whitesmoke px-1">
                                                        {{$purchase->sweet_data->date_name}}
                                                    </td>
                                                    <td colspan="6" class="bg-whitesmoke px-1" >
                                                        {{$purchase->sweet_data->voucher_number}}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td colspan="6" class="bg-whitesmoke px-1">
                                                        <h5 class="color-scarlet" style="font-size: 0.9rem; margin-bottom: 0; margin-left: 0px; margin-top: 20px">Ptos acumulados</h5>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td colspan="6" class="bg-whitesmoke px-1" >
                                                        {{$purchase->sweet_data->points}} puntos
                                                    </td>
                                                </tr>

                                                <!-------------------------------------->
                                                <!-- Items -->
                                                <!-------------------------------------->
                                                <tr>
                                                    <td colspan="10" class="bg-whitesmoke px-1">
                                                        <table border="0" cellspacing="0" cellpadding="0" width="100%">
                                                            @foreach($purchase->sweet_data->items as $item)
                                                                <tr>
                                                                    <td style="vertical-align: top; padding-top: 9px" width="110px">
                                                                        <img src="{{$item->image}}" width="100" height="100" border="0">
                                                                    </td>
                                                                    <td style="padding-top: 9px">
                                                                        <span style="font-size: 0.9rem; margin-bottom: 15px;">{{$item->name}}</span><br>
                                                                        <span style="font-size: 0.9rem; padding-bottom: 15px;">x {{$item->quantity}}</span><br>
                                                                        <span style="font-size: 0.9rem; padding-bottom: 15px; color: #0a0302"><strong>S/ {{number_format($item->price, 2, '.', '')}}</strong></span>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </table>
                                                    </td>
                                                </tr>

                                            </table>
                                        </td>
                                    </tr>
                                @endif

                                <tr>
                                    <td style="padding-top: 5px; padding-bottom: 5px" colspan="10">
                                        <hr>
                                    </td>
                                </tr>

                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td  class="bg-whitesmoke px-1"  >
                            <table cellpadding="0" cellspacing="0" border="0" width="100%" >

                                <tr>
                                    <td width="20" style="vertical-align: middle; padding-top: 10px;">
                                        <img src="{{asset("assets/mails/show_qr.png")}}" width="30">
                                    </td>
{{--                                    <td style="padding-left: 10px; vertical-align: top">--}}
{{--                                        <p class="ml-1 my-1" style="font-size: 1rem;">Muestra el código desde tu celular para canjear tus combos ingresar a la sala. No necesitas pasar por boleteria--}}
{{--                                            ni imprimir este documento</p>--}}
{{--                                    </td>--}}
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-top: 20px;">
                            <table cellpadding="0" cellspacing="0" border="0" width="100%">

                                <!-------------------------------------->
                                <!-- Datos del cine -->
                                <!-------------------------------------->
                                <tr>
                                    <td width="15" style="vertical-align: top">
                                        <img src="{{asset("assets/mails/logo_gold.png")}}" width="30">
                                    </td>
                                    <td style="vertical-align: center; padding-left: 5px; padding-bottom: 7px">
                                    <span class="my-1" style="font-size: 1rem; color: #0a0302">
                                        <strong>{{isset($purchase->headquarter) ? $purchase->headquarter->name : '-'}}</strong>
                                    </span>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="2" style="padding-bottom: 7px;">
                                        {{isset($purchase->headquarter) ? $purchase->headquarter->address : '-'}}
                                    </td>
                                </tr>

                                <!-------------------------------------->
                                <!-- Tipo de pago y total -->
                                <!-------------------------------------->
                                <tr>
                                    <td colspan="5" class="bg-whitesmoke px-1">
                                        <h5 class="color-scarlet" style="font-size: 0.9rem; margin-bottom: 0; margin-left: 0px; margin-top: 10px">Tipo de pago</h5>
                                    </td>

                                    <td colspan="5" class="bg-whitesmoke px-1">
                                        <h5 class="color-scarlet" style="font-size: 0.9rem; margin-bottom: 0; margin-left: 0px; margin-top: 10px">Total</h5>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="5">
                                        <span style="font-size: 1rem;margin-top: 0;">{{isset($purchase->payment_data) ? ucfirst(strtolower($purchase->payment_data->type)): '-'}}</span>
                                    </td>

                                    <td colspan="5">
                                        <span style="font-size: 1rem;margin-top: 0;">{{isset($purchase->payment_data) ? $purchase->payment_data->currency . ' ' . number_format($purchase->payment_data->total, 2, '.', ''): '-'}}</span>
                                    </td>
                                </tr>




                                <!-------------------------------------->
                                <!-- Nombre y tarjeta -->
                                <!-------------------------------------->
                                <tr>
                                    <td colspan="5" class="bg-whitesmoke px-1">
                                        <h5 class="color-scarlet" style="font-size: 0.9rem; margin-bottom: 0; margin-left: 0px; margin-top: 15px">Nombre</h5>
                                    </td>

                                    <td colspan="5" class="bg-whitesmoke px-1">
                                        <h5 class="color-scarlet" style="font-size: 0.9rem; margin-bottom: 0; margin-left: 0px; margin-top: 15px">Tarjeta</h5>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="5">
                                        <span style="font-size: 1rem;margin-top: 0;">{{isset($purchase->payment_data) ? $purchase->payment_data->full_name : '-'}}</span>
                                    </td>

                                    <td colspan="5">
                                        <span style="font-size: 1rem;margin-top: 0;">{{isset($purchase->payment_data) ? $purchase->payment_data->card : '-'}}</span>
                                    </td>
                                </tr>

                                <!-------------------------------------->
                                <!-- Fecha y hora del pedido -->
                                <!-------------------------------------->
                                <tr>
                                    <td colspan="5" class="bg-whitesmoke px-1">
                                        <h5 class="color-scarlet" style="font-size: 0.9rem; margin-bottom: 0; margin-left: 0px; margin-top: 15px">Fecha y Hora del pedido</h5>
                                    </td>

                                    <td colspan="5" class="bg-whitesmoke px-1">
                                        <h5 class="color-scarlet" style="font-size: 0.9rem; margin-bottom: 0; margin-left: 0px; margin-top: 15px">Nro. Pedido</h5>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="5">
                                        <span style="font-size: 1rem;margin-top: 0;">{{isset($purchase->payment_data) ? App\Helpers\Helper::dateTimeByFormat($purchase->payment_data->datetime, null, 'd/m/Y h:i A') : '-'}}</span>
                                    </td>

                                    <td colspan="5">
                                        <span style="font-size: 1rem;margin-top: 0;">{{$purchase->id}}</span>
                                    </td>
                                </tr>

                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

    </table>
</body>
</html>
