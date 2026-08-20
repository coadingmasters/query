<!DOCTYPE html>
<html lang="{{ config('brand.lang') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login — {{ config('app.name') }}</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" href="/favicon.ico" sizes="16x16 32x32 48x48">
    @vite(['resources/css/app.css'])
</head>
<body class="flex min-h-screen items-center justify-center bg-surface-soft px-4">

    <div class="w-full max-w-sm rounded-2xl border border-line bg-surface p-8 shadow-sm">
        <h1 class="font-heading text-xl font-bold text-ink">Admin Login</h1>
        <p class="mt-1 text-sm text-ink-muted">{{ config('app.name') }} admin area.</p>

        @if ($errors->any())
            <p class="mt-4 rounded-lg bg-red-50 px-3 py-2 text-sm text-red-700">
                {{ $errors->first() }}
            </p>
        @endif

        <form method="POST" action="{{ route('admin.login.attempt') }}" class="mt-6 space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-ink">Email</label>
                <input type="email" name="email" id="email" required autofocus
                       value="{{ old('email') }}"
                       class="mt-1 w-full rounded-lg border border-line px-3 py-2 text-sm text-ink focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-ink">Password</label>
                <input type="password" name="password" id="password" required
                       class="mt-1 w-full rounded-lg border border-line px-3 py-2 text-sm text-ink focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
            </div>

            <label class="flex items-center gap-2 text-sm text-ink-muted">
                <input type="checkbox" name="remember" class="rounded border-line">
                Keep me signed in
            </label>

            <button type="submit"
                    class="w-full rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                Sign in
            </button>
        </form>
    </div>

</body>
</html>
