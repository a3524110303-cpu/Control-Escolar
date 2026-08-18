<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Panel de control</h1>
            <p class="mt-1 text-sm text-slate-500">Bienvenido, {{ $user->display_name }}.</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <x-flash />

            @if ($user->hasAnyRole('Administrador', 'Director'))
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
                    @foreach ($counts as $label => $value)
                        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                            <p class="text-sm text-slate-500">{{ $label }}</p>
                            <p class="mt-2 text-3xl font-bold text-blue-950">{{ $value }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8 grid gap-5 md:grid-cols-3">
                    <a href="{{ route('alumnos.index') }}" class="rounded-xl bg-blue-950 p-6 text-white shadow-sm hover:bg-blue-900">
                        <h2 class="font-semibold">Gestionar alumnos</h2>
                        <p class="mt-2 text-sm text-blue-100">Altas, cuentas, grupos y planes de estudio.</p>
                    </a>
                    <a href="{{ route('docentes.index') }}" class="rounded-xl bg-cyan-600 p-6 text-white shadow-sm hover:bg-cyan-500">
                        <h2 class="font-semibold">Gestionar docentes</h2>
                        <p class="mt-2 text-sm text-cyan-50">Personal docente y cuentas de acceso.</p>
                    </a>
                    <a href="{{ route('academico.index') }}" class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200 hover:ring-blue-400">
                        <h2 class="font-semibold text-slate-900">Configuración académica</h2>
                        <p class="mt-2 text-sm text-slate-500">Planes, grupos, materias, periodos y cargas.</p>
                    </a>
                </div>
            @elseif ($user->rol === 'Docente')
                @if (! $user->docente)
                    <div class="rounded-xl border border-amber-200 bg-amber-50 p-5 text-amber-900">
                        Esta cuenta no está vinculada con un registro docente. Solicita la corrección al administrador.
                    </div>
                @elseif ($cargas->isEmpty())
                    <div class="rounded-xl bg-white p-6 shadow-sm">Todavía no tienes grupos asignados.</div>
                @else
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                        @foreach ($cargas as $carga)
                            <a href="{{ route('cargas.show', $carga) }}" class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200 hover:ring-blue-400">
                                <p class="font-semibold text-blue-950">{{ $carga->materia->nombre }}</p>
                                <p class="mt-2 text-sm text-slate-600">{{ $carga->grupoEscolar?->etiqueta ?? $carga->grado.'° '.$carga->grupo }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ $carga->periodo->nombre_ciclo }}</p>
                            </a>
                        @endforeach
                    </div>
                @endif
            @else
                @if (! $user->alumno)
                    <div class="rounded-xl border border-amber-200 bg-amber-50 p-5 text-amber-900">
                        Esta cuenta no está vinculada con un registro de alumno.
                    </div>
                @else
                    <div class="grid gap-6 lg:grid-cols-2">
                        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                            <h2 class="font-semibold text-slate-900">Mi información</h2>
                            <dl class="mt-4 grid grid-cols-2 gap-3 text-sm">
                                <dt class="text-slate-500">Nombre</dt><dd>{{ $user->alumno->nombre_completo }}</dd>
                                <dt class="text-slate-500">NIA</dt><dd>{{ $user->alumno->nia }}</dd>
                                <dt class="text-slate-500">Grupo</dt><dd>{{ $user->alumno->grupo?->etiqueta ?? 'Sin asignar' }}</dd>
                            </dl>
                        </div>
                        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                            <h2 class="font-semibold text-slate-900">Trámite documental</h2>
                            @if ($tramite)
                                <p class="mt-3 text-sm">Estado: <strong>{{ $tramite->estatus }}</strong></p>
                                @if ($tramite->observaciones)<p class="mt-2 text-sm text-red-700">{{ $tramite->observaciones }}</p>@endif
                                <a class="mt-3 inline-block text-sm text-blue-700 underline" href="{{ route('tramites.download', $tramite) }}">Descargar documento actual</a>
                            @endif
                            <form method="POST" action="{{ route('tramites.store') }}" enctype="multipart/form-data" class="mt-4 space-y-3">
                                @csrf
                                <input type="file" name="documento" accept="application/pdf" required class="block w-full text-sm">
                                <button class="rounded-lg bg-blue-950 px-4 py-2 text-sm font-semibold text-white">Enviar PDF</button>
                            </form>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-app-layout>
