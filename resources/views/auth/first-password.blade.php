<x-guest-layout>
    <h1 class="text-xl font-semibold text-slate-900">Protege tu cuenta</h1>
    <p class="mt-2 text-sm text-slate-600">Por ser tu primer acceso debes reemplazar la contraseña temporal.</p>

    <form method="POST" action="{{ route('password.first.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('PUT')
        <div>
            <x-input-label for="current_password" value="Contraseña temporal" />
            <x-text-input id="current_password" name="current_password" type="password" class="mt-1 block w-full" required autofocus />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="password" value="Nueva contraseña" />
            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" required />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="password_confirmation" value="Confirmar nueva contraseña" />
            <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" required />
        </div>
        <x-primary-button>Guardar y continuar</x-primary-button>
    </form>
</x-guest-layout>
