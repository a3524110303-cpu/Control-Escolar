<x-app-layout>
    <x-slot name="header"><h1 class="text-xl font-semibold text-slate-900">Mi perfil</h1></x-slot>
    <div class="py-8">
        <div class="mx-auto max-w-4xl space-y-6 px-4 sm:px-6 lg:px-8">
            <x-flash />
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                @include('profile.partials.update-profile-information-form')
            </div>
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                @include('profile.partials.update-password-form')
            </div>
        </div>
    </div>
</x-app-layout>
