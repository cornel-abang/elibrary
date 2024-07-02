<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Return every Author fetch with their Books
     */
    protected $with = ['books'];

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}