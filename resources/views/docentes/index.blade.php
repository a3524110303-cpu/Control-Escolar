<x-app-layout>
    <x-slot name="header"><h1 class="text-xl font-semibold text-slate-900">Docentes</h1></x-slot>
    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <x-flash />
            <section class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h2 class="text-lg font-semibold">Registrar docente</h2>
                <form method="POST" action="{{ route('docentes.store') }}" class="mt-5 grid gap-4 md:grid-cols-3">
                    @csrf
                    <input name="curp" value="{{ old('curp') }}" placeholder="CURP (18 caracteres)" maxlength="18" required class="rounded-lg border-slate-300 uppercase">
                    <input name="nombre" value="{{ old('nombre') }}" placeholder="Nombre" required class="rounded-lg border-slate-300">
                    <input name="apellido_paterno" value="{{ old('apellido_paterno') }}" placeholder="Apellido paterno" required class="rounded-lg border-slate-300">
                    <input name="apellido_materno" value="{{ old('apellido_materno') }}" placeholder="Apellido materno" class="rounded-lg border-slate-300">
                    <input type="email" name="correo_electronico" value="{{ old('correo_electronico') }}" placeholder="Correo" required class="rounded-lg border-slate-300">
                    <input name="telefono" value="{{ old('telefono') }}" placeholder="Teléfono" class="rounded-lg border-slate-300">
                    <input name="direccion" value="{{ old('direccion') }}" placeholder="Dirección" class="rounded-lg border-slate-300 md:col-span-2">
                    <input type="password" name="password" placeholder="Contraseña temporal" required class="rounded-lg border-slate-300">
                    <input type="password" name="password_confirmation" placeholder="Confirmar contraseña" required class="rounded-lg border-slate-300">
                    <div class="md:col-span-3"><button class="rounded-lg bg-blue-950 px-5 py-2.5 font-semibold text-white">Registrar docente</button></div>
                </form>
            </section>
            <section class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-200">
                <div class="overflow-x-auto"><table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-slate-600"><tr><th class="p-4">CURP</th><th class="p-4">Docente</th><th class="p-4">Contacto</th><th class="p-4">Cargas</th><th class="p-4"></th></tr></thead>
                    <tbody class="divide-y divide-slate-100">@forelse($docentes as $docente)<tr><td class="p-4 font-mono">{{ $docente->curp }}</td><td class="p-4">{{ $docente->nombre_completo }}</td><td class="p-4">{{ $docente->correo_electronico }}<br><span class="text-xs text-slate-500">{{ $docente->telefono }}</span></td><td class="p-4">{{ $docente->cargas_horarias_count }}</td><td class="p-4 text-right"><form method="POST" action="{{ route('docentes.destroy',$docente) }}" onsubmit="return confirm('¿Eliminar este docente?')">@csrf @method('DELETE')<button class="text-red-700">Eliminar</button></form></td></tr>@empty<tr><td colspan="5" class="p-8 text-center text-slate-500">No hay docentes registrados.</td></tr>@endforelse</tbody>
                </table></div><div class="p-4">{{ $docentes->links() }}</div>
            </section>
        </div>
    </div>
</x-app-layout>
