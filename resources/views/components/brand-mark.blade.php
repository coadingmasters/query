@props(['class' => 'size-9'])

{{-- A paw inside a magnifier: the two halves of the name. Inline rather than
     a file so it costs no request and can take colour from the design tokens. --}}
<svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 56 56" fill="none" aria-hidden="true">
    <circle cx="26" cy="26" r="17" stroke="var(--color-primary)" stroke-width="4"/>
    <path d="M38.5 38.5 49 49" stroke="var(--color-accent-vivid)" stroke-width="5" stroke-linecap="round"/>
    <g fill="var(--color-primary)">
        <ellipse cx="26" cy="31.8" rx="5.1" ry="3.9"/>
        <circle cx="18.4" cy="21.8" r="2.1"/>
        <circle cx="23" cy="17.8" r="2.1"/>
        <circle cx="29" cy="17.8" r="2.1"/>
        <circle cx="33.6" cy="21.8" r="2.1"/>
    </g>
</svg>
