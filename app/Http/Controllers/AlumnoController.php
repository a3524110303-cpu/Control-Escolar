<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Grupo;
use App\Models\PlanEstudio;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AlumnoController extends Controller
{
    public function index(): View
    {
        return view('alumnos.index', [
            'alumnos' => Alumno::with(['planEstudio', 'grupo'])->orderBy('apellido_paterno')->paginate(20),
            'planes' => PlanEstudio::where('vigente', true)->orderBy('nombre')->get(),
            'grupos' => Grupo::orderBy('semestre')->orderBy('nombre')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nia' => ['required', 'alpha_num:ascii', 'max:30', Rule::unique(Alumno::class), Rule::unique(User::class, 'identificador')],
            'curp' => ['required', 'string', 'size:18', 'regex:/^[A-Z0-9]{18}$/i', Rule::unique(Alumno::class)],
            'nombre' => ['required', 'string', 'max:100'],
            'apellido_paterno' => ['required', 'string', 'max:100'],
            'apellido_materno' => ['nullable', 'string', 'max:100'],
            'fecha_nacimiento' => ['required', 'date', 'before:today'],
            'genero' => ['required', Rule::in(['Masculino', 'Femenino', 'Otro'])],
            'plan_estudio_id' => ['required', 'exists:planes_estudio,id'],
            'grupo_id' => ['required', 'exists:grupos,id'],
            'correo_electronico' => ['required', 'email', Rule::unique(Alumno::class), Rule::unique(User::class, 'email_recuperacion')],
            'password' => ['required', 'confirmed', Password::min(12)->mixedCase()->numbers()->symbols()],
        ]);

        $grupo = Grupo::findOrFail($data['grupo_id']);

        DB::transaction(function () use ($data, $grupo): void {
            Alumno::create([
                ...$data,
                'nia' => strtoupper($data['nia']),
                'curp' => strtoupper($data['curp']),
                'semestre_actual' => (string) $grupo->semestre,
                'grupo_actual' => $grupo->nombre,
                'turno' => $grupo->turno,
            ]);

            User::create([
                'identificador' => strtoupper($data['nia']),
                'email_recuperacion' => strtolower($data['correo_electronico']),
                'password' => $data['password'],
                'rol' => 'Alumno',
                'estatus' => true,
                'primer_ingreso' => true,
            ]);
        });

        return back()->with('status', 'Alumno y cuenta de acceso creados.');
    }

    public function destroy(Alumno $alumno): RedirectResponse
    {
        DB::transaction(function () use ($alumno): void {
            User::where('identificador', $alumno->nia)->delete();
            $alumno->delete();
        });

        return back()->with('status', 'Alumno eliminado.');
    }
}
