<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VwBooksWithSubject extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'vw_books_with_subjects';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
