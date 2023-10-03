<?php


namespace App\Http\Controllers\BackOffice\Settings;


use App\Http\Controllers\Controller;
use App\Http\Requests\BackOffice\Settings\SystemConfigurationRequest;
use App\Http\Resources\BackOffice\Settings\SystemConfigurationResource;
use App\Models\Settings\Repositories\Interfaces\SettingRepositoryInterface;
use App\Traits\ApiResponser;

class SystemConfigurationController extends Controller
{
    use ApiResponser;

    private SettingRepositoryInterface $settingRepository;

    public function __construct(SettingRepositoryInterface $settingRepository)
    {
        $this->settingRepository = $settingRepository;
        $this->middleware('permission:create-user', ['only' => ['store', 'index']]);
    }

    public function index()
    {
        $data = $this->settingRepository->getSystemConfiguration();
        return $this->success(new SystemConfigurationResource($data));
    }

    public function store(SystemConfigurationRequest $request)
    {
        $body = $request->validated();
        $data = $this->settingRepository->saveSystemConfiguration($body);
        return $this->success(new SystemConfigurationResource($data));
    }
}
