<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $table = 'books';

    protected $fillable = [
        'author', 'title', 'year', 'total_copies',
        'available_copies', 'price', 'is_new', 'annotation'
    ];

    protected $casts = [
        'year' => 'integer',
        'price' => 'float',
        'is_new' => 'boolean',
        'total_copies' => 'integer',
        'available_copies' => 'integer',
    ];

    /**
     * Связь с выдачами
     */
    public function loans()
    {
        return $this->hasMany(BookLoan::class, 'book_id');
    }

    /**
     * Проверка наличия активных выдач (книга на руках)
     */
    public function activeLoan()
    {
        // Проверяем, есть ли активные выдачи у этой книги
        return $this->loans()
            ->whereNull('returned_at')
            ->exists();
    }
}