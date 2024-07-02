<?php

namespace Tests\Feature\Controllers;

use App\Models\Author;
use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class BookControllerTest extends TestCase
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
        Book::truncate();

        Book::factory()->count(3)->create();

        $response = $this->getJson('/api/books');

        $response->assertStatus(200)
                 ->assertJsonCount(3)
                 ->assertJsonStructure([
                     '*' => ['id', 'title', 'author_id', 'created_at', 'updated_at']
                 ]);
    }

    public function testStore()
    {
        $bookData = [
            'title' => $this->faker->title,
            'author_id' => Author::factory()->create()->id
        ];

        $response = $this->postJson('/api/books', $bookData);
        
        $this->assertEquals(
            $response->json()['author_id'],
            $bookData['author_id']
        );
        $response->assertStatus(201)
                 ->assertJsonFragment($bookData);
    }

    public function testStoreWithInvalidData()
    {
        $invalidData = ['title' => '', 'author_id' => null]; // Both title and author_id are required

        $response = $this->postJson('/api/books', $invalidData);

        $response->assertStatus(422)
                 ->assertJsonStructure([
                     'errors' => ['title', 'author_id'],
                 ]);
    }

    public function testShow()
    {
        $book = Book::factory()->create();

        $response = $this->getJson("/api/books/{$book->id}");

        $response->assertStatus(200)
                 ->assertJson($book->toArray());
    }

    public function testUpdate()
    {
        $book = Book::factory()->create();
        $updatedData = [
            'title' => 'Updated Title', 
            'author_id' => Book::factory()->create()->id
        ];

        $response = $this->putJson("/api/books/{$book->id}", $updatedData);

        $response->assertStatus(200)
                 ->assertJsonFragment($updatedData);
    }

    public function testUpdateWithInvalidData()
    {
        $book = Book::factory()->create();
        /**
         * Author with that id must already exist in the db
         */
        $invalidData = ['title' => 'Another', 'author_id' => 2];

        $response = $this->putJson("/api/books/{$book->id}", $invalidData);

        $response->assertStatus(422)
                 ->assertJsonStructure([
                     'errors' => ['author_id'],
                 ]);
    }

    public function testDestroy()
    {
        $book = Book::factory()->create();

        $response = $this->deleteJson("/api/books/{$book->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
