<?php

namespace App\Services;

use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class InvitationService
{
    public function inviteUser(array $data)
    {
        $user = Auth::user();
        $roleToInvite = $user->role === 'SuperAdmin' ? 'Admin' : ($data['role'] ?? 'Member');
        
        $companyId = null;
        if ($user->role === 'SuperAdmin') {
            $company = Company::create(['name' => $data['name']]);
            $companyId = $company->id;
        } else {
            $companyId = $user->company_id;
        }

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make('password'),
            'role' => $roleToInvite,
            'company_id' => $companyId,
        ]);
    }
}
