<?php


namespace App\Http\Controllers\BackOffice\Rooms;


use App\Http\Controllers\Controller;
use App\Models\Rooms\Repositories\Interfaces\RoomRepositoryInterface;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    use ApiResponser;

    private RoomRepositoryInterface $repository;

    public function __construct(RoomRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function sync(Request $request){
        try {
            DB::beginTransaction();
            $this->repository->sync($request->all());
            $response = $this->success();
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->internalErrorResponse($exception);
        }
        DB::commit();
        return $response;
    }
}
