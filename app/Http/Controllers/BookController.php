<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Services\BookService;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;

class BookController extends Controller
{
    /**
     * Initialize Book service
     * 
     * @param BookService $service 
    */
    public function __construct(private BookService $service){}

    public function index()
    {
        return $this->service->fetchBooks();
    }

    public function store(StoreBookRequest $request)
    {
        return $this->service->storeBook($request->validated());
    }

    /**
     * Laravel already fetched the Author
     * Automatically through apiResource 
     * routing mechanism. 
     * So I simply return it here
    */
    public function show(Book $book)
    {
        $book = Book::with('author')->find($book->id);

        return $book;
    }

    public function update(
        UpdateBookRequest $request, 
        Book $book
    )
    {
        $this->service->updateBook(
            $book, $request->validated()
        );

        /**
         * Make sure to return the updated copy
         */
        return $book->refresh();
    }

    public function destroy(Book $book)
    {
        $this->service->deleteBook($book);
        return response()->noContent();
    }
}