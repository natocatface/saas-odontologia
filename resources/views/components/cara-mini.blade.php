@props(['n'])

<svg viewBox="0 0 24 24" class="h-6 w-6">
    <polygon points="0,0 24,0 17,7 7,7"   :fill="colorCara({{ $n }},'v')" stroke="#cbd5e1" stroke-width="0.7" />
    <polygon points="24,0 24,24 17,17 17,7" :fill="colorCara({{ $n }},'d')" stroke="#cbd5e1" stroke-width="0.7" />
    <polygon points="0,24 24,24 17,17 7,17" :fill="colorCara({{ $n }},'l')" stroke="#cbd5e1" stroke-width="0.7" />
    <polygon points="0,0 0,24 7,17 7,7"   :fill="colorCara({{ $n }},'m')" stroke="#cbd5e1" stroke-width="0.7" />
    <rect x="7" y="7" width="10" height="10" :fill="colorCara({{ $n }},'o')" stroke="#cbd5e1" stroke-width="0.7" />
</svg>
