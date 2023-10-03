<?php

namespace App\Http\Controllers\Auth;

use App\Models\Users\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterClientRequest;
use App\Http\Resources\Consumer\Customers\CustomerResource;
use Illuminate\Support\Facades\Auth;
use App\User;
use Exception;
use Illuminate\Support\Facades\DB;

class LoginApiController extends ApiController
{
    use AuthenticatesUsers;

    public $maxAttempts = 5; // change to the max attemp you want.
    public $decayMinutes = 5; // change to the minutes you want

    private $userRepository;
    private $roleRepository;
    private $registerRepository;

    protected $types_documents;

    /**
     * LoginApiController constructor.
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->types_documents = config('constants.types_documents');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response|void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function apiLogin(LoginRequest $request)
    {
        try {
            $this->validateLogin($request);
            if ($this->hasTooManyLoginAttempts($request)) {
                $this->fireLockoutEvent($request);
                return $this->sendLockoutResponse($request);
            }
            if ($this->attemptLogin($request)) {
                $this->clearLoginAttempts($request);
                $userLogin = User::where('username', $request->username)->first();
                $token = $this->createToken($userLogin);
                $token->token->save();
                $user = Auth::user();
                $dataCustomer = $this->userRepository->getDataProfile($user);
                $data = collect([
                    'id_user' => $user->id,
                    'username' => $user->username,
                    'create_at' => $token->token->created_at,
                    'expires_at' => $token->token->expires_at,
                    'access_token' => $token->accessToken
                ]);
                $result = $dataCustomer->merge($data);
                $customerResource = new CustomerResource($result);
                return $this->successResponse($customerResource, 200);
            } else {
                $this->incrementLoginAttempts($request);
                return $this->errorResponse(['status' => 422, 'message' => 'Usuario o clave incorrecta. Inténtelo nuevamente.'], 422);
            }
        } catch (Exception $exception) {
            logger($exception);
            $message = 'Error al iniciar sesion. Inténtelo nuevamente.';
            $response = $this->errorResponse(['status' => 500, 'message' => $message, 'dev' => $exception], 500, $exception);
        }
        return $response;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiLogout(Request $request)
    {
        $request->user()->token()->revoke();
        DB::table('oauth_access_tokens')
            ->where('user_id', '=', $request->user()->id)
            ->update(['revoked' => true]);
        return $this->success(['message' => 'Successfully logged out']);
    }

    /**
     * @param Request $request
     * @param User $user
     * @return mixed
     */
    protected function createToken($user)
    {
        $tokenResult = $user->createToken('Personal Access Token');
        return $tokenResult;
    }

    public function username()
    {
        return 'username';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(RegisterClientRequest $request)
    {
        try {
            DB::beginTransaction();
            [$user, $customer] = $this->userRepository->createCustomer($request);
            if ($this->hasTooManyLoginAttempts($request)) {
                $this->fireLockoutEvent($request);
                return $this->sendLockoutResponse($request);
            }
            $requestObj = new Request(array('username' => $customer->document_number, 'password' => $request->password));
            if ($this->attemptLogin($requestObj)) {
                $this->clearLoginAttempts($requestObj);
                $token = $this->createToken($user);
                $token->token->save();
            }
            $data = [
                'id_user' => $user->id,
                'username' => $user->username,
                'create_at' => $token->token->created_at,
                'expires_at' => $token->token->expires_at,
                'access_token' => $token->accessToken,
                'ticket_points' => 0,
                'candy_points' => 0,
                'image' => null,
            ];
            $result = array_merge($data, $customer->toArray());
            $response = $this->successResponse($result);
        } catch (Exception $exception) {
            DB::rollBack();
            $message = 'Error al crear usuario. Inténtelo nuevamente.';
            $response = $this->errorResponse(['status' => 500, 'message' => $message, 'dev' => $exception], 500, $exception);
        }
        DB::commit();
        return $response;
    }
}
