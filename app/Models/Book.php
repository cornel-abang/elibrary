<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $author_id
 * @property string $title
 * @property string $description
 * @property BelongsTo<Author> $author
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 */
class Book extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'author_id', 'description'];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }
}