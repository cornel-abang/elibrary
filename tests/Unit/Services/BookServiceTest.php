<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\Book;
use App\Services\BookService;
use Illuminate\Database\Eloquent\Collection;

class BookServiceTest extends TestCase
{
    protected $bookService;
    
    protected function setUp(): void
    {
        parent::setUp();

        /**
         * Create a default book record for test cases
         */
        Book::factory()->create();
        /**
         * Service to test
         */
        $this->bookService = new BookService();
    }

    public function testFetchBooks()
    {
        $books = $this->bookService->fetchBooks();

        $this->assertInstanceOf(Collection::class, $books);
    }

    public function testStoreBook()
    {
        $bookDetails = [
            'title' => 'Test Book',
            'author_id' => \App\Models\Author::factory()->create()->id,
        ];

        $book = $this->bookService->storeBook($bookDetails);
        
        $this->assertInstanceOf(Book::class, $book);
        $this->assertEquals($bookDetails['title'], $book->title);
        $this->assertEquals($bookDetails['author_id'], $book->author_id);
    }

    public function testUpdateBook()
    {
        $book = Book::factory(['title' => 'Existing Book'])->create();
        $updatedDetails = ['title' => 'Updated Book'];

        $updated = $this->bookService->updateBook($book, $updatedDetails);

        $this->assertTrue($updated);
    }

    public function testDeleteBook()
    {
        $book = Book::factory()->create();

        $deleted = $this->bookService->deleteBook($book);

        $this->assertTrue($deleted);
    }
}