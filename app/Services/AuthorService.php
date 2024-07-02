<?php

namespace App\Services;

use App\Models\Author;
use Illuminate\Database\Eloquent\Collection;

/**
 * This class is responsible for implementing
 * all Author actions for the entire system.
 * 
*/
class AuthorService
{
    /**
     * Fetch all Authors from databse
     *
     * @return ?Collection <Author>
    */
    public function fetchAuthors()
    {
        return Author::all();
    }

    /**
     * Store Author details in the databse
     *
     * @return Author
    */
    public function storeAuthor(array $details)
    {
        return Author::create($details);
    }

    /**
     * Update a single Author record in 
     * the databse 
     *
     * @return bool
    */
    public function updateAuthor(
        Author $author,
        array $details
    )
    {
        return $author->update($details);
    }

    /**
     * Delete a single Author record in 
     * the databse 
     *
     * @return bool|null
    */
    public function deleteAuthor(Author $author)
    {
        return $author->delete();
    }
}
