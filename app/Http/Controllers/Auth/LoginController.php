<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function __construct(
        protected AuditService $audit
    ) {}

    public function showLoginForm()
    {
        return view('auth.login');
    }

    protected function throttleKey(Request $request): string
    {
        return Str::transliterate(Str::lower($request->input('login')).'|'.$request->ip());
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required|string|max:120',
            'password' => 'required|string',
        ]);

        $throttleKey = $this->throttleKey($request);

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'login' => "Demasiados intentos de acceso. Por favor intente nuevamente en {$seconds} segundos.",
            ]);
        }

        $usuario = Usuario::query()
            ->where(function ($q) use ($credentials) {
                $q->where('usuario', $credentials['login'])
                    ->orWhere('nombre_usuario', $credentials['login'])
                    ->orWhere('correo', $credentials['login']);
            })
            ->first();

        if (! $usuario || ! $usuario->estaActivo() || ! $this->passwordMatches($credentials['password'], $usuario->getAuthPassword())) {
            RateLimiter::hit($throttleKey, 60);
            throw ValidationException::withMessages([
                'login' => 'Credenciales incorrectas o usuario inactivo.',
            ]);
        }

        RateLimiter::clear($throttleKey);

        Auth::login($usuario, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    protected function passwordMatches(string $plain, string $stored): bool
    {
        if ($stored === '') {
            return false;
        }

        // Solo se acepta contraseña hasheada con bcrypt o argon.
        // Nunca se compara en texto plano.
        return Hash::check($plain, $stored);
    }
}

