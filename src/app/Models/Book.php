<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    /** @use HasFactory<\Database\Factories\BookFactory> */
    use HasFactory;

    protected $fillable = ['title', 'publisher', 'edition', 'publication_year', 'price'];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'book_author');
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'book_subject');
    }
}
