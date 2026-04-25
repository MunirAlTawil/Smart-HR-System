@extends('layout')

@section('title', 'Jobs')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Available Jobs</h1>

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="/jobs">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="department" class="form-select">
                            <option value="">All Departments</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="type" class="form-select">
                            <option value="">All Types</option>
                            @foreach($types as $type)
                                <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Jobs List -->
    <div class="row">
        @forelse($jobs as $job)
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="badge bg-primary">{{ $job->department }}</span>
                        <span class="badge bg-info">{{ $job->type }}</span>
                    </div>
                    <h5 class="card-title">{{ $job->title }}</h5>
                    <p class="card-text">{{ Str::limit($job->description, 150) }}</p>
                    <div class="mb-3">
                        <strong>Required Skills:</strong>
                        <p class="text-muted">{{ Str::limit($job->skills_required, 100) }}</p>
                    </div>
                    @if($job->location)
                    <p class="text-muted"><i class="bi bi-geo-alt"></i> {{ $job->location }}</p>
                    @endif
                    <a href="/jobs/{{ $job->id }}" class="btn btn-primary">Apply Now</a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <p class="text-muted fs-4">No jobs available</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    {{ $jobs->links() }}
</div>
@endsection
