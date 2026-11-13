<?php

namespace App\Http\Controllers;

use App\Models\IssueReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class IssueReportController extends Controller
{
    public function index(Request $request): View
    {
        $query = IssueReport::query()->visible()->latest();

        if ($request->filled('category')) {
            $query->where('category', $request->string('category'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        $reports = $query->paginate(9)->withQueryString();

        $stats = [
            'total' => IssueReport::visible()->count(),
            'pending' => IssueReport::where('status', 'pending')->count(),
            'in_progress' => IssueReport::where('status', 'in_progress')->count(),
            'resolved' => IssueReport::where('status', 'resolved')->count(),
        ];

        return view('issues.index', [
            'reports' => $reports,
            'stats' => $stats,
            'categories' => IssueReport::CATEGORIES,
            'statuses' => IssueReport::STATUSES,
        ]);
    }

    public function create(): View
    {
        return view('issues.create', [
            'categories' => IssueReport::CATEGORIES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'reporter_name' => ['required', 'string', 'max:120'],
            'reporter_email' => ['nullable', 'email', 'max:160'],
            'reporter_phone' => ['nullable', 'string', 'max:40'],
            'title' => ['required', 'string', 'max:180'],
            'category' => ['required', 'in:'.implode(',', array_keys(IssueReport::CATEGORIES))],
            'description' => ['required', 'string', 'min:20', 'max:3000'],
            'location' => ['required', 'string', 'max:220'],
            'latitude' => ['nullable', 'required_with:longitude', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'required_with:latitude', 'numeric', 'between:-180,180'],
            'photo' => ['nullable', 'image', 'max:4096'],
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $request->file('photo')->store('issue-photos', 'public');
        }

        $validated['reference'] = $this->makeReference();
        $validated['status'] = 'pending';
        $validated['priority'] = 'medium';

        $report = IssueReport::create($validated);

        return redirect()
            ->route('issues.show', $report)
            ->with('success', 'Report submitted successfully. Reference: '.$report->reference);
    }

    public function show(IssueReport $issue): View
    {
        abort_if($issue->status === 'rejected', 404);

        return view('issues.show', [
            'issue' => $issue,
        ]);
    }

    private function makeReference(): string
    {
        do {
            $reference = 'INF-'.now()->format('ymd').'-'.Str::upper(Str::random(5));
        } while (IssueReport::where('reference', $reference)->exists());

        return $reference;
    }
}
