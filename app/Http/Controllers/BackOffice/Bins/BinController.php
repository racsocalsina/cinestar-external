<?php


namespace App\Http\Controllers\BackOffice\Bins;


use App\Http\Controllers\Controller;
use App\Models\Bins\Repositories\Interfaces\BinRepositoryInterface;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BinController extends Controller
{
    use ApiResponser;

    private BinRepositoryInterface $repository;

    public function __construct(BinRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function sync(Request $request)
    {
        try {
            DB::beginTransaction();
            $res = $this->repository->sync($request->all());
        }  catch (\Exception $exception) {
            DB::rollBack();
            return $this->internalErrorResponse($exception);
        }
        DB::commit();
        return $res;
    }
}