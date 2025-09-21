@extends('auth.layout')

@section('content')
    <h1>{{ __('auth.forgot_password_title') }}</h1>

    @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <input
            type="email"
            name="email"
            placeholder="{{ __('auth.email') }}"
            required
            class="input"
            value="{{ old('email') }}"
        />

        <button type="submit" class="neon-btn w-full">
            {{ __('auth.send_link') }}
        </button>

        <p class="mt-4 text-sm">
            <a href="{{ route('login') }}">{{ __('auth.back_to_login') }}</a>
        </p>
    </form>
@endsection
