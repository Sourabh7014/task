<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use App\Services\InvitationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvitationController extends Controller
{
    protected $dashboardService;
    protected $invitationService;

    public function __construct(DashboardService $dashboardService, InvitationService $invitationService)
    {
        $this->dashboardService = $dashboardService;
        $this->invitationService = $invitationService;
    }

    public function index()
    {
        $data = $this->dashboardService->getUserList(10);
        return view('invitation.index', $data);
    }

    public function invite(Request $request)
    {
        $user = Auth::user();
        
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
        ];

        if ($user->role === 'Admin') {
            $rules['role'] = 'required|in:Admin,Member';
        }

        $request->validate($rules);

        $this->invitationService->inviteUser($request->all());

        return redirect()->route('dashboard')->with('success', 'User invited successfully.');
    }
}
