@extends('admin.layout')

@section('title', 'Messages')

@section('content')
<h2>Messages</h2>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($contacts as $contact)
                <tr>
                    <td>{{ $contact->name }}</td>
                    <td>{{ $contact->email }}</td>
                    <td>{{ $contact->subject }}</td>
                    <td>
                        <span class="badge bg-{{ $contact->status == 'unread' ? 'danger' : 'success' }}">
                            {{ $contact->status }}
                        </span>
                    </td>
                    <td>{{ $contact->created_at->format('Y-m-d') }}</td>
                    <td>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modal{{ $contact->id }}">
                            Read
                        </button>
                        @if($contact->status == 'unread')
                        <form action="/admin/contacts/{{ $contact->id }}/read" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success">Mark as Read</button>
                        </form>
                        @endif
                        <form
                            action="{{ route('admin.contacts.delete', $contact->id) }}"
                            method="POST"
                            class="d-inline js-delete-contact"
                            data-contact-name="{{ $contact->name }}"
                            data-contact-subject="{{ $contact->subject }}"
                        >
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>

                <!-- Modal -->
                <div class="modal fade" id="modal{{ $contact->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">{{ $contact->subject }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p><strong>Name:</strong> {{ $contact->name }}</p>
                                <p><strong>Email:</strong> {{ $contact->email }}</p>
                                <p><strong>Phone:</strong> {{ $contact->phone ?? 'Not specified' }}</p>
                                <hr>
                                <p><strong>Message:</strong></p>
                                <p>{{ $contact->message }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
        
        {{ $contacts->links() }}
    </div>
</div>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('form.js-delete-contact').forEach((form) => {
      form.addEventListener('submit', (e) => {
        e.preventDefault();

        const name = form.getAttribute('data-contact-name') || 'this sender';
        const subject = form.getAttribute('data-contact-subject') || 'this message';

        Swal.fire({
          title: 'Delete message?',
          html: `Are you sure you want to delete <b>${subject}</b> from <b>${name}</b>?`,
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
