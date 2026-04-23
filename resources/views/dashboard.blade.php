@extends('layouts.app')

@section('content')
<div class="row g-4">
    <div class="col-md-12">
        <div class="card p-4 bg-white shadow-sm border-0 mb-4">
            <h1 class="fw-bold text-primary mb-2">Hello, {{ Auth::user()->name }}!</h1>
            <p class="text-muted mb-0">Logged in as: <strong>{{ Auth::user()->role }}</strong> • Company: <strong>{{ Auth::user()->company->name ?? 'None' }}</strong></p>
        </div>
    </div>

    @if(Auth::user()->role === 'SuperAdmin')
        <!-- SuperAdmin Order: Company List THEN Generated URLs -->
        @include('partials.dashboard-user-list')
        @include('partials.dashboard-url-list')
    @else
        <!-- Admin/Member Order: Generated URLs THEN Team Members -->
        @include('partials.dashboard-url-list')
        @include('partials.dashboard-user-list')
    @endif
</div>

<!-- Invite User Modal -->
<div class="modal fade" id="inviteUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Invite New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('invitation.invite') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" placeholder="John Doe" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="john@example.com" required>
                        </div>

                        @if(Auth::user()->role === 'Admin')
                            <div class="col-md-12">
                                <label class="form-label">Role</label>
                                <select name="role" class="form-select" required>
                                    <option value="Member">Member</option>
                                    <option value="Admin">Admin</option>
                                </select>
                            </div>
                        @endif

                        @if(Auth::user()->role === 'SuperAdmin')
                            <div class="col-12">
                                <div class="form-text mt-2">
                                    <i class="bi bi-info-circle"></i> A new company will be automatically created using the <strong>User's Name</strong>, and they will be assigned the <strong>Admin</strong> role.
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Invite User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createUrlModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Create Short URL</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('urls.create') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Original URL</label>
                        <input type="url" name="original_url" class="form-control" placeholder="https://example.com" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Shorten URL</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
