@extends('layouts.shop')

@section('title', 'Finaliser la commande — Boutique Paris')

@section('content')

    <div class="max-w-4xl mx-auto px-6 py-16">
        <p class="font-[IBM_Plex_Mono] text-xs uppercase tracking-[0.3em] text-[#C9A24B] mb-4">
            Finalisation
        </p>
        <h1 class="font-[Fraunces] text-4xl mb-12">Finaliser la commande</h1>

        @if ($cart->items->isEmpty())
            <p class="font-[IBM_Plex_Mono] text-sm text-[#9C9788]">
                Votre panier est vide.
                <a href="{{ route('home') }}" class="text-[#C9A24B] hover:underline">Retour à la boutique</a>
            </p>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">

                <form action="{{ route('checkout.store') }}" method="POST" class="space-y-6" id="checkout-form">
                    @csrf

                    @guest
                        <div class="border border-[#C9A24B]/30 bg-[#C9A24B]/5 p-4 mb-6">
                            <p class="font-[IBM_Plex_Mono] text-xs text-[#C9A24B] mb-3 uppercase tracking-widest">
                                Commander sans compte
                            </p>

                            <div>
                                <label class="font-[IBM_Plex_Mono] text-[11px] uppercase tracking-widest text-[#9C9788] block mb-2">
                                    Votre e-mail (pour le suivi de commande)
                                </label>

                                <input
                                    type="email"
                                    name="guest_email"
                                    value="{{ old('guest_email') }}"
                                    class="w-full bg-transparent border border-white/20 px-4 py-3 focus:border-[#C9A24B] outline-none text-sm"
                                >

                                @error('guest_email')
                                    <p class="text-[#e9b3bb] text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <p class="font-[IBM_Plex_Mono] text-[10px] text-[#9C9788] mt-3">
                                Vous avez déjà un compte ?
                                <a href="{{ route('login') }}" class="text-[#C9A24B] hover:underline">
                                    Se connecter
                                </a>
                            </p>
                        </div>
                    @endguest


                    {{-- NOM --}}
                    <div>
                        <label class="font-[IBM_Plex_Mono] text-[11px] uppercase tracking-widest text-[#9C9788] block mb-2">
                            Nom complet
                        </label>

                        <input
                            type="text"
                            name="shipping_name"
                            value="{{ old('shipping_name', auth()->user()->name ?? '') }}"
                            required
                            class="w-full bg-transparent border border-white/20 px-4 py-3 focus:border-[#C9A24B] outline-none"
                        >

                        @error('shipping_name')
                            <p class="text-[#e9b3bb] text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>


                    {{-- TELEPHONE --}}
                    <div>
                        <label class="font-[IBM_Plex_Mono] text-[11px] uppercase tracking-widest text-[#9C9788] block mb-2">
                            Téléphone
                        </label>

                        <input
                            type="text"
                            name="shipping_phone"
                            value="{{ old('shipping_phone') }}"
                            required
                            class="w-full bg-transparent border border-white/20 px-4 py-3 focus:border-[#C9A24B] outline-none"
                        >

                        @error('shipping_phone')
                            <p class="text-[#e9b3bb] text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>


                    {{-- WILAYA --}}
                    <div>
                        <label class="font-[IBM_Plex_Mono] text-[11px] uppercase tracking-widest text-[#9C9788] block mb-2">
                            Wilaya
                        </label>

                        <select
                            name="shipping_wilaya"
                            required
                            id="wilaya-select"
                            class="w-full bg-[#14151C] border border-white/20 px-4 py-3 focus:border-[#C9A24B] outline-none text-[#F6F3EC]"
                        >
                            <option value="">-- Choisir une wilaya --</option>

                            @foreach ($wilayas as $code => $name)
                                <option
                                    value="{{ $name }}"
                                    {{ old('shipping_wilaya') == $name ? 'selected' : '' }}
                                >
                                    {{ $code }} — {{ $name }}
                                </option>
                            @endforeach
                        </select>

                        @error('shipping_wilaya')
                            <p class="text-[#e9b3bb] text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>


                    {{-- COMMUNE --}}
                    <div>
                        <label class="font-[IBM_Plex_Mono] text-[11px] uppercase tracking-widest text-[#9C9788] block mb-2">
                            Commune
                        </label>

                        <select
                            name="shipping_commune"
                            required
                            id="commune-select"
                            class="w-full bg-[#14151C] border border-white/20 px-4 py-3 focus:border-[#C9A24B] outline-none text-[#F6F3EC]"
                            disabled
                        >
                            <option value="">-- Choisir une wilaya d'abord --</option>
                        </select>

                        @error('shipping_commune')
                            <p class="text-[#e9b3bb] text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>


                    {{-- TYPE DE LIVRAISON --}}
                    <div>
                        <label class="font-[IBM_Plex_Mono] text-[11px] uppercase tracking-widest text-[#9C9788] block mb-3">
                            Type de livraison
                        </label>

                        <div class="grid grid-cols-2 gap-4">

                            {{-- STOP DESK --}}
                            <label
                                id="label-stop"
                                class="delivery-option cursor-pointer border p-4 text-center transition-all duration-200
                                {{ old('delivery_type', 'a_domicile') == 'stop_desk'
                                    ? 'border-[#C9A24B] bg-[#C9A24B]/10'
                                    : 'border-white/20' }}"
                            >
                                <input
                                    type="radio"
                                    name="delivery_type"
                                    value="stop_desk"
                                    class="sr-only"
                                    {{ old('delivery_type', 'a_domicile') == 'stop_desk' ? 'checked' : '' }}
                                >

                                <p class="font-[Fraunces] text-lg mb-1">
                                    Stop Desk
                                </p>

                                <p class="font-[IBM_Plex_Mono] text-[10px] text-[#9C9788]">
                                    Retrait au bureau
                                </p>

                                <p
                                    id="stop-price"
                                    class="font-[IBM_Plex_Mono] text-sm text-[#C9A24B] mt-2"
                                >
                                    —
                                </p>
                            </label>


                            {{-- A DOMICILE --}}
                            <label
                                id="label-domicile"
                                class="delivery-option cursor-pointer border p-4 text-center transition-all duration-200
                                {{ old('delivery_type', 'a_domicile') == 'a_domicile'
                                    ? 'border-[#C9A24B] bg-[#C9A24B]/10'
                                    : 'border-white/20' }}"
                            >
                                <input
                                    type="radio"
                                    name="delivery_type"
                                    value="a_domicile"
                                    class="sr-only"
                                    {{ old('delivery_type', 'a_domicile') == 'a_domicile' ? 'checked' : '' }}
                                >

                                <p class="font-[Fraunces] text-lg mb-1">
                                    À Domicile
                                </p>

                                <p class="font-[IBM_Plex_Mono] text-[10px] text-[#9C9788]">
                                    Livraison chez vous
                                </p>

                                <p
                                    id="domicile-price"
                                    class="font-[IBM_Plex_Mono] text-sm text-[#C9A24B] mt-2"
                                >
                                    —
                                </p>
                            </label>

                        </div>

                        @error('delivery_type')
                            <p class="text-[#e9b3bb] text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>


                    {{-- ADRESSE --}}
                    <div>
                        <label class="font-[IBM_Plex_Mono] text-[11px] uppercase tracking-widest text-[#9C9788] block mb-2">
                            Adresse complète
                        </label>

                        <textarea
                            name="shipping_address"
                            required
                            rows="3"
                            class="w-full bg-transparent border border-white/20 px-4 py-3 focus:border-[#C9A24B] outline-none"
                        >{{ old('shipping_address') }}</textarea>

                        @error('shipping_address')
                            <p class="text-[#e9b3bb] text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>


                    {{-- NOTES --}}
                    <div>
                        <label class="font-[IBM_Plex_Mono] text-[11px] uppercase tracking-widest text-[#9C9788] block mb-2">
                            Notes (facultatif)
                        </label>

                        <textarea
                            name="notes"
                            rows="2"
                            class="w-full bg-transparent border border-white/20 px-4 py-3 focus:border-[#C9A24B] outline-none"
                        >{{ old('notes') }}</textarea>
                    </div>


                    {{-- BOUTON --}}
                    <button
                        type="submit"
                        class="w-full px-6 py-4 bg-[#C9A24B] text-[#14151C] font-[IBM_Plex_Mono] text-xs uppercase tracking-widest hover:bg-[#dab564] transition-colors"
                    >
                        Confirmer la commande
                    </button>

                </form>


                {{-- ========================================================= --}}
                {{-- RECAPITULATIF --}}
                {{-- ========================================================= --}}

                <div>

                    <p class="font-[IBM_Plex_Mono] text-[10px] uppercase tracking-widest text-[#9C9788] mb-6 border-b border-white/10 pb-3">
                        Récapitulatif
                    </p>


                    {{-- ARTICLES --}}
                    @foreach ($cart->items as $item)

                        <div class="flex items-center gap-3 py-3 border-b border-white/8 last:border-0">

                            {{-- IMAGE --}}
                            <div class="w-14 h-16 rounded-lg overflow-hidden bg-white/5 flex-shrink-0">

                                @if ($item->variant?->product?->image)

                                    <img
                                        src="{{ asset('storage/' . $item->variant->product->image) }}"
                                        alt="{{ $item->variant->product->name }}"
                                        class="w-full h-full object-cover"
                                    >

                                @else

                                    <div class="w-full h-full flex items-center justify-center text-xl">
                                        👕
                                    </div>

                                @endif

                            </div>


                            {{-- DETAILS --}}
                            <div class="flex-1 min-w-0">

                                <p class="font-[Inter] text-sm font-medium truncate">
                                    {{ $item->variant?->product?->name ?? $item->product_name }}
                                </p>

                                <p class="font-[IBM_Plex_Mono] text-[10px] text-[#9C9788]">
                                    {{ $item->variant?->size }}
                                    {{ $item->variant?->color ? '· ' . $item->variant->color : '' }}
                                </p>

                                <p class="font-[IBM_Plex_Mono] text-[10px] text-[#9C9788]">
                                    × {{ $item->quantity }}
                                </p>

                            </div>


                            {{-- PRIX ARTICLE --}}
                            <p class="font-[IBM_Plex_Mono] text-sm text-[#C9A24B] flex-shrink-0">
                                {{ number_format($item->total, 0, ',', ' ') }} DA
                            </p>

                        </div>

                    @endforeach


                    {{-- ===================================================== --}}
                    {{-- TOTALS --}}
                    {{-- ===================================================== --}}

                    <div class="border-t border-white/10 pt-4 space-y-2">

                        {{-- SOUS TOTAL --}}
                        <div class="flex justify-between font-[IBM_Plex_Mono] text-xs text-[#9C9788]">

                            <span>
                                Sous-total
                            </span>

                            <span>
                                {{ number_format($cart->total, 0, ',', ' ') }} DA
                            </span>

                        </div>


                        {{-- LIVRAISON --}}
                        <div class="flex justify-between font-[IBM_Plex_Mono] text-xs text-[#9C9788]">

                            <span>
                                Livraison
                            </span>

                            <span id="shipping-display">
                                —
                            </span>

                        </div>


                        {{-- TOTAL --}}
                        <div class="flex justify-between font-[IBM_Plex_Mono] text-base text-[#C9A24B] pt-2 border-t border-white/10">

                            <span>
                                Total
                            </span>

                            <span id="total-display">
                                {{ number_format($cart->total, 0, ',', ' ') }} DA
                            </span>

                        </div>

                    </div>

                </div>

            </div>
        @endif

    </div>


    {{-- ============================================================= --}}
    {{-- JAVASCRIPT --}}
    {{-- ============================================================= --}}

    <script>

        /*
        |--------------------------------------------------------------------------
        | TOTAL PANIER
        |--------------------------------------------------------------------------
        */

        const cartTotal = {{ (float) $cart->total }};


        /*
        |--------------------------------------------------------------------------
        | PRIX LIVRAISON VENANT DIRECTEMENT DE LARAVEL
        |--------------------------------------------------------------------------
        |
        | Exemple :
        |
        | deliveryPrices['Alger']['stop_desk']    = 300
        | deliveryPrices['Alger']['a_domicile']  = 500
        |
        */

        const deliveryPrices = @json($deliveryPrices);


        /*
        |--------------------------------------------------------------------------
        | ELEMENTS
        |--------------------------------------------------------------------------
        */

        const wilayaSelect = document.getElementById('wilaya-select');

        const communeSelect = document.getElementById('commune-select');

        const shippingDisplay = document.getElementById('shipping-display');

        const totalDisplay = document.getElementById('total-display');

        const stopPrice = document.getElementById('stop-price');

        const domicilePrice = document.getElementById('domicile-price');


        /*
        |--------------------------------------------------------------------------
        | FORMAT PRIX
        |--------------------------------------------------------------------------
        */

        function formatPrice(price) {

            return Number(price).toLocaleString('fr-DZ') + ' DA';

        }


        /*
        |--------------------------------------------------------------------------
        | RECUPERER PRIX DE LIVRAISON
        |--------------------------------------------------------------------------
        */

        function getShippingPrice() {

            const wilaya = wilayaSelect.value;

            const type = document.querySelector(
                'input[name="delivery_type"]:checked'
            )?.value;


            /*
            | Pas encore de wilaya
            */

            if (!wilaya) {
                return null;
            }


            /*
            | Wilaya inexistante dans les tarifs
            */

            if (!deliveryPrices[wilaya]) {
                return null;
            }


            /*
            | Type de livraison inexistant
            */

            if (
                typeof deliveryPrices[wilaya][type] === 'undefined'
            ) {
                return null;
            }


            return Number(
                deliveryPrices[wilaya][type]
            );

        }


        /*
        |--------------------------------------------------------------------------
        | AFFICHER LES PRIX DES DEUX TYPES
        |--------------------------------------------------------------------------
        */

        function updateDeliveryOptionsPrices() {

            const wilaya = wilayaSelect.value;


            /*
            | Aucune wilaya sélectionnée
            */

            if (!wilaya || !deliveryPrices[wilaya]) {

                stopPrice.textContent = '—';

                domicilePrice.textContent = '—';

                return;
            }


            /*
            | STOP DESK
            */

            if (
                typeof deliveryPrices[wilaya]['stop_desk'] !== 'undefined'
            ) {

                stopPrice.textContent =
                    formatPrice(
                        deliveryPrices[wilaya]['stop_desk']
                    );

            } else {

                stopPrice.textContent = '—';

            }


            /*
            | A DOMICILE
            */

            if (
                typeof deliveryPrices[wilaya]['a_domicile'] !== 'undefined'
            ) {

                domicilePrice.textContent =
                    formatPrice(
                        deliveryPrices[wilaya]['a_domicile']
                    );

            } else {

                domicilePrice.textContent = '—';

            }

        }


        /*
        |--------------------------------------------------------------------------
        | METTRE A JOUR LE TOTAL
        |--------------------------------------------------------------------------
        */

        function updatePrices() {

            const shipping = getShippingPrice();


            /*
            | Pas de wilaya
            */

            if (shipping === null) {

                shippingDisplay.textContent = '—';

                totalDisplay.textContent =
                    formatPrice(cartTotal);

                return;
            }


            /*
            | AFFICHER LIVRAISON
            */

            shippingDisplay.textContent =
                formatPrice(shipping);


            /*
            | CALCUL TOTAL
            */

            const total =
                cartTotal + shipping;


            totalDisplay.textContent =
                formatPrice(total);

        }


        /*
        |--------------------------------------------------------------------------
        | STYLE DES BOX LIVRAISON
        |--------------------------------------------------------------------------
        */

        function updateDeliveryStyle() {

            const selected =
                document.querySelector(
                    'input[name="delivery_type"]:checked'
                )?.value;


            const labelStop =
                document.getElementById('label-stop');

            const labelDomicile =
                document.getElementById('label-domicile');


            if (selected === 'stop_desk') {

                labelStop.style.borderColor =
                    '#C9A24B';

                labelStop.style.backgroundColor =
                    'rgba(201,162,75,0.1)';


                labelDomicile.style.borderColor =
                    'rgba(255,255,255,0.2)';

                labelDomicile.style.backgroundColor =
                    'transparent';

            } else {

                labelDomicile.style.borderColor =
                    '#C9A24B';

                labelDomicile.style.backgroundColor =
                    'rgba(201,162,75,0.1)';


                labelStop.style.borderColor =
                    'rgba(255,255,255,0.2)';

                labelStop.style.backgroundColor =
                    'transparent';

            }

        }


        /*
        |--------------------------------------------------------------------------
        | CHANGEMENT TYPE LIVRAISON
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll(
                'input[name="delivery_type"]'
            )
            .forEach(radio => {

                radio.addEventListener(
                    'change',
                    function() {

                        updateDeliveryStyle();

                        updatePrices();

                    }
                );

            });


        /*
        |--------------------------------------------------------------------------
        | CHANGEMENT WILAYA
        |--------------------------------------------------------------------------
        */

        wilayaSelect.addEventListener(
            'change',
            function() {

                const wilaya = this.value;


                /*
                | Mettre immédiatement à jour les prix
                */

                updateDeliveryOptionsPrices();

                updatePrices();


                /*
                | Pas de wilaya
                */

                if (!wilaya) {

                    communeSelect.innerHTML =
                        '<option value="">-- Choisir une wilaya d\'abord --</option>';

                    communeSelect.disabled = true;

                    return;

                }


                /*
                | Chargement communes
                */

                communeSelect.innerHTML =
                    '<option value="">Chargement...</option>';

                communeSelect.disabled = true;


                /*
                | Requête communes
                */

                fetch(
                    `/communes/${encodeURIComponent(wilaya)}`
                )

                .then(res => {

                    if (!res.ok) {
                        throw new Error(
                            'Erreur HTTP'
                        );
                    }

                    return res.json();

                })

                .then(communes => {

                    communeSelect.innerHTML =
                        '<option value="">-- Choisir une commune --</option>';


                    communes.forEach(
                        commune => {

                            const option =
                                document.createElement(
                                    'option'
                                );

                            option.value =
                                commune;

                            option.textContent =
                                commune;

                            communeSelect.appendChild(
                                option
                            );

                        }
                    );


                    communeSelect.disabled =
                        false;

                })

                .catch(() => {

                    communeSelect.innerHTML =
                        '<option value="">Erreur de chargement</option>';

                    communeSelect.disabled =
                        false;

                });

            }
        );


        /*
        |--------------------------------------------------------------------------
        | INITIALISATION
        |--------------------------------------------------------------------------
        */

        updateDeliveryStyle();

        updateDeliveryOptionsPrices();

        updatePrices();

    </script>

@endsection