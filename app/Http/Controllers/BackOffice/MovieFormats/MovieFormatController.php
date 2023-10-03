<?php


namespace App\Http\Controllers\BackOffice\MovieFormats;


use App\Http\Controllers\Controller;
use App\Http\Requests\BackOffice\MovieFormats\MovieFormatDeleteRequest;
use App\Http\Requests\BackOffice\MovieFormats\MovieFormatRequest;
use App\Http\Resources\BackOffice\MovieFormats\MovieFormatResource;
use App\Models\MovieFormats\MovieFormat;
use App\Models\MovieFormats\Repositories\Interfaces\MovieFormatRepositoryInterface;
use App\Traits\ApiResponser;
use App\Traits\Controllers\ChangeStatus;

class MovieFormatController extends Controller
{
    use ApiResponser, ChangeStatus;

    private $repository;
    private $movieFormatRepository;

    public function __construct(
        MovieFormat $repository,
        MovieFormatRepositoryInterface $movieFormatRepository
    )
    {
        $this->repository =$repository;

        $this->middleware('permission:read-roomtype', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-roomtype', ['only' => ['store']]);
        $this->middleware('permission:update-roomtype', ['only' => ['update']]);
        $this->middleware('permission:delete-roomtype', ['only' => ['destroy']]);

        $this->movieFormatRepository = $movieFormatRepository;
    }

    public function index()
    {
        $data = $this->movieFormatRepository->all();
        return MovieFormatResource::collection($data)->additional(['status' => 200]);
    }

    public function store(MovieFormatRequest $request, MovieFormat $movieFormat)
    {
        $movieFormat = $this->movieFormatRepository->create($request->validated());

        return $this->created(
            new MovieFormatResource($movieFormat)
        );
    }

    public function update(MovieFormatRequest $request, MovieFormat $movieFormat)
    {
        $movieFormat = $this->movieFormatRepository->update($movieFormat, $request->validated());

        return $this->successResponse(
            new MovieFormatResource($movieFormat)
        );
    }

    public function destroy(MovieFormatDeleteRequest $request, MovieFormat $movieFormat)
    {
        $this->movieFormatRepository->delete($movieFormat);
        return $this->successResponse([]);
    }

}
