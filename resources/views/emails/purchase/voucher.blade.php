@extends('emails.layout', ['tradeName' => $tradeName])
@section('content')
    @php
        $url_info_receipt = null;
        $purchase = $data['data'];

        $movieVersion = null;

        if(isset($purchase->ticket_data->movie->version->short))
        {
            $movieVersion = " / {$purchase->ticket_data->movie->version->short}";
        }

        if(isset($config))
            if(isset($config['url_info_receipt']))
                $url_info_receipt = $config['url_info_receipt'];

    @endphp
    <table cellpadding="0" cellspacing="0" border="0" width="100%" style="color: #727272">

        <tr>
            <td class="bg-whitesmoke px-1">
                <table  cellspacing="0" border="0" width="100%">
                    <tr>
                        <td colspan="10">
                            <table cellpadding="0" cellspacing="10" border="0" width="100%">
                                <tr>
                                    <td colspan="10" align="right">
                                        <h6 style="margin: 0;" class="color-scarlet my-0"> Nro. de compra</h6>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="10" class="bg-whitesmoke px-1" style="padding-bottom: 10px; padding-top: 0px;" align="right">
                                        <h6 class="my-0" style="margin-bottom: 0; margin-left: 0px; color: #0a0302; font-size: 1.6rem;"><strong>{{isset($purchase) ? $purchase->id :''}}</strong></h6>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!--====================================================-->
                    <!-- Boleto -->
                    <!--====================================================-->
                    @if(isset($purchase->ticket_data))
                    <tr>
                        <td style="vertical-align: top; padding-top: 10px">
                            <img src="{{route('render.purchase-voucher-ticket.qr', isset($purchase->guid) ? $purchase->guid : 'no-guid')}}" width="190">
                        </td>

                        <td  style="vertical-align: top;">
                            <table cellpadding="0" cellspacing="10" border="0" width="100%">

                                <!-------------------------------------->
                                <!-- Pelicula -->
                                <!-------------------------------------->
                                <tr>
                                    <td colspan="10" class="bg-whitesmoke px-1" style="padding-bottom: 0px; padding-top: 0px;">
                                        <h2 class="color-scarlet" style="font-size: 0.9rem; margin-bottom: 0; margin-left: 0px">Película</h2>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="10" class="bg-whitesmoke px-1" style="padding-bottom: 10px; padding-top: 0px;">
                                        <h6 class="my-0" style="margin-bottom: 0; margin-left: 0px; color: #0a0302; font-size: 1.6rem;"><strong>{{$purchase->ticket_data->movie->name}}</strong></h6>
                                    </td>
                                </tr>

                                <!-------------------------------------->
                                <!-- Fecha, hora y sala -->
                                <!-------------------------------------->
                                <tr>
                                    <td colspan="5" class="bg-whitesmoke px-1" style="padding-top: 20px">
                                        <img src="{{asset("assets/mails/calendar.png")}}" width="15" height="15" style="top:2px">
                                        <span style="padding-left: 10px; font-size: 0.9rem;">{{$purchase->ticket_data->start_at_name}}</span>
                                    </td>
                                    <td colspan="5"  align="center" style="text-align: center; padding-top: 20px">
                                        <span class="color-scarlet" style="font-size: 1.3rem; margin-bottom: 0; margin-left: 0px; font-weight: bold">
                                            Sala {{$purchase->ticket_data->room}}
                                        </span>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="10" class="bg-whitesmoke px-1">
                                        <img src="{{asset("assets/mails/time.png")}}" width="15" height="15" style="top:2px">
                                        <span style="padding-left: 5px; font-size: 0.9rem;">
                                            {{\App\Helpers\Helper::dateTimeByFormat($purchase->ticket_data->start_at, null, 'h:i A'). $movieVersion}}
                                        </span>
                                    </td>
                                </tr>

                                <!-------------------------------------->
                                <!-- Butacas -->
                                <!-------------------------------------->
                                <tr>
                                    <td colspan="6" class="bg-whitesmoke px-1">
                                        <img src="{{asset("assets/mails/chair.png")}}" width="15" height="15" style="top:2px">
                                        <span style="padding-left: 10px; font-size: 0.9rem;">{{$purchase->ticket_data->seats}}</span>
                                    </td>
                                </tr>


                                <!-------------------------------------->
                                <!-- N° Comprobante -->
                                <!-------------------------------------->
                                <tr>
                                    <td colspan="12" class="bg-whitesmoke px-1" style="padding-top: 5px">
                                        <span class="color-scarlet" style="font-size: 0.9rem; margin-bottom: 0; margin-left: 0px; font-weight: bold">N° Comprobante:</span>
                                        <span style="padding-left: 5px; font-size: 0.9rem;">{{$purchase->ticket_data->voucher_number}}</span>
                                    </td>
                                </tr>

                                <!-------------------------------------->
                                <!-- Puntos -->
                                <!-------------------------------------->
                                <tr>
                                    <td colspan="12" class="bg-whitesmoke px-1" style="padding-top: 5px">
                                        <span class="color-scarlet" style="font-size: 0.9rem; margin-bottom: 0; margin-left: 0px; font-weight: bold">Ptos acumulados:</span>
                                        <span style="padding-left: 5px; font-size: 0.9rem;">{{$purchase->ticket_data->points}} puntos</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    @endif


                    <!--====================================================-->
                    <!-- Dulceria -->
                    <!--====================================================-->
                    @if(isset($purchase->sweet_data))
                    <tr>
                        <td style="padding-top: 15px;" colspan="10">

                        </td>
                    </tr>

                    <tr>
                        <td style="vertical-align: top; padding-top: 10px">
                            <img src="{{route('render.purchase-voucher-sweet.qr', isset($purchase->guid) ? $purchase->guid : 'no-guid')}}" width="190">
                        </td>

                        <td  style="vertical-align: top;">
                            <table cellpadding="0" cellspacing="10" border="0" width="100%">

                                <!-------------------------------------->
                                <!-- Title -->
                                <!-------------------------------------->
                                <tr>
                                    <td colspan="10" class="bg-whitesmoke px-1" style="padding-bottom: 10px; padding-top: 0px;">
                                        <h6 class="my-0" style="margin-bottom: 0; margin-left: 0px; color: #0a0302; font-size: 1.4rem;"><strong>Compra en chocolatería</strong></h6>
                                    </td>
                                </tr>

                                <!-------------------------------------->
                                <!-- Fecha -->
                                <!-------------------------------------->
                                <tr>
                                    <td colspan="10" class="bg-whitesmoke px-1">
                                        <!--<img src="{{asset("assets/mails/calendar.png")}}" width="15" height="15" style="top:2px">-->
                                        <span class="color-scarlet" style="font-size: 1rem; margin-bottom: 0; margin-left: 0px; font-weight: bold; display: block;">Fecha de recojo:</span>
                                        <span style="font-size: 1rem; display: block; padding-top: 5px">{{$purchase->sweet_data->date_name}}</span>
                                    </td>
                                </tr>

                                <!-------------------------------------->
                                <!-- N° Comprobante -->
                                <!-------------------------------------->
                                <tr>
                                    <td colspan="12" class="bg-whitesmoke px-1" style="padding-top: 5px">
                                        <span class="color-scarlet" style="font-size: 0.9rem; margin-bottom: 0; margin-left: 0px; font-weight: bold">N° Comprobante:</span>
                                        <span style="padding-left: 5px; font-size: 0.9rem;">{{$purchase->sweet_data->voucher_number}}</span>
                                    </td>
                                </tr>

                                <!-------------------------------------->
                                <!-- Puntos -->
                                <!-------------------------------------->
                                <tr>
                                    <td colspan="12" class="bg-whitesmoke px-1" style="padding-top: 5px">
                                        <span class="color-scarlet" style="font-size: 0.9rem; margin-bottom: 0; margin-left: 0px; font-weight: bold">Ptos acumulados:</span>
                                        <span style="padding-left: 5px; font-size: 0.9rem;">{{$purchase->sweet_data->points}} puntos</span>
                                    </td>
                                </tr>

                            </table>
                        </td>
                    </tr>
                    @endif

                </table>
            </td>
        </tr>

        <tr>
            <td  class="bg-whitesmoke px-1" style="padding-top: 15px">
                <table cellpadding="0" cellspacing="0" border="0" width="100%" >

                    <tr>
                        <td width="20">
                            <img src="{{asset("assets/mails/show_qr.png")}}">
                        </td>
{{--                        <td>--}}
{{--                            <p class="ml-1 my-1" style="font-size: 1rem;">Muestra el código desde tu celular para canjear tus combos ingresar a la sala. No necesitas pasar por boletería--}}
{{--                                ni imprimir este documento</p>--}}
{{--                        </td>--}}
                    </tr>
                </table>
            </td>
        </tr>

        <!-------------------------------------->
        <!-- Datos del cliente y cine -->
        <!-------------------------------------->
        <tr>
            <td style="padding-top: 10px;">
                <table cellpadding="0" cellspacing="0" border="0" width="100%">

                    <tr>
                        <td style="padding-bottom: 0px; padding-top: 0px;" width="40%">
                            <h5 class="color-scarlet my-0" style="font-size: 0.9rem; padding-bottom: 5px">Cliente</h5>
                            <span style="font-size: 1rem;margin-top: 0;">{{isset($purchase->payment_data) ? $purchase->payment_data->full_name : '-'}}</span>
                        </td>

                        <td style="padding-bottom: 0px; padding-top: 0px;" width="60%">
                            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tr>
                                    <td width="20">
                                        <img src="{{asset("assets/mails/logo_gold.png")}}">
                                    </td>
                                    <td  style="vertical-align: center">
                                        <p class="my-1" style="margin-left: 0.5rem; font-size: 0.9rem;">
                                            <strong style="color: #000">{{isset($purchase->headquarter) ? $purchase->headquarter->name : '-'}}</strong>,
                                            {{isset($purchase->headquarter) ? $purchase->headquarter->address : '-'}}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>


        <!-------------------------------------->
        <!-- Detalle de tickets -->
        <!-------------------------------------->
        @if(isset($purchase->ticket_data))
            <tr>
                <td style="padding-top: 20px">
                    <table cellpadding="3" cellspacing="3" border="0" width="100%">
                        <tr>
                            <td rowspan="100" style="vertical-align: top" width="50">
                                <span><img src="{{asset("assets/mails/yellow_ticket.png")}}" width="40"></span>
                            </td>
                            <td width="250">
                                <span class="color-scarlet" style="font-size: 0.9rem;"><strong>Tipo de entradas</strong></span>
                            </td>
                            <td>
                                <span class="color-scarlet" style="font-size: 0.9rem;"><strong>Cantidad</strong></span>
                            </td>
                            <td style="text-align: right">
                                <span class="color-scarlet" style="font-size: 0.9rem;"><strong>Precio Unitario</strong></span>
                            </td>
                        </tr>

            @foreach($purchase->ticket_data->ticket_types as $item)
                <tr>
                    <td>
                        <span style="font-size: 0.9rem;">{{$item->name}}</span>
                    </td>
                    <td>
                        <span style="font-size: 0.9rem;"> {{$item->quantity . ' ' . ($item->quantity == 1 ? 'entrada' : 'entradas')}}</span>
                    </td>
                    <td style="text-align: right">
                        <span style="font-size: 0.9rem;">S/ {{number_format($item->price, 2, '.', '')}}</span>
                    </td>
                </tr>
            @endforeach
                    </table>
                </td>
            </tr>
        @endif


        <!-------------------------------------->
        <!-- Detalle de dulceria -->
        <!-------------------------------------->
        @if(isset($purchase->sweet_data))
            <tr>
                <td style="padding-top: 30px">
                    <table cellpadding="3" cellspacing="3" border="0" width="100%">
                        <tr>
                            <td rowspan="100" style="vertical-align: top" width="50">
                                <span><img src="{{asset("assets/mails/yellow_sweet.png")}}" width="40"></span>
                            </td>
                            <td width="250">
                                <span class="color-scarlet" style="font-size: 0.9rem;"><strong>Chocolatería</strong></span>
                            </td>
                            <td>
                                <span class="color-scarlet" style="font-size: 0.9rem;"><strong>Cantidad</strong></span>
                            </td>
                            <td style="text-align: right">
                                <span class="color-scarlet" style="font-size: 0.9rem;"><strong>Precio Unitario</strong></span>
                            </td>
                        </tr>

                        @foreach($purchase->sweet_data->items as $item)
                            <tr>
                                <td>
                                    <span style="font-size: 0.9rem;">{{$item->name}}</span>
                                </td>
                                <td>
                                    <span style="font-size: 0.9rem;"> {{$item->quantity . ' ' . \App\Helpers\CastNameHelper::getSweetNameByQuantity($item->sweet_type, $item->quantity)}}</span>
                                </td>
                                <td style="text-align: right">
                                    <span style="font-size: 0.9rem;">S/ {{number_format($item->price, 2, '.', '')}}</span>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </td>
            </tr>
        @endif

        <tr>
            <td style="padding-top: 10px; padding-bottom: 10px">
                <hr>
            </td>
        </tr>
        <tr>
            <td class="text-right" style="padding-bottom: 10px">
                <strong style="font-size: 0.8rem;color: #000">Sub  Total: S/ {{number_format($purchase->sub_total, 2, '.', '')}} </strong>
            </td>
        </tr>
        <tr>
            <td class="text-right">
                <strong style="font-size: 1.1rem;" class="color-scarlet">Total: S/ {{number_format($purchase->total, 2, '.', '')}} </strong>
            </td>
        </tr>

        @if($url_info_receipt != null)
        <tr>
            <td style="padding-top: 25px;">
                <p style="font-size: 0.9rem;">
                    Para descargar tu comprobante electrónico ingresa a
                    <a href="{{$url_info_receipt}}" target="_blank">{{$url_info_receipt}}</a>
                </p>
            </td>
        </tr>
        @endif

    </table>
@endsection
