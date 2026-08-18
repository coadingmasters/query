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
        ] + (config('author.founder.name') ? [
            'founder' => ['@id' => self::url('/#founder')],
        ] : []);
    }

    /**
     * The site itself.
     *
     * Deliberately without a SearchAction: that markup claims a URL Google can
     * send a query to, and there is no such endpoint here. The search box on
     * the home page filters what is already on screen.
     */
    /**
     * The person behind the site.
     *
     * Returns null when no name is configured, and every caller drops the node
     * rather than substituting a placeholder. A Person node naming nobody real
     * is worse than none: it is an assertion to Google about authorship that
     * cannot be checked, which is what its guidance for health topics exists
     * to catch.
     */
    public static function person(): ?array
    {
        $name = config('author.founder.name');

        if (! $name) {
            return null;
        }

        $node = [
            '@type' => 'Person',
            '@id' => self::url('/#founder'),
            'name' => $name,
            'jobTitle' => config('author.founder.role'),
            'description' => config('author.founder.tagline'),
            'url' => self::url('/author'),
            'email' => config('brand.email'),
            'worksFor' => ['@id' => self::url('/#organization')],
        ];

        // sameAs is the part that makes the name checkable, so it is only
        // emitted when there is something to check.
        if ($profiles = config('author.founder.profiles')) {
            $node['sameAs'] = $profiles;
        }

        if ($image = config('author.founder.image')) {
            if ($resolved = \App\Support\Images::get($image)) {
                $node['image'] = self::url($resolved['src']);
            }
        }

        return $node;
    }

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
    public static function graph(?array ...$nodes): array
    {
        // Nullable, so a builder that has nothing to say can return null and
        // the caller does not have to guard every one of them. person() is the
        // case that matters: no configured author, no node.
        $nodes = array_filter($nodes);

        return [
            '@context' => 'https://schema.org',
            '@graph' => array_values(array_filter([
                self::organization(),
                self::website(),
                // Every page's Organization points at #founder, so the Person
                // has to be defined everywhere too. Defining it only on /about
                // left that reference dangling on the other five.
                self::person(),
                ...array_merge(...$nodes),
            ])),
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
