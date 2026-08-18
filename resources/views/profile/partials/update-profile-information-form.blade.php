<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">Información de la cuenta</h2>
        <p class="mt-1 text-sm text-gray-600">Actualiza el correo utilizado para recuperar tu contraseña.</p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="identificador" value="Identificador" />
            <x-text-input id="identificador" type="text" class="mt-1 block w-full bg-gray-100" :value="$user->identificador" disabled />
        </div>
        <div>
            <x-input-label for="rol" value="Rol" />
            <x-text-input id="rol" type="text" class="mt-1 block w-full bg-gray-100" :value="$user->rol" disabled />
        </div>
        <div>
            <x-input-label for="email_recuperacion" value="Correo de recuperación" />
            <x-text-input id="email_recuperacion" name="email_recuperacion" type="email" class="mt-1 block w-full" :value="old('email_recuperacion', $user->email_recuperacion)" required />
            <x-input-error class="mt-2" :messages="$errors->get('email_recuperacion')" />
        </div>
        <div class="flex items-center gap-4">
            <x-primary-button>Guardar</x-primary-button>
            @if (session('status') === 'profile-updated')<span class="text-sm text-green-700">Guardado.</span>@endif
        </div>
    </form>
</section>
