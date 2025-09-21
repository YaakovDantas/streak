@extends('auth.layout')

@section('content')
    <h1 class="text-2xl font-semibold mb-6 text-center">{{ __('auth.login_title') }}</h1>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <input
            type="email"
            name="email"
            placeholder="{{ __('auth.email') }}"
            required
            class="input w-full"
            value="{{ old('email') }}"
        />
        <input
            type="password"
            name="password"
            placeholder="{{ __('auth.password') }}"
            required
            class="input w-full"
        />

        <div class="text-right">
            <a href="{{ route('password.request') }}" class="text-sm underline" style="color: #bae8e8;">
                {{ __('auth.forgot') }}
            </a>
        </div>

        <button type="submit" class="neon-btn w-full">
            {{ __('auth.login') }}
        </button>
    </form>

    <p class="mt-6 text-sm text-center">{{ __('auth.or_login_with') }}</p>

    <div class="grid grid-cols-2 gap-3 mt-3">
        <a href="{{ route('auth.redirect', 'google') }}"
           class="flex items-center justify-center gap-2 px-4 py-2 border rounded hover:bg-gray-100 transition text-sm">
            <img src="{{ asset('social/google.png') }}" alt="Google" class="w-[20px] h-[20px] object-contain" />
            Google
        </a>

        <a href="{{ route('auth.redirect', 'github') }}"
           class="flex items-center justify-center gap-2 px-4 py-2 border rounded hover:bg-gray-100 transition text-sm">
            <img src="{{ asset('social/github.png') }}" alt="GitHub" class="w-[20px] h-[20px] object-contain" />
            GitHub
        </a>

        {{-- Comentados:
        <a href="{{ route('auth.redirect', 'twitter') }}"
           class="flex items-center justify-center gap-2 px-4 py-2 border rounded hover:bg-gray-100 transition text-sm">
            <img src="{{ asset('social/twitter.png') }}" alt="Twitter" class="w-[20px] h-[20px] object-contain" />
            Twitter
        </a>

        <a href="{{ route('auth.redirect', 'facebook') }}"
           class="flex items-center justify-center gap-2 px-4 py-2 border rounded hover:bg-gray-100 transition text-sm">
            <img src="{{ asset('social/facebook.png') }}" alt="Facebook" class="w-[20px] h-[20px] object-contain" />
            Facebook
        </a>
        --}}
    </div>

    <p class="mt-6 text-sm text-center">
        {{ __('auth.register_prompt') }}
        <a href="{{ route('register') }}" class="text-sm underline" style="color: #bae8e8;">
            {{ __('auth.register_link') }}
        </a>
    </p>
@endsection
