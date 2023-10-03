<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BillingProcessCompleted
{
    use Dispatchable, SerializesModels;
    
    public $purchaseId;
    
    public function __construct($purchaseId)
    {
        $this->purchaseId = $purchaseId;
    }
}