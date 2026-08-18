<x-app-layout>
    <x-slot name="header">
        <div><h1 class="text-xl font-semibold text-slate-900">{{ $cargaHoraria->materia->nombre }}</h1><p class="text-sm text-slate-500">{{ $cargaHoraria->grupoEscolar?->etiqueta }} · {{ $cargaHoraria->periodo->nombre_ciclo }}</p></div>
    </x-slot>
    <div class="py-8"><div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
        <x-flash />
        @if($alumnos->isEmpty())
            <div class="rounded-xl bg-white p-8 text-center text-slate-500 shadow-sm">No hay alumnos asignados a este grupo.</div>
        @else
            <section class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h2 class="font-semibold">Pase de lista</h2>
                <form method="POST" action="{{ route('asistencias.store',$cargaHoraria) }}" class="mt-4">@csrf
                    <label class="text-sm">Fecha <input type="date" name="fecha" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}" required class="ml-2 rounded-lg border-slate-300"></label>
                    <div class="mt-4 overflow-x-auto"><table class="min-w-full divide-y divide-slate-200 text-sm"><thead class="text-left text-slate-500"><tr><th class="py-2">Alumno</th><th>Estado</th></tr></thead><tbody class="divide-y divide-slate-100">@foreach($alumnos as $alumno)<tr><td class="py-3">{{ $alumno->nombre_completo }}</td><td><select name="asistencias[{{ $alumno->id }}]" class="rounded-lg border-slate-300"><option>Asistencia</option><option>Falta</option><option>Justificado</option></select></td></tr>@endforeach</tbody></table></div>
                    <button class="mt-4 rounded-lg bg-blue-950 px-5 py-2 font-semibold text-white">Guardar asistencia</button>
                </form>
            </section>

            <section class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h2 class="font-semibold">Calificaciones</h2>
                <div class="mt-4 space-y-4">@foreach($alumnos as $alumno) @php($grade=$alumno->calificaciones->first())
                    <form method="POST" action="{{ route('calificaciones.store',[$cargaHoraria,$alumno]) }}" class="grid items-end gap-3 rounded-lg bg-slate-50 p-4 md:grid-cols-5">@csrf
                        <div class="md:col-span-2"><p class="font-medium">{{ $alumno->nombre_completo }}</p><p class="text-xs text-slate-500">Promedio: {{ $grade?->promedio_final ?? '—' }}</p></div>
                        @foreach(range(1,3) as $n)<label class="text-xs text-slate-500">Parcial {{ $n }}<input type="number" step="0.01" min="0" max="10" name="parcial_{{ $n }}" value="{{ $grade?->{'parcial_'.$n} }}" class="mt-1 w-full rounded-lg border-slate-300"></label>@endforeach
                        <button class="rounded-lg bg-cyan-600 px-4 py-2 font-semibold text-white">Guardar</button>
                    </form>
                @endforeach</div>
            </section>
        @endif
    </div></div>
</x-app-layout>
