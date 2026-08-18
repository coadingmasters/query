@props([
    'tone' => 'success',   // success | error
    'heading',

    // Where to send focus once the dialog closes, as a CSS selector. The
    // footer form and the contact form both render this component, so the
    // field to return to cannot be assumed.
    'focus' => null,
])

@php
    $success = $tone === 'success';
@endphp

{{-- Rendered with the open attribute so it is visible without JavaScript,
     where it lays out as an ordinary panel above the form. The script below
     upgrades it to a real modal, with backdrop, focus trap and Escape to
     close, when the browser has showModal(). --}}
@php $headingId = 'result-heading-'.Str::random(6); @endphp

<dialog data-result-dialog open aria-labelledby="{{ $headingId }}"
        @if ($focus) data-result-focus="{{ $focus }}" @endif
        class="result-card m-auto w-[calc(100%-2rem)] max-w-md rounded-2xl border border-line bg-surface p-0 shadow-2xl backdrop:bg-ink/50 backdrop:backdrop-blur-sm">

    <div class="p-6 text-center sm:p-8">
        <div @class([
            'relative mx-auto flex size-32 items-center justify-center rounded-full',
            'bg-primary-light' => $success,
            'bg-warning-light' => ! $success,
        ])>
            <svg class="result-cat size-24" viewBox="0 0 120 120" fill="none" aria-hidden="true">
                {{-- Ears, with the inner ear painted a shade in from the edge
                     so the outline still reads at small sizes. --}}
                <path d="M40 36 L45 13 L60 30 Z" fill="currentColor" class="{{ $success ? 'text-primary-vivid' : 'text-warning-vivid' }}"/>
                <path d="M80 36 L75 13 L60 30 Z" fill="currentColor" class="{{ $success ? 'text-primary-vivid' : 'text-warning-vivid' }}"/>
                <path d="M45 33 L47 21 L55 30 Z" fill="#FCE3DD"/>
                <path d="M75 33 L73 21 L65 30 Z" fill="#FCE3DD"/>

                <ellipse cx="60" cy="54" rx="31" ry="27" fill="currentColor"
                         class="{{ $success ? 'text-primary-vivid' : 'text-warning-vivid' }}"/>

                {{-- Muzzle, so the nose and mouth sit on their own ground
                     rather than on the body color. --}}
                <ellipse cx="60" cy="64" rx="17" ry="12" fill="#FFF6F1"/>

                <ellipse class="result-eye" cx="49" cy="50" rx="4.6" ry="6" fill="#12383B"/>
                <ellipse class="result-eye" cx="71" cy="50" rx="4.6" ry="6" fill="#12383B"/>
                <circle cx="50.6" cy="47.8" r="1.5" fill="#FFFFFF"/>
                <circle cx="72.6" cy="47.8" r="1.5" fill="#FFFFFF"/>

                <path d="M56 59 h8 l-4 4.5 Z" fill="#12383B"/>

                @if ($success)
                    <path d="M60 63.5 q-4 5 -8 1.5 M60 63.5 q4 5 8 1.5" stroke="#12383B"
                          stroke-width="2.4" stroke-linecap="round"/>
                @else
                    {{-- The same face, turned down: an error should look like
                         one at a glance, before the words are read. --}}
                    <path d="M53 70 q7 -5 14 0" stroke="#12383B" stroke-width="2.4" stroke-linecap="round"/>
                @endif

                <path d="M42 60 L26 56 M42 65 L27 66 M78 60 L94 56 M78 65 L93 66"
                      stroke="#12383B" stroke-width="2" stroke-linecap="round" opacity="0.45"/>

                @if ($success)
                    {{-- Waving paw, and hearts that rise and fade on a stagger. --}}
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
                @endif
            </svg>
        </div>

        <h2 id="{{ $headingId }}" class="mt-5 font-heading text-2xl font-extrabold tracking-tight text-ink">
            {{ $heading }}
        </h2>

        <div class="mt-3 text-base leading-relaxed text-ink-muted">
            {{ $slot }}
        </div>

        <button type="button" data-result-close
                class="btn-primary mt-6 w-full rounded-full sm:w-auto sm:px-10">
            {{ $success ? 'Lovely' : 'Let me fix it' }}
        </button>
    </div>
</dialog>

@once
    @push('scripts')
        <script>
            // Upgrades the panel to a real modal. Kept out of the markup so a
            // visitor without JavaScript still sees the message, just inline.
            // querySelectorAll, not querySelector: the contact page renders
            // this component and so does the footer, so a page can carry more
            // than one.
            document.querySelectorAll('[data-result-dialog]').forEach((dialog) => {
                const close = () => dialog.close ? dialog.close() : dialog.removeAttribute('open');

                if (typeof dialog.showModal === 'function') {
                    dialog.removeAttribute('open');
                    dialog.showModal();
                }

                dialog.querySelector('[data-result-close]')?.addEventListener('click', () => {
                    close();

                    // Send them where they can act: the first field that needs
                    // attention, else whichever field this dialog names.
                    const selector = dialog.dataset.resultFocus;
                    const target = document.querySelector('[aria-invalid="true"]')
                        ?? (selector ? document.querySelector(selector) : null);
                    target?.focus({ preventScroll: false });
                });

                // Clicking the backdrop lands on the dialog itself, never on
                // its contents, which is what separates the two.
                dialog.addEventListener('click', (event) => {
                    if (event.target === dialog) close();
                });
            });
        </script>
    @endpush
@endonce
