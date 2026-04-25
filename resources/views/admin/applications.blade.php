@extends('admin.layout')

@section('title', 'Applications')

@section('content')
<h2>Applications</h2>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-body">
        <table class="table table-striped">
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
                @foreach($applications as $app)
                <tr>
                    <td>{{ $app->candidate->name }}</td>
                    <td>{{ $app->jobPosting->title }}</td>
                    <td>
                        <span class="badge bg-{{ $app->match_percentage >= 70 ? 'success' : ($app->match_percentage >= 50 ? 'warning' : 'danger') }}">
                            {{ number_format($app->match_percentage, 1) }}%
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-{{ $app->status == 'accepted' ? 'success' : ($app->status == 'rejected' ? 'danger' : ($app->status == 'reviewed' ? 'info' : 'warning')) }}">
                            @if($app->status == 'accepted') Accepted
                            @elseif($app->status == 'rejected') Rejected
                            @elseif($app->status == 'reviewed') Reviewed
                            @else Pending Review
                            @endif
                        </span>
                    </td>
                    <td>{{ $app->created_at->format('Y-m-d') }}</td>
                    <td>
                        <div class="d-flex flex-wrap gap-1">
                            <a href="{{ route('result', $app->id) }}" class="btn btn-sm btn-admin-action btn-aa btn-aa-view" target="_blank">
                                <i class="bi bi-box-arrow-up-right"></i> View
                            </a>
                            <a href="{{ route('admin.applications.analysis', $app->id) }}" class="btn btn-sm btn-admin-action btn-aa btn-aa-analyze">
                                <i class="bi bi-graph-up"></i> Analyze
                            </a>
                        @if(!empty($app->candidate?->cv_path))
                            <a href="{{ route('admin.applications.cv', $app->id) }}" class="btn btn-sm btn-admin-action btn-aa btn-aa-download">
                                <i class="bi bi-download"></i> CV
                            </a>
                            <form method="POST" action="{{ route('admin.applications.cv.delete', $app->id) }}" class="d-inline js-delete-cv">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-admin-action btn-aa btn-aa-delete">
                                    <i class="bi bi-trash3"></i> Delete CV
                                </button>
                            </form>
                        @endif
                        <form method="POST" action="{{ route('admin.applications.status', $app->id) }}" class="d-inline">
                            @csrf
                            @if($app->status != 'accepted')
                            <input type="hidden" name="status" value="accepted">
                            <button type="submit" class="btn btn-sm btn-admin-action btn-aa btn-aa-accept">
                                <i class="bi bi-check2-circle"></i> Accept
                            </button>
                            @endif
                        </form>
                        @if($app->status != 'rejected')
                        <form method="POST" action="{{ route('admin.applications.status', $app->id) }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="status" value="rejected">
                            <button type="submit" class="btn btn-sm btn-admin-action btn-aa btn-aa-reject">
                                <i class="bi bi-x-circle"></i> Reject
                            </button>
                        </form>
                        @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        {{ $applications->links() }}
    </div>
</div>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('form.js-delete-cv').forEach((form) => {
      form.addEventListener('submit', (e) => {
        e.preventDefault();

        if (typeof Swal === 'undefined') {
          if (confirm('Delete this CV file?')) form.submit();
          return;
        }

        Swal.fire({
          title: 'Delete CV?',
          text: 'This will remove the uploaded CV file from storage.',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes, delete',
          cancelButtonText: 'Cancel',
          confirmButtonColor: '#dc3545',
        }).then((result) => {
          if (result.isConfirmed) form.submit();
        });
      });
    });
  });
</script>
@endpush
@endsection
