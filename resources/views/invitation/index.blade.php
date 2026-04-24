@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold m-0">
                    @if(Auth::user()->role === 'SuperAdmin') Company List 
                    @elseif(Auth::user()->role === 'Admin') Team Members 
                    @else All Users @endif
                </h3>
                <div class="d-flex gap-2 align-items-center">
                    <form action="{{ route('users.index') }}" method="GET" class="d-flex gap-2">
                        <select name="filter" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="all" {{ ($filter ?? 'all') === 'all' ? 'selected' : '' }}>All Time</option>
                            <option value="today" {{ ($filter ?? 'all') === 'today' ? 'selected' : '' }}>Today</option>
                            <option value="last_week" {{ ($filter ?? 'all') === 'last_week' ? 'selected' : '' }}>Last Week</option>
                            <option value="this_month" {{ ($filter ?? 'all') === 'this_month' ? 'selected' : '' }}>This Month</option>
                            <option value="last_month" {{ ($filter ?? 'all') === 'last_month' ? 'selected' : '' }}>Last Month</option>
                        </select>
                    </form>
                    <a href="{{ route('export.users', ['filter' => $filter ?? 'all']) }}" class="btn btn-outline-primary btn-sm text-nowrap">
                        <i class="bi bi-download"></i> Download CSV
                    </a>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">Back</a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>{{ Auth::user()->role === 'SuperAdmin' ? 'Company Name' : 'Name' }}</th>
                            @if(Auth::user()->role === 'SuperAdmin')
                                <th>Admin Email</th>
                                <th>Total Users</th>
                                <th>Total URLs</th>
                                <th>Total Hits</th>
                            @else
                                <th>Email</th>
                                <th>Role</th>
                                <th>Total URLs</th>
                                <th>Total Hits</th>
                            @endif
                            <th>Joined At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $u)
                            <tr>
                                <td>{{ Auth::user()->role === 'SuperAdmin' ? ($u->company->name ?? 'N/A') : $u->name }}</td>
                                @if(Auth::user()->role === 'SuperAdmin')
                                    <td>{{ $u->email }}</td>
                                    <td>
                                        <span class="badge bg-info text-dark">{{ $u->company->users_count ?? 0 }} Users</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $u->company->short_urls_count ?? 0 }} URLs</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ $u->company->short_urls_sum_clicks ?? 0 }} Hits</span>
                                    </td>
                                @else
                                    <td>{{ $u->email }}</td>
                                    <td>
                                        <span class="badge bg-{{ $u->role === 'Admin' ? 'primary' : 'secondary' }}">
                                            {{ $u->role }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $u->short_urls_count ?? 0 }} URLs</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ $u->short_urls_sum_clicks ?? 0 }} Hits</span>
                                    </td>
                                @endif
                                <td>{{ $u->created_at->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No entries found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                <p class="small text-muted m-0">Showing {{ $users->count() }} of total {{ $totalUsers }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
