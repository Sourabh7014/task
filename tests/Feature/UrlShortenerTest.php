<?php

use App\Models\Company;
use App\Models\ShortUrl;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->companyA = Company::create(['name' => 'Company A']);
    $this->companyB = Company::create(['name' => 'Company B']);

    $this->superAdmin = User::create([
        'name' => 'Super Admin',
        'email' => 'super@test.com',
        'password' => Hash::make('password'),
        'role' => 'SuperAdmin'
    ]);

    $this->adminA = User::create([
        'name' => 'Admin A',
        'email' => 'adminA@test.com',
        'password' => Hash::make('password'),
        'role' => 'Admin',
        'company_id' => $this->companyA->id
    ]);

    $this->memberA = User::create([
        'name' => 'Member A',
        'email' => 'memberA@test.com',
        'password' => Hash::make('password'),
        'role' => 'Member',
        'company_id' => $this->companyA->id
    ]);

    $this->adminB = User::create([
        'name' => 'Admin B',
        'email' => 'adminB@test.com',
        'password' => Hash::make('password'),
        'role' => 'Admin',
        'company_id' => $this->companyB->id
    ]);
});

test('SuperAdmin can see Company Stats (URLs and Hits) on dashboard', function () {
    $url1 = ShortUrl::create([
        'original_url' => 'https://test1.com',
        'short_code' => 'TEST11',
        'user_id' => $this->adminA->id,
        'company_id' => $this->companyA->id
    ]);
    
    $url2 = ShortUrl::create([
        'original_url' => 'https://test2.com',
        'short_code' => 'TEST22',
        'user_id' => $this->adminA->id,
        'company_id' => $this->companyA->id
    ]);

    $this->actingAs($this->adminA);
    $this->get('/s/TEST11');
    $this->get('/s/TEST11');
    $this->get('/s/TEST11');

    $response = $this->actingAs($this->superAdmin)->get('/dashboard');
    $response->assertStatus(200);
    
    $response->assertSee('Company A');
    $response->assertSee('2 URLs');
    $response->assertSee('3 Hits');
});

test('SuperAdmin can see latest Companies on dashboard with one Admin each', function () {
    $response = $this->actingAs($this->superAdmin)->get('/dashboard');
    $response->assertStatus(200);
    $response->assertSee('Company A');
    $response->assertSee('Company B');
    $response->assertSee('adminA@test.com');
});

test('Admin can only see their team members on dashboard (excluding themselves)', function () {
    $response = $this->actingAs($this->adminA)->get('/dashboard');
    $response->assertStatus(200);
    $response->assertSee('Member A');
    $response->assertDontSee('Admin B');
    $response->assertSee('total 1');
});

test('Admin and Member can create short urls', function () {
    $this->actingAs($this->adminA)
        ->post('/urls', ['original_url' => 'https://google.com'])
        ->assertRedirect('/urls');
});

test('SuperAdmin cannot create short urls', function () {
    $this->actingAs($this->superAdmin)
        ->post('/urls', ['original_url' => 'https://google.com'])
        ->assertStatus(403);
});

test('Admin sees short urls created in their own company', function () {
    ShortUrl::create([
        'original_url' => 'https://companyA.com',
        'short_code' => 'AAAAAA',
        'user_id' => $this->adminA->id,
        'company_id' => $this->companyA->id
    ]);

    $response = $this->actingAs($this->adminA)->get('/urls');
    $response->assertStatus(200);
    $response->assertSee('https://companyA.com');
});

test('Short urls are not publicly resolvable and redirect to the original url', function () {
    $url = ShortUrl::create([
        'original_url' => 'https://secret.com',
        'short_code' => 'SECRET',
        'user_id' => $this->adminA->id,
        'company_id' => $this->companyA->id
    ]);

    $this->get('/s/SECRET')->assertRedirect('/login');
});

test('SuperAdmin invitation creates a company with the user name', function () {
    $this->actingAs($this->superAdmin)
        ->post('/invite', [
            'name' => 'Test Organization',
            'email' => 'org@test.com'
        ])
        ->assertRedirect('/dashboard');

    $company = Company::where('name', 'Test Organization')->first();
    expect($company)->not->toBeNull();

    $user = User::where('email', 'org@test.com')->first();
    expect($user->role)->toBe('Admin');
    expect($user->company_id)->toBe($company->id);
});

test('Admin can invite Admin or Member to their own company', function () {
    $this->actingAs($this->adminA)
        ->post('/invite', [
            'name' => 'Another Admin',
            'email' => 'anotheradmin@test.com',
            'role' => 'Admin'
        ])
        ->assertRedirect('/dashboard');

    $user = User::where('email', 'anotheradmin@test.com')->first();
    expect($user->role)->toBe('Admin');
    expect($user->company_id)->toBe($this->companyA->id);
});
