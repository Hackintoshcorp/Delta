<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Controller_data extends Model{

    protected $table = 'controller_data';
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'devices_list',
        'history',
    ];
}