<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $bio
 * @property HasMany<Book> $books
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 */
class Author extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'bio'];

    /**
     * Return every Author fetch with their Books
     */
    protected $with = ['books'];

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}