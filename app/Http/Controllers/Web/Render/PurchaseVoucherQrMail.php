<?php
namespace App\Http\Controllers\Web\Render;

use App\Dtos\Mails\EmailPurchaseVoucherDto;
use App\Dtos\Purchases\PurchaseSweetQrDto;
use App\Dtos\Purchases\PurchaseTicketQrDto;
use App\Http\Controllers\Controller;
use App\Models\Purchases\Purchase;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PurchaseVoucherQrMail extends Controller
{
    public function showTicketQr($guid)
    {
        $purchase = Purchase::where('guid', $guid)->first();

        if(!$purchase)
            return ['message' => 'guid not found'];

        $qr = json_encode(PurchaseTicketQrDto::makeByPurchase($purchase)->toArray());

        return response(QrCode::format('png')
            ->size(250)
            ->generate($qr)
        )->header('Content-Type', 'image/png');
    }

    public function showSweetQr($guid)
    {
        $purchase = Purchase::where('guid', $guid)->first();

        if(!$purchase)
            return ['message' => 'guid not found'];

        $qr = json_encode(PurchaseSweetQrDto::makeByPurchase($purchase)->toArray());

        return response(QrCode::format('png')
            ->size(250)
            ->generate($qr)
        )->header('Content-Type', 'image/png');
    }

    public function showPdf($guid)
    {
        $purchase = Purchase::with(['headquarter'])
            ->where('guid', $guid)
            ->first();

        $data = EmailPurchaseVoucherDto::makeByPurchase($purchase)->toArray();
        $pdf = \PDF::loadView('pdf.voucher', [
            'data' => $data,
            'tradeName' => $purchase->headquarter->trade_name
        ]);

        return $pdf->inline();
    }
}
