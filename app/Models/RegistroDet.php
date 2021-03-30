<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class RegistroDet extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'registros_det';

    protected $dates = ['deleted_at'];

    protected $fillable = ['id_registro', 'cedula', 'nombre', 'comentario', 'empresa', 'gerencia', 'ubicacion'];

    protected $primaryKey = 'id_reg_det';

    public function registro_cab()
    {
        return $this->belongsTo(RegistroCab::class, 'id_registro');
    }
    public function registro_sub()
    {
        return $this->hasMany(RegistroSubdet::class, 'id_reg_det');
    }

}
