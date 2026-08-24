@extends('admin.layouts.app')
@section('title', 'Clients')

@section('content')

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="font-[Fraunces] text-3xl">Clients</h1>
            <p class="font-[IBM_Plex_Mono] text-[16px] text-[#9C9788] mt-1">{{ $users->total() }} comptes enregistrés</p>
        </div>
    </div>

    <div class="rounded-xl border border-white/8 bg-[#0E1018] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-white/8 bg-white/2">
                        <th class="px-5 py-4 text-left font-[IBM_Plex_Mono] text-[12px] uppercase tracking-[0.15em] text-[#9C9788]">Nom</th>
                        <th class="px-5 py-4 text-left font-[IBM_Plex_Mono] text-[12px] uppercase tracking-[0.15em] text-[#9C9788]">E-mail</th>
                        <th class="px-5 py-4 text-left font-[IBM_Plex_Mono] text-[12px] uppercase tracking-[0.15em] text-[#9C9788]">Connexion</th>
                        <th class="px-5 py-4 text-center font-[IBM_Plex_Mono] text-[12px] uppercase tracking-[0.15em] text-[#9C9788]">Commandes</th>
                        <th class="px-5 py-4 text-left font-[IBM_Plex_Mono] text-[12px] uppercase tracking-[0.15em] text-[#9C9788]">Inscrit le</th>
                        <th class="px-5 py-4 text-left font-[IBM_Plex_Mono] text-[12px] uppercase tracking-[0.15em] text-[#9C9788]">Rôle</th>
                        <th class="px-5 py-4 text-left font-[IBM_Plex_Mono] text-[12px] uppercase tracking-[0.15em] text-[#9C9788]">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr class="border-b border-white/4 hover:bg-white/2 transition-colors">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-[#C9A24B]/20 flex items-center justify-center flex-shrink-0">
                                        @if ($user->avatar)
                                            <img src="{{ $user->avatar }}" class="w-full h-full rounded-full object-cover">
                                        @else
                                            <span class="font-[IBM_Plex_Mono] text-[11px] text-[#C9A24B]">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </span>
                                        @endif
                                    </div>
                                    <span class="font-[Inter] text-sm">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 font-[IBM_Plex_Mono] text-[13px] text-[#9C9788]">{{ $user->email }}</td>
                            <td class="px-5 py-4">
                                @if ($user->google_id)
                                    <span class="font-[IBM_Plex_Mono] text-[13px] px-2 py-0.5 rounded bg-blue-500/10 text-blue-400 border border-blue-500/20">Google</span>
                                @else
                                    <span class="font-[IBM_Plex_Mono] text-[11px] px-2 py-0.5 rounded bg-white/5 text-[#9C9788]">Email</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-center font-[IBM_Plex_Mono] text-sm">{{ $user->orders_count }}</td>
                            <td class="px-5 py-4 font-[IBM_Plex_Mono] text-[14px] text-[#9C9788]">
                                {{ $user->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-5 py-4">
                                <span class="font-[IBM_Plex_Mono] text-[13px] px-2.5 py-1 rounded-full
                                    {{ $user->is_admin
                                        ? 'text-[#C9A24B] bg-[#C9A24B]/10 border border-[#C9A24B]/20'
                                        : 'text-[#9C9788] bg-white/5' }}">
                                    {{ $user->is_admin ? '⭐ Admin' : 'Client' }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                @if ($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.toggle-admin', $user) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                                class="font-[IBM_Plex_Mono] text-[11px] hover:underline uppercase tracking-wider
                                                    {{ $user->is_admin ? 'text-red-400' : 'text-[#C9A24B]' }}">
                                            {{ $user->is_admin ? 'Révoquer' : 'Admin' }}
                                        </button>
                                    </form>
                                @else
                                    <span class="font-[IBM_Plex_Mono] text-[13px] text-[#9C9788]">Vous</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center font-[IBM_Plex_Mono] text-xs text-[#9C9788]">Aucun client</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($users->hasPages())
            <div class="px-6 py-4 border-t border-white/8">{{ $users->links() }}</div>
        @endif
    </div>

@endsection