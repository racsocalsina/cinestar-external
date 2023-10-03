<?php

namespace App\Models\TypeDocuments;

use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    protected $table = 'document_types';
    protected $fillable = [
        'name',
        'code'
    ];
    protected $hidden = ['pivot'];
}
