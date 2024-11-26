@props(['title', 'value', 'color' => 'blue', 'icon' => null])

<div class="group relative transform transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl">
    <div
        class="absolute -inset-0.5 bg-gradient-to-r from-{{ $color }}-400 to-{{ $color }}-600 
                rounded-xl opacity-40 group-hover:opacity-100 blur-lg transition-all duration-300">
    </div>

    <div
        class="relative p-6 rounded-xl shadow-lg 
                bg-gradient-to-br from-{{ $color }}-600 to-{{ $color }}-800 
                text-white overflow-hidden">
        {{-- Icon Section --}}
        @if ($icon)
            <div class="absolute top-0 right-0 m-4 opacity-20 group-hover:opacity-40 transition-opacity">
                {!! $icon !!}
            </div>
        @endif

        {{-- Content --}}
        <div class="relative z-10">
            <div class="flex items-center justify-between">
                <h4 class="font-semibold text-lg text-white/80 group-hover:text-white transition-colors">
                    {{ $title }}
                </h4>

                {{-- Trending Indicator --}}
                <div class="flex items-center text-sm text-white/70 group-hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 9.586 14.586 6H12z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="text-xs">+2.5%</span>
                </div>
            </div>

            <p class="mt-3 text-3xl font-bold text-white tracking-wide">
                {{ $value }}
            </p>
        </div>

        {{-- Subtle Background Effect --}}
        <div
            class="absolute bottom-0 right-0 w-32 h-32 bg-white/10 rounded-full 
                    transform translate-x-1/2 translate-y-1/2 
                    group-hover:scale-125 transition-transform duration-500">
        </div>
    </div>
</div>
