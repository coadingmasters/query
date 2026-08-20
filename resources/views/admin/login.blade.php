<!DOCTYPE html>
<html lang="{{ config('brand.lang') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login — {{ config('app.name') }}</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" href="/favicon.ico" sizes="16x16 32x32 48x48">
    @fonts
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-surface-soft">

    <div class="grid min-h-screen lg:grid-cols-2">

        {{-- Left: full-bleed photo panel, hidden below lg since there is no
             room to do it justice on a phone screen. --}}
        <div class="relative hidden overflow-hidden bg-primary-dark lg:block">
            <x-img name="purrquery-admin-login-cat-owner"
                   alt="Cat owner smiling while holding her cat at home"
                   sizes="50vw"
                   priority
                   class="absolute inset-0"/>

            <div class="absolute inset-0 bg-gradient-to-t from-primary-dark via-primary-dark/40 to-primary-dark/10"></div>

            <div class="paw absolute top-16 left-10 size-10 text-white/60" style="animation-delay:-4s">
                <svg viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="15" r="5"/><circle cx="5" cy="7" r="2.6"/><circle cx="12" cy="4.5" r="2.6"/><circle cx="19" cy="7" r="2.6"/></svg>
            </div>
            <div class="paw absolute top-1/3 right-14 size-7 text-white/40" style="animation-delay:-12s">
                <svg viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="15" r="5"/><circle cx="5" cy="7" r="2.6"/><circle cx="12" cy="4.5" r="2.6"/><circle cx="19" cy="7" r="2.6"/></svg>
            </div>

            <div class="relative flex h-full flex-col justify-between p-10 text-white">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5">
                    <span class="flex size-10 items-center justify-center overflow-hidden rounded-full bg-white/95">
                        <x-img name="purrquerylogo" alt="" sizes="40px" fit="contain"/>
                    </span>
                    <span class="font-heading text-lg font-extrabold tracking-tight">{{ config('app.name') }}</span>
                </a>

                <div class="max-w-md animate-[result-pop_0.6s_cubic-bezier(0.16,1,0.3,1)_both]">
                    <p class="text-xs font-bold tracking-[0.14em] text-white/70 uppercase">Admin</p>
                    <h1 class="mt-3 font-heading text-3xl leading-tight font-extrabold tracking-tight text-balance text-white">
                        Everything for cat owners, managed from one place.
                    </h1>
                    <p class="mt-4 text-sm leading-relaxed text-white/80">
                        Sign in to manage guides, tools and the messages
                        readers send in.
                    </p>
                </div>
            </div>
        </div>

        {{-- Right: the form. --}}
        <div class="flex items-center justify-center px-6 py-16">
            <div class="w-full max-w-sm animate-[result-pop_0.5s_cubic-bezier(0.16,1,0.3,1)_both]">

                <a href="{{ route('home') }}" class="mb-8 inline-flex items-center gap-2.5 lg:hidden">
                    <span class="flex size-10 items-center justify-center overflow-hidden rounded-full bg-surface-soft">
                        <x-img name="purrquerylogo" alt="" sizes="40px" fit="contain"/>
                    </span>
                    <span class="font-heading text-lg font-extrabold tracking-tight text-ink">{{ config('app.name') }}</span>
                </a>

                <h2 class="font-heading text-2xl font-extrabold tracking-tight text-ink">Welcome back</h2>
                <p class="mt-1.5 text-sm text-ink-muted">Sign in with your admin account to continue.</p>

                @if ($errors->any())
                    <p class="mt-6 flex items-center gap-2 rounded-lg bg-danger-light px-3.5 py-2.5 text-sm text-danger">
                        <svg class="size-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16h.01"/>
                        </svg>
                        {{ $errors->first() }}
                    </p>
                @endif

                <form method="POST" action="{{ route('admin.login.attempt') }}" class="mt-7 space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-semibold text-ink">Email</label>
                        <input type="email" name="email" id="email" required autofocus
                               value="{{ old('email') }}" placeholder="you@purrquery.com"
                               class="mt-1.5 w-full rounded-xl border border-line-strong bg-surface px-3.5 py-2.5 text-sm text-ink transition placeholder:text-ink-muted/60 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-ink">Password</label>
                        <input type="password" name="password" id="password" required placeholder="••••••••••"
                               class="mt-1.5 w-full rounded-xl border border-line-strong bg-surface px-3.5 py-2.5 text-sm text-ink transition placeholder:text-ink-muted/60 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    </div>

                    <label class="flex items-center gap-2 text-sm text-ink-muted">
                        <input type="checkbox" name="remember" class="size-4 rounded border-line-strong text-primary focus:ring-primary/30">
                        Keep me signed in
                    </label>

                    <button type="submit"
                            class="group flex w-full items-center justify-center gap-2 rounded-xl bg-primary-vivid px-4 py-2.75 text-sm font-bold text-ink shadow-sm transition hover:brightness-95 hover:shadow-md active:scale-[0.99]">
                        Sign in
                        <svg class="size-4 transition group-hover:translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                            <path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </form>

                <p class="mt-8 text-center text-xs text-ink-muted">
                    <a href="{{ route('home') }}" class="hover:text-ink">&larr; Back to {{ config('app.name') }}</a>
                </p>
            </div>
        </div>

    </div>

</body>
</html>
