<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

use App\Models\User;
use App\Models\Book;

class ApiListBooksTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testListAllBooks()
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

        $accessToken = $user->createToken($credentials['email'])->plainTextToken;
        $this->actingAs($user);

        $response = $this->json('GET', '/api/v1/books', [], ['Authorization' => 'Bearer ' . $accessToken]);
        $responseArray = json_decode($response->getContent(), true);
        $user->tokens()->delete();

        $this->assertEquals(200, $responseArray['status_code']);
        $this->assertEquals( 'success', $responseArray['status']);
    }

    public function testListSpecificBook()
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

        $accessToken = $user->createToken($credentials['email'])->plainTextToken;
        $this->actingAs($user);

        $response = $this->json('GET', '/api/v1/books/' . Book::first()->id, [], ['Authorization' => 'Bearer ' . $accessToken]);
        $responseArray = json_decode($response->getContent(), true);
        $user->tokens()->delete();

        $this->assertEquals(200, $responseArray['status_code']);
        $this->assertEquals( 'success', $responseArray['status']);
    }
}
