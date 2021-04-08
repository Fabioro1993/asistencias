<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class RegistroSubdet extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'registros_subdet';

    protected $dates = ['deleted_at'];

    protected $fillable = ['id_registro', 'id_reg_det', 'id_evaluacion', 'resultado'];

    protected $primaryKey = 'id_reg_sub';

    public function registro_cab()
    {
        return $this->belongsTo(RegistroCab::class, 'id_registro');
    }

    public function registro_det()
    {
        return $this->belongsTo(RegistroDet::class, 'id_reg_det');
    }

    public function evalua()
    {
        return $this->belongsTo(Evaluacion::class, 'id_evaluacion');
    }
}
