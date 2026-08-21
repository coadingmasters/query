@php
    $input = 'mt-1.5 w-full rounded-xl border border-line-strong bg-surface px-3.5 py-2.5 text-sm text-ink transition focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none';
    $label = 'block text-sm font-semibold text-ink';
    $hint = 'mt-0.5 text-xs text-ink-muted';
@endphp

<x-admin.shell active="settings" title="Settings">

    <div class="animate-[result-pop_0.5s_cubic-bezier(0.16,1,0.3,1)_both]">
        <h2 class="font-heading text-2xl font-extrabold tracking-tight text-ink">Site settings</h2>
        <p class="mt-1 text-sm text-ink-muted">
            Anything left blank here falls back to what's already configured on the server — nothing here overwrites
            that, it only overrides it while a value is set.
        </p>
    </div>

    <x-admin.toast :message="session('status')"/>

    @if ($errors->any())
        <div class="mt-5 rounded-xl border border-danger/30 bg-danger-light px-4 py-3 text-sm text-danger">
            <p class="font-semibold">Please fix the following:</p>
            <ul class="mt-1 list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="mt-6 max-w-3xl space-y-8">
        @csrf
        @method('PUT')

        {{-- ── Brand ─────────────────────────────────────────────────── --}}
        <div class="rounded-2xl border border-line bg-surface p-6 shadow-sm">
            <h3 class="font-heading text-sm font-bold tracking-wider text-ink uppercase">Brand</h3>
            <p class="mt-1 text-sm text-ink-muted">Used in page titles, meta descriptions, the footer, and the contact form's recipient address.</p>

            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="brand_email" class="{{ $label }}">Contact email</label>
                    <input id="brand_email" name="brand_email" type="email" value="{{ old('brand_email', $settings->brand_email) }}" placeholder="hello@purrquery.com" class="{{ $input }}">
                </div>
                <div>
                    <label for="brand_tagline" class="{{ $label }}">Tagline</label>
                    <input id="brand_tagline" name="brand_tagline" type="text" value="{{ old('brand_tagline', $settings->brand_tagline) }}" placeholder="Smart tools and clear answers for cat owners" class="{{ $input }}">
                </div>
            </div>
            <div class="mt-4">
                <label for="brand_description" class="{{ $label }}">Site description</label>
                <textarea id="brand_description" name="brand_description" rows="2" class="{{ $input }}">{{ old('brand_description', $settings->brand_description) }}</textarea>
                <p class="{{ $hint }}">Used as the default meta description where a page doesn't set its own.</p>
            </div>
        </div>

        {{-- ── Global SEO ───────────────────────────────────────────── --}}
        <div class="rounded-2xl border border-line bg-surface p-6 shadow-sm">
            <h3 class="font-heading text-sm font-bold tracking-wider text-ink uppercase">Global SEO</h3>
            <p class="mt-1 text-sm text-ink-muted">Defaults used wherever a page doesn't set its own — the site name in every title tag, and the image/card shown when a page is shared.</p>

            <div class="mt-4">
                <label for="seo_site_name" class="{{ $label }}">Site name</label>
                <input id="seo_site_name" name="seo_site_name" type="text" value="{{ old('seo_site_name', $settings->seo_site_name) }}" placeholder="{{ config('app.name') }}" class="{{ $input }}">
            </div>

            <div class="mt-4" x-data="{ preview: {{ \Illuminate\Support\Js::from($settings->seo_og_image_url) }}, remove: false }">
                <label class="{{ $label }}">Default social share image (og:image)</label>
                <div class="mt-1.5 flex items-center gap-4">
                    <div class="flex size-20 shrink-0 items-center justify-center overflow-hidden rounded-xl border border-line-strong bg-surface-section">
                        <template x-if="preview && !remove">
                            <img :src="preview" class="size-full object-cover" alt="">
                        </template>
                        <template x-if="!preview || remove">
                            <svg class="size-7 text-ink-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-5-5L5 21"/></svg>
                        </template>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <button type="button" x-on:click="$refs.ogImageFile.click()" class="text-left text-sm font-semibold text-primary hover:text-primary-hover">Upload image</button>
                        <button type="button" x-show="preview && !remove" x-cloak x-on:click="remove = true; preview = null" class="text-left text-sm font-semibold text-danger hover:text-danger/80">Remove</button>
                    </div>
                </div>
                <input x-ref="ogImageFile" type="file" name="seo_og_image" accept="image/jpeg,image/png,image/webp" class="hidden"
                       x-on:change="if ($event.target.files[0]) { remove = false; preview = URL.createObjectURL($event.target.files[0]); }">
                <input type="hidden" name="remove_seo_og_image" x-bind:value="remove ? 1 : 0">
                <p class="{{ $hint }}">1200×630 works best. Falls back to the site's committed default when unset.</p>
            </div>

            <div class="mt-4">
                <p class="{{ $label }}">Twitter card type</p>
                <div class="mt-1.5 flex gap-2">
                    @foreach (['summary_large_image' => 'Large image', 'summary' => 'Summary (square)'] as $value => $cardLabel)
                        <label class="flex flex-1 cursor-pointer items-center justify-center gap-2 rounded-xl border border-line-strong px-3 py-2.5 text-sm font-semibold text-ink has-checked:border-primary has-checked:bg-primary-light/40">
                            <input type="radio" name="seo_twitter_card" value="{{ $value }}" @checked(old('seo_twitter_card', $settings->seo_twitter_card ?: 'summary_large_image') === $value) class="size-4 text-primary focus:ring-primary/30">
                            {{ $cardLabel }}
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ── Author / founder ──────────────────────────────────────── --}}
        <div class="rounded-2xl border border-line bg-surface p-6 shadow-sm">
            <h3 class="font-heading text-sm font-bold tracking-wider text-ink uppercase">Author</h3>
            <p class="mt-1 text-sm text-ink-muted">
                Who bylines health content and appears in structured data. Leave this section blank rather than
                filling it with a placeholder — an anonymous site is a more honest signal than an invented author.
            </p>

            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="author_name" class="{{ $label }}">Name</label>
                    <input id="author_name" name="author_name" type="text" value="{{ old('author_name', $settings->author_name) }}" class="{{ $input }}">
                </div>
                <div>
                    <label for="author_role" class="{{ $label }}">Role</label>
                    <input id="author_role" name="author_role" type="text" value="{{ old('author_role', $settings->author_role) }}" placeholder="Founder, developer and editor" class="{{ $input }}">
                </div>
            </div>
            <div class="mt-4">
                <label for="author_tagline" class="{{ $label }}">Byline tagline</label>
                <input id="author_tagline" name="author_tagline" type="text" value="{{ old('author_tagline', $settings->author_tagline) }}" class="{{ $input }}">
                <p class="{{ $hint }}">One line, shown under the name on tool and article bylines.</p>
            </div>
            <div class="mt-4">
                <label for="author_bio" class="{{ $label }}">Bio</label>
                <textarea id="author_bio" name="author_bio" rows="5" class="{{ $input }}">{{ old('author_bio', $settings->author_bio) }}</textarea>
                <p class="{{ $hint }}">One paragraph per blank line — each becomes its own paragraph on the about page.</p>
            </div>

            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="author_linkedin_url" class="{{ $label }}">LinkedIn</label>
                    <input id="author_linkedin_url" name="author_linkedin_url" type="url" value="{{ old('author_linkedin_url', $settings->author_linkedin_url) }}" placeholder="https://" class="{{ $input }}">
                </div>
                <div>
                    <label for="author_twitter_url" class="{{ $label }}">X / Twitter</label>
                    <input id="author_twitter_url" name="author_twitter_url" type="url" value="{{ old('author_twitter_url', $settings->author_twitter_url) }}" placeholder="https://" class="{{ $input }}">
                </div>
                <div>
                    <label for="author_github_url" class="{{ $label }}">GitHub</label>
                    <input id="author_github_url" name="author_github_url" type="url" value="{{ old('author_github_url', $settings->author_github_url) }}" placeholder="https://" class="{{ $input }}">
                </div>
                <div>
                    <label for="author_website_url" class="{{ $label }}">Personal site</label>
                    <input id="author_website_url" name="author_website_url" type="url" value="{{ old('author_website_url', $settings->author_website_url) }}" placeholder="https://" class="{{ $input }}">
                </div>
            </div>
            <p class="{{ $hint }} mt-2">
                Photo isn't set here — it's a real photograph committed with the site's other images, not something to
                upload casually. Ask a developer to add it if you have one ready.
            </p>
        </div>

        {{-- ── Medical reviewer ──────────────────────────────────────── --}}
        <div class="rounded-2xl border border-line bg-surface p-6 shadow-sm">
            <h3 class="font-heading text-sm font-bold tracking-wider text-ink uppercase">Medical reviewer</h3>
            <p class="mt-1 text-sm text-ink-muted">
                The piece that changes how health content is read. Only fill this in once a licensed vet has actually
                reviewed the guides and agreed to be named — it can't be a stand-in for that.
            </p>

            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="reviewer_name" class="{{ $label }}">Name</label>
                    <input id="reviewer_name" name="reviewer_name" type="text" value="{{ old('reviewer_name', $settings->reviewer_name) }}" class="{{ $input }}">
                </div>
                <div>
                    <label for="reviewer_credentials" class="{{ $label }}">Credentials</label>
                    <input id="reviewer_credentials" name="reviewer_credentials" type="text" value="{{ old('reviewer_credentials', $settings->reviewer_credentials) }}" placeholder="DVM" class="{{ $input }}">
                </div>
                <div>
                    <label for="reviewer_reviewed_on" class="{{ $label }}">Reviewed on</label>
                    <input id="reviewer_reviewed_on" name="reviewer_reviewed_on" type="date" value="{{ old('reviewer_reviewed_on', $settings->reviewer_reviewed_on?->format('Y-m-d')) }}" class="{{ $input }}">
                </div>
                <div>
                    <label for="reviewer_profile_url" class="{{ $label }}">Profile link</label>
                    <input id="reviewer_profile_url" name="reviewer_profile_url" type="url" value="{{ old('reviewer_profile_url', $settings->reviewer_profile_url) }}" placeholder="https://" class="{{ $input }}">
                </div>
            </div>
        </div>

        {{-- ── Schema / Organization ────────────────────────────────── --}}
        <div class="rounded-2xl border border-line bg-surface p-6 shadow-sm">
            <h3 class="font-heading text-sm font-bold tracking-wider text-ink uppercase">Schema (Organization)</h3>
            <p class="mt-1 text-sm text-ink-muted">
                The structured-data block search engines read to identify the business itself — separate from the
                founder above. Update it here once; it applies to the JSON-LD on every page.
            </p>

            <div class="mt-4" x-data="{ preview: {{ \Illuminate\Support\Js::from($settings->schema_org_logo_url) }}, remove: false }">
                <label class="{{ $label }}">Organization logo</label>
                <div class="mt-1.5 flex items-center gap-4">
                    <div class="flex size-20 shrink-0 items-center justify-center overflow-hidden rounded-xl border border-line-strong bg-surface-section">
                        <template x-if="preview && !remove">
                            <img :src="preview" class="size-full object-cover" alt="">
                        </template>
                        <template x-if="!preview || remove">
                            <svg class="size-7 text-ink-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-5-5L5 21"/></svg>
                        </template>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <button type="button" x-on:click="$refs.orgLogoFile.click()" class="text-left text-sm font-semibold text-primary hover:text-primary-hover">Upload logo</button>
                        <button type="button" x-show="preview && !remove" x-cloak x-on:click="remove = true; preview = null" class="text-left text-sm font-semibold text-danger hover:text-danger/80">Remove</button>
                    </div>
                </div>
                <input x-ref="orgLogoFile" type="file" name="schema_org_logo" accept="image/jpeg,image/png,image/webp" class="hidden"
                       x-on:change="if ($event.target.files[0]) { remove = false; preview = URL.createObjectURL($event.target.files[0]); }">
                <input type="hidden" name="remove_schema_org_logo" x-bind:value="remove ? 1 : 0">
                <p class="{{ $hint }}">Falls back to the default social share image above when unset.</p>
            </div>

            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="schema_facebook_url" class="{{ $label }}">Facebook</label>
                    <input id="schema_facebook_url" name="schema_facebook_url" type="url" value="{{ old('schema_facebook_url', $settings->schema_facebook_url) }}" placeholder="https://" class="{{ $input }}">
                </div>
                <div>
                    <label for="schema_instagram_url" class="{{ $label }}">Instagram</label>
                    <input id="schema_instagram_url" name="schema_instagram_url" type="url" value="{{ old('schema_instagram_url', $settings->schema_instagram_url) }}" placeholder="https://" class="{{ $input }}">
                </div>
                <div>
                    <label for="schema_twitter_url" class="{{ $label }}">X / Twitter</label>
                    <input id="schema_twitter_url" name="schema_twitter_url" type="url" value="{{ old('schema_twitter_url', $settings->schema_twitter_url) }}" placeholder="https://" class="{{ $input }}">
                </div>
                <div>
                    <label for="schema_youtube_url" class="{{ $label }}">YouTube</label>
                    <input id="schema_youtube_url" name="schema_youtube_url" type="url" value="{{ old('schema_youtube_url', $settings->schema_youtube_url) }}" placeholder="https://" class="{{ $input }}">
                </div>
            </div>
            <p class="{{ $hint }} mt-2">Empty ones are left out — an unverifiable social link is worse than no link.</p>
        </div>

        {{-- ── Legal ─────────────────────────────────────────────────── --}}
        <div class="rounded-2xl border border-line bg-surface p-6 shadow-sm">
            <h3 class="font-heading text-sm font-bold tracking-wider text-ink uppercase">Legal</h3>
            <div class="mt-4">
                <label for="legal_jurisdiction" class="{{ $label }}">Jurisdiction</label>
                <input id="legal_jurisdiction" name="legal_jurisdiction" type="text" value="{{ old('legal_jurisdiction', $settings->legal_jurisdiction) }}" placeholder="e.g. the State of Delaware, United States" class="{{ $input }}">
                <p class="{{ $hint }}">Named in the Terms page's governing-law clause.</p>
            </div>
        </div>

        {{-- ── Sitemap ───────────────────────────────────────────────── --}}
        <div class="rounded-2xl border border-line bg-surface p-6 shadow-sm">
            <h3 class="font-heading text-sm font-bold tracking-wider text-ink uppercase">Sitemap</h3>
            <p class="mt-1 text-sm text-ink-muted">
                Built automatically from the site's real pages and published posts — cached for a day, so
                "regenerate" just clears that cache rather than editing anything by hand.
            </p>

            <p class="mt-3 text-sm text-ink">
                Last generated:
                <span class="font-semibold">{{ $sitemapLastGenerated?->diffForHumans() ?? 'not yet — it builds on first request' }}</span>
            </p>

            <div class="mt-4">
                <label for="sitemap_excluded_paths" class="{{ $label }}">Exclude paths</label>
                <textarea id="sitemap_excluded_paths" name="sitemap_excluded_paths" rows="3" placeholder="/faq&#10;/blog/some-old-post" class="{{ $input }}">{{ old('sitemap_excluded_paths', $settings->sitemap_excluded_paths) }}</textarea>
                <p class="{{ $hint }}">One path per line. Applies on the next regenerate.</p>
            </div>
        </div>

        {{-- ── Robots.txt ────────────────────────────────────────────── --}}
        <div class="rounded-2xl border border-line bg-surface p-6 shadow-sm">
            <h3 class="font-heading text-sm font-bold tracking-wider text-ink uppercase">robots.txt</h3>
            <p class="mt-1 text-sm text-ink-muted">Served exactly as written here. Leave blank to use the default (allow everything, point at the sitemap).</p>
            <div class="mt-4">
                <textarea id="robots_txt" name="robots_txt" rows="6" placeholder="User-agent: *&#10;Allow: /&#10;&#10;Sitemap: {{ rtrim(config('app.url'), '/') }}/sitemap.xml" class="{{ $input }} font-mono">{{ old('robots_txt', $settings->robots_txt) }}</textarea>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="rounded-full bg-primary-vivid px-6 py-2.5 text-sm font-bold text-ink shadow-sm transition hover:brightness-95">
                Save settings
            </button>
        </div>
    </form>

    <form method="POST" action="{{ route('admin.settings.regenerate-sitemap') }}" class="mt-4 flex justify-end">
        @csrf
        <button type="submit" class="flex items-center gap-2 rounded-full border border-line-strong bg-surface px-5 py-2.5 text-sm font-semibold text-ink-muted transition hover:border-primary hover:text-primary">
            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12a9 9 0 1 1-2.6-6.4M21 4v5h-5"/></svg>
            Regenerate sitemap now
        </button>
    </form>

</x-admin.shell>
