<?php

namespace App\Mail;

use App\Dtos\Mails\EmailPurchaseVoucherDto;
use App\Models\Purchases\Purchase;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class EmailPurchaseVoucher extends Mailable
{
    use Queueable, SerializesModels;

    public $theme = 'cinestar';

    private $purchase;
    private $config;

    /**
     * Create a new message instance.
     *
     * @param Purchase $purchase
     */
    public function __construct(Purchase $purchase, $config = null)
    {
        $this->purchase = $purchase;
        $this->config = $config;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $guid = $this->createPdfToTemp($this->purchase);
        $paymentGatewayInfo = $this->purchase->payment_gateway_info;
        $tradeName = strtolower($this->purchase->headquarter->trade_name);
        $this->theme = $tradeName;
        $filepath = "app/temp/{$guid}.pdf";

        return $this
            ->from(env("MAIL_FROM_ADDRESS"), ucfirst($tradeName))
            ->to($paymentGatewayInfo->email)
            ->subject('Comprobante de compra cinestar')
            ->markdown('emails.purchase.voucher', [
                'data'      => (EmailPurchaseVoucherDto::makeByPurchase($this->purchase))->toArray(),
                'tradeName' => $tradeName,
                'config'    => $this->config
            ])
            ->attach(storage_path($filepath), [
                'as'   => 'Comprobante-de-compra.pdf',
                'mime' => 'application/pdf',
            ]);
    }

    private function createPdfToTemp($purchase): string
    {
        $guid = isset($purchase->guid) ? $purchase->guid : now()->timestamp;
        $data = EmailPurchaseVoucherDto::makeByPurchase($purchase)->toArray();
        $pdf = SnappyPdf::loadView('pdf.voucher', [
            'data'      => $data,
            'tradeName' => $purchase->headquarter->trade_name,
            'config'    => $this->config
        ]);

        $filepath = "temp/{$guid}.pdf";
        Storage::disk('local')->put($filepath, $pdf->output());

        return $guid;
    }
}
