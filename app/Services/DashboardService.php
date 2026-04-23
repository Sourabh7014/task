<?php

namespace App\Services;

use App\Models\User;
use App\Models\ShortUrl;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardService
{
    public function getDashboardData($filter = 'all')
    {
        $user = Auth::user();

        $userQuery = $this->getUserQuery($user);
        $totalUsers = $userQuery->count();
        $users = $userQuery->orderBy('id', 'desc')->limit(2)->get();
        $urlQuery = $this->getUrlQuery($user, $filter);
        $totalUrls = $urlQuery->count();
        $urls = $urlQuery->orderBy('id', 'desc')->limit(2)->get();

        $companies = Company::all();

        return compact('users', 'totalUsers', 'urls', 'totalUrls', 'filter', 'companies');
    }

    public function getUserList($limit = 10)
    {
        $user = Auth::user();
        $query = $this->getUserQuery($user);
        
        $totalUsers = $query->count();
        $users = $query->orderBy('id', 'desc')->limit($limit)->get();

        return compact('users', 'totalUsers');
    }

    public function getUrlList($limit = 10)
    {
        $user = Auth::user();
        $query = $this->getUrlQuery($user);
        
        $totalUrls = $query->count();
        $urls = $query->orderBy('id', 'desc')->limit($limit)->get();

        return compact('urls', 'totalUrls');
    }

    protected function getUserQuery($user)
    {
        $query = User::with('company');

        if ($user->role === 'SuperAdmin') {
            return User::where('role', 'Admin')
                ->whereIn('id', function($q) {
                    $q->selectRaw('MIN(id)')
                      ->from('users')
                      ->where('role', 'Admin')
                      ->groupBy('company_id');
                })
                ->with(['company' => function($q) {
                    $q->withCount(['users', 'shortUrls'])
                      ->withSum('shortUrls', 'clicks');
                }]);
        } elseif ($user->role === 'Admin') {
            return $query->where('company_id', $user->company_id)
                         ->where('id', '!=', $user->id)
                         ->withCount('shortUrls')
                         ->withSum('shortUrls', 'clicks');
        } else {
            return $query->where('id', $user->id);
        }
    }

    protected function getUrlQuery($user, $filter = 'all')
    {
        $query = ShortUrl::with(['user', 'company']);

        if ($user->role === 'Admin') {
            $query->where('company_id', $user->company_id);
        } elseif ($user->role === 'Member') {
            $query->where('user_id', $user->id);
        }

        if ($filter !== 'all') {
            $date = match($filter) {
                'today' => Carbon::today(),
                'last_week' => Carbon::now()->subWeek(),
                'this_month' => Carbon::now()->startOfMonth(),
                'last_month' => Carbon::now()->subMonth()->startOfMonth(),
                default => null,
            };

            if ($date) {
                if ($filter === 'last_month') {
                    $query->whereBetween('created_at', [
                        Carbon::now()->subMonth()->startOfMonth(),
                        Carbon::now()->subMonth()->endOfMonth()
                    ]);
                } else {
                    $query->where('created_at', '>=', $date);
                }
            }
        }

        return $query;
    }
}
