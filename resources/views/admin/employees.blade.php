@extends('admin.layout')

@section('title', 'Manage Employees')

@section('content')
<h2>Manage Employees</h2>

<div class="mb-3">
    <a href="/admin/employees/create" class="btn btn-primary">Add New Employee</a>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Employee Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Position</th>
                    <th>Department</th>
                    <th>Salary</th>
                    <th>Hire Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($employees as $employee)
                <tr>
                    <td>{{ $employee->name }}</td>
                    <td>{{ $employee->email }}</td>
                    <td>{{ $employee->phone ?? '-' }}</td>
                    <td>{{ $employee->position }}</td>
                    <td>{{ $employee->department ?? '-' }}</td>
                    <td>{{ $employee->salary ? '$' . number_format($employee->salary, 2) : '-' }}</td>
                    <td>{{ $employee->hire_date->format('Y-m-d') }}</td>
                    <td>
                        <span class="badge bg-{{ $employee->status == 'active' ? 'success' : ($employee->status == 'on_leave' ? 'warning' : 'danger') }}">
                            @if($employee->status == 'active') Active
                            @elseif($employee->status == 'on_leave') On Leave
                            @else Inactive
                            @endif
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.employees.analyze', $employee->id) }}" class="btn btn-sm btn-info">
                            <i class="bi bi-graph-up"></i> Analyze Performance
                        </a>
                        <a href="{{ route('admin.employees.edit', $employee->id) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('admin.employees.delete', $employee->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        {{ $employees->links() }}
    </div>
</div>
@endsection
