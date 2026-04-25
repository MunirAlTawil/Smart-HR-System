@extends('layout')

@section('title', $job->title)

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <h2>{{ $job->title }}</h2>
                        <span class="badge bg-primary fs-6">{{ $job->department }}</span>
                    </div>
                    
                    <div class="mb-4">
                        <span class="badge bg-info me-2">{{ $job->type }}</span>
                        @if($job->location)
                        <span class="badge bg-secondary"><i class="bi bi-geo-alt"></i> {{ $job->location }}</span>
                        @endif
                        @if($job->salary_min)
                        <span class="badge bg-success">${{ $job->salary_min }} - ${{ $job->salary_max }}</span>
                        @endif
                    </div>

                    <h4>Job Description</h4>
                    <p class="text-muted">{{ $job->description }}</p>

                    <h4>Requirements</h4>
                    <p class="text-muted">{{ $job->requirements }}</p>

                    <h4>Required Skills</h4>
                    <p class="text-muted">{{ $job->skills_required }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Apply for this Job</h5>
                    <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#applyModal">
                        Apply Now
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Apply Modal -->
<div class="modal fade" id="applyModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Apply for Job: {{ $job->title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/apply" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="job_id" value="{{ $job->id }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Resume (PDF or DOCX)</label>
                        <input type="file" name="cv" class="form-control" accept=".pdf,.doc,.docx" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Application</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
