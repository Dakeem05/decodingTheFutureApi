<?php

namespace Tests\Feature\EventRegistration;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterEventTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    public function test_new_users_can_register(): void
    {
        $response = $this->post('/api/v1/event-registration/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '7040001678',
        ]);

        $response->assertStatus(201);
    }
}
