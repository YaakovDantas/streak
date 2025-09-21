@props(['streak', 'clickedToday'])

<div class="text-center">
    <h1 class="text-4xl font-bold mb-4">
        🔥 {{ __('streak.current') }}: {{ $streak->current_streak ?? 0 }}
    </h1>
    <h2 class="text-xl mb-2">
        🏆 {{ __('streak.highest') }}: {{ $streak->highest_streak ?? 0 }}
    </h2>

    <form id="click-form" method="POST" action="{{ route('click') }}">
        @csrf
        <button
            type="submit"
            class="mt-6 px-8 py-4 text-white font-bold text-lg rounded-full transition-all duration-300
            {{ $clickedToday ? 'bg-[#2c698d] cursor-not-allowed opacity-60' : 'bg-gradient-to-r from-[#2c698d] to-[#bae8e8] hover:scale-105 hover:shadow-lg' }}"
            {{ $clickedToday ? 'disabled' : '' }}
        >
            {{ $clickedToday ? __('streak.maintained') : __('streak.click') }}
        </button>
    </form>

    <div id="streak-feedback" class="mt-4 text-[#00ffae] font-semibold text-lg opacity-0 transition-opacity duration-500"></div>
</div>
