{{-- The happy cat face used by the name generator's result dialog and its
     live preview panel. Pulled out once both needed the same markup, rather
     than a third copy-paste of this SVG. --}}
@props(['size' => 'size-24'])

<svg class="result-cat {{ $size }}" viewBox="0 0 120 120" fill="none" aria-hidden="true">
    <path d="M40 36 L45 13 L60 30 Z" fill="currentColor" class="text-primary-vivid"/>
    <path d="M80 36 L75 13 L60 30 Z" fill="currentColor" class="text-primary-vivid"/>
    <path d="M45 33 L47 21 L55 30 Z" fill="#FCE3DD"/>
    <path d="M75 33 L73 21 L65 30 Z" fill="#FCE3DD"/>
    <ellipse cx="60" cy="54" rx="31" ry="27" fill="currentColor" class="text-primary-vivid"/>
    <ellipse cx="60" cy="64" rx="17" ry="12" fill="#FFF6F1"/>
    <ellipse class="result-eye" cx="49" cy="50" rx="4.6" ry="6" fill="#12383B"/>
    <ellipse class="result-eye" cx="71" cy="50" rx="4.6" ry="6" fill="#12383B"/>
    <circle cx="50.6" cy="47.8" r="1.5" fill="#FFFFFF"/>
    <circle cx="72.6" cy="47.8" r="1.5" fill="#FFFFFF"/>
    <path d="M56 59 h8 l-4 4.5 Z" fill="#12383B"/>
    <path d="M60 63.5 q-4 5 -8 1.5 M60 63.5 q4 5 8 1.5" stroke="#12383B" stroke-width="2.4" stroke-linecap="round"/>
    <path d="M42 60 L26 56 M42 65 L27 66 M78 60 L94 56 M78 65 L93 66" stroke="#12383B" stroke-width="2" stroke-linecap="round" opacity="0.45"/>
    <g class="result-paw">
        <ellipse cx="98" cy="74" rx="9" ry="10" fill="currentColor" class="text-primary-vivid"/>
        <circle cx="94" cy="69" r="2.1" fill="#FCE3DD"/>
        <circle cx="98.5" cy="67.5" r="2.1" fill="#FCE3DD"/>
        <circle cx="102.5" cy="70" r="2.1" fill="#FCE3DD"/>
        <ellipse cx="98" cy="76" rx="4" ry="3.4" fill="#FCE3DD"/>
    </g>
    <path class="result-heart" d="M20 30c-2.4-1.5-4.6-3.1-4.6-5.6a2.6 2.6 0 0 1 4.6-1.5 2.6 2.6 0 0 1 4.6 1.5c0 2.5-2.2 4.1-4.6 5.6Z" fill="#F47C6B"/>
    <path class="result-heart result-heart-2" d="M100 26c-2-1.3-3.9-2.6-3.9-4.7a2.2 2.2 0 0 1 3.9-1.3 2.2 2.2 0 0 1 3.9 1.3c0 2.1-1.9 3.4-3.9 4.7Z" fill="#F47C6B"/>
    <path class="result-heart result-heart-3" d="M31 15c-1.7-1.1-3.3-2.2-3.3-4a1.9 1.9 0 0 1 3.3-1.1 1.9 1.9 0 0 1 3.3 1.1c0 1.8-1.6 2.9-3.3 4Z" fill="#F47C6B"/>
</svg>
