<?php


namespace App\Http\Controllers\BackOffice\MovieTimeTariffs;

use App\Http\Controllers\Controller;
use App\Http\Requests\Consumer\MovieTimeTariff\SyncMovieTimeTariffeRequest;
use App\Models\MovieTimeTariffs\Repositories\Interfaces\MovieTimeTariffRepositoryInterface;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MovieTimeTariffController extends Controller
{
    use ApiResponser;

    private $movieTimeTariffRepository;

    public function __construct(
        MovieTimeTariffRepositoryInterface $movieTimeTariffRepository
    ) {
        $this->movieTimeTariffRepository = $movieTimeTariffRepository;
    }

    public function sync(SyncMovieTimeTariffeRequest $request){
        try {
            DB::beginTransaction();
            $this->movieTimeTariffRepository->sync($request->all());
            $response = $this->success();
        } catch (Exception $exception) {
            DB::rollBack();
            return $this->internalErrorResponse($exception);
        }
        DB::commit();
        return $response;
    }
}
