<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_nik_and_password(): void
    {
        $user = User::factory()->create(['nik' => 'EMP001', 'role' => 'it', 'password' => Hash::make('secret-password')]);
        $this->post(route('login.store'), ['nik' => 'EMP001', 'password' => 'secret-password'])->assertRedirect(route('dashboard.index'));
        $this->assertAuthenticatedAs($user);
    }
}
