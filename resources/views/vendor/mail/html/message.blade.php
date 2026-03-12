<x-mail::layout>
    {{-- Header --}}
    <x-slot:header>
        <x-mail::header >
            <div style="text-align:center; margin-bottom:20px;">
                <img src="{{ asset('logo.png') }}" alt="{{ config('app.name') }}" width="120">
            </div>
        </x-mail::header>
    </x-slot:header>

    {{-- Body --}}
    {!! $slot !!}

    {{-- Subcopy --}}
    @isset($subcopy)
    <x-slot:subcopy>
        <x-mail::subcopy>
            {!! $subcopy !!}
        </x-mail::subcopy>
    </x-slot:subcopy>
    @endisset

    {{-- Footer --}}
    <x-slot:footer>
        <x-mail::footer>
            <sub style="color:#6b7280;">
                Cet email est une notification automatique envoyée par {{ config('app.name') }}.
                <!-- Pour toute question, contactez-nous : support@{{ parse_url(config('app.url'), PHP_URL_HOST) }} -->
            </sub>


            © {{ date('Y') }} {{ config('app.name') }}. {{ __('All rights reserved.') }}
        </x-mail::footer>
    </x-slot:footer>
</x-mail::layout>