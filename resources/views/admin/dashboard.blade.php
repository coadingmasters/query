<!DOCTYPE html>
<html lang="{{ config('brand.lang') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard — {{ config('app.name') }}</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" href="/favicon.ico" sizes="16x16 32x32 48x48">
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-surface-soft">

    <header class="flex items-center justify-between border-b border-line bg-surface px-6 py-4">
        <span class="font-heading text-lg font-bold text-ink">{{ config('app.name') }} Admin</span>

        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="text-sm font-medium text-ink-muted hover:text-ink">
                Log out
            </button>
        </form>
    </header>

    <main class="container-page py-10">
        <h1 class="font-heading text-2xl font-bold text-ink">
            Welcome, {{ auth()->user()->name }}
        </h1>
        <p class="mt-1 text-sm text-ink-muted">
            This is a placeholder dashboard. Real tools for managing the site
            will go here.
        </p>

        <div class="mt-8 grid gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-line bg-surface p-5">
                <p class="text-xs font-semibold tracking-wide text-ink-muted uppercase">Blog posts</p>
                <p class="mt-2 text-2xl font-bold text-ink">{{ count(config('blog')) }}</p>
            </div>
            <div class="rounded-2xl border border-line bg-surface p-5">
                <p class="text-xs font-semibold tracking-wide text-ink-muted uppercase">Contact messages</p>
                <p class="mt-2 text-2xl font-bold text-ink">{{ \App\Models\ContactMessage::count() }}</p>
            </div>
            <div class="rounded-2xl border border-line bg-surface p-5">
                <p class="text-xs font-semibold tracking-wide text-ink-muted uppercase">Subscribers</p>
                <p class="mt-2 text-2xl font-bold text-ink">{{ \App\Models\Subscriber::count() }}</p>
            </div>
        </div>
    </main>

</body>
</html>
