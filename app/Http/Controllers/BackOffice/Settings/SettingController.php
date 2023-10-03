<?php


namespace App\Http\Controllers\BackOffice\Settings;

use App\Http\Controllers\Controller;
use App\Http\Resources\BackOffice\Settings\SettingErpSystemVarCollection;
use App\Models\Settings\Repositories\Interfaces\SettingRepositoryInterface;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    use ApiResponser;

    private SettingRepositoryInterface $settingRepository;

    public function __construct(SettingRepositoryInterface $settingRepository)
    {
        $this->settingRepository = $settingRepository;
        $this->middleware('permission:create-user', ['only' => ['getErpSystemVars']]);
    }

    public function sync(Request $request){
        try {
            DB::beginTransaction();

            $body = $request->all();
            $this->settingRepository->sync($body);

            $response = $this->success();
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->internalErrorResponse($exception);
        }
        DB::commit();
        return $response;
    }

    public function getErpSystemVars()
    {
        $data = $this->settingRepository->getErpSystemVars();
        return $this->success(SettingErpSystemVarCollection::collection($data));
    }

}
