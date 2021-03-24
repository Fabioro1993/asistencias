<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    use HasFactory;

    protected $table = 'evaluaciones';

    protected $fillable=['evaluacion', 'formato', 'abrv', 'max'];

    protected $primaryKey = 'id_evaluacion';
}
