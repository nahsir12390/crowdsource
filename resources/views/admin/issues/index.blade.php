<x-layouts.app title="Admin Reports - CivicTrack">
    <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-sm font-semibold text-[#0f6b5b]">Admin dashboard</p>
                <h1 class="mt-1 text-3xl font-bold">Report management</h1>
            </div>
            <a href="{{ route('issues.create') }}" class="rounded-md bg-[#0f6b5b] px-4 py-2 text-sm font-bold text-white">Add report</a>
        </div>

        <div class="mt-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-5">
            @foreach($stats as $label => $value)
                <div class="rounded-lg border border-[#d9ded4] bg-white p-4">
                    <p class="text-2xl font-bold">{{ $value }}</p>
                    <p class="mt-1 text-xs font-semibold uppercase text-[#647067]">{{ str_replace('_', ' ', $label) }}</p>
                </div>
            @endforeach
        </div>

        <form method="GET" class="mt-6 grid gap-3 rounded-lg border border-[#d9ded4] bg-white p-4 md:grid-cols-[1fr_1fr_auto]">
            <select name="category" class="rounded-md border border-[#cbd4ca] px-3 py-2 text-sm">
                <option value="">All categories</option>
                @foreach($categories as $value => $label)
                    <option value="{{ $value }}" @selected(request('category') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <select name="status" class="rounded-md border border-[#cbd4ca] px-3 py-2 text-sm">
                <option value="">All statuses</option>
                @foreach($statuses as $value => $label)
                    <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <button class="rounded-md bg-[#253127] px-4 py-2 text-sm font-semibold text-white">Filter</button>
        </form>

        <div class="mt-6 overflow-hidden rounded-lg border border-[#d9ded4] bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[#e2e6dd] text-sm">
                    <thead class="bg-[#f1f4ee] text-left text-xs font-bold uppercase text-[#526057]">
                        <tr>
                            <th class="px-4 py-3">Reference</th>
                            <th class="px-4 py-3">Issue</th>
                            <th class="px-4 py-3">Category</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Priority</th>
                            <th class="px-4 py-3">Submitted</th>
                            <th class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#edf0e9]">
                        @forelse($reports as $report)
                            <tr class="hover:bg-[#fbfcf9]">
                                <td class="px-4 py-3 font-semibold text-[#0f6b5b]">
                                    <a href="{{ route('admin.issues.show', $report) }}">{{ $report->reference }}</a>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="font-semibold">{{ $report->title }}</p>
                                    <p class="mt-1 max-w-md truncate text-xs text-[#647067]">{{ $report->location }}</p>
                                </td>
                                <td class="px-4 py-3">{{ $report->categoryLabel() }}</td>
                                <td class="px-4 py-3"><x-status-badge :status="$report->status" /></td>
                                <td class="px-4 py-3">{{ $report->priorityLabel() }}</td>
                                <td class="px-4 py-3 text-[#647067]">{{ $report->created_at->format('M j, Y') }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex min-w-56 flex-wrap items-center gap-2">
                                        <a href="{{ route('admin.issues.show', $report) }}" class="rounded-md border border-[#cbd4ca] px-3 py-1.5 text-xs font-semibold text-[#253127] hover:bg-[#eef2ea]">
                                            Review
                                        </a>

                                        @if($report->status === 'pending')
                                            <form action="{{ route('admin.issues.update', $report) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="verified">
                                                <input type="hidden" name="priority" value="{{ $report->priority }}">
                                                <input type="hidden" name="admin_note" value="{{ $report->admin_note }}">
                                                <button class="rounded-md bg-[#0f6b5b] px-3 py-1.5 text-xs font-bold text-white hover:bg-[#0b584b]">
                                                    Approve
                                                </button>
                                            </form>
                                        @endif

                                        @if(in_array($report->status, ['pending', 'verified'], true))
                                            <form action="{{ route('admin.issues.update', $report) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="in_progress">
                                                <input type="hidden" name="priority" value="{{ $report->priority }}">
                                                <input type="hidden" name="admin_note" value="{{ $report->admin_note }}">
                                                <button class="rounded-md bg-[#f4c95d] px-3 py-1.5 text-xs font-bold text-[#253127] hover:bg-[#f1bd35]">
                                                    Start work
                                                </button>
                                            </form>
                                        @endif

                                        @if($report->status !== 'resolved')
                                            <form action="{{ route('admin.issues.update', $report) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="resolved">
                                                <input type="hidden" name="priority" value="{{ $report->priority }}">
                                                <input type="hidden" name="admin_note" value="{{ $report->admin_note }}">
                                                <button class="rounded-md bg-[#253127] px-3 py-1.5 text-xs font-bold text-white hover:bg-[#151c17]">
                                                    Resolve
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-10 text-center text-[#647067]">No reports found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">{{ $reports->links() }}</div>
    </section>
</x-layouts.app>
