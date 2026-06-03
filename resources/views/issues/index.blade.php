<x-layouts.app title="Reports - CivicTrack">
    <section class="bg-[#123d36] text-white">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 py-12 sm:px-6 lg:grid-cols-[1.1fr_.9fr] lg:px-8 lg:py-16">
            <div>
                <p class="text-sm font-semibold text-[#9bd4c8]">Community-powered infrastructure reporting</p>
                <h1 class="mt-3 max-w-3xl text-4xl font-bold leading-tight sm:text-5xl">CivicTrack</h1>
                <p class="mt-4 max-w-2xl text-base leading-7 text-[#d9eee9]">
                    Report damaged roads, flooding, power faults, water issues, and public safety concerns so responsible teams can review and resolve them faster.
                </p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('issues.create') }}" class="rounded-md bg-[#f4c95d] px-4 py-2.5 text-sm font-bold text-[#253127] hover:bg-[#f1bd35]">Submit a report</a>
                    <a href="#reports" class="rounded-md border border-[#6da89c] px-4 py-2.5 text-sm font-semibold text-white hover:bg-white/10">View reports</a>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                @foreach($stats as $label => $value)
                    <div class="rounded-lg border border-white/15 bg-white/10 p-5">
                        <p class="text-3xl font-bold">{{ $value }}</p>
                        <p class="mt-1 text-sm capitalize text-[#d9eee9]">{{ str_replace('_', ' ', $label) }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="reports" class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <form method="GET" class="mb-6 grid gap-3 rounded-lg border border-[#d9ded4] bg-white p-4 md:grid-cols-[1fr_1fr_auto]">
            <select name="category" class="rounded-md border border-[#cbd4ca] px-3 py-2 text-sm">
                <option value="">All categories</option>
                @foreach($categories as $value => $label)
                    <option value="{{ $value }}" @selected(request('category') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <select name="status" class="rounded-md border border-[#cbd4ca] px-3 py-2 text-sm">
                <option value="">All statuses</option>
                @foreach($statuses as $value => $label)
                    @if($value !== 'rejected')
                        <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                    @endif
                @endforeach
            </select>
            <button class="rounded-md bg-[#253127] px-4 py-2 text-sm font-semibold text-white hover:bg-[#151c17]">Filter</button>
        </form>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @forelse($reports as $report)
                <a href="{{ route('issues.show', $report) }}" class="group rounded-lg border border-[#d9ded4] bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-xs font-semibold uppercase text-[#0f6b5b]">{{ $report->reference }}</p>
                            <h2 class="mt-1 text-lg font-bold text-[#1f2522] group-hover:text-[#0f6b5b]">{{ $report->title }}</h2>
                        </div>
                        <x-status-badge :status="$report->status" />
                    </div>
                    <p class="mt-3 line-clamp-3 text-sm leading-6 text-[#5c685f]">{{ $report->description }}</p>
                    <div class="mt-4 flex flex-wrap gap-2 text-xs font-medium text-[#526057]">
                        <span class="rounded-md bg-[#eef2ea] px-2 py-1">{{ $report->categoryLabel() }}</span>
                        <span class="rounded-md bg-[#eef2ea] px-2 py-1">{{ $report->location }}</span>
                    </div>
                </a>
            @empty
                <div class="rounded-lg border border-[#d9ded4] bg-white p-8 text-center md:col-span-2 xl:col-span-3">
                    <p class="font-semibold">No reports found.</p>
                    <p class="mt-1 text-sm text-[#647067]">Be the first to submit an infrastructure issue.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-6">{{ $reports->links() }}</div>
    </section>
</x-layouts.app>
