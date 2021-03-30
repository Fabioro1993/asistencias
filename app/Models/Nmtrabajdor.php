<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Helpers\Helper;

class Nmtrabajdor extends Model
{
    use HasFactory;

    public function __construct()
    {
        $this->connection = Helper::getConexionEmpresa();
    }

    //protected $connection = 'oso';
    
    //protected $table = 'nmtrabajador';
    protected $table = 'oso_trabajador';
    protected $primaryKey = 'CEDULA';
    public $incrementing = false;
}
