<?php

namespace App\Console\Commands;

use App\Enums\PurchaseStatus;
use App\Jobs\Purchase\SendPurchaseEmail;
use App\Models\Purchases\Purchase;
use App\Models\Settings\Repositories\Interfaces\SettingRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ResendEmailPurchase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resend:voucher';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reenviar email de la compra';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $date = Carbon::parse('24-11-2022');
        $settingRepository = \App::make(SettingRepositoryInterface::class);
        $config = $settingRepository->getSystemConfiguration();
        $purchases = $this->purchaseTicket($date);
        $purchases = $purchases->merge($this->purchaseSweet($date));
        $purchases = $purchases->unique('id')->values();
        foreach ($purchases as $purchase) {
            SendPurchaseEmail::dispatch($purchase, $config);
        }
        return 0;
    }


    private function purchaseTicket($date)
    {
        return Purchase::where('status', PurchaseStatus::COMPLETED)
            ->whereDate('created_at', $date)
            ->whereHas('purchase_voucher', function ($query) {
                $query->whereNotNull('purchase_ticket_id')
                    ->whereHas('purchase_ticket', function ($query) {
                        $query->where('send_internal', PurchaseStatus::COMPLETED);
                    });
            })
            ->get();
    }

    private function purchaseSweet($date)
    {
        return Purchase::where('status', PurchaseStatus::COMPLETED)
            ->whereDate('created_at', $date)
            ->whereHas('purchase_voucher', function ($query) {
                $query->whereNotNull('purchase_sweet_id')
                    ->whereHas('purchase_sweet', function ($query) {
                        $query->where('send_internal', PurchaseStatus::COMPLETED);
                    });
            })
            ->get();
    }
}
