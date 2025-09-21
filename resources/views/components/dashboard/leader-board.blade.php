@props(['data', 'type'])

<template x-if="tab === '{{$type}}'">
    <ul class="space-y-1">
        @foreach ($data['top3'] as $index => $user)
            <li class="flex justify-between">
                <span class="{{ $index === 0 ? 'font-bold text-[#00ffae]' : '' }}">
                    #{{ $index + 1 }} {{ $user->nickname ?? $user->name }}
                </span>
                <span>{{ $user->clicks_count }}🔥</span>
            </li>
        @endforeach

        @if ($data['user'])
            <hr>
            <li class="flex justify-between">
                <span class="font-bold text-[#f24f00]">
                    #{{ $data['user']['position'] }} {{ request()->user()->nickname ?? request()->user()->name }}
                </span>
                <span>{{ $data['user']['clicks_count'] }}🔥</span>
            </li>
        @endif
    </ul>
</template>
