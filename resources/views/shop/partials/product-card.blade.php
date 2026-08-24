@php
    $unsplashIds = [
        'photo-1594938298603-c8148c4dae35',
        'photo-1551232864-3f0890e580d9',
        'photo-1512436991641-6745cdb1723f',
        'photo-1523381210434-271e8be1f52b',
        'photo-1558618666-fcd25c85cd64',
        'photo-1469334031218-e382a71b716b',
        'photo-1515886657613-9f3515b0c78f',
        'photo-1539109136881-3be0616acf4b',
        'photo-1600185365483-26d7a4cc7519',
        'photo-1567401893414-76b7b1e5a7a5',
        'photo-1571945153237-4929e783af4a',
        'photo-1584917865442-de89df76afd3',
    ];

    $imgId = $unsplashIds[$product->id % count($unsplashIds)];
    $imgUrl = "https://images.unsplash.com/{$imgId}?w=600&q=80&fit=crop";
@endphp

<a href="{{ route('shop.product', $product->slug) }}" class="group block">

    {{-- Image --}}
    <div class="aspect-[3/4] overflow-hidden rounded-xl md:rounded-2xl bg-white/5 mb-2 md:mb-4 relative">

        @if ($product->image)
            <img
                src="{{ asset('storage/' . $product->image) }}"
                alt="{{ $product->trans_name }}"
                loading="lazy"
                onerror="this.onerror=null; this.src='{{ $imgUrl }}';"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
            >
        @else
            <img
                src="{{ $imgUrl }}"
                alt="{{ $product->trans_name }}"
                loading="lazy"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
            >
        @endif

        @if ($product->sale_price)
            <div class="absolute top-2 left-2 md:top-3 md:left-3">
                <span class="font-[IBM_Plex_Mono] text-[9px] md:text-[10px] bg-red-500 text-white px-1.5 py-0.5 rounded-full uppercase tracking-wider">
                    Promo
                </span>
            </div>
        @endif

    </div>

    {{-- Infos --}}
    <div>
        <p class="font-[Fraunces] text-sm md:text-base leading-tight mb-1 group-hover:text-[#C9A24B] transition-colors line-clamp-2">
            {{ $product->trans_name }}
        </p>

        <div class="flex items-center gap-1.5 md:gap-2">

            @if ($product->sale_price)

                <span class="font-[IBM_Plex_Mono] text-xs md:text-sm text-[#C9A24B] font-bold">
                    {{ number_format($product->sale_price, 0, ',', ' ') }} DA
                </span>

                <span class="font-[IBM_Plex_Mono] text-[10px] md:text-xs text-[#9C9788] line-through">
                    {{ number_format($product->price, 0, ',', ' ') }} DA
                </span>

            @else

                <span class="font-[IBM_Plex_Mono] text-xs md:text-sm text-[#C9A24B] font-bold">
                    {{ number_format($product->price, 0, ',', ' ') }} DA
                </span>

            @endif

        </div>
    </div>

</a>