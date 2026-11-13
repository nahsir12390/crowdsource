<x-layouts.app title="{{ $issue->reference }} - CivicTrack">
    <section class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="grid gap-6 lg:grid-cols-[1fr_320px]">
            <article class="rounded-lg border border-[#d9ded4] bg-white p-6 shadow-sm">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold text-[#0f6b5b]">{{ $issue->reference }}</p>
                        <h1 class="mt-1 text-3xl font-bold">{{ $issue->title }}</h1>
                    </div>
                    <x-status-badge :status="$issue->status" />
                </div>
                @if($issue->photo_path)
                    <img src="{{ asset('storage/'.$issue->photo_path) }}" alt="{{ $issue->title }}" class="mt-6 h-80 w-full rounded-lg object-cover">
                @endif
                <p class="mt-6 whitespace-pre-line leading-7 text-[#415047]">{{ $issue->description }}</p>
                @if($issue->admin_note)
                    <div class="mt-6 rounded-lg border border-[#cbd4ca] bg-[#f9faf7] p-4">
                        <p class="text-sm font-bold">Official update</p>
                        <p class="mt-1 text-sm leading-6 text-[#526057]">{{ $issue->admin_note }}</p>
                    </div>
                @endif
            </article>
            <aside class="rounded-lg border border-[#d9ded4] bg-white p-5 shadow-sm">
                <h2 class="font-bold">Report details</h2>
                <dl class="mt-4 space-y-4 text-sm">
                    <div><dt class="font-semibold text-[#647067]">Category</dt><dd>{{ $issue->categoryLabel() }}</dd></div>
                    <div><dt class="font-semibold text-[#647067]">Priority</dt><dd>{{ $issue->priorityLabel() }}</dd></div>
                    <div><dt class="font-semibold text-[#647067]">Location</dt><dd>{{ $issue->location }}</dd></div>
                    <div><dt class="font-semibold text-[#647067]">Reported by</dt><dd>{{ $issue->reporter_name }}</dd></div>
                    <div><dt class="font-semibold text-[#647067]">Submitted</dt><dd>{{ $issue->created_at->format('M j, Y g:i A') }}</dd></div>
                    @if($issue->latitude !== null && $issue->longitude !== null)
                        <div><dt class="font-semibold text-[#647067]">Coordinates</dt><dd>{{ $issue->latitude }}, {{ $issue->longitude }}</dd></div>
                    @endif
                </dl>
            </aside>
        </div>
    </section>
</x-layouts.app>
