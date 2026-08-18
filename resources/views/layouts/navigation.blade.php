@php($user = Auth::user())
<nav x-data="{ open: false }" class="border-b border-slate-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 justify-between">
            <div class="flex items-center gap-8">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 font-bold text-blue-950">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo" class="h-10 w-10 rounded-full object-contain">
                    <span class="hidden md:inline">Control Escolar</span>
                </a>

                <div class="hidden items-center gap-6 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Panel</x-nav-link>
                    @if ($user->hasAnyRole('Administrador', 'Director'))
                        <x-nav-link :href="route('alumnos.index')" :active="request()->routeIs('alumnos.*')">Alumnos</x-nav-link>
                        <x-nav-link :href="route('docentes.index')" :active="request()->routeIs('docentes.*')">Docentes</x-nav-link>
                        <x-nav-link :href="route('academico.index')" :active="request()->routeIs('academico.*')">Académico</x-nav-link>
                    @endif
                    @if ($user->rol === 'Administrador')
                        <x-nav-link :href="route('users.create')" :active="request()->routeIs('users.*')">Usuarios</x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden items-center gap-4 sm:flex">
                <div class="text-right">
                    <p class="text-sm font-semibold text-slate-800">{{ $user->identificador }}</p>
                    <p class="text-xs text-slate-500">{{ $user->rol }}</p>
                </div>
                <a href="{{ route('profile.edit') }}" class="text-sm text-blue-700 hover:underline">Perfil</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-sm text-red-700 hover:underline">Salir</button>
                </form>
            </div>

            <button @click="open = ! open" class="p-2 text-slate-600 sm:hidden" aria-label="Abrir menú">☰</button>
        </div>
    </div>

    <div x-show="open" class="space-y-2 border-t border-slate-100 px-4 py-4 sm:hidden">
        <a href="{{ route('dashboard') }}" class="block">Panel</a>
        @if ($user->hasAnyRole('Administrador', 'Director'))
            <a href="{{ route('alumnos.index') }}" class="block">Alumnos</a>
            <a href="{{ route('docentes.index') }}" class="block">Docentes</a>
            <a href="{{ route('academico.index') }}" class="block">Académico</a>
        @endif
        @if ($user->rol === 'Administrador')
            <a href="{{ route('users.create') }}" class="block">Usuarios</a>
        @endif
        <a href="{{ route('profile.edit') }}" class="block">Perfil</a>
        <form method="POST" action="{{ route('logout') }}">@csrf<button class="text-red-700">Salir</button></form>
    </div>
</nav>
