@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold m-0">All Short URLs</h3>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">Back to Dashboard</a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Original URL</th>
                            <th>Short URL</th>
                            <th>Creator</th>
                            <th>Company</th>
                            <th>Hits</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($urls as $url)
                            <tr>
                                <td class="text-truncate" style="max-width: 300px;">{{ $url->original_url }}</td>
                                <td>
                                    <a href="{{ route('urls.resolve', $url->short_code) }}" target="_blank" class="text-primary fw-medium">
                                        {{ url('/s/' . $url->short_code) }}
                                    </a>
                                </td>
                                <td>{{ $url->user->name }}</td>
                                <td>{{ $url->company->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-success">{{ $url->clicks }}</span>
                                </td>
                                <td>{{ $url->created_at->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No URLs found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                <p class="small text-muted m-0">Showing {{ $urls->count() }} of total {{ $totalUrls }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
