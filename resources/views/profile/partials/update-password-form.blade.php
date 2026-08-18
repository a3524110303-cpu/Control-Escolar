<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">Cambiar contraseña</h2>
        <p class="mt-1 text-sm text-gray-600">Usa al menos 12 caracteres con mayúsculas, minúsculas, números y símbolos.</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')
        <div>
            <x-input-label for="update_password_current_password" value="Contraseña actual" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="update_password_password" value="Nueva contraseña" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="update_password_password_confirmation" value="Confirmar contraseña" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" />
        </div>
        <div class="flex items-center gap-4">
            <x-primary-button>Guardar</x-primary-button>
            @if (session('status') === 'password-updated')<span class="text-sm text-green-700">Contraseña actualizada.</span>@endif
        </div>
    </form>
</section>
