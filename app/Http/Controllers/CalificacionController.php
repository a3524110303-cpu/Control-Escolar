<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Calificacion;
use App\Models\CargaHoraria;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CalificacionController extends Controller
{
    public function store(Request $request, CargaHoraria $cargaHoraria, Alumno $alumno): RedirectResponse
    {
        $this->authorizeCarga($request, $cargaHoraria);
        abort_unless($alumno->grupo_id === $cargaHoraria->grupo_id, 422, 'El alumno no pertenece al grupo.');

        $data = $request->validate([
            'parcial_1' => ['nullable', 'numeric', 'between:0,10'],
            'parcial_2' => ['nullable', 'numeric', 'between:0,10'],
            'parcial_3' => ['nullable', 'numeric', 'between:0,10'],
            'observaciones_parcial_1' => ['nullable', 'string', 'max:500'],
            'observaciones_parcial_2' => ['nullable', 'string', 'max:500'],
            'observaciones_parcial_3' => ['nullable', 'string', 'max:500'],
        ]);

        $periodo = $cargaHoraria->periodo;
        $today = Carbon::today();

        foreach ([1, 2, 3] as $partial) {
            $field = "parcial_{$partial}";
            if ($request->filled($field)) {
                $start = $periodo->{"inicio_parcial_{$partial}"};
                $end = $periodo->{"fin_parcial_{$partial}"};
                abort_unless($today->betweenIncluded($start, $end), 422, "El parcial {$partial} está fuera del periodo de captura.");
            }
        }

        $calificacion = Calificacion::firstOrNew([
            'alumno_id' => $alumno->id,
            'carga_horaria_id' => $cargaHoraria->id,
        ]);

        foreach ([1, 2, 3] as $partial) {
            $score = "parcial_{$partial}";
            $notes = "observaciones_parcial_{$partial}";
            if (array_key_exists($score, $data) && $data[$score] !== null) {
                $calificacion->{$score} = $data[$score];
            }
            if (array_key_exists($notes, $data)) {
                $calificacion->{$notes} = $data[$notes];
            }
        }

        $scores = array_values(array_filter([
            $calificacion->parcial_1,
            $calificacion->parcial_2,
            $calificacion->parcial_3,
        ], fn (mixed $score): bool => $score !== null));

        $calificacion->promedio_final = $scores === []
            ? null
            : round(array_sum($scores) / count($scores), 2);
        $calificacion->save();

        return back()->with('status', 'Calificaciones guardadas correctamente.');
    }

    private function authorizeCarga(Request $request, CargaHoraria $carga): void
    {
        $user = $request->user()->loadMissing('docente');
        $allowed = $user->hasAnyRole('Administrador', 'Director')
            || ($user->rol === 'Docente' && $user->docente?->id === $carga->docente_id);

        abort_unless($allowed, 403);
    }
}
