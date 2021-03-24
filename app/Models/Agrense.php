<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agrense extends Model
{
    use HasFactory;

    protected $connection = 'agrense';
    protected $table = 'nmtrabajador';
    public $incrementing = false;
    public $timestamps = false;
}
