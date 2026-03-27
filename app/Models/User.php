<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'username',
        'password',
        'role',
        'full_name'
    ];

    protected $hidden = ['password'];

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isLibrarian()
    {
        return $this->role === 'librarian';
    }

    public function verifyPassword($password)
    {
        return password_verify($password, $this->password);
    }
}