@props(['streak', 'clickedToday', 'badges'])

<div x-data="{ tab: 'streak' }" class="text-white font-sans px-4 py-6 max-w-3xl mx-auto">
    <!-- Abas -->
    <div class="flex justify-center gap-2 mb-6">
        <button @click="tab = 'streak'"
                :class="tab === 'streak' ? 'bg-[#bae8e8] text-[#272643]' : 'border border-[#bae8e8] text-[#bae8e8]'"
                class="px-4 py-2 rounded-full font-bold transition">
            🔥 Streak
        </button>
        <button @click="tab = 'badges'"
                :class="tab === 'badges' ? 'bg-[#bae8e8] text-[#272643]' : 'border border-[#bae8e8] text-[#bae8e8]'"
                class="px-4 py-2 rounded-full font-bold transition">
            🏅 Badges
        </button>
    </div>

    <div x-show="tab === 'streak'" class="transition duration-300">
        {{-- Card de Streak + Botão --}}
        <x-streak-card :streak="$streak" :clickedToday="$clickedToday" />
    </div>

    <div x-show="tab === 'badges'" class="transition duration-300 flex flex-row flex-wrap justify-start">
        {{-- Lista de Badges --}}
        <x-list-badges :badges="$badges" />
    </div>
</div>
