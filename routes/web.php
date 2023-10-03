<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Telescope\TelescopeLoginController;

Route::get('health-check', 'ELBController@healthCheck');
Route::get('elb', 'ELBController@elbTest');

Route::group(['prefix'=>'debugger', 'namespace'=>'Debugger'], function ()
{
    Route::group(['prefix'=>'mails', 'namespace'=>'Mails'], function () {
        Route::get('purchase-voucher/{id}', 'PurchaseVoucherController@show');
        Route::get('purchase-voucher/{id}/send', 'PurchaseVoucherController@send');
        Route::get('purchase-voucher/{id}/pdf', 'PurchaseVoucherController@showPdf');
        Route::get('purchase-voucher-ticket/{guid}/qr','PurchaseVoucherController@showTicketQr');
        Route::get('purchase-voucher-sweet/{guid}/qr','PurchaseVoucherController@showSweetQr');
    });

    Route::group(['prefix'=>'dto'], function () {
        Route::get('purchase-voucher/{id}', function ($id){
            $model = \App\Models\Purchases\Purchase::query()->findOrFail($id);
            return (\App\Dtos\Mails\EmailPurchaseVoucherDto::makeByPurchase($model))->toArray();
        });
    });

    Route::group(['prefix'=>'payu'], function () {
        Route::get('web-checkout', 'Payu\PayuWebCheckoutController@index');
        Route::get('web-checkout/response', 'Payu\PayuWebCheckoutController@response')->name('payu.wc.response');
        Route::get('web-checkout/confirmation', 'Payu\PayuWebCheckoutController@confirmation')->name('payu.wc.confirmation');
    });


    /*
    Route::group(['prefix'=>'sequence'], function () {
        Route::get('/{code}', function ($code){
            return \App\Models\AutoIncrementCodes\Service\InvoiceSequenceFactory::lastCode($code);
        });
        Route::get('invoice/{code}', function ($code){
            return \App\Models\AutoIncrementCodes\Service\InvoiceSequenceFactory::reserveInvoice($code);
        });
        Route::get('ticket/{code}', function ($code){
            return \App\Models\AutoIncrementCodes\Service\InvoiceSequenceFactory::reserveTicket($code);
        });
    });
    */
});

Route::group(['namespace'=>'Web'  ], function () {
    Route::get('purchase-voucher/{guid}/pdf', 'Render\PurchaseVoucherQrMail@showPdf')->name('render.purchase-voucher-ticket.pdf');;
    Route::get('purchase-voucher-ticket/{guid}/qr','Render\PurchaseVoucherQrMail@showTicketQr')->name('render.purchase-voucher-ticket.qr');
    Route::get('purchase-voucher-sweet/{guid}/qr','Render\PurchaseVoucherQrMail@showSweetQr')->name('render.purchase-voucher-sweet.qr');
});

Route::get('codigos', 'Web\WebController@codes');
