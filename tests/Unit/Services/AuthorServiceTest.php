<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\Author;
use App\Services\AuthorService;
use Illuminate\Database\Eloquent\Collection;

class AuthorServiceTest extends TestCase
{
    protected $authorService;
    
    protected function setUp(): void
    {
        parent::setUp();

        /**
         * Create a default author record for test cases
         */
        Author::factory()->create();
        /**
         * Service to test
         */
        $this->authorService = new AuthorService();
    }

    public function testFetchAuthors()
    {
        $authors = $this->authorService->fetchAuthors();

        $this->assertInstanceOf(Collection::class, $authors);
    }

    public function testStoreAuthor()
    {
        $authorDetails = ['name' => 'Test Sire'];

        $author = $this->authorService->storeAuthor($authorDetails);
        
        $this->assertInstanceOf(Author::class, $author);
        $this->assertEquals($authorDetails['name'], $author->name);
    }

    public function testUpdateAuthor()
    {
        $author = Author::factory(['name' => 'My good man'])->create();
        $updatedDetails = ['name' => 'My good sir'];

        $updated = $this->authorService->updateAuthor($author, $updatedDetails);

        $this->assertTrue($updated);
    }

    public function testDeleteAuthor()
    {
        $author = Author::factory()->create();

        $deleted = $this->authorService->deleteAuthor($author);

        $this->assertTrue($deleted);
    }
}