<?php

namespace App\Http\Controllers\Telescope;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TelescopeLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('telescope-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::guard('web')->attempt($credentials)) {
            // Autenticación exitosa, redirigir al dashboard de Telescope
            return redirect('/telescope');
        } else {
            // Autenticación fallida, redirigir de vuelta a la página de inicio de sesión con un mensaje de error
            return redirect()->back()->withErrors(['message' => 'Credenciales inválidas']);
        }
    }
}
