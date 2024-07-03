<?php

namespace App\Services;

use App\Models\Book;
use Illuminate\Database\Eloquent\Collection;

/**
 * This class is responsible for implementing
 * all Book actions for the entire system.
 * 
*/
class BookService
{
    /**
     * Fetch all Books from database
     * with their Author
     *
     * @return ?Collection <Book>
    */
    public function fetchBooks()
    {
        return Book::with('author')->get();
    }

    /**
     * Store Book details in the database
     *
     * @return Book
    */
    public function storeBook(array $details)
    {
        return Book::create($details);
    }

    /**
     * Update a single Book record in 
     * the databse 
     *
     * @return bool - helpful for my testing
    */
    public function updateBook(
        Book $book,
        array $details
    )
    {
        return $book->update($details);
    }

    /**
     * Delete a single Book record in 
     * the databse 
     *
     * @return bool|null
    */
    public function deleteBook(Book $book)
    {
        return $book->delete();
    }

    public function searchBooks(string $queryString)
    {
        $books = Book::with('author')
            ->where('title', 'like', "%{$queryString}%")
            ->orWhereHas('author', function($query) use ($queryString)
                {
                    $query->where('name', 'like', "%{$queryString}%");
                })
            ->get();

        return $books;
    }
}