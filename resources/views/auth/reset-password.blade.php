@extends('auth.layout')

@section('content')
    <h1>{{ __('reset_password_title') }}</h1>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <input
            type="email"
            name="email"
            placeholder="{{ __('auth.email') }}"
            required
            class="input"
            value="{{ old('email', $email) }}"
        />
        <input
            type="password"
            name="password"
            placeholder="{{ __('auth.new_password') }}"
            required
            class="input"
        />
        <input
            type="password"
            name="password_confirmation"
            placeholder="{{ __('auth.confirm_new_password') }}"
            required
            class="input"
        />

        <button type="submit" class="neon-btn w-full">
            {{ __('auth.reset_password_title') }}
        </button>
    </form>
@endsection
