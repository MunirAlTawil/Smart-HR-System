<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AuthController;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/jobs', [JobController::class, 'index'])->name('jobs');
Route::get('/jobs/{id}', [JobController::class, 'show'])->name('job.details');
Route::post('/apply', [ApplicationController::class, 'apply'])->name('apply');
Route::get('/result/{id}', [ApplicationController::class, 'result'])->name('result');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Auth Routes (guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/quick-login/{role}', [AuthController::class, 'quickLogin'])->name('quick-login');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Admin Routes (auth + admin role)
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Jobs Management
    Route::get('/jobs', [AdminController::class, 'jobs'])->name('admin.jobs');
    Route::get('/jobs/create', [AdminController::class, 'createJob'])->name('admin.jobs.create');
    Route::post('/jobs', [AdminController::class, 'storeJob'])->name('admin.jobs.store');
    Route::get('/jobs/{id}/edit', [AdminController::class, 'editJob'])->name('admin.jobs.edit');
    Route::put('/jobs/{id}', [AdminController::class, 'updateJob'])->name('admin.jobs.update');
    Route::delete('/jobs/{id}', [AdminController::class, 'deleteJob'])->name('admin.jobs.delete');
    
    // Candidates
    Route::get('/candidates', [AdminController::class, 'candidates'])->name('admin.candidates');
    Route::get('/candidates/{id}', [AdminController::class, 'candidateDetails'])->name('admin.candidates.show');
    Route::delete('/candidates/{id}', [AdminController::class, 'deleteCandidate'])->name('admin.candidates.delete');
    Route::post('/candidates/{id}/approve', [AdminController::class, 'approveCandidate'])->name('admin.candidates.approve');
    Route::post('/candidates/{id}/reject', [AdminController::class, 'rejectCandidate'])->name('admin.candidates.reject');
    
    // Applications
    Route::get('/applications', [AdminController::class, 'applications'])->name('admin.applications');
    Route::post('/applications/{id}/status', [AdminController::class, 'updateApplicationStatus'])->name('admin.applications.status');
    Route::get('/applications/{id}/analysis', [AdminController::class, 'applicationAnalysis'])->name('admin.applications.analysis');
    Route::get('/applications/{id}/cv', [AdminController::class, 'downloadApplicationCv'])->name('admin.applications.cv');
    Route::delete('/applications/{id}/cv', [AdminController::class, 'deleteApplicationCv'])->name('admin.applications.cv.delete');
    
    // Contacts
    Route::get('/contacts', [AdminController::class, 'contacts'])->name('admin.contacts');
    Route::post('/contacts/{id}/read', [AdminController::class, 'markContactAsRead'])->name('admin.contacts.read');
    Route::delete('/contacts/{id}', [AdminController::class, 'deleteContact'])->name('admin.contacts.delete');
    
    // Employees (more specific routes first)
    Route::get('/employees', [AdminController::class, 'employees'])->name('admin.employees');
    Route::get('/employees/create', [AdminController::class, 'createEmployee'])->name('admin.employees.create');
    Route::post('/employees', [AdminController::class, 'storeEmployee'])->name('admin.employees.store');
    
    // Employee Analysis (before general routes)
    Route::get('/employees/{id}/analyze', [AdminController::class, 'analyzeEmployee'])->name('admin.employees.analyze');
    Route::post('/employees/{id}/analyze/run', [AdminController::class, 'runAnalysis'])->name('admin.employees.runAnalysis');
    
    // Employee Edit & Delete
    Route::get('/employees/{id}/edit', [AdminController::class, 'editEmployee'])->name('admin.employees.edit');
    Route::put('/employees/{id}', [AdminController::class, 'updateEmployee'])->name('admin.employees.update');
    Route::delete('/employees/{id}', [AdminController::class, 'deleteEmployee'])->name('admin.employees.delete');
});
