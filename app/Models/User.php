<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {
    use \Illuminate\Auth\Authenticatable;

    protected $table = 'users';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
    ];
}