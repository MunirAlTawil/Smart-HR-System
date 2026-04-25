@extends('admin.layout')

@section('title', 'Manage Jobs')

@section('content')
<h2>Manage Jobs</h2>

<div class="mb-3">
    <a href="/admin/jobs/create" class="btn btn-primary">Add New Job</a>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Job Title</th>
                    <th>Department</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($jobs as $job)
                <tr>
                    <td>{{ $job->title }}</td>
                    <td>{{ $job->department }}</td>
                    <td>{{ $job->type }}</td>
                    <td>
                        <span class="badge bg-{{ $job->status == 'active' ? 'success' : 'danger' }}">
                            {{ $job->status }}
                        </span>
                    </td>
                    <td>
                        <a href="/admin/jobs/{{ $job->id }}/edit" class="btn btn-sm btn-primary">Edit</a>
                        <form action="/admin/jobs/{{ $job->id }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-center justify-content-md-end">
            {{ $jobs->links() }}
        </div>
    </div>
</div>
@endsection
