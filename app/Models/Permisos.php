<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permisos extends Model
{
    use HasFactory;

    protected $table = 'permisos';

    protected $fillable=['id', 'empresa', 'gerencia', 'ubicacion'];

    protected $primaryKey = 'id_permiso';
}
