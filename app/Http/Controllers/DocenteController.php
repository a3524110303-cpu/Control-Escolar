<?php

namespace App\Http\Controllers;

use App\Models\Docente;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class DocenteController extends Controller
{
    public function index(): View
    {
        return view('docentes.index', [
            'docentes' => Docente::withCount('cargasHorarias')->orderBy('apellido_paterno')->paginate(20),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'curp' => ['required', 'string', 'size:18', 'regex:/^[A-Z0-9]{18}$/i', Rule::unique(Docente::class), Rule::unique(User::class, 'identificador')],
            'nombre' => ['required', 'string', 'max:100'],
            'apellido_paterno' => ['required', 'string', 'max:100'],
            'apellido_materno' => ['nullable', 'string', 'max:100'],
            'correo_electronico' => ['required', 'email', Rule::unique(Docente::class), Rule::unique(User::class, 'email_recuperacion')],
            'telefono' => ['nullable', 'string', 'max:15'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Password::min(12)->mixedCase()->numbers()->symbols()],
        ]);

        DB::transaction(function () use ($data): void {
            Docente::create([
                ...$data,
                'curp' => strtoupper($data['curp']),
                'correo_electronico' => strtolower($data['correo_electronico']),
            ]);

            User::create([
                'identificador' => strtoupper($data['curp']),
                'email_recuperacion' => strtolower($data['correo_electronico']),
                'password' => $data['password'],
                'rol' => 'Docente',
                'estatus' => true,
                'primer_ingreso' => true,
            ]);
        });

        return back()->with('status', 'Docente y cuenta de acceso creados.');
    }

    public function destroy(Docente $docente): RedirectResponse
    {
        abort_if($docente->cargasHorarias()->exists(), 422, 'No se puede eliminar un docente con cargas asignadas.');

        DB::transaction(function () use ($docente): void {
            User::where('identificador', $docente->curp)->delete();
            $docente->delete();
        });

        return back()->with('status', 'Docente eliminado.');
    }
}
