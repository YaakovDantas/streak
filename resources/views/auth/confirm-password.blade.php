@extends('auth.layout')

@section('content')
    <h1>{{ __('auth.confirm_password_title') }}</h1>

    <p class="mb-4">
        {{ __('auth.confirm_password_text') }}
    </p>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <input
            type="password"
            name="password"
            placeholder="{{ __('auth.password') }}"
            required
            class="input"
        />

        <button type="submit" class="neon-btn w-full">
            {{ __('auth.confirm') }}
        </button>
    </form>
@endsection
