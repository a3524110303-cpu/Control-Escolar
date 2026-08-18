<x-app-layout>
    <x-slot name="header"><h1 class="text-xl font-semibold text-slate-900">Configuración académica</h1></x-slot>
    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <x-flash />
            <div class="grid gap-6 lg:grid-cols-2">
                <section class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h2 class="font-semibold">Planes de estudio</h2>
                    <form method="POST" action="{{ route('academico.planes.store') }}" class="mt-4 flex flex-wrap gap-3">@csrf
                        <input name="nombre" placeholder="Nombre del plan" required class="min-w-0 flex-1 rounded-lg border-slate-300">
                        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="vigente" value="1" checked> Vigente</label>
                        <button class="rounded-lg bg-blue-950 px-4 py-2 text-white">Agregar</button>
                    </form>
                    <ul class="mt-4 divide-y divide-slate-100 text-sm">@forelse($planes as $plan)<li class="flex justify-between py-2"><span>{{ $plan->nombre }} @if($plan->vigente)<span class="text-emerald-700">· vigente</span>@endif</span><span class="text-slate-500">{{ $plan->materias_count }} materias · {{ $plan->alumnos_count }} alumnos</span></li>@empty<li class="py-3 text-slate-500">Sin planes.</li>@endforelse</ul>
                </section>

                <section class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h2 class="font-semibold">Grupos</h2>
                    <form method="POST" action="{{ route('academico.grupos.store') }}" class="mt-4 grid grid-cols-4 gap-3">@csrf
                        <input name="nombre" placeholder="A" required class="rounded-lg border-slate-300">
                        <select name="semestre" required class="rounded-lg border-slate-300">@foreach(range(1,6) as $n)<option value="{{ $n }}">{{ $n }}°</option>@endforeach</select>
                        <select name="turno" required class="rounded-lg border-slate-300"><option>Matutino</option><option>Vespertino</option></select>
                        <button class="rounded-lg bg-blue-950 px-4 py-2 text-white">Agregar</button>
                    </form>
                    <ul class="mt-4 grid grid-cols-2 gap-2 text-sm">@forelse($grupos as $grupo)<li class="rounded-lg bg-slate-50 p-3">{{ $grupo->etiqueta }} <span class="text-slate-500">· {{ $grupo->alumnos_count }}</span></li>@empty<li class="text-slate-500">Sin grupos.</li>@endforelse</ul>
                </section>

                <section class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h2 class="font-semibold">Materias</h2>
                    <form method="POST" action="{{ route('academico.materias.store') }}" class="mt-4 grid gap-3 sm:grid-cols-4">@csrf
                        <input name="nombre" placeholder="Materia" required class="rounded-lg border-slate-300 sm:col-span-2">
                        <select name="semestre" required class="rounded-lg border-slate-300">@foreach(range(1,6) as $n)<option value="{{ $n }}">{{ $n }}°</option>@endforeach</select>
                        <select name="plan_estudio_id" required class="rounded-lg border-slate-300"><option value="">Plan</option>@foreach($planes as $plan)<option value="{{ $plan->id }}">{{ $plan->nombre }}</option>@endforeach</select>
                        <button class="rounded-lg bg-blue-950 px-4 py-2 text-white sm:col-span-4">Agregar materia</button>
                    </form>
                    <ul class="mt-4 max-h-56 divide-y divide-slate-100 overflow-auto text-sm">@forelse($materias as $materia)<li class="flex justify-between py-2"><span>{{ $materia->nombre }}</span><span class="text-slate-500">{{ $materia->semestre }}° · {{ $materia->planEstudio->nombre }}</span></li>@empty<li class="text-slate-500">Sin materias.</li>@endforelse</ul>
                </section>

                <section class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h2 class="font-semibold">Periodos de evaluación</h2>
                    <form method="POST" action="{{ route('academico.periodos.store') }}" class="mt-4 grid grid-cols-2 gap-3">@csrf
                        <input name="nombre_ciclo" placeholder="Ciclo escolar" required class="col-span-2 rounded-lg border-slate-300">
                        @foreach(range(1,3) as $n)
                            <label class="text-xs text-slate-500">Inicio parcial {{ $n }}<input type="date" name="inicio_parcial_{{ $n }}" required class="mt-1 w-full rounded-lg border-slate-300 text-sm"></label>
                            <label class="text-xs text-slate-500">Fin parcial {{ $n }}<input type="date" name="fin_parcial_{{ $n }}" required class="mt-1 w-full rounded-lg border-slate-300 text-sm"></label>
                        @endforeach
                        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="activo" value="1" checked> Periodo activo</label>
                        <button class="rounded-lg bg-blue-950 px-4 py-2 text-white">Crear periodo</button>
                    </form>
                    <ul class="mt-4 divide-y divide-slate-100 text-sm">@forelse($periodos as $periodo)<li class="py-2">{{ $periodo->nombre_ciclo }} @if($periodo->activo)<span class="text-emerald-700">· activo</span>@endif</li>@empty<li class="text-slate-500">Sin periodos.</li>@endforelse</ul>
                </section>
            </div>

            <section class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h2 class="font-semibold">Cargas horarias</h2>
                <form method="POST" action="{{ route('academico.cargas.store') }}" class="mt-4 grid gap-3 md:grid-cols-5">@csrf
                    <select name="docente_id" required class="rounded-lg border-slate-300"><option value="">Docente</option>@foreach($docentes as $docente)<option value="{{ $docente->id }}">{{ $docente->nombre_completo }}</option>@endforeach</select>
                    <select name="materia_id" required class="rounded-lg border-slate-300"><option value="">Materia</option>@foreach($materias as $materia)<option value="{{ $materia->id }}">{{ $materia->semestre }}° · {{ $materia->nombre }}</option>@endforeach</select>
                    <select name="periodo_id" required class="rounded-lg border-slate-300"><option value="">Periodo</option>@foreach($periodos as $periodo)<option value="{{ $periodo->id }}">{{ $periodo->nombre_ciclo }}</option>@endforeach</select>
                    <select name="grupo_id" required class="rounded-lg border-slate-300"><option value="">Grupo</option>@foreach($grupos as $grupo)<option value="{{ $grupo->id }}">{{ $grupo->etiqueta }}</option>@endforeach</select>
                    <button class="rounded-lg bg-cyan-600 px-4 py-2 font-semibold text-white">Asignar</button>
                </form>
                <div class="mt-5 overflow-x-auto"><table class="min-w-full divide-y divide-slate-200 text-sm"><thead class="text-left text-slate-500"><tr><th class="py-2">Materia</th><th>Grupo</th><th>Docente</th><th>Periodo</th><th></th></tr></thead><tbody class="divide-y divide-slate-100">@forelse($cargas as $carga)<tr><td class="py-3">{{ $carga->materia->nombre }}</td><td>{{ $carga->grupoEscolar?->etiqueta }}</td><td>{{ $carga->docente->nombre_completo }}</td><td>{{ $carga->periodo->nombre_ciclo }}</td><td><a class="text-blue-700 underline" href="{{ route('cargas.show',$carga) }}">Abrir</a></td></tr>@empty<tr><td colspan="5" class="py-5 text-center text-slate-500">Sin cargas.</td></tr>@endforelse</tbody></table></div>
            </section>
        </div>
    </div>
</x-app-layout>
