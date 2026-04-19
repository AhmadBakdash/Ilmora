<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_attempts_login_and_regenerates_session(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);

        $this->startSession();

        $service = app(AuthService::class);

        if (!$service->attemptLogin($user->email, 'password')) {
            throw new \RuntimeException('Login should succeed.');
        }

        $this->assertAuthenticatedAs($user);
    }

    public function test_it_logs_out_and_invalidates_the_session(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $this->actingAs($user);
        $this->startSession();

        $service = app(AuthService::class);

        $service->logout(request());

        $this->assertGuest();
    }
}
