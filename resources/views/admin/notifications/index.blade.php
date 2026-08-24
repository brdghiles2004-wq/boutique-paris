@extends('admin.layouts.app')
@section('title', 'Notifications')

@section('content')

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="font-[Fraunces] text-3xl">Notifications</h1>
            <p class="font-[IBM_Plex_Mono] text-[11px] text-[#9C9788] mt-1">
                {{ auth()->user()->unreadNotifications->count() }} non lue(s)
            </p>
        </div>
        <form action="{{ route('admin.notifications.read-all') }}" method="POST">
            @csrf
            <button type="submit"
                    class="font-[IBM_Plex_Mono] text-[10px] px-4 py-2 border border-white/10 text-[#9C9788] hover:text-[#C9A24B] hover:border-[#C9A24B]/30 rounded-lg transition-colors uppercase tracking-widest">
                Tout marquer comme lu
            </button>
        </form>
    </div>

    <div class="space-y-3">
        @forelse ($notifications as $notif)
            @php
                $isRead = ! is_null($notif->read_at);
                $type = $notif->data['type'] ?? 'other';
                $icons = ['new_order' => '📦', 'new_user' => '👤', 'new_feedback' => '💬'];
                $colors = [
                    'new_order'    => ['text' => '#C9A24B', 'bg' => 'rgba(201,162,75,0.08)',  'border' => 'rgba(201,162,75,0.2)'],
                    'new_user'     => ['text' => '#22c55e', 'bg' => 'rgba(34,197,94,0.08)',   'border' => 'rgba(34,197,94,0.2)'],
                    'new_feedback' => ['text' => '#6366f1', 'bg' => 'rgba(99,102,241,0.08)', 'border' => 'rgba(99,102,241,0.2)'],
                ];
                $c = $colors[$type] ?? ['text' => '#9C9788', 'bg' => 'rgba(255,255,255,0.03)', 'border' => 'rgba(255,255,255,0.08)'];
            @endphp

            <div class="rounded-xl border p-5 flex items-start gap-4 transition-all {{ $isRead ? 'opacity-50' : '' }}"
                 style="background: {{ $c['bg'] }}; border-color: {{ $c['border'] }}">

                <div class="w-10 h-10 rounded-full flex items-center justify-center text-xl flex-shrink-0"
                     style="background: {{ $c['bg'] }}; border: 1px solid {{ $c['border'] }}">
                    {{ $icons[$type] ?? '🔔' }}
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="font-[Inter] text-sm font-semibold {{ $isRead ? 'text-[#9C9788]' : '' }}"
                               style="{{ ! $isRead ? 'color: ' . $c['text'] : '' }}">
                                {{ $notif->data['title'] ?? 'Notification' }}
                            </p>
                            <p class="font-[IBM_Plex_Mono] text-[11px] text-[#9C9788] mt-0.5">
                                {{ $notif->data['message'] ?? '' }}
                            </p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="font-[IBM_Plex_Mono] text-[10px] text-[#9C9788]">
                                {{ $notif->created_at->diffForHumans() }}
                            </p>
                            @if (! $isRead)
                                <span class="font-[IBM_Plex_Mono] text-[9px] uppercase tracking-wider"
                                      style="color: {{ $c['text'] }}">● Nouveau</span>
                            @endif
                        </div>
                    </div>

                    @if (!empty($notif->data['url']))
                        <a href="{{ $notif->data['url'] }}"
                           class="inline-block mt-3 font-[IBM_Plex_Mono] text-[10px] uppercase tracking-wider hover:underline"
                           style="color: {{ $c['text'] }}">
                            Voir le détail →
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="rounded-xl border border-white/8 bg-[#0E1018] py-16 text-center">
                <p class="text-4xl mb-4">🔔</p>
                <p class="font-[Fraunces] text-xl text-[#9C9788]">Aucune notification</p>
                <p class="font-[IBM_Plex_Mono] text-[11px] text-[#9C9788] mt-2">Les notifications apparaîtront ici</p>
            </div>
        @endforelse
    </div>

    @if ($notifications->hasPages())
        <div class="mt-6">{{ $notifications->links() }}</div>
    @endif

@endsection