<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\Users\UpdateUserRequest;
use App\Http\Controllers\ApiController;
use App\Http\Resources\Users\ProfileResource;
use Illuminate\Http\Request;
use App\User;
use App\Http\Resources\Users\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Models\Users\Repositories\Interfaces\UserRepositoryInterface;
use Exception;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\API\Users\UpdateImageRequest;

class UsersController extends ApiController
{
    use AuthenticatesUsers;

    private $userRepository;

    public function __construct(
        UserRepositoryInterface $userRepositoryInterface
    ) {
        $this->userRepository = $userRepositoryInterface;
    }

    public function index()
    {
        $users = User::orderBy('id', 'ASC');
        return UserResource::collection($users->with('belongsToProfile')->get());
    }

    public function getProfile(){
        try {
            $user = Auth::user();
            $data = $this->userRepository->getDataProfile($user);
            $response = $this->successResponse(new ProfileResource($data));
        } catch (Exception $exception) {
            $message = 'Error obtener información del perfil. Inténtelo nuevamente.';
            $response = $this->errorResponse(['status' => 500, 'message' => $message, 'dev' => $exception], 500, $exception);
        }
        return $response;
    }

    public function editProfile(UpdateUserRequest $request){

        try {
            DB::beginTransaction();
            $data = $this->userRepository->editProfile(Auth::user(), $request);
            $response = $this->successResponse($data);
        } catch (Exception $exception) {
            DB::rollBack();
            $message = 'Error al editar la información del perfil. Inténtelo nuevamente.';
            $response = $this->errorResponse(['status' => 500, 'message' => $message, 'dev' => $exception], 500, $exception);

        }
        DB::commit();
        return $response;
    }

    public function editImageProfile(UpdateImageRequest $request){
        try {
            DB::beginTransaction();
            $user = Auth::user();
            $data = $this->userRepository->editImageProfile($user, $request);
            $response = $this->successResponse($data);
        } catch (Exception $exception) {
            $message = 'Error al editar la foto del perfil. Inténtelo nuevamente.';
            $response = $this->errorResponse(['status' => 500, 'message' => $message, 'dev' => $exception], 500, $exception);
            DB::rollBack();
        }
        DB::commit();
        return $response;
    }
}
