<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\CargaHoraria;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CargaHorariaController extends Controller
{
    public function show(Request $request, CargaHoraria $cargaHoraria): View
    {
        $this->authorizeCarga($request, $cargaHoraria);
        $cargaHoraria->load(['docente', 'materia', 'periodo', 'grupoEscolar']);

        $alumnos = Alumno::where('grupo_id', $cargaHoraria->grupo_id)
            ->with(['calificaciones' => fn ($query) => $query->where('carga_horaria_id', $cargaHoraria->id)])
            ->orderBy('apellido_paterno')
            ->get();

        return view('cargas.show', compact('cargaHoraria', 'alumnos'));
    }

    private function authorizeCarga(Request $request, CargaHoraria $carga): void
    {
        $user = $request->user()->loadMissing('docente');
        $allowed = $user->hasAnyRole('Administrador', 'Director')
            || ($user->rol === 'Docente' && $user->docente?->id === $carga->docente_id);

        abort_unless($allowed, 403);
    }
}
