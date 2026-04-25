@extends('admin.layout')

@section('title', 'Candidates')

@section('content')
<h2>Candidates</h2>

<div class="card">
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Applications Count</th>
                    <th>Approval Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($candidates as $candidate)
                <tr>
                    <td>{{ $candidate->name }}</td>
                    <td>{{ $candidate->email }}</td>
                    <td>{{ $candidate->phone }}</td>
                    <td>{{ $candidate->applications_count }}</td>
                    <td>
                        <span class="badge bg-{{ $candidate->approval_status == 'approved' ? 'success' : ($candidate->approval_status == 'rejected' ? 'danger' : 'warning') }}">
                            @if($candidate->approval_status == 'approved') Approved
                            @elseif($candidate->approval_status == 'rejected') Rejected
                            @else Pending Review
                            @endif
                        </span>
                    </td>
                    <td>
                        <a href="/admin/candidates/{{ $candidate->id }}" class="btn btn-sm btn-primary">View Details</a>
                        @if($candidate->approval_status != 'approved')
                        <form action="/admin/candidates/{{ $candidate->id }}/approve" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success">Approve</button>
                        </form>
                        @endif
                        @if($candidate->approval_status != 'rejected')
                        <form action="/admin/candidates/{{ $candidate->id }}/reject" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                        </form>
                        @endif
                        <form action="/admin/candidates/{{ $candidate->id }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        {{ $candidates->links() }}
    </div>
</div>
@endsection
