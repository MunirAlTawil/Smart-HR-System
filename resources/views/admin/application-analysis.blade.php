@extends('admin.layout')

@section('title', 'Application Analysis')

@section('content')
<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div>
        <h2 class="mb-1">CV Analysis</h2>
        <div class="text-muted small">
            Candidate: <strong>{{ $application->candidate->name }}</strong>
            <span class="mx-2">•</span>
            Job: <strong>{{ $application->jobPosting->title }}</strong>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('result', $application->id) }}" class="btn btn-outline-primary" target="_blank">Open Public Result</a>
        <a href="{{ route('admin.applications') }}" class="btn btn-secondary">Back</a>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <div class="row g-3 align-items-center">
            <div class="col-md-4">
                <div class="text-muted small mb-1">Match Percentage</div>
                <span class="badge fs-6 bg-{{ $application->match_percentage >= 70 ? 'success' : ($application->match_percentage >= 50 ? 'warning' : 'danger') }}">
                    {{ number_format($application->match_percentage, 1) }}%
                </span>
            </div>
            <div class="col-md-8">
                <div class="progress" style="height: 14px;">
                    <div
                        class="progress-bar bg-{{ $application->match_percentage >= 70 ? 'success' : ($application->match_percentage >= 50 ? 'warning' : 'danger') }}"
                        role="progressbar"
                        style="width: {{ $application->match_percentage }}%"
                        aria-valuenow="{{ $application->match_percentage }}"
                        aria-valuemin="0"
                        aria-valuemax="100"
                    ></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header fw-semibold">Matched Skills</div>
            <div class="card-body">
                @if(!empty($application->matched_skills))
                    <div class="alert alert-success mb-0">
                        {{ $application->matched_skills }}
                    </div>
                @else
                    <div class="text-muted">No matched skills stored.</div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header fw-semibold">Missing Skills</div>
            <div class="card-body">
                @if(!empty($application->missing_skills))
                    <div class="alert alert-warning mb-0">
                        {{ $application->missing_skills }}
                    </div>
                @else
                    <div class="text-muted">No missing skills stored.</div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header">
        <div class="fw-semibold">How the score was calculated</div>
        <div class="text-muted small">
            This section explains the system’s analysis behind the match percentage, so HR can understand the result clearly.
        </div>
    </div>
    <div class="card-body">
        @php
            $pretty = !empty($analysis) ? json_encode($analysis, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : null;
            $jobSkillsRaw = $application->jobPosting->skills_required ?? '';
            $jobSkills = array_values(array_filter(array_map('trim', explode(',', (string) $jobSkillsRaw))));
            $matchedSkillsRaw = $application->matched_skills ?? '';
            $matchedSkills = array_values(array_filter(array_map('trim', explode(',', (string) $matchedSkillsRaw))));

            // Prefer stored analysis_details counts, fallback to computed counts
            $totalRequired = $analysis['total_required_skills'] ?? count($jobSkills);
            $matchedCount = $analysis['matched_count'] ?? count($matchedSkills);
            $score = $analysis['match'] ?? ($analysis['match_percentage'] ?? null);
        @endphp

        @if(!empty($analysis) || $jobSkillsRaw || $matchedSkillsRaw)
            <div class="row g-3 mb-2">
                <div class="col-md-4">
                    <div class="border rounded p-3 h-100">
                        <div class="text-muted small">Overall Score</div>
                        <div class="fs-5 fw-semibold">
                            {{ is_numeric($score) ? number_format((float)$score, 1) . '%' : number_format($application->match_percentage, 1) . '%' }}
                        </div>
                        <div class="text-muted small">Same as Match Percentage above</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded p-3 h-100">
                        <div class="text-muted small">Skills Required</div>
                        <div class="fs-5 fw-semibold">
                            {{ (int) $totalRequired }}
                        </div>
                        <div class="text-muted small">Skills listed in the job posting</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded p-3 h-100">
                        <div class="text-muted small">Skills Matched</div>
                        <div class="fs-5 fw-semibold">
                            {{ (int) $matchedCount }}
                        </div>
                        <div class="text-muted small">Found in the candidate’s CV</div>
                    </div>
                </div>
            </div>

            <div class="alert alert-info mb-3">
                Tip: If the score is low but the CV looks relevant, make sure the CV is text-based (not a scanned image) so the system can read it.
            </div>

            <details class="border rounded p-3 bg-light">
                <summary class="d-flex align-items-center justify-content-between">
                    <span class="fw-semibold">Technical details (for debugging)</span>
                    <span class="text-muted small">Click to expand</span>
                </summary>
                <div class="d-flex align-items-center justify-content-end flex-wrap gap-2 mt-3">
                    <button class="btn btn-sm btn-outline-secondary" type="button" onclick="copyAnalysisJson()">Copy details</button>
                </div>
                <pre id="analysisJson" class="mt-2 mb-0 p-3 rounded border bg-white" style="white-space: pre-wrap;">{{ $pretty ?? '{}' }}</pre>
            </details>
        @else
            <div class="text-muted">No analysis details were stored for this application.</div>
        @endif
    </div>
</div>

@push('scripts')
<script>
  function copyAnalysisJson() {
    const el = document.getElementById('analysisJson');
    if (!el) return;
    const text = el.innerText || el.textContent || '';
    if (!text) return;
    navigator.clipboard.writeText(text);
  }
</script>
@endpush
@endsection

