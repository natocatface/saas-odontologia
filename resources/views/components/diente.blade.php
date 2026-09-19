@props(['n', 'bottom' => false])

@php
    $ultimo = ((int) $n) % 10;
    $tipo = match (true) {
        in_array($ultimo, [1, 2], true) => 'incisivo',
        $ultimo === 3 => 'canino',
        in_array($ultimo, [4, 5], true) => 'premolar',
        default => 'molar',
    };

    // Cada tipo: ancho, corona (rellenable), raices[], detalle (lineas internas). ViewBox 56x104, corona arriba.
    $shapes = [
        'incisivo' => [
            'w' => 'w-6',
            'crown' => 'M20 12 Q20 6 28 6 Q36 6 36 12 L34 40 Q33 46 28 46 Q23 46 22 40 Z',
            'roots' => ['M28 46 C24 46 24 60 26 78 C27 92 27 98 28 98 C29 98 29 92 30 78 C32 60 32 46 28 46 Z'],
            'detalle' => 'M22 13 Q28 9 34 13 M28 22 L28 70',
        ],
        'canino' => [
            'w' => 'w-6',
            'crown' => 'M28 5 Q35 15 34 21 L33 40 Q32 46 28 46 Q24 46 23 40 L22 21 Q21 15 28 5 Z',
            'roots' => ['M28 46 C23 46 23 62 25 80 C26 96 26 101 28 101 C30 101 30 96 31 80 C33 62 33 46 28 46 Z'],
            'detalle' => 'M28 10 L28 72',
        ],
        'premolar' => [
            'w' => 'w-7',
            'crown' => 'M17 17 Q17 9 23 9 Q28 6 33 9 Q39 9 39 17 L37 40 Q36 46 28 46 Q20 46 19 40 Z',
            'roots' => ['M28 46 C24 46 22 62 24 82 C25 94 25 98 28 98 C31 98 31 94 32 82 C34 62 32 46 28 46 Z'],
            'detalle' => 'M21 14 Q24 19 28 15 Q32 19 35 14 M28 24 L28 74',
        ],
        'molar' => [
            'w' => 'w-9',
            'crown' => 'M10 18 Q10 9 18 9 Q28 6 38 9 Q46 9 46 18 L44 40 Q43 47 35 47 L21 47 Q13 47 12 40 Z',
            'roots' => [
                'M23 47 C19 47 16 64 18 86 C19 95 23 94 23 85 L26 49 Z',
                'M33 47 C37 47 40 64 38 86 C37 95 33 94 33 85 L30 49 Z',
            ],
            'detalle' => 'M16 14 Q20 19 24 14 Q28 19 32 14 Q36 19 40 14 M28 18 L28 44',
        ],
    ];
    $s = $shapes[$tipo];
@endphp

<button type="button" @click="pick({{ $n }})" class="flex flex-col items-center focus:outline-none">
    @unless($bottom)
        <span class="relative rounded-lg transition" :class="selected === {{ $n }} ? 'bg-blue-100/60 ring-2 ring-blue-400' : 'hover:bg-slate-100'">
            <svg viewBox="0 0 56 104" class="{{ $s['w'] }} h-16 rotate-180">
                <g :stroke="selected === {{ $n }} ? '#2563eb' : '#64748b'" stroke-width="2" stroke-linejoin="round" stroke-linecap="round">
                    @foreach ($s['roots'] as $r)<path d="{{ $r }}" fill="#ffffff" />@endforeach
                    <path d="{{ $s['crown'] }}" :fill="color({{ $n }})" />
                    <path d="{{ $s['detalle'] }}" fill="none" stroke="#cbd5e1" stroke-width="1.4" />
                </g>
            </svg>
            <template x-if="datos[{{ $n }}].general === 'ausente' || datos[{{ $n }}].general === 'extraccion'">
                <span class="absolute inset-0 flex items-center justify-center text-rose-600 font-black text-xl">✕</span>
            </template>
            <template x-if="datos[{{ $n }}].nota">
                <span class="absolute top-0 right-0 h-2.5 w-2.5 rounded-full bg-blue-500 ring-2 ring-white"></span>
            </template>
        </span>
        <span x-show="caraHallazgo({{ $n }})" x-cloak class="-mt-0.5"><x-cara-mini :n="$n" /></span>
        <span class="text-[11px] text-slate-500 font-bold mt-0.5">{{ $n }}</span>
    @endunless

    @if($bottom)
        <span class="text-[11px] text-slate-500 font-bold mb-0.5">{{ $n }}</span>
        <span x-show="caraHallazgo({{ $n }})" x-cloak class="-mb-0.5"><x-cara-mini :n="$n" /></span>
        <span class="relative rounded-lg transition" :class="selected === {{ $n }} ? 'bg-blue-100/60 ring-2 ring-blue-400' : 'hover:bg-slate-100'">
            <svg viewBox="0 0 56 104" class="{{ $s['w'] }} h-16">
                <g :stroke="selected === {{ $n }} ? '#2563eb' : '#64748b'" stroke-width="2" stroke-linejoin="round" stroke-linecap="round">
                    @foreach ($s['roots'] as $r)<path d="{{ $r }}" fill="#ffffff" />@endforeach
                    <path d="{{ $s['crown'] }}" :fill="color({{ $n }})" />
                    <path d="{{ $s['detalle'] }}" fill="none" stroke="#cbd5e1" stroke-width="1.4" />
                </g>
            </svg>
            <template x-if="datos[{{ $n }}].general === 'ausente' || datos[{{ $n }}].general === 'extraccion'">
                <span class="absolute inset-0 flex items-center justify-center text-rose-600 font-black text-xl">✕</span>
            </template>
            <template x-if="datos[{{ $n }}].nota">
                <span class="absolute top-0 right-0 h-2.5 w-2.5 rounded-full bg-blue-500 ring-2 ring-white"></span>
            </template>
        </span>
    @endif
</button>
