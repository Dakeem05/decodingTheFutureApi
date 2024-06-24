<?php

namespace Tests\Feature\EventRegistration;

use Database\Factories\EventRegistrationFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class VerifyOtpTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    public function test_example(): void
    {
        $data =  [
            'email' => 'studybuddy5354@gmail.com',
            'otp' => '704078',
        ];
        // $response = $this->post('/api/v1/event-registration/verify', [
            //     'email' => 'studybuddy5354@gmail.com',
            //     'otp' => '704078',
            // ]);
            // var_dump($response);
            // $response->assertStatus(200);
            $user = EventRegistrationFactory::factory()->unverified()->create();
            $response = $this->actingAs($user, 'api')->json('POST', '/api/v1/event-registration/verify',$data);
            $response->assertSuccessful();
            // if ($) {
            //     # code...
            // }
        // Event::fake();

        // $verificationUrl = URL::temporarySignedRoute(
        //     'verification.verify',
        //     now()->addMinutes(60),
        //     ['id' => $user->id, 'hash' => sha1($user->email)]
        // );

        // $response = $this->actingAs($user)->get($verificationUrl);

        // Event::assertDispatched(Verified::class);
        // $this->assertTrue($user->fresh()->hasVerifiedEmail());
        // $response->assertRedirect(config('app.frontend_url').'/dashboard?verified=1');
    }
}
