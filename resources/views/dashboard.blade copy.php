@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="flex items-center justify-center min-h-screen bg-[#272643] px-4 text-[#ffffff] font-sans">
    <div class="border-2 border-[#bae8e8] p-10 rounded-2xl shadow-[0_0_20px_#2c698d] w-full max-w-md bg-[#1e1e2f] text-center">

        {{-- Progress Bar da Semana --}}
        <x-week-progress :weekStreak="$weekStreak" />

        {{-- Tab Streak or Badges --}}
        <x-tab-streak-badge :streak="$streak" :clickedToday="$clickedToday" :badges="$badges"/>

        {{-- Refis com Abas --}}
        <x-refill-tabs :refillBalance="$refillBalance" />


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

        const frases = [
            "🔥 Manteve o foco!",
            "🚀 Mais um dia completo!",
            "💪 Você está crescendo!",
            "🧠 Hábito em construção!",
            "👏 Excelente!"
        ];

        if (form && button && !button.disabled) {
            form.addEventListener('submit', async function (e) {
                e.preventDefault();
                button.disabled = true;

                confetti({ particleCount: 120, spread: 80, origin: { y: 0.6 } });

                feedback.textContent = frases[Math.floor(Math.random() * frases.length)];
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
