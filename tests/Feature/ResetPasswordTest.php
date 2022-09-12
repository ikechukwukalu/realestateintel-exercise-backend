<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

use App\Models\User;

class ResetPasswordTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testValidUrlForForgotPassword()
    {
        $response = $this->get('/forgot/password');

        $response->assertStatus(200);
    }

    public function testErrorValidationForResetPassword()
    {
        $postData = [
            'email' => 'testuser2gmail.com', //Wrong email format
            'password' => '12345678',
            'password_confirmation' => '1234567' //None matching passwords
        ];

        $response = $this->post('/reset/password', $postData);
        $responseArray = json_decode($response->getContent(), true);

        $this->assertEquals(500, $responseArray['status_code']);
        $this->assertEquals('fail', $responseArray['status']);

        //This test would also run correctly if an existing email is passed
    }

    public function testResetPassword()
    {
        $random = Str::random(40);
        $postData = [
            'email' => 'test-' . $random . '-user@gmail.com',
            'password' => '12345678',
            'password_confirmation' => '12345678'
        ];

        $user =  User::firstOrCreate(
            ['email' => $postData['email']],
            [
                'name' => $random,
                'password' => Hash::make($postData['password'])
            ]
        );

        $response = $this->post('/reset/password', $postData);
        $responseArray = json_decode($response->getContent(), true);

        $this->assertEquals(200, $responseArray['status_code']);
        $this->assertEquals( 'success', $responseArray['status']);
    }
}
