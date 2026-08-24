@extends('admin.layouts.app')
@section('title', 'Support')

@section('content')

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="font-[Fraunces] text-3xl">Messages Support</h1>
            <p class="font-[IBM_Plex_Mono] text-[15px] text-[#9C9788] mt-1">
                {{ $unreadCount > 0 ? $unreadCount . ' non lu(s)' : 'Tous les messages sont lus' }}
            </p>
        </div>
    </div>

    <div class="rounded-xl border border-white/8 bg-[#0E1018] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-white/8 bg-white/2">
                        <th class="px-5 py-4 text-left font-[IBM_Plex_Mono] text-[12px] uppercase tracking-[0.15em] text-[#9C9788]">Expéditeur</th>
                        <th class="px-5 py-4 text-left font-[IBM_Plex_Mono] text-[12px] uppercase tracking-[0.15em] text-[#9C9788]">E-mail</th>
                        <th class="px-5 py-4 text-left font-[IBM_Plex_Mono] text-[12px] uppercase tracking-[0.15em] text-[#9C9788]">Sujet</th>
                        <th class="px-5 py-4 text-left font-[IBM_Plex_Mono] text-[12px] uppercase tracking-[0.15em] text-[#9C9788]">Date</th>
                        <th class="px-5 py-4 text-center font-[IBM_Plex_Mono] text-[12px] uppercase tracking-[0.15em] text-[#9C9788]">Statut</th>
                        <th class="px-5 py-4 text-left font-[IBM_Plex_Mono] text-[12px] uppercase tracking-[0.15em] text-[#9C9788]">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($messages as $msg)
                        <tr class="border-b border-white/4 hover:bg-white/2 transition-colors {{ ! $msg->is_read ? 'bg-[#C9A24B]/3' : '' }}">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center flex-shrink-0">
                                        <span class="font-[IBM_Plex_Mono] text-xs text-[#9C9788]">
                                            {{ strtoupper(substr($msg->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <span class="font-[Inter] text-sm {{ ! $msg->is_read ? 'font-semibold' : '' }}">
                                        {{ $msg->name }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-5 py-4 font-[IBM_Plex_Mono] text-[14px] text-[#9C9788]">{{ $msg->email }}</td>
                            <td class="px-5 py-4 font-[IBM_Plex_Mono] text-[12px]">{{ $msg->subject }}</td>
                            <td class="px-5 py-4 font-[IBM_Plex_Mono] text-[13px] text-[#9C9788]">
                                {{ $msg->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-5 py-4 text-center space-y-1">
    @if ($msg->is_read)
        <span class="font-[IBM_Plex_Mono] text-[12px] px-2 py-0.5 rounded-full text-green-400 bg-green-400/10 block">
            ✓ Lu
        </span>
    @else
        <span class="font-[IBM_Plex_Mono] text-[12px] px-2 py-0.5 rounded-full text-[#C9A24B] bg-[#C9A24B]/10 block">
            ● Nouveau
        </span>
    @endif

    @if ($msg->isReplied())
        <span class="font-[IBM_Plex_Mono] text-[12px] px-2 py-0.5 rounded-full text-blue-400 bg-blue-400/10 block">
            📩 Répondu
        </span>
    @endif
</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('admin.support.show', $msg) }}"
                                       class="font-[IBM_Plex_Mono] text-[11px] text-[#C9A24B] hover:underline uppercase tracking-wider">
                                        Voir
                                    </a>
                                    <form action="{{ route('admin.support.destroy', $msg) }}" method="POST"
                                          onsubmit="return confirm('Supprimer ce message ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="font-[IBM_Plex_Mono] text-[11px] text-red-400 hover:underline uppercase tracking-wider">
                                            Suppr.
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center font-[IBM_Plex_Mono] text-xs text-[#9C9788]">
                                Aucun message de support
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($messages->hasPages())
            <div class="px-6 py-4 border-t border-white/8">{{ $messages->links() }}</div>
        @endif
    </div>

@endsection