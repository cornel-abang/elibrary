<?php

namespace Tests\Unit\Controllers;

use Mockery;
use Tests\TestCase;
use App\Models\Author;
use App\Services\AuthorService;
use App\Http\Requests\StoreAuthorRequest;
use App\Http\Controllers\AuthorController;
use App\Http\Requests\UpdateAuthorRequest;

class AuthorControllerTest extends TestCase
{
    protected $serviceMock;
    protected $controller;

    public function setUp(): void
    {
        parent::setUp();
        
        /**
         * Mock the srvices so the test can focus on
         * testing the AuthorController itself
         */
        $this->serviceMock = Mockery::mock(AuthorService::class);
        $this->controller = new AuthorController($this->serviceMock);
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testIndex()
    {
        $authors = Author::factory()->count(3)->make();
        $this->serviceMock->shouldReceive('fetchAuthors')
            ->once()
            ->andReturn($authors);

        $response = $this->controller->index();
        
        $this->assertEquals($authors, $response);
    }

    public function testStore()
    {
        $authorData = Author::factory()->make()->toArray();
        $this->serviceMock->shouldReceive('storeAuthor')
            ->once()
            ->with($authorData)
            ->andReturn($authorData);
        /**
         * Mock the Request class to focus 
         * the test on the Controller
         */
        $request = Mockery::mock(StoreAuthorRequest::class);
        $request->shouldReceive('validated')
            ->once()
            ->andReturn($authorData);
        
        $response = $this->controller->store($request);
        
        $this->assertEquals($authorData, $response);
    }

    public function testShow()
    {
        $author = Author::factory()->make();
        
        $response = $this->controller->show($author);
        
        $this->assertEquals($author, $response);
    }

    public function testUpdate()
    {
        $author = Author::factory()->make();
        $updatedData = ['name' => 'Updated Name'];
        
        $this->serviceMock->shouldReceive('updateAuthor')
            ->once()
            ->with($author, $updatedData)
            ->andReturn($author);

        /**
         * Mock the Request class to focus 
         * the test on the Controller
         */
        $request = Mockery::mock(UpdateAuthorRequest::class);
        $request->shouldReceive('validated')
            ->once()
            ->andReturn($updatedData);
        
        $response = $this->controller->update($request, $author);
        
        $this->assertEquals($author, $response);
    }

    public function testDestroy()
    {
        $author = Author::factory()->make();
        
        $this->serviceMock->shouldReceive('deleteAuthor')
            ->once()
            ->with($author);

        $response = $this->controller->destroy($author);
        
        $this->assertEquals(204, $response->status());
    }
}
