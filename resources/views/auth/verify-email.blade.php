@extends('auth.layout')

@section('content')
    <h1>{{ __('auth.verify_email_title') }}</h1>

    <p class="mb-4">
        {{ __('auth.verify_email_text') }}
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ __('auth.verification_sent') }}
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="neon-btn w-full">
            {{ __('auth.resend_verification') }}
        </button>
    </form>

    <p class="mt-4 text-sm">
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
           {{ __('auth.logout') }}
        </a>
    </p>

    <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
        @csrf
    </form>
@endsection
