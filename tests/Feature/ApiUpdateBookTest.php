<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

use App\Models\User;
use App\Models\Book;

class ApiUpdateBookTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function testErrorValidationForUpdateBook()
    {
        $credentials = [
            'email' => 'testuser1@gmail.com',
            'password' => '12345678'
        ];

        $user =  User::firstOrCreate(
            ['email' => $credentials['email']],
            [
                'name' => Str::random(40),
                'password' => Hash::make($credentials['password'])
            ]
        );

        $postData = [
            'name' => Str::random(40),
            'release_date' => Str::random(40) // Wrong format
        ];

        $accessToken = $user->createToken($credentials['email'])->plainTextToken;
        $this->actingAs($user);

        $response = $this->json('PATCH', '/api/v1/books/' . Book::first()->id, $postData, ['Authorization' => 'Bearer ' . $accessToken]);
        $responseArray = json_decode($response->getContent(), true);
        $user->tokens()->delete();

        $this->assertEquals(500, $responseArray['status_code']);
        $this->assertEquals( 'fail', $responseArray['status']);
    }

    public function testUpdateBook()
    {
        $credentials = [
            'email' => 'testuser1@gmail.com',
            'password' => '12345678'
        ];

        $user =  User::firstOrCreate(
            ['email' => $credentials['email']],
            [
                'name' => Str::random(40),
                'password' => Hash::make($credentials['password'])
            ]
        );

        $postData = [
            'name' => Str::random(40),
            'release_date' => date('Y-m-d')
        ];

        $accessToken = $user->createToken($credentials['email'])->plainTextToken;
        $this->actingAs($user);

        $response = $this->json('PATCH', '/api/v1/books/' . Book::first()->id, $postData, ['Authorization' => 'Bearer ' . $accessToken]);
        $responseArray = json_decode($response->getContent(), true);
        $user->tokens()->delete();

        $this->assertEquals(200, $responseArray['status_code']);
        $this->assertEquals( 'success', $responseArray['status']);
    }
}
