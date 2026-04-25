@extends('admin.layout')

@section('title', 'Edit Employee')

@section('content')
<h2>Edit Employee</h2>

<form method="POST" action="/admin/employees/{{ $employee->id }}">
    @csrf
    @method('PUT')
    
    <div class="mb-3">
        <label class="form-label">Employee Name</label>
        <input type="text" name="name" class="form-control" value="{{ $employee->name }}" required>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ $employee->email }}" required>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Phone Number</label>
        <input type="text" name="phone" class="form-control" value="{{ $employee->phone }}">
    </div>
    
    <div class="mb-3">
        <label class="form-label">Position</label>
        <input type="text" name="position" class="form-control" value="{{ $employee->position }}" required>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Department</label>
        <input type="text" name="department" class="form-control" value="{{ $employee->department }}">
    </div>
    
    <div class="mb-3">
        <label class="form-label">Salary</label>
        <input type="number" step="0.01" name="salary" class="form-control" value="{{ $employee->salary }}">
    </div>
    
    <div class="mb-3">
        <label class="form-label">Hire Date</label>
        <input type="date" name="hire_date" class="form-control" value="{{ $employee->hire_date->format('Y-m-d') }}" required>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Employee ID</label>
        <input type="text" name="employee_id" class="form-control" value="{{ $employee->employee_id }}">
    </div>
    
    <div class="mb-3">
        <label class="form-label">Notes</label>
        <textarea name="notes" class="form-control" rows="3">{{ $employee->notes }}</textarea>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select" required>
            <option value="active" {{ $employee->status == 'active' ? 'selected' : '' }}>Active</option>
            <option value="on_leave" {{ $employee->status == 'on_leave' ? 'selected' : '' }}>On Leave</option>
            <option value="terminated" {{ $employee->status == 'terminated' ? 'selected' : '' }}>Terminated</option>
        </select>
    </div>
    
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="/admin/employees" class="btn btn-secondary">Cancel</a>
</form>
@endsection
