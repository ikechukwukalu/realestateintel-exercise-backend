<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

use App\Models\User;

class ApiPostBookTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function testErrorValidationForPostBook()
    {
        $credentials = [
            'email' => 'testuser1@gmail.com',
            'password' => 'password'
        ];

        $user =  User::firstOrCreate(
            ['email' => $credentials['email']],
            [
                'name' => Str::random(40),
                'password' => Hash::make($credentials['password'])
            ]
        );

        $postData = [
            'name' => '', // Wrong format
            'isbn' => Str::random(40), // Wrong format
            'authors' => '', // Wrong format
            'publisher' => '', // Wrong format
            'number_of_pages' => '', // Wrong format
            'country' => '', // Wrong format
            'release_date' => '' // Wrong format
        ];

        $accessToken = $user->createToken($credentials['email'])->plainTextToken;
        $this->actingAs($user);

        $response = $this->json('POST', '/api/v1/books', $postData, ['Authorization' => 'Bearer ' . $accessToken]);
        $responseArray = json_decode($response->getContent(), true);
        $user->tokens()->delete();

        $this->assertEquals(500, $responseArray['status_code']);
        $this->assertEquals( 'fail', $responseArray['status']);
    }

    public function testPostBook()
    {
        $credentials = [
            'email' => 'testuser1@gmail.com',
            'password' => 'password'
        ];

        $user =  User::firstOrCreate(
            ['email' => $credentials['email']],
            [
                'name' => Str::random(40),
                'password' => Hash::make($credentials['password'])
            ]
        );

        $postData = [
            'name' => Str::random(15),
            'isbn' => Str::random(40),
            'authors' => Str::random(40),
            'publisher' => Str::random(40),
            'number_of_pages' => 45,
            'country' => 'USA',
            'release_date' => date('Y-m-d')
        ];

        $accessToken = $user->createToken($credentials['email'])->plainTextToken;
        $this->actingAs($user);

        $response = $this->json('POST', '/api/v1/books', $postData, ['Authorization' => 'Bearer ' . $accessToken]);
        $responseArray = json_decode($response->getContent(), true);
        $user->tokens()->delete();

        $this->assertEquals(200, $responseArray['status_code']);
        $this->assertEquals( 'success', $responseArray['status']);
    }
}
