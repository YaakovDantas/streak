@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="flex flex-col items-center justify-center min-h-screen bg-[#272643] px-4 text-[#ffffff] font-sans p-3"
    {{-- Streak, Profile e Logout --}}
    x-data="{ tab: '{{ session('activeTab', 'streak') }}' }">
    <div

    >
        <!-- Tabs -->
        <div class="flex justify-center gap-4 mb-6 mt-4">
            <button
                @click="tab = 'streak'"
                :class="tab === 'streak'
                    ? 'bg-[#bae8e8] text-[#272643]'
                    : 'bg-[#ffffff]/10 border border-[#e3f6f5] text-[#ffffff]/80'"
                class="px-4 py-2 rounded-xl font-medium hover:opacity-80 transition"
            >
                {{ __('streak.streak') }}
            </button>

            <button
                @click="tab = 'profile'"
                :class="tab === 'profile'
                    ? 'bg-[#bae8e8] text-[#272643]'
                    : 'bg-[#ffffff]/10 border border-[#e3f6f5] text-[#ffffff]/80'"
                class="px-4 py-2 rounded-xl font-medium hover:opacity-80 transition"
            >
                {{ __('streak.profile') }}
            </button>

            <button
                @click="tab = 'logout'"
                :class="tab === 'logout'
                    ? 'bg-[#bae8e8] text-[#272643]'
                    : 'bg-[#ffffff]/10 border border-[#e3f6f5] text-[#ffffff]/80'"
                class="px-4 py-2 rounded-xl font-medium hover:opacity-80 transition"
            >
                {{ __('streak.logout') }}
            </button>
        </div>

        <!-- Conteúdo Streak -->
        <div x-show="tab === 'streak'" class="flex justify-center gap-2 mb-6 mt-4 p-2" >
            <div
                class="border-2 border-[#bae8e8] p-10 rounded-2xl shadow-[0_0_20px_#2c698d] w-screen max-w-md bg-[#1e1e2f] text-center"
            >
            {{-- Progress Bar da Semana --}}
            <x-week-progress :weekStreak="$weekStreak" />

            {{-- Tab Streak or Badges --}}
            <x-tab-streak-badge :streak="$streak" :clickedToday="$clickedToday" :badges="$badges"/>

            {{-- Refis com Abas --}}
            <x-refill-tabs :refillBalance="$refillBalance" />
            </div>
        </div>

        <!-- Conteúdo Profile -->
        <div x-show="tab === 'profile'" class="text-left space-y-4" >
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                <input type="hidden" name="tab" x-model="tab">
                <div
                    class="border-2 border-[#bae8e8] p-10 rounded-2xl shadow-[0_0_20px_#2c698d] w-full max-w-md bg-[#1e1e2f] text-center"
                >
                    <label class="block mb-3">
                        <span class="text-[#bae8e8] text-sm">{{ __('streak.nickname') }}</span>
                        <input type="text" name="nickname" value="{{ old('nickname', auth()->user()->nickname) }}"
                            class="mt-1 w-full px-3 py-2 rounded-lg bg-[#ffffff]/10 border border-[#e3f6f5] text-white focus:outline-none focus:ring focus:ring-[#bae8e8]"
                            placeholder="{{ __('streak.nickname') }}">
                        @error('nickname')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </label>

                    <label class="block mb-3">
                        <span class="text-[#bae8e8] text-sm">{{ __('streak.email') }}</span>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                            class="mt-1 w-full px-3 py-2 rounded-lg bg-[#ffffff]/10 border border-[#e3f6f5] text-white focus:outline-none focus:ring focus:ring-[#bae8e8]"
                            placeholder="{{ __('streak.email_placeholder') }}">
                            @error('email')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                    </label>

                    <label class="block mb-3">
                        <span class="text-[#bae8e8] text-sm">{{ __('streak.country') }}</span>
                        <select name="country_id"
                                class="mt-1 w-full px-3 py-2 rounded-lg bg-[#ffffff]/10 border border-[#e3f6f5] text-white focus:outline-none focus:ring focus:ring-[#bae8e8]">
                            <option class="text-black" value="">{{ __('streak.select_country') }}</option>
                            @foreach($countries as $country)
                                <option class="text-black " value="{{ $country->id }}"
                                    {{ auth()->user()->country_id == $country->id ? 'selected' : '' }}>
                                    {{ $country->name }} ({{ $country->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('country_id')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </label>

                    <label class="block mb-3">
                        <span class="text-[#bae8e8] text-sm">{{ __('streak.new_password') }}</span>
                        <input type="password" name="password"
                            value=""
                            autocomplete="new-password"
                            class="mt-1 w-full px-3 py-2 rounded-lg bg-[#ffffff]/10 border border-[#e3f6f5] text-white focus:outline-none focus:ring focus:ring-[#bae8e8]"
                            placeholder="">
                        @error('password')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </label>

                    <label class="block mb-3">
                        <span class="text-[#bae8e8] text-sm">{{ __('streak.confirm_password') }}</span>
                        <input type="password" name="password_confirmation"
                            autocomplete="new-password"
                            value=""
                            class="mt-1 w-full px-3 py-2 rounded-lg bg-[#ffffff]/10 border border-[#e3f6f5] text-white focus:outline-none focus:ring focus:ring-[#bae8e8]"
                            placeholder="">
                        @error('password_confirmation')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </label>

                    <button type="submit" class="mt-2 w-full bg-[#bae8e8] text-[#272643] py-2 rounded-lg font-medium hover:bg-[#94d2d2] transition">
                        {{ __('streak.save_changes') }}
                    </button>
                </div>
            </form>
        </div>


        <!-- Conteúdo Logout -->
        <div x-show="tab === 'logout'" class="space-y-4">
            <div
                class="border-2 border-[#bae8e8] p-10 rounded-2xl shadow-[0_0_20px_#2c698d] w-full max-w-md bg-[#1e1e2f] text-center"
            >
                <p class="text-[#ffffff]/80">{{ __('streak.logout_confirm') }}</p>
                <button class="w-full bg-red-500 hover:bg-red-600 text-white py-2 rounded-lg font-medium transition">
                    {{ __('streak.logout_button') }}
                </button>
            </div>
        </div>
</div>

        {{-- Mensagem de Sessão --}}
        @if(session('message'))
            <p class="mt-6 text-[#00ffae] font-medium">{{ session('message') }}</p>
        @endif
    </div>
    {{-- Leader board --}}
    <x-overall-board :weekLeaderboard="$weekLeaderboard" :monthLeaderboard="$monthLeaderboard" :allTimeLeaderboard="$allTimeLeaderboard" />




</div>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('click-form');
        const button = form.querySelector('button[type="submit"]');
        const feedback = document.getElementById('streak-feedback');

        const frases = @json(__('streak.feedback'));
        const streakKeptText = @json(__('streak.streak_kept'));
        const errorText = @json(__('streak.error'));
        const networkErrorText = @json(__('streak.network_error'));

        if (form && button && !button.disabled) {
            form.addEventListener('submit', async function (e) {
                e.preventDefault();
                button.disabled = true;

                confetti({ particleCount: 120, spread: 80, origin: { y: 0.6 } });

                feedback.classList.add('opacity-100');
                button.classList.add('scale-105');

                setTimeout(async () => {
                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({})
                        });

                        if (response.ok) {
                            button.textContent = "🔥 Streak mantido!";
                            button.classList.remove('bg-gradient-to-r', 'hover:scale-105', 'hover:shadow-lg');
                            button.classList.add('bg-[#2c698d]', 'cursor-not-allowed', 'opacity-60');
                            feedback.classList.remove('opacity-100');
                            window.location.reload();
                        } else {
                            feedback.textContent = '⚠️ Algo deu errado.';
                            feedback.classList.add('text-red-500');
                            button.disabled = false;
                        }
                    } catch (err) {
                        console.log(err)
                        feedback.textContent = '⚠️ Erro de rede.';
                        feedback.classList.add('text-red-500');
                        button.disabled = false;
                    }
                }, 2500);
            });
        }
    });
</script>


@endsection
