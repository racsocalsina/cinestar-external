<?php


namespace App\Models\Contact\Repositories;


use App\Helpers\Helper;
use App\Jobs\SendContactEmail;
use App\Models\Contact\Contact;
use App\Models\Contact\Repositories\Interfaces\ContactRepositoryInterface;

class ContactRepository implements ContactRepositoryInterface
{
    public function create($body)
    {
        $body['trade_name'] = Helper::getTradeNameHeader();
        $data = Contact::create($body);

        SendContactEmail::dispatch($data);

        return $data;
    }
}
