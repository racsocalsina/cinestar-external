<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PayU Test - Web CheckOut</title>
</head>
<body>
<h2>Web-Checkout de Payu (Formulario) - Test</h2>
<div class="flex-center position-ref full-height">
    <form method="post" action="https://sandbox.checkout.payulatam.com/ppp-web-gateway-payu/">
        <input name="merchantId" type="hidden" value="{{$data['merchant_id']}}">
        <input name="accountId" type="hidden" value="512323">
        <input name="description" type="hidden" value="Test Cinestar Payu">
        <input name="referenceCode" type="hidden" value="{{$data['reference_code']}}">
        <input name="amount" type="hidden" value="{{$data['amount']}}">
        <input name="tax" type="hidden" value="0">
        <input name="taxReturnBase" type="hidden" value="0">
        <input name="currency" type="hidden" value="{{$data['currency']}}">
        <input name="signature" type="hidden" value="{{$data['signature']}}">
        <input name="test" type="hidden" value="1">
        <input name="buyerFullName" type="hidden" value="Carlos Silva">
        <input name="buyerEmail" type="hidden" value="csilva@peruapps.com.pe">
        <input name="responseUrl" type="hidden" value="{{route('payu.wc.response')}}">
        <input name="confirmationUrl" type="hidden" value="{{route('payu.wc.confirmation')}}">
        <input name="Submit" type="submit" value="Enviar">
    </form>
</div>
</body>
</html>
