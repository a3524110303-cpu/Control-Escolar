<x-app-layout>
    <x-slot name="header"><h1 class="text-xl font-semibold text-slate-900">Alumnos</h1></x-slot>
    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <x-flash />
            <section class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h2 class="text-lg font-semibold">Registrar alumno</h2>
                @if ($planes->isEmpty() || $grupos->isEmpty())
                    <p class="mt-3 rounded-lg bg-amber-50 p-3 text-sm text-amber-900">Primero crea un plan y un grupo en Configuración académica.</p>
                @else
                    <form method="POST" action="{{ route('alumnos.store') }}" class="mt-5 grid gap-4 md:grid-cols-3">
                        @csrf
                        <input name="nia" value="{{ old('nia') }}" placeholder="NIA" required class="rounded-lg border-slate-300 uppercase">
                        <input name="curp" value="{{ old('curp') }}" placeholder="CURP (18 caracteres)" maxlength="18" required class="rounded-lg border-slate-300 uppercase">
                        <input name="nombre" value="{{ old('nombre') }}" placeholder="Nombre" required class="rounded-lg border-slate-300">
                        <input name="apellido_paterno" value="{{ old('apellido_paterno') }}" placeholder="Apellido paterno" required class="rounded-lg border-slate-300">
                        <input name="apellido_materno" value="{{ old('apellido_materno') }}" placeholder="Apellido materno" class="rounded-lg border-slate-300">
                        <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" required class="rounded-lg border-slate-300">
                        <select name="genero" required class="rounded-lg border-slate-300"><option value="">Género</option>@foreach(['Masculino','Femenino','Otro'] as $item)<option @selected(old('genero')===$item)>{{ $item }}</option>@endforeach</select>
                        <select name="plan_estudio_id" required class="rounded-lg border-slate-300"><option value="">Plan de estudio</option>@foreach($planes as $plan)<option value="{{ $plan->id }}" @selected(old('plan_estudio_id')==$plan->id)>{{ $plan->nombre }}</option>@endforeach</select>
                        <select name="grupo_id" required class="rounded-lg border-slate-300"><option value="">Grupo</option>@foreach($grupos as $grupo)<option value="{{ $grupo->id }}" @selected(old('grupo_id')==$grupo->id)>{{ $grupo->etiqueta }}</option>@endforeach</select>
                        <input type="email" name="correo_electronico" value="{{ old('correo_electronico') }}" placeholder="Correo de recuperación" required class="rounded-lg border-slate-300 md:col-span-2">
                        <input type="password" name="password" placeholder="Contraseña temporal" required class="rounded-lg border-slate-300">
                        <input type="password" name="password_confirmation" placeholder="Confirmar contraseña" required class="rounded-lg border-slate-300">
                        <div class="md:col-span-3"><button class="rounded-lg bg-blue-950 px-5 py-2.5 font-semibold text-white">Registrar alumno</button></div>
                    </form>
                @endif
            </section>

            <section class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50 text-left text-slate-600"><tr><th class="p-4">NIA</th><th class="p-4">Alumno</th><th class="p-4">Grupo</th><th class="p-4">Plan</th><th class="p-4"></th></tr></thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($alumnos as $alumno)
                                <tr><td class="p-4 font-mono">{{ $alumno->nia }}</td><td class="p-4">{{ $alumno->nombre_completo }}<br><span class="text-xs text-slate-500">{{ $alumno->correo_electronico }}</span></td><td class="p-4">{{ $alumno->grupo?->etiqueta ?? 'Sin grupo' }}</td><td class="p-4">{{ $alumno->planEstudio->nombre }}</td><td class="p-4 text-right"><form method="POST" action="{{ route('alumnos.destroy',$alumno) }}" onsubmit="return confirm('¿Eliminar este alumno?')">@csrf @method('DELETE')<button class="text-red-700">Eliminar</button></form></td></tr>
                            @empty
                                <tr><td colspan="5" class="p-8 text-center text-slate-500">No hay alumnos registrados.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4">{{ $alumnos->links() }}</div>
            </section>
        </div>
    </div>
</x-app-layout>
