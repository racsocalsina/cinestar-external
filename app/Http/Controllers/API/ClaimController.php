<?php


namespace App\Http\Controllers\API;


use App\Helpers\ListHelper;
use App\Http\Requests\Claim\ClaimRequest;
use App\Models\Claim\Claim;
use App\Models\Claim\ClaimIdentificationType;
use App\Models\Claim\ClaimType;
use App\Models\Claim\Repositories\Interfaces\ClaimRepositoryInterface;
use App\Models\TypeDocuments\DocumentType;
use App\Traits\ApiResponser;

class ClaimController
{
    use ApiResponser;

    private $claimRepository;

    public function __construct(ClaimRepositoryInterface $claimRepository)
    {
        $this->claimRepository = $claimRepository;
    }

    public function parameters()
    {
        return [
            'types'                => ClaimType::orderBy('name')->get(),
            'identification_types' => ClaimIdentificationType::orderBy('name')->get(),
            'document_types'       => DocumentType::orderBy('name')->get(),
            'older_list'           => ListHelper::getOlderList(),
        ];
    }

    public function store(ClaimRequest $request)
    {
        $data = $this->claimRepository->create($request->validated());
        return $this->created([
            'id' => $data->id
        ]);
    }

    public function download(Claim $claim)
    {
        $file = $this->claimRepository->buildPDFClaimDocument($claim);
        return $file->download('Documento-de-sustento.pdf');
    }
}
