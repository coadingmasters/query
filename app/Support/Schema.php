<?php

namespace App\Support;

/**
 * Builds the structured data every page shares.
 *
 * Before this existed, each controller declared its own Organization node and
 * pointed `isPartOf` at `/#website`, a node that was only ever defined on the
 * home page. Those references dangled: a crawler reading /about on its own had
 * nothing to resolve them against. Each page's graph is now self-contained,
 * and the shared nodes are identical everywhere, which is what lets Google
 * merge them into one entity rather than treating them as several.
 */
class Schema
{
    private static function url(string $path = ''): string
    {
        return rtrim(config('app.url'), '/').$path;
    }

    /** The publisher. Identical on every page, so the @id resolves to one thing. */
    public static function organization(): array
    {
        return [
            '@type' => 'Organization',
            '@id' => self::url('/#organization'),
            'name' => config('app.name'),
            'url' => self::url('/'),
            'email' => config('brand.email'),
            'description' => config('brand.description'),
            'logo' => [
                '@type' => 'ImageObject',
                '@id' => self::url('/#logo'),
                'url' => self::url('/og-image.png'),
                'width' => 1200,
                'height' => 630,
            ],
        ];
    }

    /**
     * The site itself.
     *
     * Deliberately without a SearchAction: that markup claims a URL Google can
     * send a query to, and there is no such endpoint here. The search box on
     * the home page filters what is already on screen.
     */
    public static function website(): array
    {
        return [
            '@type' => 'WebSite',
            '@id' => self::url('/#website'),
            'name' => config('app.name'),
            'url' => self::url('/'),
            'description' => config('brand.description'),
            'inLanguage' => str_replace('_', '-', app()->getLocale()),
            'publisher' => ['@id' => self::url('/#organization')],
        ];
    }

    /**
     * @param  array<string, string|null>  $trail  label => path, null path for the current page
     */
    public static function breadcrumbs(string $pagePath, array $trail): array
    {
        $items = [];
        $position = 1;

        foreach ($trail as $label => $path) {
            $item = ['@type' => 'ListItem', 'position' => $position++, 'name' => $label];

            // The last crumb is the current page and carries no link, which is
            // what Google's guidance asks for.
            if ($path !== null) {
                $item['item'] = self::url($path);
            }

            $items[] = $item;
        }

        return [
            '@type' => 'BreadcrumbList',
            '@id' => self::url($pagePath.'#breadcrumb'),
            'itemListElement' => $items,
        ];
    }

    /** Wraps a page-level node with the shared publisher and site nodes. */
    public static function graph(array ...$nodes): array
    {
        return [
            '@context' => 'https://schema.org',
            '@graph' => [self::organization(), self::website(), ...array_merge(...$nodes)],
        ];
    }

    /**
     * A named collection: the tools, the food guides. It tells Google these
     * cards are one set rather than unrelated links, which is how a listing
     * page gets understood as a listing page.
     *
     * @param  array<int, array{name: string, description?: string}>  $items
     */
    public static function itemList(string $id, string $name, array $items): array
    {
        return [
            '@type' => 'ItemList',
            '@id' => self::url($id),
            'name' => $name,
            'numberOfItems' => count($items),
            'itemListElement' => collect($items)->values()->map(fn (array $item, int $i): array => array_filter([
                '@type' => 'ListItem',
                'position' => $i + 1,
                'name' => $item['name'],
                'description' => $item['description'] ?? null,
            ]))->all(),
        ];
    }

    /** @param array<int, array{q: string, a: string}> $pairs */
    public static function faq(string $id, array $pairs): array
    {
        return [
            '@type' => 'FAQPage',
            '@id' => self::url($id),
            'mainEntity' => collect($pairs)->map(fn (array $pair): array => [
                '@type' => 'Question',
                'name' => $pair['q'],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $pair['a']],
            ])->all(),
        ];
    }
}
