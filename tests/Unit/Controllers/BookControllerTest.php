<?php

namespace Tests\Unit\Controllers;

use Mockery;
use Tests\TestCase;
use App\Models\Book;
use App\Models\Author;
use App\Services\BookService;
use App\Http\Requests\StoreBookRequest;
use App\Http\Controllers\BookController;
use App\Http\Requests\UpdateBookRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $service;
    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();

        /**
         * Mock the srvices so the test can focus on
         * testing the BookController itself
         */
        $this->service = Mockery::mock(BookService::class);
        $this->controller = new BookController($this->service);
    }

    public function testIndex()
    {
        $books = Book::factory()->count(3)->make();
        $this->service->shouldReceive('fetchBooks')
            ->once()
            ->andReturn($books);

        $response = $this->controller->index();

        $this->assertEquals($books, $response);
    }

    public function testStore()
    {
        $bookData = Book::factory()->make()->toArray();

        $request = Mockery::mock(StoreBookRequest::class);
        $request->shouldReceive('validated')
            ->once()
            ->andReturn($bookData);

        $this->service->shouldReceive('storeBook')
            ->once()
            ->with($bookData)
            ->andReturn($bookData);

        $response = $this->controller->store($request);

        $this->assertEquals($bookData, $response);
    }

    public function testShow()
    {
        $book = Book::factory()
            ->for(Author::factory())
            ->create();

        $response = $this->controller->show($book);
        
        $this->assertEquals($book->id, $response->id);
    }

    public function testUpdate()
    {
        $request = Mockery::mock(UpdateBookRequest::class);
        $request->shouldReceive('validated')
            ->once()
            ->andReturn(['name' => 'Updated Book']);

        $book = new Book(['name' => 'Old Book']);

        $this->service->shouldReceive('updateBook')
            ->once()
            ->with($book, ['name' => 'Updated Book'])
            ->andReturn(true);

        $response = $this->controller->update($request, $book);

        $this->assertEquals($book, $response);
    }

    public function testDestroy()
    {
        $book = Book::factory()->make();

        $this->service->shouldReceive('deleteBook')
            ->once()
            ->with($book)
            ->andReturn(true);

        $response = $this->controller->destroy($book);

        $this->assertEquals(204, $response->getStatusCode());
    }
}