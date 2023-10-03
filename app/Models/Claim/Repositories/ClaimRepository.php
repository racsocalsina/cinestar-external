<?php


namespace App\Models\Claim\Repositories;


use App\Helpers\FunctionHelper;
use App\Helpers\Helper;
use App\Jobs\SendClaimEmail;
use App\Models\Claim\Claim;
use App\Models\Claim\Repositories\Interfaces\ClaimRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class ClaimRepository implements ClaimRepositoryInterface
{
    public function create($body)
    {
        $body['code'] = (int)now()->timestamp;
        $body['trade_name'] = Helper::getTradeNameHeader();
        $data = Claim::create($body);

        $this->sendEmail($data);

        return $data;
    }

    public function buildPDFClaimDocument(Claim $model)
    {
        $data = Claim::with([
            'sede_district.province',
            'sede_district.department',
            'person_district.province',
            'person_district.department',
            'identification_type',
            'type',
            'document_type'
        ])
            ->whereId($model->id)
            ->first();

        return \PDF::loadView('pdf.claim-document', compact('data'));
    }

    private function sendEmail(Claim $claim)
    {
        // build pdf
        $pdf = $this->buildPDFClaimDocument($claim);

        // send email
        $path = 'temp/' . FunctionHelper::createGuid() . '.pdf';
        Storage::disk('local')->put($path, $pdf->output());
        SendClaimEmail::dispatch($path, $claim);
    }

}
