@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card p-4">
            <h3 class="fw-bold mb-4">Invite New User</h3>
            <form action="{{ route('invitation.invite') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" placeholder="John Doe" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="john@example.com" required>
                    </div>

                    @if(Auth::user()->role === 'Admin')
                        <div class="col-md-12">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                                <option value="Member">Member</option>
                                <option value="Admin">Admin</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif

                    @if(Auth::user()->role === 'SuperAdmin')
                        <div class="col-12">
                            <div class="form-text mt-2">
                                <i class="bi bi-info-circle"></i> A new company will be automatically created using the <strong>User's Name</strong>, and they will be assigned the <strong>Admin</strong> role.
                            </div>
                        </div>
                    @endif

                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-primary">Invite User</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
