<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reader extends Model
{
    protected $table = 'readers';
    public $timestamps = true;
    
    protected $fillable = [
        'library_card_number',
        'full_name',
        'address',
        'phone'
    ];
    
    public function loans()
    {
        return $this->hasMany(BookLoan::class, 'reader_id');
    }
    
    public function books()
    {
        return $this->belongsToMany(Book::class, 'book_loans', 'reader_id', 'book_id')
                    ->withPivot('issued_at', 'returned_at');
    }
    
    public function activeBooks()
    {
        return $this->books()->wherePivotNull('returned_at');
    }
    
    public function historyBooks()
    {
        return $this->books()->wherePivotNotNull('returned_at');
    }
}
