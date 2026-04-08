<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'El usuario es obligatorio.',
            'password.required' => 'La contrasena es obligatoria.',
        ]);

        $normalizedUsername = mb_strtolower(trim($credentials['username']));

        $user = User::query()
            ->whereRaw('LOWER(username) = ?', [$normalizedUsername])
            ->first();

        if (! $user || ! $user->estado || ! Hash::check($credentials['password'], $user->password)) {
            return back()
                ->withInput($request->only('username'))
                ->withErrors([
                    'username' => 'Las credenciales no coinciden con nuestros registros o el usuario esta inactivo.',
                ]);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'))
            ->with('status', 'Bienvenido de nuevo.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('status', 'Tu sesion se cerro correctamente.');
    }
}
