<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookLoan extends Model
{
    protected $table = 'book_loans';

    protected $fillable = [
        'book_id',
        'reader_id',
        'issued_at',
        'returned_at',
        'due_date'
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'returned_at' => 'datetime',
        'due_date' => 'datetime',
    ];

    // Связь с книгой
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    // Связь с читателем
    public function reader()
    {
        return $this->belongsTo(Reader::class);
    }

    // Проверка, возвращена ли книга
    public function isReturned()
    {
        return !is_null($this->returned_at);
    }

    // Проверка, просрочена ли выдача
    public function isOverdue()
    {
        if ($this->isReturned()) {
            return false;
        }

        return $this->due_date && $this->due_date < now();
    }

    // Скоуп для активных выдач (книги на руках)
    public static function activeLoans()
    {
        return self::whereNull('returned_at');
    }
}