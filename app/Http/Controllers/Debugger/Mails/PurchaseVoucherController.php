<?php


namespace App\Http\Controllers\Debugger\Mails;


use App\Dtos\Mails\EmailPurchaseVoucherDto;
use App\Dtos\Purchases\PurchaseSweetQrDto;
use App\Dtos\Purchases\PurchaseTicketQrDto;
use App\Helpers\ApiResponse;
use App\Mail\EmailPurchaseVoucher;
use App\Models\Purchases\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PurchaseVoucherController
{
    public function __construct()
    {
        if(!env('APP_DEBUG') || env('APP_ENV') == 'production')
            return abort(403);
    }

    public function show($id, Request  $request) {
        $email = $this->getEmail($id);
        return $email->render();
    }

    public function showPdf($id, Request  $request)
    {
        $purchase = Purchase::query()->findOrFail($id);
        $data = EmailPurchaseVoucherDto::makeByPurchase($purchase)->toArray();
        $pdf = \PDF::loadView('pdf.voucher', [
            'data' => $data,
            'tradeName' => 'CINESTAR'
        ]);

        return $pdf->inline();

        // save file
        /*
        $guid = $purchase->id . $purchase->created_at->timestamp;
        $path = "temp/{$guid}.pdf";
        Storage::disk('local')->put($path, $pdf->output());
        */
    }

    public function send($id, Request  $request) {
        $request->validate([
            'email'=>'required'
        ]);
        $email = $this->getEmail($id);
        Mail::to([
            [
                'email' => $request->email,
                'name' => 'de',
            ]
        ])->send($email);
        return ApiResponse::success([
            'to'=>$request->get('email')
        ]);

    }

    private function getEmail($id): EmailPurchaseVoucher
    {
        $model = Purchase::query()->findOrFail($id);
        $config = [
          'url_info_receipt' => "https://test.com"
        ];
        $email = new EmailPurchaseVoucher($model, $config);
        return $email;
    }

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
}
