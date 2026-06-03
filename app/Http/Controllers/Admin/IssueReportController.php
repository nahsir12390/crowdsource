<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IssueReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IssueReportController extends Controller
{
    public function login(): View
    {
        return view('admin.login');
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'password' => ['required', 'string'],
        ]);

        if ($validated['password'] !== config('app.admin_password', 'admin123')) {
            return back()->withErrors(['password' => 'The admin password is incorrect.']);
        }

        $request->session()->put('is_admin', true);

        return redirect()->route('admin.issues.index');
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget('is_admin');

        return redirect()->route('issues.index');
    }

    public function index(Request $request): View
    {
        $this->authorizeAdmin($request);

        $query = IssueReport::query()->latest();

        if ($request->filled('category')) {
            $query->where('category', $request->string('category'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        return view('admin.issues.index', [
            'reports' => $query->paginate(12)->withQueryString(),
            'categories' => IssueReport::CATEGORIES,
            'statuses' => IssueReport::STATUSES,
            'stats' => [
                'total' => IssueReport::count(),
                'pending' => IssueReport::where('status', 'pending')->count(),
                'verified' => IssueReport::where('status', 'verified')->count(),
                'in_progress' => IssueReport::where('status', 'in_progress')->count(),
                'resolved' => IssueReport::where('status', 'resolved')->count(),
            ],
        ]);
    }

    public function show(Request $request, IssueReport $issue): View
    {
        $this->authorizeAdmin($request);

        return view('admin.issues.show', [
            'issue' => $issue,
            'statuses' => IssueReport::STATUSES,
            'priorities' => IssueReport::PRIORITIES,
        ]);
    }

    public function update(Request $request, IssueReport $issue): RedirectResponse
    {
        $this->authorizeAdmin($request);

        $validated = $request->validate([
            'status' => ['required', 'in:'.implode(',', array_keys(IssueReport::STATUSES))],
            'priority' => ['required', 'in:'.implode(',', array_keys(IssueReport::PRIORITIES))],
            'admin_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $validated['resolved_at'] = $validated['status'] === 'resolved' ? now() : null;

        $issue->update($validated);

        return redirect()
            ->route('admin.issues.show', $issue)
            ->with('success', 'Report updated.');
    }

    private function authorizeAdmin(Request $request): void
    {
        abort_unless($request->session()->get('is_admin'), 403);
    }
}
