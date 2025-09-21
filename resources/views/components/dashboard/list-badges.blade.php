
@props(['badges'])

@foreach($badges as $badge)
    <div class="relative group flex flex-col items-center p-2 rounded-xl {{ $badge->hasBadge ? 'shadow-md' : 'opacity-30' }} m-2 w-16 mt-8">
        <img src="{{ asset('badges/' . $badge->slug . '.png') }}"
            alt="{{ $badge->name }}"
            class="w-12 h-12 mb-1">

        <div class="w-full bg-gray-200 rounded-full h-1 mt-1">
            <div class="bg-blue-500 h-1 rounded-full" style="width: {{ $badge->progress }}%"></div>
        </div>

        <div
            class="mt-4 absolute bottom-full mb-2 w-40 text-center text-xs px-2 py-1 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10 pointer-events-none"
            style="
                background: #272643;
                color: #bae8e8;
                box-shadow:
                0 0 4px #bae8e8,
                0 0 8px #2c698d,
                0 0 16px #bae8e8,
                0 0 24px #2c698d;
            "
            >
            {{ __('badges.' . $badge->slug) }}
        </div>
    </div>
@endforeach
