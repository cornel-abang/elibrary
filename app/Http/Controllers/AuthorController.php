<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Services\AuthorService;
use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;

class AuthorController extends Controller
{
    /**
     * Initialize Author service
     * 
     * @param AuthorService $service 
    */
    public function __construct(private AuthorService $service){}

    public function index()
    {
        return $this->service->fetchAuthors();
    }

    public function store(StoreAuthorRequest $request)
    {
        return $this->service->storeAuthor(
            $request->validated()
        );
    }

    /**
     * Laravel already fetched the Author
     * Automatically through apiResource 
     * routing mechanism. So I simply return it here
    */
    public function show(Author $author)
    {
        return $author;
    }

    public function update(
        UpdateAuthorRequest $request, 
        Author $author
    )
    {
        $this->service->updateAuthor(
            $author, $request->validated()
        );
        
        return $author;
    }

    public function destroy(Author $author)
    {
        $this->service->deleteAuthor($author);
        return response()->noContent();
    }
}