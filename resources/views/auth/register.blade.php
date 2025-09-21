@extends('auth.layout')

@section('content')
    <h1>{{ __('auth.register_title') }}</h1>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <input
            type="text"
            name="name"
            placeholder="{{ __('auth.name') }}"
            required
            class="input"
            value="{{ old('name') }}"
        />
        <input
            type="email"
            name="email"
            placeholder="{{ __('auth.email') }}"
            required
            class="input"
            value="{{ old('email') }}"
        />
        <input
            type="password"
            name="password"
            placeholder="{{ __('auth.password') }}"
            required
            class="input"
        />
        <input
            type="password"
            name="password_confirmation"
            placeholder="{{ __('auth.password_confirmation') }}"
            required
            class="input"
        />

        <button type="submit" class="neon-btn w-full">
            {{ __('auth.register_title') }}
        </button>

        <p class="mt-4 text-sm">
            {{ __('auth.already_have_account') }}
            <a href="{{ route('login') }}">{{ __('auth.login') }}</a>
        </p>
    </form>
@endsection
