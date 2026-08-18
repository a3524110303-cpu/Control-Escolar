<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\CargaHoraria;
use App\Models\Docente;
use App\Models\Grupo;
use App\Models\Materia;
use App\Models\Tramite;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user()->loadMissing(['alumno', 'docente']);
        $counts = [];
        $cargas = collect();
        $tramite = null;

        if ($user->hasAnyRole('Administrador', 'Director')) {
            $counts = [
                'Alumnos' => Alumno::count(),
                'Docentes' => Docente::count(),
                'Grupos' => Grupo::count(),
                'Materias' => Materia::count(),
                'Trámites pendientes' => Tramite::where('estatus', 'Pendiente')->count(),
            ];
        } elseif ($user->rol === 'Docente' && $user->docente) {
            $cargas = CargaHoraria::with(['materia', 'grupoEscolar', 'periodo'])
                ->where('docente_id', $user->docente->id)
                ->get();
        } elseif ($user->rol === 'Alumno' && $user->alumno) {
            $tramite = $user->alumno->tramites()->latest()->first();
        }

        return view('dashboard', compact('user', 'counts', 'cargas', 'tramite'));
    }
}
