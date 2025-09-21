@props(['weekLeaderboard', 'monthLeaderboard', 'allTimeLeaderboard'])

<!-- DESKTOP: Leaderboard fixo lateral -->
<div class="hidden sm:block absolute top-4 right-4 w-[260px] bg-[#1e1e2f] border border-[#bae8e8] rounded-xl p-4 shadow-xl text-sm text-white" x-data="{ tab: 'week' }">
    <div class="flex gap-2 mb-3">
        <button @click="tab = 'week'" :class="tab === 'week' ? 'bg-[#bae8e8] text-[#272643]' : 'bg-transparent border border-[#bae8e8] text-[#bae8e8]'" class="flex-1 py-1.5 rounded-full font-bold">
            {{ __('leaderboard.week') }}
        </button>
        <button @click="tab = 'month'" :class="tab === 'month' ? 'bg-[#bae8e8] text-[#272643]' : 'bg-transparent border border-[#bae8e8] text-[#bae8e8]'" class="flex-1 py-1.5 rounded-full font-bold">
            {{ __('leaderboard.month') }}
        </button>
        <button @click="tab = 'all'" :class="tab === 'all' ? 'bg-[#bae8e8] text-[#272643]' : 'bg-transparent border border-[#bae8e8] text-[#bae8e8]'" class="flex-1 py-1.5 rounded-full font-bold">
            {{ __('leaderboard.all') }}
        </button>
    </div>

    <x-leader-board :data="$weekLeaderboard" :type="'week'" />
    <x-leader-board :data="$monthLeaderboard" :type="'month'" />
    <x-leader-board :data="$allTimeLeaderboard" :type="'all'" />
</div>

<!-- MOBILE: botão fixo e modal -->
<div class="sm:hidden fixed bottom-4 left-0 right-0 flex justify-center z-50" x-data="{ showModal: false, tab: 'week' }">
    <button @click="showModal = true" class="bg-[#bae8e8] text-[#272643] font-bold px-6 py-2 rounded-full shadow-lg">
        {{ __('leaderboard.see') }}
    </button>

    <!-- Modal -->
    <div
        x-show="showModal"
        x-transition
        @click.self="showModal = false"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
    >
        <div class="bg-[#1e1e2f] w-11/12 max-w-md p-4 rounded-xl border border-[#bae8e8] text-white text-sm" @click.stop>
            <div class="flex justify-between items-center mb-3">
                <h2 class="text-lg font-bold">{{ __('leaderboard.title') }}</h2>
                <button @click="showModal = false" class="text-white text-xl">&times;</button>
            </div>

            <div class="flex gap-2 mb-3">
                <button @click="tab = 'week'" :class="tab === 'week' ? 'bg-[#bae8e8] text-[#272643]' : 'bg-transparent border border-[#bae8e8] text-[#bae8e8]'" class="flex-1 py-1.5 rounded-full font-bold">
                    {{ __('leaderboard.week') }}
                </button>
                <button @click="tab = 'month'" :class="tab === 'month' ? 'bg-[#bae8e8] text-[#272643]' : 'bg-transparent border border-[#bae8e8] text-[#bae8e8]'" class="flex-1 py-1.5 rounded-full font-bold">
                    {{ __('leaderboard.month') }}
                </button>
                <button @click="tab = 'all'" :class="tab === 'all' ? 'bg-[#bae8e8] text-[#272643]' : 'bg-transparent border border-[#bae8e8] text-[#bae8e8]'" class="flex-1 py-1.5 rounded-full font-bold">
                    {{ __('leaderboard.all') }}
                </button>
            </div>

            <x-leader-board :data="$weekLeaderboard" :type="'week'" />
            <x-leader-board :data="$monthLeaderboard" :type="'month'" />
            <x-leader-board :data="$allTimeLeaderboard" :type="'all'" />
        </div>
    </div>
</div>
