<?php


namespace App\Models\Claim;

use App\Models\TypeDocuments\DocumentType;
use App\Models\Ubigeo\UbDistrict;
use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    protected $guarded = ['id'];

    public function type()
    {
        return $this->belongsTo(ClaimType::class, 'claim_type_id');
    }

    public function identification_type()
    {
        return $this->belongsTo(ClaimIdentificationType::class, 'identification_type_id');
    }

    public function document_type()
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
    }

    public function sede_district()
    {
        return $this->hasOne(UbDistrict::class, 'id', 'sede_district_id');
    }

    public function person_district()
    {
        return $this->hasOne(UbDistrict::class, 'id', 'person_district_id');
    }
}
