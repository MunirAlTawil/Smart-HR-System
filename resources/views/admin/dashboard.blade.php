@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Dashboard</h2>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5><i class="bi bi-briefcase"></i> Jobs</h5>
                    <h2>{{ $stats['total_jobs'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5><i class="bi bi-people"></i> Candidates</h5>
                    <h2>{{ $stats['total_candidates'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5><i class="bi bi-file-earmark-text"></i> Applications</h5>
                    <h2>{{ $stats['total_applications'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5><i class="bi bi-percent"></i> Average Match</h5>
                    <h2>{{ number_format($stats['average_match'], 1) }}%</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Applications -->
    <div class="card">
        <div class="card-header">
            <h5>Recent Applications</h5>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Candidate</th>
                        <th>Job</th>
                        <th>Match Percentage</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentApplications as $app)
                    <tr>
                        <td>{{ $app->candidate->name }}</td>
                        <td>{{ $app->jobPosting->title }}</td>
                        <td>
                            <span class="badge bg-{{ $app->match_percentage >= 70 ? 'success' : ($app->match_percentage >= 50 ? 'warning' : 'danger') }}">
                                {{ number_format($app->match_percentage, 1) }}%
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $app->status }}</span>
                        </td>
                        <td>{{ $app->created_at->format('Y-m-d') }}</td>
                        <td>
                            <a href="{{ route('admin.applications') }}" class="btn btn-sm btn-primary">View Applications</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
