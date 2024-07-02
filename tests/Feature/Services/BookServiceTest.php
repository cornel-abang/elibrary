<?php

namespace Tests\Feature\Services;

use App\Models\Author;
use Tests\TestCase;
use App\Models\Book;
use App\Services\BookService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $bookService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bookService = new BookService();
    }

    public function testFetchBooks()
    {
        Book::factory()
            ->for(Author::factory())
            ->create();

        $books = $this->bookService->fetchBooks();

        $this->assertInstanceOf(Book::class, $books->first());
        $this->assertNotNull($books->first()->author); // Ensure author relationship is loaded
    }

    public function testStoreBook()
    {
        $bookDetails = [
            'title' => 'Test Book',
            'author_id' => Author::factory()->create()->id,
        ];

        $book = $this->bookService->storeBook($bookDetails);

        $this->assertDatabaseHas('books', ['title' => 'Test Book']);
        $this->assertInstanceOf(Book::class, $book);
    }

    public function testUpdateBook()
    {
        $book = Book::factory()->create(['title' => 'Existing Book']);
        $updatedDetails = ['title' => 'Updated Book'];

        $this->bookService->updateBook($book, $updatedDetails);

        $this->assertDatabaseHas('books', ['id' => $book->id, 'title' => 'Updated Book']);
    }

    public function testDeleteBook()
    {
        $book = Book::factory()->create();

        $this->bookService->deleteBook($book);

        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }
}