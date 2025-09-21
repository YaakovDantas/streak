@props(['weekStreak'])

<div class="flex justify-center gap-2 mb-6 mt-4">
    @foreach ($weekStreak as $day)
        @php
            $bgColor = $day['clicked']
                ? 'bg-[#bae8e8] text-[#272643]'
                : ($day['refilled']
                    ? 'bg-[#ffc107] text-[#272643]'
                    : 'bg-[#ffffff] bg-opacity-10 border border-[#e3f6f5] text-[#ffffff]/60');
        @endphp

        <div class="w-10 h-14 sm:w-12 sm:h-16 rounded-xl p-1.5 flex flex-col items-center justify-center {{ $bgColor }}">
            <span class="text-sm font-medium">{{ Str::upper(Str::ascii(\Carbon\Carbon::parse($day['date'])->isoFormat('ddd'))) }}</span>

            @if ($day['clicked'])
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#2c698d]" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd" d="M20.293 6.293a1 1 0 010 1.414L10 18l-4.293-4.293a1 1 0 011.414-1.414L10 15.172l9.293-9.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
            @elseif ($day['refilled'])
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mt-1 text-[#272643]" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 2C10 2 5 7.23 5 11a5 5 0 0010 0c0-3.77-5-9-5-9zm0 15a3 3 0 01-3-3c0-1.38 1.25-3.39 3-5.72 1.75 2.33 3 4.34 3 5.72a3 3 0 01-3 3z" clip-rule="evenodd" />
                </svg>
            @else
                <div class="w-2 h-2 rounded-full bg-[#ffffff]/30 mt-1"></div>
            @endif
        </div>
    @endforeach
</div>
