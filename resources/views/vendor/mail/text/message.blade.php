<x-mail::layout>
    {{-- Header --}}
    <x-slot:header>
        <x-mail::header :url="config('app.url')">
            <div style="text-align:center; margin-bottom:20px;">
                <img src="{{ asset('logo.png') }}" alt="{{ config('app.name') }}" width="120">
            </div>
        </x-mail::header>
    </x-slot:header>

    {{-- Body --}}
    {{ $slot }}

    {{-- Subcopy --}}
    @isset($subcopy)
    <x-slot:subcopy>
        <x-mail::subcopy>
            {{ $subcopy }}
        </x-mail::subcopy>
    </x-slot:subcopy>
    @endisset

    {{-- Footer --}}
    <x-slot:footer>
        <x-mail::footer>
            © {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
        </x-mail::footer>
    </x-slot:footer>
</x-mail::layout>