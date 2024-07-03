<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\Book;
use App\Models\User;
use App\Models\Author;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthorControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        /**
         * Create an authenticated user before 
         * every test case request. And add the 
         * token to the header, for every request
        */

        /**
         * Ceate the user
         */
        $user = User::factory()->create([
            'password' => Hash::make('password')
        ]);

        /**
         * Generate user token
         */
        $token = JWTAuth::fromUser($user);

        /**
         * Set the authorization headers
         */
        $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ]);
    }

    public function testIndex()
    {
        Author::factory()->create();

        $response = $this->getJson('/api/authors');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     '*' => ['id', 'name', 'created_at', 'updated_at']
                 ]);
    }

    public function testStore()
    {
        $authorData = [
            'name' => $this->faker->name,
            'bio' => $this->faker->sentence,
        ];

        $response = $this->postJson('/api/authors', $authorData);

        $response->assertStatus(201)
                 ->assertJsonFragment($authorData);
    }

    public function testStoreWithInvalidData()
    {
        /**
         * Name is required & cannot be empty
         */
        $invalidData = ['name' => '']; 

        $response = $this->postJson('/api/authors', $invalidData);

        $response->assertStatus(422)
                 ->assertJsonStructure([
                     'errors' => ['name'],
                 ]);
    }

    public function testShow()
    {
        $author = Author::factory()->create();

        $response = $this->getJson("/api/authors/{$author->id}");

        $response->assertStatus(200)
                 ->assertJson($author->toArray());
    }

    public function testUpdate()
    {
        $author = Author::factory()->create();
        $updatedData = ['name' => 'Another Name', 'bio' => 'another bio'];

        /**
         * Just making sure..lol
         */
        $this->assertNotEquals($updatedData['name'], $author->name);

        $response = $this->putJson("/api/authors/{$author->id}", $updatedData);

        /**
         * Ensure author latest record
         */
        $author->refresh();

        $this->assertEquals($updatedData['name'], $author->name);
        $response->assertStatus(200)
                 ->assertJsonFragment($updatedData);
    }

    public function testUpdateWithInvalidData()
    {
        $author = Author::factory()->create();
        $invalidData = ['name' => 001]; // name must be a valid string

        $response = $this->putJson("/api/authors/{$author->id}", $invalidData);

        $response->assertStatus(422)
                 ->assertJsonStructure([
                     'errors' => ['name'],
                 ]);
    }

    public function testDestroy()
    {
        $author = Author::factory()->create();

        $response = $this->deleteJson("/api/authors/{$author->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('authors', ['id' => $author->id]);
    }
}
