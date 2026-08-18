<?php

namespace Tests\Feature;

use App\Models\Alumno;
use App\Models\CargaHoraria;
use App\Models\Docente;
use App\Models\Grupo;
use App\Models\Materia;
use App\Models\Periodo;
use App\Models\PlanEstudio;
use App\Models\Tramite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AcademicWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_can_open_only_assigned_load(): void
    {
        $context = $this->academicContext();

        $this->actingAs($context['teacherUser'])
            ->get(route('cargas.show', $context['carga']))
            ->assertOk()
            ->assertSee($context['materia']->nombre);

        $other = User::factory()->role('Docente')->create();
        $this->actingAs($other)->get(route('cargas.show', $context['carga']))->assertForbidden();
    }

    public function test_teacher_can_record_attendance_for_group_students(): void
    {
        $context = $this->academicContext();

        $this->actingAs($context['teacherUser'])->post(route('asistencias.store', $context['carga']), [
            'fecha' => today()->toDateString(),
            'asistencias' => [$context['alumno']->id => 'Asistencia'],
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('asistencias', [
            'alumno_id' => $context['alumno']->id,
            'carga_horaria_id' => $context['carga']->id,
            'estatus' => 'Asistencia',
        ]);
    }

    public function test_teacher_can_record_grade_during_open_partial(): void
    {
        $context = $this->academicContext();

        $this->actingAs($context['teacherUser'])->post(
            route('calificaciones.store', [$context['carga'], $context['alumno']]),
            ['parcial_1' => 8.5]
        )->assertSessionHasNoErrors();

        $this->assertDatabaseHas('calificaciones', [
            'alumno_id' => $context['alumno']->id,
            'carga_horaria_id' => $context['carga']->id,
            'parcial_1' => 8.50,
            'promedio_final' => 8.50,
        ]);
    }

    public function test_student_can_upload_and_admin_can_review_document(): void
    {
        Storage::fake('local');
        $context = $this->academicContext();

        $this->actingAs($context['studentUser'])->post(route('tramites.store'), [
            'documento' => UploadedFile::fake()->create('documento.pdf', 100, 'application/pdf'),
        ])->assertSessionHasNoErrors();

        $tramite = Tramite::firstOrFail();
        Storage::disk('local')->assertExists($tramite->ruta_pdf);

        $admin = User::factory()->create();
        $this->actingAs($admin)->patch(route('tramites.update', $tramite), [
            'estatus' => 'Rechazado',
            'observaciones' => 'Documento ilegible.',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('tramites', [
            'id' => $tramite->id,
            'estatus' => 'Rechazado',
            'observaciones' => 'Documento ilegible.',
        ]);
    }

    /** @return array<string, mixed> */
    private function academicContext(): array
    {
        $plan = PlanEstudio::create(['nombre' => 'Plan 2026', 'vigente' => true]);
        $grupo = Grupo::create(['nombre' => 'A', 'semestre' => 1, 'turno' => 'Matutino']);
        $materia = Materia::create(['nombre' => 'Matemáticas I', 'semestre' => 1, 'plan_estudio_id' => $plan->id]);
        $periodo = Periodo::create([
            'nombre_ciclo' => 'Ciclo de prueba',
            'activo' => true,
            'inicio_parcial_1' => today()->subDay(),
            'fin_parcial_1' => today()->addDay(),
            'inicio_parcial_2' => today()->addDays(2),
            'fin_parcial_2' => today()->addDays(3),
            'inicio_parcial_3' => today()->addDays(4),
            'fin_parcial_3' => today()->addDays(5),
        ]);
        $docente = Docente::create([
            'curp' => 'DOCN900101HPLABC01',
            'nombre' => 'Docente',
            'apellido_paterno' => 'Prueba',
            'correo_electronico' => 'docente@example.test',
        ]);
        $teacherUser = User::factory()->role('Docente')->create(['identificador' => $docente->curp]);
        $alumno = Alumno::create([
            'nia' => 'NIA0001',
            'curp' => 'ALUM100101MPLABC01',
            'nombre' => 'Alumna',
            'apellido_paterno' => 'Prueba',
            'fecha_nacimiento' => '2010-01-01',
            'genero' => 'Femenino',
            'plan_estudio_id' => $plan->id,
            'grupo_id' => $grupo->id,
            'semestre_actual' => '1',
            'grupo_actual' => 'A',
            'turno' => 'Matutino',
            'correo_electronico' => 'alumna@example.test',
        ]);
        $studentUser = User::factory()->role('Alumno')->create(['identificador' => $alumno->nia]);
        $carga = CargaHoraria::create([
            'grado' => '1',
            'grupo' => 'A',
            'grupo_id' => $grupo->id,
            'docente_id' => $docente->id,
            'materia_id' => $materia->id,
            'periodo_id' => $periodo->id,
        ]);

        return compact('plan', 'grupo', 'materia', 'periodo', 'docente', 'teacherUser', 'alumno', 'studentUser', 'carga');
    }
}
