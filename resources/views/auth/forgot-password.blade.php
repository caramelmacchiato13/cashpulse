<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <!-- <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a> -->
            <img src="{{ asset('img/logo.png') }}" style="width: 500px;" />
        </x-slot>

        <div class="mb-4 text-base text-gray-600">
            {{ __('Lupa kata sandi? Tenang saja. Masukkan email Anda dan kami akan mengirimkan link untuk membuat kata sandi baru.') }}
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-label for="email" :value="__('Email')" />

                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button>
                    {{ __('Perbarui Password') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
