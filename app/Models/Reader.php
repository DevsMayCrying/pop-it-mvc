<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reader extends Model
{
    protected $table = 'readers';
    
    protected $fillable = [
        'library_card_number',
        'full_name',
        'address',
        'phone'
    ];
    
    // Связь с выдачами книг
    public function bookLoans()
    {
        return $this->hasMany(BookLoan::class, 'reader_id');
    }
    
    // Активные выдачи (книги на руках) — где returned_at = NULL
    public function activeLoans()
    {
        return $this->hasMany(BookLoan::class, 'reader_id')
                    ->whereNull('returned_at');
    }
    
    // История выдач
    public function loanHistory()
    {
        return $this->hasMany(BookLoan::class, 'reader_id')
                    ->orderBy('created_at', 'desc');
    }
}
