<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Helper;
use App\Http\Requests\Auth\RecoverPasswordApp;
use App\Http\Requests\Auth\ResetPasswordApp;
use App\Jobs\SendRecoverPasswordEmail;
use App\Models\Customers\Customer;
use App\Models\PasswordResets\PasswordReset;
use App\Models\Users\Repositories\Interfaces\UserRepositoryInterface;
use App\Http\Controllers\ApiController;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ForgotPasswordApiController extends ApiController
{
    use SendsPasswordResetEmails;

    /**
     * Constant representing the user not found response.
     *
     * @var string
     */
    const INVALID_USER = 'passwords.user';

    private $userRepository;

    /**
     * ForgotPasswordApiController constructor.
     */
    public function __construct(
        UserRepositoryInterface $userRepositoryInterface
    ) {
        $this->userRepository = $userRepositoryInterface;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendLinkEmail(Request $request)
    {
        try {
            if(!isset($request->origin)){
                return $this->errorResponse(['status' => 422, 'message' => "Faltan datos"], 422);
            }
            if($request->origin === 'web'){
                if(!isset($request->email)){
                    return $this->errorResponse(['status' => 422, 'message' => "Ingrese su correo electrónico "], 422);
                }
                $customer = Customer::where('email', $request->email)->first();
                if(!isset($customer->id)){
                    return $this->errorResponse(['status' => 422, 'message' => "Correo electrónico no registrado"], 422);
                }
            }else{
                if(!isset($request->nro_document)){
                    return $this->errorResponse(['status' => 422, 'message' => "Ingrese su número de documento"], 422);
                }
                $customer = Customer::where('document_number', $request->nro_document)->first();
                if(!isset($customer->id)){
                    return $this->errorResponse(['status' => 422, 'message' => "Número de documento no registrado"], 422);
                }
            }
            if(!isset($customer->id)){
                return $this->errorResponse(['status' => 422, 'message' => "Usuario no registrado"], 422);
            }
            $passwordReset = PasswordReset::create(
                [
                    'email'      => $customer->email,
                    'token'      => Str::random(60),
                    'created_at' => Carbon::now()
                ]
            );
            $this->pushMail($customer, $passwordReset->token);
            $response = $this->successResponse(['status' => 200, 'message' => "Se envio un mail para que restablescas tu contraseña"]);
        } catch (Exception $exception) {
            $message = 'Error al recuperar la contraseña. Inténtelo nuevamente.';
            $response = $this->errorResponse(['status' => 500, 'message' => $message, 'dev' => $exception], 500, $exception);
        }
        return $response;
    }

    public function sendSms(RecoverPasswordApp $request) {

        $document_number = $request->document_number;
        $cellphone = $request->cellphone;

        $user = User::where('username', $document_number)
            ->whereHas('customer', function ($query) use ($cellphone) {
                return $query->where('cellphone', $cellphone);
            })
            ->first();
        $message = 'No se encontró un usuario que coincida con los datos ingresados';
        if (!$user) return $this->errorResponse(['message' => $message, 'dev' => 'DNI o celular no registrado'], 404);

        $code = rand(100000,999999);

        //$this->dispatch(new SendVerificationMessage($customer->cellphone, $code));

        return $this->successResponse([
            'code' => $code
        ]);
    }

    public function resetPassword(ResetPasswordApp $request) {

        $user = User::where('username', $request->document_number)->first();

        if (!$user) return $this->errorResponse(['message' => 'Usuario no registrado', 'dev' => 'DNI no registrado'], 404);

        $validate = Helper::validatePassword($request->password);
        if(!$validate[0]){
            return $this->errorResponse(['status' => 422, 'message' => $validate[1]], 422);
        }

        $validate = Helper::validatePassword($request->password_confirmation);
        if(!$validate[0]){
            return $this->errorResponse(['status' => 422, 'message' => $validate[1]], 422);
        }

        if($request->password !== $request->password_confirmation){
            return $this->errorResponse(['status' => 422, 'message' => "Las contraseñas ingresadas no son iguales"], 422);
        }

        $user->update(['password' => bcrypt($request->password)]);

        return $this->successResponse(['message' => 'La contraseña se actualizó correctamente' ]);
    }

    public function changePassword(Request $request){
        try {
            //-----------------------------------------------------------------
            //VALIDAR QUE TODOS LOS CAMPOS HAYAN SIDO INGRESADOS
            //-----------------------------------------------------------------
            if(!isset($request->token)){
                return $this->errorResponse(['status' => 422, 'message' => "Error al cambiar la contraseña. Inténtelo nuevamente."], 422);
            }
            if(!isset($request->origin)){
                return $this->errorResponse(['status' => 422, 'message' => "Error al cambiar la contraseña. Inténtelo nuevamente."], 422);
            }
            if($request->origin === 'web'){
                if(!isset($request->email)){
                    return $this->errorResponse(['status' => 422, 'message' => "Ingrese su correo electrónico "], 422);
                }
            }else{
                if(!isset($request->nro_document)){
                    return $this->errorResponse(['status' => 422, 'message' => "Ingrese su número de documento"], 422);
                }
            }
            if(!isset($request->password)){
                return $this->errorResponse(['status' => 422, 'message' => "Falta ingresar la contraseña"], 422);
            }
            if(!isset($request->password_confirmation)){
                return $this->errorResponse(['status' => 422, 'message' => "Falta ingresar la contraseña"], 422);
            }
            //-----------------------------------------------------------------
            //VALIDACION DE CONTRASEÑAS
            //-----------------------------------------------------------------
            if(strlen($request->password) < 6){
                return $this->errorResponse(['status' => 422, 'message' => "La contraseña debe tener minimo 6 caracteres"], 422);
            }
            if(strlen($request->password_confirmation) < 6){
                return $this->errorResponse(['status' => 422, 'message' => "La contraseña debe tener minimo 6 caracteres"], 422);
            }
            $validate = Helper::validatePassword($request->password);
            if(!$validate[0]){
                return $this->errorResponse(['status' => 422, 'message' => $validate[1]], 422);
            }
            $validate = Helper::validatePassword($request->password_confirmation);
            if(!$validate[0]){
                return $this->errorResponse(['status' => 422, 'message' => $validate[1]], 422);
            }
            if($request->password !== $request->password_confirmation){
                return $this->errorResponse(['status' => 422, 'message' => "Las contraseñas ingresadas no son iguales"], 422);
            }

            if($request->origin === 'web'){
                $customer = Customer::where('email', $request->email)->first();
            }else{
                $customer = Customer::where('document_number', $request->nro_document)->first();
            }
            if(!isset($customer->email)){
                return $this->errorResponse(['status' => 422, 'message' => "No se encontro al usuario"], 422);
            }
            $isValidateToke = $this->validateToken($request->token, $customer->email);
            if(!$isValidateToke){
                return $this->errorResponse(['status' => 422, 'message' => "Error al cambiar la contraseña. Inténtelo nuevamente."], 422);
            }
            $this->userRepository->changePassword($request);
            $response = $this->successResponse(['status' => 200]);
        } catch (Exception $exception) {
            $message = 'Error al cambiar la contraseña. Inténtelo nuevamente.';
            $response = $this->errorResponse(['status' => 500, 'message' => $message, 'dev' => $exception], 500, $exception);
        }

        return $response;
    }

    /**
     * @param $user
     * @return string
     */
    private function pushMail($customer, $token)
    {
        if (is_null($customer)) {
            return static::INVALID_USER;
        }

        $tradeName = trim(strtoupper($customer->trade_name));
        $envName = "{$tradeName}_FRONTEND_URL";
        $url = Helper::addSlashToUrl(env($envName));

        $url = $url.'change-password?token='.$token.'&email='.$customer->email;
        $params = [
            'params_user' => $customer->name,
            'params_url' => $url,
            'trade_name' => $tradeName,
            'email' => $customer->email
        ];

        SendRecoverPasswordEmail::dispatch($params);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    private function validateForm(Request $request){
        $messsages = array(
            'email.required'=> trans('passwords.user'),
            'email'=> trans('Introduce una dirección de correo electrónico válida.'),
        );

        $rules = array(
            'email'=>'required|email',
        );

        return $request->validate($rules,$messsages);
    }

    private function validateToken($token, $email): bool{
        $passwordReset = PasswordReset::where([
            'email' => $email,
            'token' => $token
        ])->first();
        if(!isset($passwordReset->email)){
            return false;
        }
        return true;
    }
}
