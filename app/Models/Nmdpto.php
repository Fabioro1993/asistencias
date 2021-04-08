<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Nmdpto extends Model
{
    use HasFactory;
    
    protected $connection = 'oso';
    protected $table = 'nmdpto';
    protected $primaryKey = 'DEP_CODIGO';
    public $incrementing = false;

    public function scopeDpto($query)
    {
        //$dept_agrense = DB::connection('mysql')->table('agren_dept as nmdpto')
        $dept_agrense = DB::connection('agrense')->table('nmdpto')
                ->selectRaw('"agrense" as empresa, nmagren.nmdpto.DEP_CODIGO, nmagren.nmdpto.DEP_DESCRI')->get();
        
        //$dept_oso = DB::connection('mysql')->table('oso_dept as nmdpto')
        $dept_oso = DB::connection('oso')->table('nmdpto')
                ->selectRaw('"oso" as empresa, nmoso.nmdpto.DEP_CODIGO, nmoso.nmdpto.DEP_DESCRI')->get();

        $result = $dept_agrense->merge($dept_oso);

        return $result;
        
        // SELECT "oso" as empresa, nmoso.nmdpto.DEP_CODIGO, nmoso.nmdpto.DEP_DESCRI
        // FROM nmoso.nmdpto
        // UNION
        // SELECT "agrense" as empresa, nmagren.nmdpto.DEP_CODIGO, nmagren.nmdpto.DEP_DESCRI
        // FROM nmagren.nmdpto
    }
}
