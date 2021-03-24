<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Helpers\Helper;

class Nmdpto extends Model
{
    use HasFactory;
    
    protected $connection = 'oso';
    protected $table = 'nmdpto';
    protected $primaryKey = 'DEP_CODIGO';
    public $incrementing = false;
}
