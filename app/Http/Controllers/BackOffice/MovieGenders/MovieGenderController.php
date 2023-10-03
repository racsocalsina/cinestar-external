<?php


namespace App\Http\Controllers\BackOffice\MovieGenders;


use App\Http\Controllers\Controller;
use App\Http\Requests\BackOffice\MovieGenders\MovieGenderDeleteRequest;
use App\Http\Requests\BackOffice\MovieGenders\MovieGenderRequest;
use App\Http\Resources\BackOffice\MovieGenders\MovieGenderResource;
use App\Models\MovieGenders\MovieGender;
use App\Models\MovieGenders\Repositories\Interfaces\MovieGenderRepositoryInterface;;
use App\Traits\ApiResponser;
use App\Traits\Controllers\ChangeStatus;
use Illuminate\Http\Request;

class MovieGenderController extends Controller
{
    use ApiResponser, ChangeStatus;

    private $repository;
    private $movieGenderRepository;

    public function __construct(
        MovieGender $repository,
        MovieGenderRepositoryInterface $movieGenderRepository
    )
    {
        $this->repository = $repository;
        $this->movieGenderRepository = $movieGenderRepository;

        $this->middleware('permission:read-moviegenre', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-moviegenre', ['only' => ['store']]);
        $this->middleware('permission:update-moviegenre', ['only' => ['update']]);
        $this->middleware('permission:delete-moviegenre', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $data = $this->movieGenderRepository->search($request->all(), false);
        return MovieGenderResource::collection($data)->additional(['status' => 200]);
    }

    public function store(MovieGenderRequest $request, MovieGender $movieGender)
    {
        $data = $this->movieGenderRepository->create($request->validated());

        return $this->created(
            new MovieGenderResource($data)
        );
    }

    public function show(MovieGender $movieGender)
    {
        return $this->successResponse(
            new MovieGenderResource($movieGender)
        );
    }

    public function update(MovieGenderRequest $request, MovieGender $movieGender)
    {
        $data = $this->movieGenderRepository->update($movieGender, $request->validated());

        return $this->successResponse(
            new MovieGenderResource($data)
        );
    }

    public function destroy(MovieGenderDeleteRequest $request, MovieGender $movieGender)
    {
        $this->movieGenderRepository->delete($movieGender);
        return $this->successResponse([]);
    }

}
