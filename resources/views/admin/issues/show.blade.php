<x-layouts.app title="Manage {{ $issue->reference }} - CivicTrack">
    <section class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
            <div>
                <p class="text-sm font-semibold text-[#0f6b5b]">{{ $issue->reference }}</p>
                <h1 class="mt-1 text-3xl font-bold">{{ $issue->title }}</h1>
                <p class="mt-1 text-sm text-[#647067]">{{ $issue->location }}</p>
            </div>
            <x-status-badge :status="$issue->status" />
        </div>

        <div class="grid gap-6 lg:grid-cols-[1fr_380px]">
            <article class="rounded-lg border border-[#d9ded4] bg-white p-6 shadow-sm">
                @if($issue->photo_path)
                    <img src="{{ asset('storage/'.$issue->photo_path) }}" alt="{{ $issue->title }}" class="mb-6 h-80 w-full rounded-lg object-cover">
                @endif
                <p class="whitespace-pre-line leading-7 text-[#415047]">{{ $issue->description }}</p>
                <dl class="mt-6 grid gap-4 border-t border-[#edf0e9] pt-6 text-sm sm:grid-cols-2">
                    <div><dt class="font-semibold text-[#647067]">Reporter</dt><dd>{{ $issue->reporter_name }}</dd></div>
                    <div><dt class="font-semibold text-[#647067]">Phone</dt><dd>{{ $issue->reporter_phone ?: 'Not provided' }}</dd></div>
                    <div><dt class="font-semibold text-[#647067]">Email</dt><dd>{{ $issue->reporter_email ?: 'Not provided' }}</dd></div>
                    <div><dt class="font-semibold text-[#647067]">Category</dt><dd>{{ $issue->categoryLabel() }}</dd></div>
                </dl>
            </article>

            <form action="{{ route('admin.issues.update', $issue) }}" method="POST" class="rounded-lg border border-[#d9ded4] bg-white p-5 shadow-sm">
                @csrf
                @method('PATCH')
                <h2 class="text-lg font-bold">Admin decision</h2>
                <p class="mt-1 text-sm text-[#647067]">Approve, start work, resolve, or reject this report.</p>
                <div class="mt-5 space-y-4">
                    <div>
                        <label class="text-sm font-semibold">What should happen next?</label>
                        <select name="status" class="mt-1 w-full rounded-md border border-[#cbd4ca] px-3 py-2">
                            @foreach($statuses as $value => $label)
                                <option value="{{ $value }}" @selected(old('status', $issue->status) === $value)>
                                    @if($value === 'verified')
                                        Approve report
                                    @elseif($value === 'in_progress')
                                        Start work
                                    @elseif($value === 'resolved')
                                        Mark as resolved
                                    @elseif($value === 'rejected')
                                        Reject report
                                    @else
                                        Keep pending
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <x-field-error name="status" />
                    </div>
                    <div>
                        <label class="text-sm font-semibold">Priority</label>
                        <select name="priority" class="mt-1 w-full rounded-md border border-[#cbd4ca] px-3 py-2">
                            @foreach($priorities as $value => $label)
                                <option value="{{ $value }}" @selected(old('priority', $issue->priority) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <x-field-error name="priority" />
                    </div>
                    <div>
                        <label class="text-sm font-semibold">Official note</label>
                        <textarea name="admin_note" rows="7" class="mt-1 w-full rounded-md border border-[#cbd4ca] px-3 py-2">{{ old('admin_note', $issue->admin_note) }}</textarea>
                        <x-field-error name="admin_note" />
                    </div>
                </div>
                <button class="mt-5 w-full rounded-md bg-[#0f6b5b] px-4 py-2.5 text-sm font-bold text-white hover:bg-[#0b584b]">Save decision</button>
                <a href="{{ route('admin.issues.index') }}" class="mt-3 block rounded-md border border-[#cbd4ca] px-4 py-2.5 text-center text-sm font-semibold">Back to admin</a>
            </form>
        </div>
    </section>
</x-layouts.app>
