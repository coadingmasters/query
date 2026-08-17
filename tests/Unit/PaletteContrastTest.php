<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Guards the palette against accessibility regressions.
 *
 * The tokens in resources/css/app.css are the single source of truth — this
 * reads them rather than restating them, so a colour cannot be changed in one
 * place and silently pass here.
 *
 * Contrast is checked against *every* surface a colour may sit on, not just
 * white. That distinction matters: several values cleared 4.5:1 on white but
 * dropped to ~3.95:1 on the tinted surfaces, which is where they actually get
 * used on cards and section bands.
 */
class PaletteContrastTest extends TestCase
{
    /** WCAG AA for normal-size text. */
    private const AA = 4.5;

    /** Backgrounds any text in this design system can land on. */
    private const SURFACES = [
        'surface', 'surface-soft', 'surface-section', 'primary-light', 'accent-light',
    ];

    /** Tokens used for text, which must clear AA on every surface above. */
    private const TEXT = [
        'ink', 'ink-muted', 'primary', 'primary-hover', 'primary-dark',
        'accent', 'accent-dark', 'success', 'warning', 'danger', 'info',
    ];

    /**
     * Bright brand colours used as fills — badge backgrounds, icons, the
     * decorative fields on the launch page. Never body copy.
     */
    private const FILLS = [
        'accent-vivid', 'success-vivid', 'warning-vivid', 'danger-vivid', 'info-vivid',
    ];

    /** @return array<string, string> token name => hex value */
    private function tokens(): array
    {
        $css = file_get_contents(__DIR__.'/../../resources/css/app.css');
        preg_match_all('/--color-([a-z-]+):\s*(#[0-9A-Fa-f]{6})/', $css, $m, PREG_SET_ORDER);

        return array_column($m, 2, 1);
    }

    private function relativeLuminance(string $hex): float
    {
        $channels = sscanf($hex, '#%02x%02x%02x');

        $linear = array_map(function (int $c): float {
            $v = $c / 255;

            return $v <= 0.03928 ? $v / 12.92 : (($v + 0.055) / 1.055) ** 2.4;
        }, $channels);

        return 0.2126 * $linear[0] + 0.7152 * $linear[1] + 0.0722 * $linear[2];
    }

    private function contrast(string $a, string $b): float
    {
        $la = $this->relativeLuminance($a);
        $lb = $this->relativeLuminance($b);

        return (max($la, $lb) + 0.05) / (min($la, $lb) + 0.05);
    }

    public function test_text_colours_clear_aa_on_every_surface(): void
    {
        $tokens = $this->tokens();

        foreach (self::TEXT as $fg) {
            foreach (self::SURFACES as $bg) {
                $this->assertArrayHasKey($fg, $tokens, "missing token: --color-$fg");
                $this->assertArrayHasKey($bg, $tokens, "missing token: --color-$bg");

                $ratio = $this->contrast($tokens[$fg], $tokens[$bg]);

                $this->assertGreaterThanOrEqual(
                    self::AA,
                    $ratio,
                    sprintf('%s (%s) on %s (%s) is %.2f:1, below AA %.1f:1',
                        $fg, $tokens[$fg], $bg, $tokens[$bg], $ratio, self::AA)
                );
            }
        }
    }

    /**
     * A fill sits behind content, so measuring it against the page is the
     * wrong question — an amber that is "too light" against white is fine as
     * a badge background. What has to hold is that a label stays readable on
     * top of it. Every fill takes the same ink label, so the rule is one
     * thing to remember rather than a per-colour judgement.
     */
    public function test_labels_stay_readable_on_every_fill(): void
    {
        $tokens = $this->tokens();

        foreach (self::FILLS as $fill) {
            $this->assertArrayHasKey($fill, $tokens, "missing token: --color-$fill");

            $ratio = $this->contrast($tokens['ink'], $tokens[$fill]);

            $this->assertGreaterThanOrEqual(
                self::AA,
                $ratio,
                sprintf('ink (%s) on %s (%s) is %.2f:1 — this fill cannot carry a label',
                    $tokens['ink'], $fill, $tokens[$fill], $ratio)
            );
        }
    }

    /**
     * The brand green is too light for body text, so the token that reads as
     * the default ("accent") must be the safe one. If the two were swapped,
     * every `text-accent` in the codebase would quietly fail.
     */
    public function test_the_default_accent_is_the_text_safe_shade(): void
    {
        $tokens = $this->tokens();

        $this->assertGreaterThan(
            $this->contrast($tokens['accent-vivid'], $tokens['surface']),
            $this->contrast($tokens['accent'], $tokens['surface']),
            'accent must be darker than accent-vivid, or text-accent fails AA'
        );
    }
}
