<x-guest-layout>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: url('{{ asset('img/bg2.png') }}') no-repeat center center fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
            min-height: 100vh;
        }
        
        .auth-card-wrapper {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Make the auth card completely transparent */
        .auth-card {
            background-color: transparent !important;
            box-shadow: none;
        }
    </style>
    
    <div class="auth-card-wrapper">
        <x-auth-card class="auth-card">
            <x-slot name="logo">
                <a href="/">
                    <!--x-application-logo class="w-20 h-20 fill-current text-gray-500" /-->
                    <img src="{{ asset('img/logo.png') }}" style="width: 500px;" />
                </a>
            </x-slot>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-label for="email" :value="__('Email')" />

                    <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-label for="password" :value="__('Password')" />

                    <x-input id="password" class="block mt-1 w-full"
                                    type="password"
                                    name="password"
                                    required autocomplete="current-password" />
                </div>

                <!-- Remember Me -->
                <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="remember">
                        <span class="ml-2 text-sm text-gray-600">{{ __('Ingatkan saya') }}</span>
                    </label>
                </div>

                <div class="flex items-center justify-end mt-4" style="display: flex; justify-content: flex-end; align-items: center;">
                    <a href="{{ route('register') }}" style="margin-right: 30px; font-size: 14px; font-weight: 500;">
                        Register
                    </a>
                    
                    <button type="submit" style="background-color: #1f2937; color: white; padding: 8px 16px; border-radius: 4px; font-weight: 500; border: none;">
                        Log in
                    </button>
                </div>
            </form>
        </x-auth-card>
    </div>
</x-guest-layout>