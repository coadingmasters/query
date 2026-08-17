import Alpine from 'alpinejs';

/**
 * Cycles a list of words in place. The markup renders the first word server
 * side, so the line still reads correctly if this script never runs, and the
 * interval is skipped entirely for visitors who prefer reduced motion.
 */
Alpine.data('rotator', (words, interval = 2600) => ({
    current: words[0],

    init() {
        if (words.length < 2 || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            return;
        }

        let i = 0;
        setInterval(() => {
            i = (i + 1) % words.length;
            this.current = words[i];
        }, interval);
    },
}));

window.Alpine = Alpine;
Alpine.start();
