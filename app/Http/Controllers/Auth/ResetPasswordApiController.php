<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\ApiController;
use App\Mail\Auth\ConfirmPasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Mail;

class ResetPasswordApiController extends ApiController
{
    use ResetsPasswords;

    /**
     * ResetPasswordApiController constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return array
     */
    protected function rulesReset()
    {
        return [
            'token' => 'required',
            'password' => 'required|confirmed|min:6',
        ];
    }

    /**
     * Get the password reset credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentialsReset(Request $request)
    {
        return $request->only(
            'password', 'password_confirmation', 'token'
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $request->validate($this->rulesReset(), $this->validationMessages());

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $user = User::find($request->userId);
        $user->password = bcrypt($request->password);
        $user->resetPassword = 0;
        $user->save();
        return response()->json(['status' => true]);
    }

    /**
     * @param $user
     * @return string
     */
    private function pushMail($user)
    {

        Mail::to($user)
            ->queue(new ConfirmPasswordReset($user));

        return 'passwords.reset';

    }

    /**
     * @return array
     */
    protected function validationMessages()
    {
        return $messsages = array(
            'password.confirmed' => trans('Las contraseñas ingresadas no son iguales'),
            'password.min' => trans('La contraseña debe tener al menos 6 dígitos'),
        );
    }


}
