@extends('admin.layout')

@section('title', 'Create New Employee')

@section('content')
<h2>Create New Employee</h2>

<form method="POST" action="/admin/employees">
    @csrf
    
    <div class="mb-3">
        <label class="form-label">Employee Name</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Phone Number</label>
        <input type="text" name="phone" class="form-control">
    </div>
    
    <div class="mb-3">
        <label class="form-label">Position</label>
        <input type="text" name="position" class="form-control" required>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Department</label>
        <input type="text" name="department" class="form-control">
    </div>
    
    <div class="mb-3">
        <label class="form-label">Salary</label>
        <input type="number" step="0.01" name="salary" class="form-control">
    </div>
    
    <div class="mb-3">
        <label class="form-label">Hire Date</label>
        <input type="date" name="hire_date" class="form-control" required>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Employee ID</label>
        <input type="text" name="employee_id" class="form-control">
    </div>
    
    <div class="mb-3">
        <label class="form-label">Notes</label>
        <textarea name="notes" class="form-control" rows="3"></textarea>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select" required>
            <option value="active">Active</option>
            <option value="on_leave">On Leave</option>
            <option value="terminated">Terminated</option>
        </select>
    </div>
    
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="/admin/employees" class="btn btn-secondary">Cancel</a>
</form>
@endsection
