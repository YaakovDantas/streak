@props(['refillBalance'])

@php
    $tabs = [
        ['id' => 'free', 'label' => __('refill.tabs.free'), 'color' => 'text-[#272643] bg-[#bae8e8]', 'description' => __('refill.descriptions.free')],
        ['id' => 'ad', 'label' => __('refill.tabs.ad'), 'color' => 'text-[#272643] bg-[#fce38a]', 'description' => __('refill.descriptions.ad')],
        //['id' => 'paid', 'label' => __('refill.tabs.paid'), 'color' => 'text-white bg-[#2c698d]', 'description' => __('refill.descriptions.paid')],
    ];
@endphp
<div
    x-data="refillTabs"
    x-cloak
    class="mt-10 bg-[#1e1e2f] border border-[#bae8e8] rounded-xl shadow-inner text-white p-6"
>
    <h3 class="text-xl font-semibold mb-4 text-[#bae8e8]"> {{ __('refill.title') }}</h3>

    <div class="text-sm">
        <div class="flex justify-center gap-2 mb-4">
            @foreach ($tabs as $tab)
                <button
                    @click="tab = '{{ $tab['id'] }}'"
                    :class="tab === '{{ $tab['id'] }}' ? '{{ $tab['color'] }}' : 'bg-transparent border border-[#bae8e8] text-[#bae8e8]'"
                    class="px-4 py-2 font-bold rounded-full transition-all"
                >
                    {{ $tab['label'] }}
                </button>
            @endforeach
        </div>

        <div class="mt-4">
            <template x-if="tab === 'free'">
                <div class="text-center space-y-2">
                    <div class="text-lg font-bold">
                        {{ trans_choice('refill.balance.free', $refillBalance->free ?? 0, ['count' => $refillBalance->free ?? 0]) }}
                    </div>
                    <p class="text-[#ffffff]/70">{{ __('refill.descriptions.free') }}</p>
                </div>
            </template>

            <template x-if="tab === 'ad'">
                <div class="text-center space-y-3">
                    <div class="text-lg font-bold">
                        {{ trans_choice('refill.balance.ad', $refillBalance->ad ?? 0, ['count' => $refillBalance->ad ?? 0]) }}
                    </div>
                    <p class="text-[#ffffff]/70">{{ __('refill.descriptions.ad') }}</p>

                    <button
                        @click="watchAd"
                        class="mt-2 bg-[#fce38a] text-[#272643] font-bold px-4 py-2 rounded-lg hover:scale-105 transition-transform"
                        type="button"
                        x-bind:disabled="showAd"
                        x-bind:class="showAd ? 'opacity-50 cursor-not-allowed' : ''"
                    >
                        📺 {{ __('refill.actions.watch_ad') }}
                    </button>
                </div>
            </template>

            {{-- <template x-if="tab === 'paid'">
                <div class="text-center space-y-3">
                    <div class="text-lg font-bold">
                        {{ trans_choice('refill.balance.paid', $refillBalance->paid ?? 0, ['count' => $refillBalance->paid ?? 0]) }}
                    </div>
                    <p class="text-[#ffffff]/70">{{ __('refill.descriptions.paid') }}</p>
                    <form method="POST" action="{{ route('refill.paid') }}">
                        @csrf
                        <button
                            class="mt-2 bg-gradient-to-r from-[#2c698d] to-[#4791c5] text-white font-bold px-4 py-2 rounded-lg hover:scale-105 transition-transform"
                        >
                            💳 {{ __('refill.actions.buy_refill') }}
                        </button>
                    </form>
                </div>
            </template> --}}
        </div>
    </div>
    <div
        x-show="showAd"
        x-transition
        style="display: none"
        class="fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-50"
    >
        <div
            id="ad-container"
            class="bg-white rounded-lg shadow-lg max-w-4xl w-full max-h-[90vh] p-4 relative"
        >
        </div>
        <button
            @click="closeAd"
            class="absolute top-2 right-2 bg-yellow-400 text-[#272643] rounded px-3 py-1 font-bold hover:bg-yellow-300 z-50"
            type="button"
            >
            ✖
        </button>
    </div>
</div>
</div>

<script async='async' src='https://www.googletagservices.com/tag/js/gpt.js'></script>
<script>
    document.addEventListener("alpine:init", () => {
        Alpine.data("refillTabs", () => ({
            tab: 'free',
            showAd: false,
            adSlot: null,

            watchAd() {
                this.showAd = true;

                if (!this.adSlot) {

                    googletag.cmd.push(function() {
                        this.adSlot = googletag.defineSlot(
                            '/6355419/Travel/Europe/France/Paris',
                            ['fluid'],
                            'ad-container'
                        ).addService(googletag.pubads());

                        // googletag.pubads().enableSingleRequest();
                        googletag.enableServices();
                        googletag.display('ad-container');
                    });
                }
            },

            closeAd() {
                this.showAd = false;
                fetch("{{ route('refill.ad') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json"
                    }
                });
                location.reload();
            }
        }));
    });
</script>
