@extends('admin.layout')

@section('title', 'Create New Job')

@section('content')
<h2>Create New Job</h2>

<form method="POST" action="/admin/jobs">
    @csrf
    
    <div class="mb-3">
        <label class="form-label">Job Title</label>
        <input type="text" name="title" class="form-control" required>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Department</label>
        <input type="text" name="department" class="form-control" required>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Job Type</label>
        <select name="type" class="form-select" required>
            <option value="full-time">Full-time</option>
            <option value="part-time">Part-time</option>
            <option value="contract">Contract</option>
        </select>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Job Description</label>
        <textarea name="description" class="form-control" rows="5" required></textarea>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Requirements</label>
        <textarea name="requirements" class="form-control" rows="5" required></textarea>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Required Skills (comma separated)</label>
        <input type="text" name="skills_required" class="form-control" required>
        <small class="text-muted">Example: PHP, Laravel, MySQL, JavaScript</small>
    </div>
    
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Location</label>
            <input type="text" name="location" class="form-control">
        </div>
        
        <div class="col-md-3 mb-3">
            <label class="form-label">Min Salary</label>
            <input type="number" name="salary_min" class="form-control">
        </div>
        
        <div class="col-md-3 mb-3">
            <label class="form-label">Max Salary</label>
            <input type="number" name="salary_max" class="form-control">
        </div>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select" required>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>
    </div>
    
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="/admin/jobs" class="btn btn-secondary">Cancel</a>
</form>
@endsection
