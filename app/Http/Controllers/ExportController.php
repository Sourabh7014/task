<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function exportUrls(Request $request)
    {
        $filter = $request->query('filter', 'all');
        $user = Auth::user();
        
        $query = $this->dashboardService->getUrlQuery($user, $filter);
        $urls = $query->orderBy('id', 'desc')->get();

        $response = new StreamedResponse(function () use ($urls) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Original URL', 'Short URL', 'Creator', 'Company', 'Hits', 'Created At']);

            foreach ($urls as $url) {
                fputcsv($handle, [
                    $url->original_url,
                    url('/s/' . $url->short_code),
                    $url->user->name,
                    $url->company->name ?? 'N/A',
                    $url->clicks,
                    $url->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="urls_export_' . now()->format('YmdHis') . '.csv"');

        return $response;
    }

    public function exportUsers(Request $request)
    {
        $filter = $request->query('filter', 'all');
        $user = Auth::user();

        $query = $this->dashboardService->getUserQuery($user, $filter);
        $users = $query->orderBy('id', 'desc')->get();

        $response = new StreamedResponse(function () use ($users, $user) {
            $handle = fopen('php://output', 'w');
            
            if ($user->role === 'SuperAdmin') {
                fputcsv($handle, ['Company Name', 'Admin Email', 'Total Users', 'Total URLs', 'Total Hits', 'Joined At']);
            } else {
                fputcsv($handle, ['Name', 'Email', 'Role', 'Total URLs', 'Total Hits', 'Joined At']);
            }

            foreach ($users as $u) {
                if ($user->role === 'SuperAdmin') {
                    fputcsv($handle, [
                        $u->company->name ?? 'N/A',
                        $u->email,
                        $u->company->users_count ?? 0,
                        $u->company->short_urls_count ?? 0,
                        $u->company->short_urls_sum_clicks ?? 0,
                        $u->created_at->format('Y-m-d H:i:s'),
                    ]);
                } else {
                    fputcsv($handle, [
                        $u->name,
                        $u->email,
                        $u->role,
                        $u->short_urls_count ?? 0,
                        $u->short_urls_sum_clicks ?? 0,
                        $u->created_at->format('Y-m-d H:i:s'),
                    ]);
                }
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="users_export_' . now()->format('YmdHis') . '.csv"');

        return $response;
    }
}
