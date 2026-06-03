<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name', 'CivicTrack') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f6f7f2] font-sans text-[#1f2522] antialiased">
    <div class="border-b border-[#d9ded4] bg-white/90">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
            <a href="{{ route('issues.index') }}" class="flex items-center gap-3">
                <span class="grid h-10 w-10 place-items-center rounded-lg bg-[#0f6b5b] text-sm font-bold text-white">CT</span>
                <span>
                    <span class="block text-base font-bold">CivicTrack</span>
                    <span class="block text-xs text-[#647067]">Infrastructure issue reporting</span>
                </span>
            </a>
            <nav class="flex items-center gap-2 text-sm font-medium">
                <a href="{{ route('issues.index') }}" class="rounded-md px-3 py-2 text-[#415047] hover:bg-[#eef2ea]">Reports</a>
                <a href="{{ route('issues.create') }}" class="rounded-md bg-[#0f6b5b] px-3 py-2 text-white hover:bg-[#0b584b]">Report issue</a>
                @if(session('is_admin'))
                    <a href="{{ route('admin.issues.index') }}" class="rounded-md px-3 py-2 text-[#415047] hover:bg-[#eef2ea]">Admin</a>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button class="rounded-md px-3 py-2 text-[#8b2f2f] hover:bg-[#f3e7e7]">Logout</button>
                    </form>
                @else
                    <a href="{{ route('admin.login') }}" class="hidden rounded-md px-3 py-2 text-[#415047] hover:bg-[#eef2ea] sm:inline-block">Admin</a>
                @endif
            </nav>
        </div>
    </div>

    <main>
        @if(session('success'))
            <div class="mx-auto mt-6 max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="rounded-lg border border-[#b9ddc7] bg-[#effaf3] px-4 py-3 text-sm font-medium text-[#23583a]">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        {{ $slot }}
    </main>
</body>
</html>
