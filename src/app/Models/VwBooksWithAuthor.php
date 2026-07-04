<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VwBooksWithAuthor extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'vw_books_with_authors';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
