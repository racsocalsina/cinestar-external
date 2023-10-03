<?php


namespace App\Http\Controllers\BackOffice\ContentManagements;


use App\Enums\ContentManagementCodeKey;
use App\Http\Controllers\Controller;
use App\Http\Requests\BackOffice\ContentManagements\ContentManagementGetRequest;
use App\Http\Requests\BackOffice\ContentManagements\ContentManagementPopupBannerStoreRequest;
use App\Models\ContentManagements\Interfaces\ContentManagementRepositoryInterface;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\DB;

class PopupBannerController extends Controller
{
    use ApiResponser;

    private ContentManagementRepositoryInterface $contentManagementRepository;

    public function __construct(
        ContentManagementRepositoryInterface $contentManagementRepository
    )
    {
        $this->contentManagementRepository = $contentManagementRepository;
        $this->middleware('permission:update-contentmanagement');
    }

    public function show(ContentManagementGetRequest $request)
    {
        $data = $this->contentManagementRepository->get(ContentManagementCodeKey::POPUP_BANNER, $request->trade_name, true);
        return $this->success($data);
    }

    public function store(ContentManagementPopupBannerStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $this->contentManagementRepository->updatePopupBanner($request);
            DB::commit();
            return $this->success($data);
        } catch (\Exception $exception) {
            DB::rollBack();
            $message = 'Error al actualizar. Inténtelo nuevamente.';
            return $this->errorResponse(['message' => $message, 'dev' => $exception], 500, $exception);
        }
    }
}
