<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Asistencia;
use App\Models\CargaHoraria;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AsistenciaController extends Controller
{
    public function store(Request $request, CargaHoraria $cargaHoraria): RedirectResponse
    {
        $this->authorizeCarga($request, $cargaHoraria);

        $data = $request->validate([
            'fecha' => ['required', 'date', 'before_or_equal:today'],
            'asistencias' => ['required', 'array', 'min:1'],
            'asistencias.*' => ['required', Rule::in(['Asistencia', 'Falta', 'Justificado'])],
        ]);

        $alumnoIds = array_map('intval', array_keys($data['asistencias']));
        $validIds = Alumno::where('grupo_id', $cargaHoraria->grupo_id)
            ->whereIn('id', $alumnoIds)
            ->pluck('id')
            ->all();

        abort_unless(count($validIds) === count(array_unique($alumnoIds)), 422, 'Hay alumnos que no pertenecen al grupo.');

        DB::transaction(function () use ($data, $cargaHoraria): void {
            foreach ($data['asistencias'] as $alumnoId => $estatus) {
                Asistencia::updateOrCreate(
                    [
                        'alumno_id' => (int) $alumnoId,
                        'carga_horaria_id' => $cargaHoraria->id,
                        'fecha' => $data['fecha'],
                    ],
                    ['estatus' => $estatus]
                );
            }
        });

        return back()->with('status', 'Asistencias guardadas correctamente.');
    }

    private function authorizeCarga(Request $request, CargaHoraria $carga): void
    {
        $user = $request->user()->loadMissing('docente');
        $allowed = $user->hasAnyRole('Administrador', 'Director')
            || ($user->rol === 'Docente' && $user->docente?->id === $carga->docente_id);

        abort_unless($allowed, 403);
    }
}
