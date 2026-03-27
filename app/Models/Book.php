<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $table = 'books';
    protected $fillable = [
        'author',
        'title',
        'year',
        'price',
        'is_new',
        'annotation'
    ];

    protected $casts = [
        'is_new' => 'boolean',
        'price' => 'float',
    ];

    public function loans()
    {
        return $this->hasMany(BookLoan::class, 'book_id');
    }

    public function readers()
    {
        return $this->belongsToMany(Reader::class, 'book_loans', 'book_id', 'reader_id')
            ->withPivot('issued_at', 'returned_at');
    }
}