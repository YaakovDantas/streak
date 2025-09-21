@props(['clicked'])

<form method="POST" action="{{ route('click') }}">
    @csrf
    <button
        type="submit"
        class="w-full py-4 px-6 rounded-full font-bold text-lg transition-all duration-300
            {{ $clicked ? 'bg-[#2c698d] cursor-not-allowed opacity-60' : 'bg-[#2c698d] hover:bg-[#bae8e8] hover:text-[#272643]' }}"
        {{ $clicked ? 'disabled' : '' }}
    >
        {{ $clicked ? 'Por hoje é só' : 'Clique Diário' }}
    </button>
</form>
