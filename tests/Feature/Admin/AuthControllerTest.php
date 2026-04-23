<?php

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('admin can login with correct credentials', function () {
    $admin = Admin::factory()->create([
        'password' => Hash::make('password'),
    ]);

    $response = $this->postJson('/admin/v1/auth/login', [
        'email' => $admin->email,
        'password' => 'password',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'status',
            'auth_token',
            'data' => [
                'admin' => [
                    'id',
                    'name',
                    'email',
                    'created_at'
                ],
            ],
        ]);
});

test('admin cannot login with incorrect password', function () {
    $admin = Admin::factory()->create([
        'password' => Hash::make('password'),
    ]);

    $response = $this->postJson('/admin/v1/auth/login', [
        'email' => $admin->email,
        'password' => 'wrong-password',
    ]);

    $response->assertStatus(422)
        ->assertJson([
            'status' => false,
            'message' => __('password_incorrect'),
        ]);
});

test('admin cannot login with unregistered email', function () {
    $response = $this->postJson('/admin/v1/auth/login', [
        'email' => 'nonexistent@example.com',
        'password' => 'password',
    ]);

    $response->assertStatus(422)
        ->assertJson([
            'message' => __('email_not_registered'),
        ]);
});
