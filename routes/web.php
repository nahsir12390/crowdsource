<?php

use App\Http\Controllers\Admin\IssueReportController as AdminIssueReportController;
use App\Http\Controllers\IssueReportController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/issues');

Route::resource('issues', IssueReportController::class)->only(['index', 'create', 'show']);

Route::post('/issues', [IssueReportController::class, 'store'])->middleware('throttle:10,1')->name('issues.store');

Route::get('/admin/login', [AdminIssueReportController::class, 'login'])->name('admin.login');
Route::post('/admin/login', [AdminIssueReportController::class, 'authenticate'])->middleware('throttle:5,1')->name('admin.authenticate');
Route::post('/admin/logout', [AdminIssueReportController::class, 'logout'])->name('admin.logout');
Route::get('/admin/issues', [AdminIssueReportController::class, 'index'])->name('admin.issues.index');
Route::get('/admin/issues/{issue}', [AdminIssueReportController::class, 'show'])->name('admin.issues.show');
Route::patch('/admin/issues/{issue}', [AdminIssueReportController::class, 'update'])->name('admin.issues.update');
