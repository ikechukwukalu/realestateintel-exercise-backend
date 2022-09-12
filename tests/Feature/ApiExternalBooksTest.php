<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

use App\Models\User;
use App\Models\Book;

class ApiExternalBooksTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testFetchAllExternalBooks()
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

        $accessToken = $user->createToken($credentials['email'])->plainTextToken;
        $this->actingAs($user);

        $response = $this->json('GET', '/api/external-books', [], ['Authorization' => 'Bearer ' . $accessToken]);
        $responseArray = json_decode($response->getContent(), true);
        $user->tokens()->delete();

        $this->assertEquals(200, $responseArray['status_code']);
        $this->assertEquals( 'success', $responseArray['status']);
    }

    public function testFetchExternalBooksByName()
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

        $accessToken = $user->createToken($credentials['email'])->plainTextToken;
        $this->actingAs($user);

        $response = $this->json('GET', '/api/external-books?name=The Mystery Knight', [], ['Authorization' => 'Bearer ' . $accessToken]);
        $responseArray = json_decode($response->getContent(), true);
        $user->tokens()->delete();

        $this->assertEquals(200, $responseArray['status_code']);
        $this->assertEquals( 'success', $responseArray['status']);
    }

    public function test404ForFetchExternalBooks()
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

        $accessToken = $user->createToken($credentials['email'])->plainTextToken;
        $this->actingAs($user);

        $response = $this->json('GET', '/api/external-books?name=wwwwwwwww', [], ['Authorization' => 'Bearer ' . $accessToken]);
        $responseArray = json_decode($response->getContent(), true);
        $user->tokens()->delete();

        $this->assertEquals(404, $responseArray['status_code']);
        $this->assertEquals( 'Not Found', $responseArray['status']);
    }
}
