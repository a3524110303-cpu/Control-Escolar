<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register', ['setup' => false]);
    }

    public function createSetup(): View
    {
        abort_if(User::query()->exists(), 404);

        return view('auth.register', ['setup' => true]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $this->validatedUser($request, false);

        return redirect()->route('users.create')
            ->with('status', "Usuario {$user->identificador} creado correctamente.");
    }

    public function storeSetup(Request $request): RedirectResponse
    {
        abort_if(User::query()->exists(), 404);
        $this->validatedUser($request, true);

        return redirect()->route('login')
            ->with('status', 'Administrador inicial creado. Ya puedes iniciar sesión.');
    }

    private function validatedUser(Request $request, bool $setup): User
    {
        $data = $request->validate([
            'identificador' => ['required', 'string', 'min:4', 'max:30', 'alpha_num:ascii', Rule::unique(User::class)],
            'email_recuperacion' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)],
            'rol' => $setup
                ? ['nullable']
                : ['required', Rule::in(User::ROLES)],
            'password' => ['required', 'confirmed', Password::min(12)->letters()->mixedCase()->numbers()->symbols()],
        ]);

        return User::create([
            'identificador' => strtoupper(trim($data['identificador'])),
            'email_recuperacion' => strtolower($data['email_recuperacion']),
            'password' => $data['password'],
            'rol' => $setup ? 'Administrador' : $data['rol'],
            'estatus' => true,
            'primer_ingreso' => true,
        ]);
    }
}
