<?php

use App\Http\Controllers\AcademicoController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\CalificacionController;
use App\Http\Controllers\CargaHorariaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TramiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => auth()->check()
    ? redirect()->route('dashboard')
    : redirect()->route('login'));

Route::middleware(['auth', 'password.changed'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::middleware('role:Administrador,Director')->group(function () {
        Route::get('/alumnos', [AlumnoController::class, 'index'])->name('alumnos.index');
        Route::post('/alumnos', [AlumnoController::class, 'store'])->name('alumnos.store');
        Route::delete('/alumnos/{alumno}', [AlumnoController::class, 'destroy'])->name('alumnos.destroy');

        Route::get('/docentes', [DocenteController::class, 'index'])->name('docentes.index');
        Route::post('/docentes', [DocenteController::class, 'store'])->name('docentes.store');
        Route::delete('/docentes/{docente}', [DocenteController::class, 'destroy'])->name('docentes.destroy');

        Route::get('/academico', [AcademicoController::class, 'index'])->name('academico.index');
        Route::post('/academico/planes', [AcademicoController::class, 'storePlan'])->name('academico.planes.store');
        Route::post('/academico/grupos', [AcademicoController::class, 'storeGrupo'])->name('academico.grupos.store');
        Route::post('/academico/materias', [AcademicoController::class, 'storeMateria'])->name('academico.materias.store');
        Route::post('/academico/periodos', [AcademicoController::class, 'storePeriodo'])->name('academico.periodos.store');
        Route::post('/academico/cargas', [AcademicoController::class, 'storeCarga'])->name('academico.cargas.store');

        Route::patch('/tramites/{tramite}', [TramiteController::class, 'update'])->name('tramites.update');
    });

    Route::middleware('role:Administrador,Director,Docente')->group(function () {
        Route::get('/cargas/{cargaHoraria}', [CargaHorariaController::class, 'show'])->name('cargas.show');
        Route::post('/cargas/{cargaHoraria}/asistencias', [AsistenciaController::class, 'store'])->name('asistencias.store');
        Route::post('/cargas/{cargaHoraria}/alumnos/{alumno}/calificaciones', [CalificacionController::class, 'store'])
            ->name('calificaciones.store');
    });

    Route::post('/tramites', [TramiteController::class, 'store'])
        ->middleware('role:Alumno')
        ->name('tramites.store');
    Route::get('/tramites/{tramite}/descargar', [TramiteController::class, 'download'])->name('tramites.download');
});

require __DIR__.'/auth.php';
