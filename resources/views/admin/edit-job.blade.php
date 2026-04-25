@extends('admin.layout')

@section('title', 'Edit Job')

@section('content')
<h2>Edit Job</h2>

<form method="POST" action="/admin/jobs/{{ $job->id }}">
    @csrf
    @method('PUT')
    
    <div class="mb-3">
        <label class="form-label">Job Title</label>
        <input type="text" name="title" class="form-control" value="{{ $job->title }}" required>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Department</label>
        <input type="text" name="department" class="form-control" value="{{ $job->department }}" required>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Job Type</label>
        <select name="type" class="form-select" required>
            <option value="full-time" {{ $job->type == 'full-time' ? 'selected' : '' }}>Full-time</option>
            <option value="part-time" {{ $job->type == 'part-time' ? 'selected' : '' }}>Part-time</option>
            <option value="contract" {{ $job->type == 'contract' ? 'selected' : '' }}>Contract</option>
        </select>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Job Description</label>
        <textarea name="description" class="form-control" rows="5" required>{{ $job->description }}</textarea>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Requirements</label>
        <textarea name="requirements" class="form-control" rows="5" required>{{ $job->requirements }}</textarea>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Required Skills</label>
        <input type="text" name="skills_required" class="form-control" value="{{ $job->skills_required }}" required>
    </div>
    
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Location</label>
            <input type="text" name="location" class="form-control" value="{{ $job->location }}">
        </div>
        
        <div class="col-md-3 mb-3">
            <label class="form-label">Min Salary</label>
            <input type="number" name="salary_min" class="form-control" value="{{ $job->salary_min }}">
        </div>
        
        <div class="col-md-3 mb-3">
            <label class="form-label">Max Salary</label>
            <input type="number" name="salary_max" class="form-control" value="{{ $job->salary_max }}">
        </div>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select" required>
            <option value="active" {{ $job->status == 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ $job->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>
    
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="/admin/jobs" class="btn btn-secondary">Cancel</a>
</form>
@endsection
