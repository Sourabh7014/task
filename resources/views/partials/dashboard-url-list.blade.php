<div class="col-md-12">
    <div class="card border-0 shadow-sm p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold m-0">Generated URLs</h5>
            <div class="d-flex gap-2">
                <form action="{{ route('dashboard') }}" method="GET" class="d-flex gap-2">
                    <select name="filter" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="all" {{ $filter === 'all' ? 'selected' : '' }}>All Time</option>
                        <option value="today" {{ $filter === 'today' ? 'selected' : '' }}>Today</option>
                        <option value="last_week" {{ $filter === 'last_week' ? 'selected' : '' }}>Last Week</option>
                        <option value="this_month" {{ $filter === 'this_month' ? 'selected' : '' }}>This Month</option>
                        <option value="last_month" {{ $filter === 'last_month' ? 'selected' : '' }}>Last Month</option>
                    </select>
                </form>
                @if(Auth::user()->role !== 'SuperAdmin')
                    <button class="btn btn-primary btn-sm text-nowrap" data-bs-toggle="modal" data-bs-target="#createUrlModal">Create New URL</button>
                @endif
                <a href="{{ route('export.urls', ['filter' => $filter]) }}" class="btn btn-outline-primary btn-sm text-nowrap">
                    <i class="bi bi-download"></i> Download CSV
                </a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Original URL</th>
                        <th>Short URL</th>
                        <th>Creator</th>
                        <th>Hits</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($urls as $url)
                        <tr>
                            <td class="text-truncate" style="max-width: 250px;">{{ $url->original_url }}</td>
                            <td>
                                <a href="{{ route('urls.resolve', $url->short_code) }}" target="_blank" class="text-primary fw-medium">
                                    {{ url('/s/' . $url->short_code) }}
                                </a>
                            </td>
                            <td>{{ $url->user->name }}</td>
                            <td>
                                <span class="badge bg-success">{{ $url->clicks }}</span>
                            </td>
                            <td>{{ $url->created_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">No URLs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-3">
            <p class="small text-muted m-0">Showing {{ $urls->count() }} of total {{ $totalUrls }}</p>
            <a href="{{ route('urls.index') }}" class="btn btn-outline-secondary btn-sm">View All</a>
        </div>
    </div>
</div>
