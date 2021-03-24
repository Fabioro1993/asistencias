<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Oso extends Model
{
    use HasFactory;

    protected $connection = 'oso';
    protected $table = 'nmtrabajador';
    public $incrementing = false;
    public $timestamps = false;
}
