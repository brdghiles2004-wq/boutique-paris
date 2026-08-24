@extends('layouts.shop')
@php use Illuminate\Support\Str; @endphp

@section('title', 'Mon profil')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 py-10">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

        {{-- LEFT SIDE --}}
        <div class="lg:col-span-1 bg-[#1C1E27] p-6 rounded-2xl border border-white/10 shadow-lg">

            <div class="flex flex-col items-center text-center">

                {{-- avatar --}}
                <div class="w-28 h-28 rounded-full bg-[#2A2C38] overflow-hidden flex items-center justify-center mb-4 border border-white/10">

@if(auth()->user()->avatar)
    <img src="{{ Str::startsWith(auth()->user()->avatar, 'http')
        ? auth()->user()->avatar
        : asset('storage/' . auth()->user()->avatar) }}"
        class="w-full h-full object-cover">
@else
    <span class="text-3xl">👤</span>
@endif

</div>

                <h2 class="text-xl font-semibold text-white">
                    {{ auth()->user()->name }}
                </h2>

                <p class="text-xs text-gray-400 break-all">
                    {{ auth()->user()->email }}
                </p>

            </div>

            <div class="mt-6 flex flex-col items-center text-center space-y-2 text-sm text-gray-400">
                <p>📞 {{ auth()->user()->phone ?? '-' }}</p>
                <p>🏙️ {{ auth()->user()->city ?? '-' }}</p>
                <p>🌍 {{ auth()->user()->country ?? 'Algérie' }}</p>
            </div>

        </div>


        {{-- RIGHT SIDE --}}
        <div class="lg:col-span-2 bg-[#1C1E27] p-6 sm:p-8 rounded-2xl border border-white/10 shadow-lg">

            <h2 class="text-xl font-semibold mb-6 text-white">
                Informations personnelles
            </h2>

            <form action="{{ route('profile.update') }}" method="POST"
      enctype="multipart/form-data"
      class="grid grid-cols-1 sm:grid-cols-2 gap-5">

    @csrf
    @method('PATCH')

  {{-- name --}}
<div>
    <label class="text-xs text-gray-400">Nom</label>
    <input type="text" name="name"
        value="{{ auth()->user()->name }}"
        class="w-full mt-1 px-3 py-2 text-sm bg-[#14151C] border border-white/10 rounded-lg">
</div>

{{-- email --}}
<div>
    <label class="text-xs text-gray-400">Email</label>
    <input type="email" name="email"
        value="{{ auth()->user()->email }}"
        class="w-full mt-1 px-3 py-2 text-sm bg-[#14151C] border border-white/10 rounded-lg">
</div>

    {{-- password --}}
    <div class="sm:col-span-2">
        <label class="text-xs text-gray-400">Nouveau mot de passe</label>
        <input type="password" name="password"
            class="w-full mt-1 px-3 py-2 text-sm bg-[#14151C] border border-white/10 rounded-lg">
    </div>

    {{-- confirm password --}}
    <div class="sm:col-span-2">
        <label class="text-xs text-gray-400">Confirmer mot de passe</label>
        <input type="password" name="password_confirmation"
            class="w-full mt-1 px-3 py-2 text-sm bg-[#14151C] border border-white/10 rounded-lg">
    </div>

    {{-- button --}}
    <div class="sm:col-span-2 flex justify-end">
        <button class="px-6 py-2 bg-[#C9A24B] text-black text-sm rounded-lg">
            Enregistrer
        </button>
    </div>

</form>

            {{-- ORDERS --}}
            <div class="mt-10">
                <h3 class="text-lg font-semibold text-white mb-3">Mes commandes</h3>

                <div class="text-sm text-gray-400">
                    Aucune commande
                </div>
            </div>

        </div>

    </div>

</div>

@endsection