<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Nmtrabajdor extends Model
{
    use HasFactory;
    
    protected $connection = 'oso';
    protected $table      = 'nmtrabajador';
    protected $primaryKey = 'CEDULA';
    public $incrementing  = false;

    public function scopeUbic($query)
    {
        //$ubc_agrense = DB::connection('mysql')->table('agren_dept as nmtrabajador')
        $ubc_agrense = DB::connection('agrense')->table('nmtrabajador')
                ->selectRaw('"agrense" as empresa, nmagren.nmtrabajador.UBICACION')
                ->whereNotNull('nmagren.nmtrabajador.UBICACION')
                ->groupby('nmagren.nmtrabajador.UBICACION')->get();
        
        //$ubc_oso = DB::connection('mysql')->table('oso_dept as nmtrabajador')
        $ubc_oso = DB::connection('oso')->table('nmtrabajador')
                ->selectRaw('"oso" as empresa, nmoso.nmtrabajador.UBICACION')
                ->whereNotNull('nmoso.nmtrabajador.UBICACION')
                ->groupby('nmoso.nmtrabajador.UBICACION')->get();

        $result = $ubc_agrense->merge($ubc_oso);

        return $result;
        
        // SELECT "oso" as empresa, nmoso.nmtrabajador.UBICACION
        // FROM nmoso.nmtrabajador
        // WHERE nmoso.nmtrabajador.UBICACION IS NOT NULL
        // UNION
        // SELECT "agrense" as empresa, nmagren.nmtrabajador.UBICACION
        // FROM nmagren.nmtrabajador
        // WHERE nmagren.nmtrabajador.UBICACION IS NOT NULL
    }
}
