<?php

use App\Models\IssueReport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

function reportData(): array
{
    return [
        'reporter_name' => 'Student',
        'title' => 'Broken water pipe',
        'category' => 'water',
        'description' => 'Water has been leaking from this pipe for several days.',
        'location' => 'School entrance',
    ];
}

test('reports and photos can be submitted using sqlite', function () {
    Storage::fake('public');
    $response = $this->post('/issues', reportData() + ['photo' => UploadedFile::fake()->image('pipe.jpg')]);
    $issue = IssueReport::firstOrFail();
    $response->assertRedirect(route('issues.show', $issue));
    Storage::disk('public')->assertExists($issue->photo_path);
    $this->get('/issues')->assertOk()->assertSee('Broken water pipe');
    $this->get(route('issues.show', $issue))->assertOk();
});

test('rejected reports are hidden from public pages and totals', function () {
    IssueReport::create(reportData() + ['reference' => 'TEST-1', 'status' => 'rejected']);
    $this->get('/issues')->assertOk()->assertViewHas('stats', fn ($stats) => $stats['total'] === 0);
    $this->get('/issues/1')->assertNotFound();
});

test('admin access requires a configured password and logout removes access', function () {
    config(['app.admin_password' => null]);
    $this->post('/admin/login', ['password' => 'admin123'])->assertSessionHasErrors('password');
    $this->get('/admin/issues')->assertForbidden();
    config(['app.admin_password' => 'test-secret']);
    $this->post('/admin/login', ['password' => 'test-secret'])->assertRedirect(route('admin.issues.index'));
    $this->get('/admin/issues')->assertOk();
    $this->post('/admin/logout')->assertRedirect('/issues');
    $this->get('/admin/issues')->assertForbidden();
});

test('editing a resolved report preserves its original resolution date', function () {
    $issue = IssueReport::create(reportData() + ['reference' => 'TEST-2', 'status' => 'resolved', 'resolved_at' => now()->subDay()]);
    $original = $issue->resolved_at->toDateTimeString();
    $this->withSession(['is_admin' => true])->patch(route('admin.issues.update', $issue), [
        'status' => 'resolved', 'priority' => 'high', 'admin_note' => 'Repair confirmed.',
    ])->assertSessionHasNoErrors()->assertRedirect();
    expect($issue->fresh()->resolved_at->toDateTimeString())->toBe($original);
    $this->withSession(['is_admin' => true])->patch(route('admin.issues.update', $issue), [
        'status' => 'in_progress', 'priority' => 'high',
    ])->assertRedirect();
    expect($issue->fresh()->resolved_at)->toBeNull();
});

test('admin login attempts are rate limited', function () {
    config(['app.admin_password' => 'test-secret']);
    for ($i = 0; $i < 5; $i++) {
        $this->post('/admin/login', ['password' => 'wrong'])->assertSessionHasErrors('password');
    }
    $this->post('/admin/login', ['password' => 'wrong'])->assertStatus(429);
});
