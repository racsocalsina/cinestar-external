<?php


namespace App\Http\Controllers\BackOffice\Headquarters;


use App\Http\Controllers\Controller;
use App\Http\Requests\BackOffice\HeadquarterImages\HeadquarterImageRequest;
use App\Http\Resources\BackOffice\Headquarters\HeadquarterResource;
use App\Models\HeadquarterImages\HeadquarterImage;
use App\Models\HeadquarterImages\Repositories\Interfaces\HeadquarterImageRepositoryInterface;
use App\Models\Headquarters\Headquarter;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\DB;

class HeadquarterImageController extends Controller
{
    use ApiResponser;

    private $headquarterImageRepository;

    public function __construct(HeadquarterImageRepositoryInterface $headquarterImageRepository)
    {
        $this->headquarterImageRepository = $headquarterImageRepository;
    }

    public function store(Headquarter $headquarter, HeadquarterImageRequest $request)
    {
        try {
            DB::beginTransaction();

            $images = $request->file('files');
            $this->headquarterImageRepository->create($headquarter, $images);
            $response = $this->successResponse([]);
        } catch (\Exception $exception) {
            $message = 'Error al crear la imagen de la sede. Inténtelo nuevamente.';
            $response = $this->errorResponse(['message' => $message, 'dev' => $exception], 500, $exception);
            DB::rollBack();
        }
        DB::commit();
        return $response;
    }

    public function destroy(HeadquarterImage $headquarterImage)
    {
        $this->headquarterImageRepository->delete($headquarterImage);
        return $this->successResponse([]);
    }

    public function markAsMain(HeadquarterImage $headquarterImage)
    {
        $this->headquarterImageRepository->markAsMain($headquarterImage);
        return $this->successResponse([]);
    }
}
