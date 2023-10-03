<?php


namespace App\Models\Admins;


use App\Models\Headquarters\Headquarter;
use App\Models\TypeDocuments\DocumentType;
use App\Package\Interfaces\Actions\ActivatableInterface;
use App\Traits\Models\Activatable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Laratrust\Traits\LaratrustUserTrait;

class Admin extends Authenticatable implements ActivatableInterface
{
    use Activatable;

    use LaratrustUserTrait;
    use HasApiTokens, Notifiable;

    protected $table = "admins";
    protected $guarded = ['id'];

    protected $dates = ['entry_date'];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function document_type()
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
    }

    public function headquarter()
    {
        return $this->belongsTo(Headquarter::class, 'headquarter_id');
    }
}
