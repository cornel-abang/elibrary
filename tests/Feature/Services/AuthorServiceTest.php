<?php

namespace Tests\Feature\Services;

use Tests\TestCase;
use App\Models\Book;
use App\Models\Author;
use App\Services\AuthorService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthorServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $authorService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authorService = new AuthorService();
    }

    public function testFetchAuthors()
    {
        /**
         * Ensure database is empty
         */
        Book::query()->delete();
        Author::query()->delete();

        Author::factory()->count(3)->create();

        $authors = $this->authorService->fetchAuthors();

        $this->assertCount(3, $authors);
        $this->assertInstanceOf(Author::class, $authors->first());
    }

    public function testStoreAuthor()
    {
        $details = ['name' => 'Mr Agbado', 'bio' => 'ermmm..hehehe'];

        $author = $this->authorService->storeAuthor($details);

        $this->assertInstanceOf(Author::class, $author);
        $this->assertDatabaseHas('authors', $details);
    }

    public function testUpdateAuthor()
    {
        $author = Author::factory()->create();
        $details = ['name' => 'Shi shi'];

        $result = $this->authorService->updateAuthor($author, $details);

        $this->assertTrue($result);
        $this->assertDatabaseHas(
            'authors', 
            ['id' => $author->id, 'name' => 'Shi shi']
        );
    }

    public function testDeleteAuthor()
    {
        $author = Author::factory()->create();

        $this->authorService->deleteAuthor($author);

        $this->assertDatabaseMissing('authors', ['id' => $author->id]);
    }
}