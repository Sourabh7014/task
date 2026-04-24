<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use App\Services\ShortUrlService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShortUrlController extends Controller
{
    protected $dashboardService;
    protected $shortUrlService;

    public function __construct(DashboardService $dashboardService, ShortUrlService $shortUrlService)
    {
        $this->dashboardService = $dashboardService;
        $this->shortUrlService = $shortUrlService;
    }

    public function index(Request $request)
    {
        $data = $this->dashboardService->getUrlList(10, $request->query('filter', 'all'));
        return view('urls.index', $data);
    }

    public function create(Request $request)
    {
        $request->validate([
            'original_url' => 'required|url'
        ]);

        $this->shortUrlService->createShortUrl($request->original_url);

        return redirect()->route('urls.index')->with('success', 'Short URL created successfully.');
    }

    public function resolve($code)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to access this URL.');
        }

        $originalUrl = $this->shortUrlService->resolveShortUrl($code);
        
        return redirect()->away($originalUrl);
    }
}
