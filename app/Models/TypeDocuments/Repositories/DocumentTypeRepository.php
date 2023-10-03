<?php


namespace App\Models\TypeDocuments\Repositories;

use App\Models\TypeDocuments\DocumentType;
use App\Models\TypeDocuments\Repositories\Interfaces\DocumentTypeRepositoryInterface;

class DocumentTypeRepository implements DocumentTypeRepositoryInterface
{

    public function listTypeDocuments()
    {
        return DocumentType::all();
    }
}
