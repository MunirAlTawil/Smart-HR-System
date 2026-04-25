@extends('admin.layout')

@section('title', 'Candidate Details')

@section('content')
<h2>Candidate Details</h2>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5>Personal Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong> {{ $candidate->name }}</p>
                <p><strong>Email:</strong> {{ $candidate->email }}</p>
                <p><strong>Phone:</strong> {{ $candidate->phone }}</p>
                <p><strong>Approval Status:</strong> 
                    <span class="badge bg-{{ $candidate->approval_status == 'approved' ? 'success' : ($candidate->approval_status == 'rejected' ? 'danger' : 'warning') }}">
                        @if($candidate->approval_status == 'approved') Approved
                        @elseif($candidate->approval_status == 'rejected') Rejected
                        @else Pending Review
                        @endif
                    </span>
                </p>
                <p><strong>Registration Date:</strong> {{ $candidate->created_at->format('Y-m-d H:i') }}</p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5>Actions</h5>
            </div>
            <div class="card-body">
                @if($candidate->approval_status != 'approved')
                <form action="/admin/candidates/{{ $candidate->id }}/approve" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success">Approve</button>
                </form>
                @endif
                @if($candidate->approval_status != 'rejected')
                <form action="/admin/candidates/{{ $candidate->id }}/reject" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger">Reject</button>
                </form>
                @endif
            </div>
        </div>

        @if($candidate->cv_path)
        <div class="card mb-4">
            <div class="card-header">
                <h5>Resume</h5>
            </div>
            <div class="card-body">
                <a href="{{ asset('storage/' . $candidate->cv_path) }}" class="btn btn-primary" target="_blank">
                    <i class="bi bi-download"></i> Download Resume
                </a>
            </div>
        </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h5>Applications Submitted</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Job</th>
                            <th>Match Percentage</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($candidate->applications as $app)
                        <tr>
                            <td>{{ $app->jobPosting->title ?? 'Not specified' }}</td>
                            <td>
                                <span class="badge bg-{{ $app->match_percentage >= 70 ? 'success' : ($app->match_percentage >= 50 ? 'warning' : 'danger') }}">
                                    {{ number_format($app->match_percentage, 1) }}%
                                </span>
                            </td>
                            <td><span class="badge bg-secondary">{{ $app->status }}</span></td>
                            <td>{{ $app->created_at->format('Y-m-d') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<a href="/admin/candidates" class="btn btn-secondary">Back</a>
@endsection
