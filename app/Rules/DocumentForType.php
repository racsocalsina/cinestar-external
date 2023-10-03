<?php

namespace App\Rules;

use App\Helpers\Helper;
use Illuminate\Contracts\Validation\Rule;

class DocumentForType implements Rule
{
    public $document_type;

    public function __construct($document_type)
    {
        $this->document_type = $document_type;
    }

    public function passes($attribute, $value)
    {
        if(!isset($this->document_type)) return false;
        return Helper::validateDocumentForType($value, $this->document_type);
    }

    public function message()
    {
        return 'El número de documento ingresado es incorrecto.';
    }
}
