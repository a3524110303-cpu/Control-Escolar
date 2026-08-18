<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class PasswordController extends Controller
{
    public function editFirst(): View
    {
        return view('auth.first-password');
    }

    public function updateFirst(Request $request): RedirectResponse
    {
        $data = $this->validatePassword($request, 'updatePassword');

        $request->user()->update([
            'password' => $data['password'],
            'primer_ingreso' => false,
        ]);

        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('status', 'password-updated');
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $this->validatePassword($request, 'updatePassword');

        $request->user()->update([
            'password' => $data['password'],
            'primer_ingreso' => false,
        ]);

        return back()->with('status', 'password-updated');
    }

    private function validatePassword(Request $request, string $bag): array
    {
        return $request->validateWithBag($bag, [
            'current_password' => ['required', 'current_password'],
            'password' => [
                'required',
                'confirmed',
                Password::min(12)->letters()->mixedCase()->numbers()->symbols(),
            ],
        ]);
    }
}
