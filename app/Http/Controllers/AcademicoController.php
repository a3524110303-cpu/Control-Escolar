<?php

namespace App\Http\Controllers;

use App\Models\CargaHoraria;
use App\Models\Docente;
use App\Models\Grupo;
use App\Models\Materia;
use App\Models\Periodo;
use App\Models\PlanEstudio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AcademicoController extends Controller
{
    public function index(): View
    {
        return view('academico.index', [
            'planes' => PlanEstudio::withCount(['materias', 'alumnos'])->orderByDesc('vigente')->get(),
            'grupos' => Grupo::withCount('alumnos')->orderBy('semestre')->orderBy('nombre')->get(),
            'materias' => Materia::with('planEstudio')->orderBy('semestre')->orderBy('nombre')->get(),
            'periodos' => Periodo::orderByDesc('activo')->orderByDesc('id')->get(),
            'cargas' => CargaHoraria::with(['docente', 'materia', 'periodo', 'grupoEscolar'])->latest()->get(),
            'docentes' => Docente::orderBy('apellido_paterno')->get(),
        ]);
    }

    public function storePlan(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255', Rule::unique(PlanEstudio::class)],
            'vigente' => ['nullable', 'boolean'],
        ]);
        PlanEstudio::create(['nombre' => $data['nombre'], 'vigente' => $request->boolean('vigente')]);

        return back()->with('status', 'Plan de estudios creado.');
    }

    public function storeGrupo(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:20'],
            'semestre' => ['required', 'integer', 'between:1,6'],
            'turno' => ['required', Rule::in(['Matutino', 'Vespertino'])],
        ]);
        Grupo::firstOrCreate($data);

        return back()->with('status', 'Grupo guardado.');
    }

    public function storeMateria(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'semestre' => ['required', 'integer', 'between:1,6'],
            'plan_estudio_id' => ['required', 'exists:planes_estudio,id'],
        ]);
        Materia::firstOrCreate($data);

        return back()->with('status', 'Materia guardada.');
    }

    public function storePeriodo(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nombre_ciclo' => ['required', 'string', 'max:255'],
            'activo' => ['nullable', 'boolean'],
            'inicio_parcial_1' => ['required', 'date'],
            'fin_parcial_1' => ['required', 'date', 'after_or_equal:inicio_parcial_1'],
            'inicio_parcial_2' => ['required', 'date', 'after:fin_parcial_1'],
            'fin_parcial_2' => ['required', 'date', 'after_or_equal:inicio_parcial_2'],
            'inicio_parcial_3' => ['required', 'date', 'after:fin_parcial_2'],
            'fin_parcial_3' => ['required', 'date', 'after_or_equal:inicio_parcial_3'],
        ]);

        DB::transaction(function () use ($data, $request): void {
            if ($request->boolean('activo')) {
                Periodo::query()->update(['activo' => false]);
            }
            Periodo::create([...$data, 'activo' => $request->boolean('activo')]);
        });

        return back()->with('status', 'Periodo creado.');
    }

    public function storeCarga(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'docente_id' => ['required', 'exists:docentes,id'],
            'materia_id' => ['required', 'exists:materias,id'],
            'periodo_id' => ['required', 'exists:periodos,id'],
            'grupo_id' => ['required', 'exists:grupos,id'],
        ]);
        $grupo = Grupo::findOrFail($data['grupo_id']);
        $materia = Materia::findOrFail($data['materia_id']);
        abort_unless((int) $materia->semestre === (int) $grupo->semestre, 422, 'La materia y el grupo deben pertenecer al mismo semestre.');

        CargaHoraria::firstOrCreate($data, [
            'grado' => (string) $grupo->semestre,
            'grupo' => $grupo->nombre,
        ]);

        return back()->with('status', 'Carga horaria asignada.');
    }
}
