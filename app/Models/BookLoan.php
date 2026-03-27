<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookLoan extends Model
{
    protected $table = 'book_loans';
    public $timestamps = false;
    
    protected $fillable = [
        'book_id',
        'reader_id',
        'issued_at',
        'returned_at'
    ];
    
    protected $casts = [
        'issued_at' => 'datetime',
        'returned_at' => 'datetime',
    ];
    
    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
    
    public function reader()
    {
        return $this->belongsTo(Reader::class, 'reader_id');
    }
    
    public function isReturned()
    {
        return !is_null($this->returned_at);
    }
    
    public function returnBook()
    {
        $this->returned_at = now();
        return $this->save();
    }
}
