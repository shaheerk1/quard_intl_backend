<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;

class AuthTest extends TestCase
{
 
    public function test_login_invalid_credentials(): void
    {
        // Mock the Auth attempt to always return false
        Auth::shouldReceive('attempt')
            ->once()
            ->with(['email' => 'wrong@mail.lk', 'password' => 'wrongpassword'])
            ->andReturn(false);

        // Create a mock request with incorrect credentials
        $request = Request::create('/login', 'POST', [
            'email' => 'wrong@mail.lk',
            'password' => 'wrongpassword'
        ]);

        // Call the login method on the AuthController
        $controller = new AuthController();
        $response = $controller->login($request);

        // Assert that the response contains the correct error message
        $this->assertArrayHasKey('message', $response->getData(true));
        $this->assertEquals('Invalid credentials', $response->getData(true)['message']);
        $this->assertEquals(401, $response->status());
    }
}
