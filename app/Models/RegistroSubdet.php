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

    

    // public function scopeAcumulado($query)
    // {
    //     return $query->sum('resultado');
    //     //dd($query);
    // //     return $query->selectRaw('RTRIM(WONbr) as OT_select, RTRIM(WODescr) as WODescr, RTRIM(WOHeader.InvtID) as InvtID, RTRIM(WOHeader.User2) as Lote, RTRIM(Inventory.InvtID) as InventoryInvtID,  RTRIM(Inventory.User3) as peso,  RTRIM(Inventory.User5) as tipopeso')
    // //                  ->join('Inventory', 'WOHeader.InvtID', '=', 'Inventory.InvtID')             
    // //                  ->where('WOHeader.Status', '=', 'A')
    // //                  ->where('SiteID', '=', 'PT001')
    // //                  ->orderBy('OT_select', 'desc')
    // //                  ->limit(1000);
    // }
}
