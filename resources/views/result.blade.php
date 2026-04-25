@extends('layout')

@section('title', 'Analysis Result')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-4">
                        <div class="progress" style="height: 100px;">
                            <div class="progress-bar bg-{{ $application->match_percentage >= 70 ? 'success' : ($application->match_percentage >= 50 ? 'warning' : 'danger') }}" 
                                 role="progressbar" 
                                 style="width: {{ $application->match_percentage }}%"
                                 aria-valuenow="{{ $application->match_percentage }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                                {{ number_format($application->match_percentage, 1) }}%
                            </div>
                        </div>
                        <h3 class="mt-3">Match Percentage: {{ number_format($application->match_percentage, 1) }}%</h3>
                    </div>

                    <div class="mb-4">
                        <h5>Applied Job:</h5>
                        <p class="text-primary fs-4">{{ $application->jobPosting->title }}</p>
                    </div>

                    <div class="mb-4">
                        <h5>Candidate Name:</h5>
                        <p>{{ $application->candidate->name }}</p>
                        <p class="text-muted">{{ $application->candidate->email }}</p>
                        <p class="text-muted">{{ $application->candidate->phone }}</p>
                    </div>

                    @if($application->matched_skills)
                    <div class="alert alert-success">
                        <h6>Matched Skills:</h6>
                        <p>{{ $application->matched_skills }}</p>
                    </div>
                    @endif

                    @if($application->missing_skills)
                    <div class="alert alert-warning">
                        <h6>Missing Skills:</h6>
                        <p>{{ $application->missing_skills }}</p>
                    </div>
                    @endif

                    <div class="mt-4">
                        <a href="/jobs" class="btn btn-primary">Browse More Jobs</a>
                        <a href="/" class="btn btn-outline-secondary">Back to Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
