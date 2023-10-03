<?php


namespace App\Http\Controllers\BackOffice\Errors;


use App\Helpers\FunctionHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\BackOffice\Logs\InternalErrorResource;
use App\Http\Resources\BackOffice\Shared\ListCollection;
use App\Jobs\SendErrorEmail;
use App\Models\Headquarters\Repositories\Interfaces\HeadquarterRepositoryInterface;
use App\Models\InternalErrors\Repositories\Interfaces\InternalErrorRepositoryInterface;
use App\Services\Mail\Actions\BuildExceptionDto;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class InternalErrorController extends Controller
{
    use ApiResponser;

    private InternalErrorRepositoryInterface $repository;
    private HeadquarterRepositoryInterface $headquarterRepository;

    public function __construct(InternalErrorRepositoryInterface $repository, HeadquarterRepositoryInterface $headquarterRepository)
    {
        $this->repository = $repository;
        $this->headquarterRepository = $headquarterRepository;
        $this->middleware('permission:create-user', ['only' => ['index']]);
    }

    public function store(Request $request)
    {
        try {
            $body = $request->all();
            $this->repository->create($body);
        }  catch (\Exception $exception) {

            $message = "Error en el proceso del guardar la excepcion de internal";
            $exceptionDto = (new BuildExceptionDto($exception))->build();
            $exceptionDto->setSubject($message);
//            SendErrorEmail::dispatch($exceptionDto);
            FunctionHelper::sendErrorMail($exceptionDto);
            return $this->errorResponse(['message' => $message, 'dev' => $exception], 500, $exception);
        }

        return $this->success();
    }

    public function index(Request $request)
    {
        $data = $this->repository->searchBO($request->all());
        return InternalErrorResource::collection($data)->additional(['status' => 200]);
    }

    public function parameters()
    {
        $data = [
            'headquarters' => ListCollection::collection($this->headquarterRepository->all()),
        ];
        return $this->success($data);
    }
}
