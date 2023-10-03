<?php


namespace App\Http\Controllers\API\Share;


use App\Http\Controllers\ApiController;
use App\Models\TypeDocuments\Repositories\Interfaces\DocumentTypeRepositoryInterface;

class TypeDocumentsController extends ApiController
{
    private $documentTypeRepository;

    public function __construct(DocumentTypeRepositoryInterface $documentTypeRepository)
    {
        $this->documentTypeRepository = $documentTypeRepository;
    }

    public function index()
    {
        $res = $this->documentTypeRepository->listTypeDocuments();
        return $this->successResponse($res);
    }
}
