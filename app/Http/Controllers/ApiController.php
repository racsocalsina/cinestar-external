<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use App\Traits\ChangeDate;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;

class ApiController extends Controller
{
    use ApiResponser, ChangeDate;

    /**
     * @throws AuthorizationException
     */
    protected function allowedAdminAction()
    {
	    if (Gate::denies('admin-action')) {
            throw new AuthorizationException('Esta acción no te es permitida');
        }
    }
}
