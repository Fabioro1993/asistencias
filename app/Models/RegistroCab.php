<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RegistroCab extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'registros_cab';

    protected $dates = ['deleted_at'];

    protected $fillable=['fecha', 'id', 'observacion', 'id_estado'];

    protected $primaryKey = 'id_registro';

    public function responsable()
    {
        return $this->belongsTo(User::class, 'id');
    }
    public function estado()
    {
        return $this->belongsTo(Estado::class, 'id_estado');
    }
    public function registro_det()
    {
        return $this->hasMany(RegistroDet::class, 'id_registro');
    }
    public function registro_sub()
    {
        return $this->hasMany(RegistroSubdet::class, 'id_registro');
    }


    public function scopeTrabajador($query)
    {
        //agren_trabajador
        //$emp_agrense = DB::connection('mysql')->table('agren_trabajador as nmtrabajador')
        $emp_agrense = DB::connection('agrense')->table('nmtrabajador')
                ->selectRaw('"agrense" as empresa, nmtrabajador.CODIGO as cedula, CONCAT(nmtrabajador.NOMBRE, " ", nmtrabajador.APELLIDO) As nombre, nmtrabajador.COD_DPTO as depto, nmtrabajador.UBICACION as ubicacion, nmdpto.DEP_DESCRI as descr, CONCAT("agrense", "_", nmtrabajador.COD_DPTO,"_", nmtrabajador.UBICACION) As emp_dep')
                ->join('nmdpto', 'nmtrabajador.COD_DPTO', '=', 'nmdpto.DEP_CODIGO')
                ->where('nmtrabajador.CONDICION', '=', 'A')
                ->whereNotNull('nmtrabajador.UBICACION')->get();
        
        //$emp_oso = DB::connection('mysql')->table('oso_trabajador as nmtrabajador')
        $emp_oso = DB::connection('oso')->table('nmtrabajador')
                ->selectRaw('"oso" as empresa, nmtrabajador.CODIGO as cedula, CONCAT(nmtrabajador.NOMBRE, " ", nmtrabajador.APELLIDO) As nombre, nmtrabajador.COD_DPTO as depto, nmtrabajador.UBICACION as ubicacion, nmdpto.DEP_DESCRI as descr, CONCAT("oso", "_", nmtrabajador.COD_DPTO,"_", nmtrabajador.UBICACION) As emp_dep')
                ->join('nmdpto', 'nmtrabajador.COD_DPTO', '=', 'nmdpto.DEP_CODIGO')
                ->where('nmtrabajador.CONDICION','=' ,'A')
                ->whereNotNull('nmtrabajador.UBICACION')->get();

        $result = $emp_agrense->merge($emp_oso);
        
        return $result;
        
        // SELECT "oso" as empresa, nmoso.nmtrabajador.CODIGO as codigo, CONCAT(nmoso.nmtrabajador.NOMBRE, ' ', nmoso.nmtrabajador.APELLIDO) As Nombre, nmoso.nmtrabajador.COD_DPTO as depto, nmoso.nmtrabajador.UBICACION as ubicacion, nmoso.nmdpto.DEP_DESCRI as descr, CONCAT('oso', '_', nmoso.nmtrabajador.COD_DPTO,'_', nmoso.nmtrabajador.UBICACION) As emp_dep
        // FROM nmoso.nmtrabajador
        // INNER JOIN nmoso.nmdpto ON nmoso.nmdpto.DEP_CODIGO = nmoso.nmtrabajador.COD_DPTO
        // WHERE nmoso.nmtrabajador.CONDICION = "A" AND  nmoso.nmtrabajador.UBICACION IS NOT NULL
        // UNION
        // SELECT "agrense" as empresa, nmagren.nmtrabajador.CODIGO as codigo, CONCAT(nmagren.nmtrabajador.NOMBRE, ' ', nmagren.nmtrabajador.APELLIDO) As Nombre, nmagren.nmtrabajador.COD_DPTO as depto, nmagren.nmtrabajador.UBICACION as ubicacion, nmagren.nmdpto.DEP_DESCRI as descr, CONCAT('agrense', '_', nmagren.nmtrabajador.COD_DPTO,'_', nmagren.nmtrabajador.UBICACION) As emp_dep
        // FROM nmagren.nmtrabajador
        // INNER JOIN nmagren.nmdpto ON nmagren.nmdpto.DEP_CODIGO = nmagren.nmtrabajador.COD_DPTO
        // WHERE nmagren.nmtrabajador.CONDICION = "A" AND nmagren.nmtrabajador.UBICACION IS NOT NULL

    }
    
    public function scopeHistorico($query, $mes, $cedula)
    {
        $hist_regi = DB::table('registros_subdet')
                ->select(
                    DB::raw('SUM(resultado) as asistencia,
                    COUNT(IF(resultado = "F", 1, NULL)) "falta",
                    COUNT(IF(resultado = "V", 1, NULL)) "vacacion",
                    COUNT(IF(resultado = "R", 1, NULL)) "reposo",
                    COUNT(IF(resultado = "P", 1, NULL)) "permiso"'),
                    'id_evaluacion', 'registros_det.cedula')
                ->join('registros_det', 'registros_subdet.id_reg_det', '=', 'registros_det.id_reg_det')
                ->join('registros_cab', 'registros_det.id_registro', '=', 'registros_cab.id_registro')
                ->whereMonth('fecha', $mes)
                ->where('cedula', $cedula)
                ->groupBy('id_evaluacion', 'cedula')
                ->get();

        return $hist_regi;
    }
    
    public function scopeFechaMax($query, $fecha)
    {
        $fecha_prop = ($fecha == null) ? date("Y-m-d") : $fecha ;

        $anio = date("Y", strtotime($fecha_prop));
        $mes = date("m", strtotime($fecha_prop));
        $dia = date("d", strtotime($fecha_prop));

        $fecha_max = ($fecha_prop < date("Y-m-15", strtotime(date($anio.'-'.$mes.'-'.$dia)))) ?   
        date("Y-m-15", strtotime(date($anio.'-'.$mes.'-'.$dia))) : //SI
        date("Y-m-t", strtotime(date($anio.'-'.$mes.'-'.$dia))); //NO

        return $fecha_max;
    }
    
    public function scopeFinSemana($query, $fecha)
    {
        $fecha_prop = ($fecha == null) ? date("t", strtotime(now())) : date("t", strtotime($fecha));
        $anio       = ($fecha == null) ? date("Y") : date("Y", strtotime($fecha));
        $mes        = ($fecha == null) ? date("m") : date("m", strtotime($fecha));
        
        for($i = 1; $i <= $fecha_prop; $i++) {

            if(date('l', strtotime($mes.'/'.$i.'/'.$anio)) == 'Saturday'){
                $finde['sabado'][] = $i;
            }

            if(date('l', strtotime($mes.'/'.$i.'/'.$anio)) == 'Sunday'){//DOMINGO
                $finde['domingo'][] = $i;
            }
        }
        return $finde;
    }

    public function scopeResumenFinSemana($query, $cedula, $dia, $fecha)
    {
        $mes   = ($fecha == null) ? date("m") : $fecha;
        
        $calculo = DB::table('registros_subdet')
                    ->select(  DB::raw('SUM(resultado) as asistencia'), 'id_evaluacion', 'registros_det.cedula')
                    ->join('registros_det', 'registros_subdet.id_reg_det', '=', 'registros_det.id_reg_det')
                    ->join('registros_cab', 'registros_det.id_registro', '=', 'registros_cab.id_registro')
                    ->whereMonth('fecha', $mes)
                    ->where('cedula', $cedula)
                    ->where('id_evaluacion', 1)
                    ->where(function($q) use($dia) {                    
                        foreach ($dia as $day) {
                            $q->whereDay('fecha', '=', $day, 'or');
                        }
                    })
                    ->groupBy('id_evaluacion', 'cedula')
                    ->get();                    
        
        if (count($calculo) > 0) {
            foreach ($calculo as $key => $value) {
                if ($value->id_evaluacion == 1) {
                    $resumen_gd_fds   = ($value->asistencia != 0) ? $value->asistencia : 0;
                }
            }
        }else{
            $resumen_gd_fds = 0;
        }
        
        return $resumen_gd_fds;
    }

    public function scopeGuardiaAdional($query, $cedula, $fecha)
    {
        $mes   = ($fecha == null) ? date("m") : $fecha;
        
        $query = DB::table('registros_subdet')
                    ->select(  DB::raw('SUM(resultado) as asistencia'), 'id_evaluacion', 'registros_det.cedula')
                    ->join('registros_det', 'registros_subdet.id_reg_det', '=', 'registros_det.id_reg_det')
                    ->join('registros_cab', 'registros_det.id_registro', '=', 'registros_cab.id_registro')
                    ->whereMonth('fecha', $mes)
                    ->where('cedula', $cedula)
                    ->where('id_evaluacion', 11)
                    ->groupBy('id_evaluacion', 'cedula')
                    ->get();
        
        return $query;
    }
    
    public function scopeResumenEdit($query, $id_reg, $ubi)
    {
        $registro_cab = RegistroCab::with(["registro_det" => function($a){
            $a->orderby('empresa', 'asc');
        }])->find($id_reg);

        foreach ($registro_cab->registro_det as $key => $value) {
            $cedula_reg[] = $value->cedula;
        }
        
        $fecha_max = RegistroCab::fechaMax($registro_cab->fecha);

        $mes = date("m", strtotime($registro_cab->fecha));

        $fin_sem = RegistroCab::finSemana($registro_cab->fecha);
        
        foreach ($registro_cab->registro_det as $key => $value) {

            $resumen_gd_totales[$value->cedula]   = null;
            
            $hist_regi = RegistroCab::historico($mes, $value->cedula);
            
            if (count($hist_regi) > 0) {
                foreach ($hist_regi as $key => $value) {
                    if ($value->id_evaluacion == 1) {
                        $resumen_asistencia[$value->cedula]   = ($value->asistencia != 0) ? $value->asistencia : null;
                        $resumen_faltajust[$value->cedula]    = ($value->falta != 0) ? $value->falta : null;
                        $resumen_vacacion[$value->cedula]     = ($value->vacacion != 0) ? $value->vacacion : null;
                        $resumen_reposo[$value->cedula]       = ($value->reposo != 0) ? $value->reposo : null;
                        $resumen_permiso[$value->cedula]      = ($value->permiso != 0) ? $value->permiso : null;
                    }
                    if ($value->id_evaluacion == 2) {
                        $resumen_hx_diurna[$value->cedula]    = ($value->asistencia != 0) ? $value->asistencia : null;
                    }
                    if ($value->id_evaluacion == 3) {
                        $resumen_hx_nocturna[$value->cedula]  = ($value->asistencia != 0) ? $value->asistencia : null;
                    }
                    if ($value->id_evaluacion == 4) {
                        $bono_nocturno[$value->cedula]        = ($value->asistencia != 0) ? $value->asistencia : null;
                    }
                }
            }else{
                $resumen_asistencia[$value->cedula]   = null; 
                $resumen_hx_diurna[$value->cedula]    = null; 
                $resumen_hx_nocturna[$value->cedula]  = null; 
                $bono_nocturno[$value->cedula]        = null;
                $resumen_faltajust[$value->cedula]    = null;
                $resumen_vacacion[$value->cedula]     = null;
                $resumen_reposo[$value->cedula]       = null;
                $resumen_permiso[$value->cedula]      = null;
                $adicionales[$value->cedula]          = null;
            }

            $hist_gd_adicional = RegistroCab::guardiaAdional($value->cedula, $mes);
            
            if (count($hist_gd_adicional) > 0) {
                foreach ($hist_gd_adicional as $key => $val_adic) {
                    if ($val_adic->id_evaluacion == 11) {
                        $adicionales[$value->cedula]  = ($val_adic->asistencia != 0) ? $val_adic->asistencia : null;
                    }
                }
            }else{
                $adicionales[$value->cedula]   = null;
            }

            $sabado = $fin_sem['sabado'];
            $resumen_sabado = RegistroCab::resumenFinSemana($value->cedula, $sabado, $mes);
            $resumen_gd_sabado[$value->cedula] = ($resumen_sabado != 0) ? $resumen_sabado : null;
            
            $domingo = $fin_sem['domingo'];
            $resumen_domingo = RegistroCab::resumenFinSemana($value->cedula, $domingo, $mes);
            $resumen_gd_domingo[$value->cedula] = ($resumen_domingo != 0) ? $resumen_domingo : null;
        }
        
        
        $nmtrabajador = RegistroCab::trabajador()->whereIn('emp_dep', $ubi);
        
        foreach ($nmtrabajador as $key => $value) {
            $cedulas_nmtrab[] = $value->cedula;
        }
        
        $resultado = array_diff($cedulas_nmtrab, $cedula_reg);

        if (count($resultado) >= 1) {
            
            foreach ($resultado as $key_resl => $val_resl) {

                $new = RegistroCab::where('fecha', $registro_cab->fecha)
                            ->whereHas('registro_det', function($q) use ($val_resl){
                                $q->where('cedula', $val_resl);
                            })->with(['registro_det.registro_sub', 'registro_det'=>function ($a) use ($val_resl){
                                $a->where('cedula', $val_resl);
                            }])
                            ->get();
                
                if (count($new) == 0) {
                    $resumen_gd_totales[$val_resl] = null;
                    $resumen_asistencia[$val_resl] = null;
                    $resumen_faltajust[$val_resl] = null;
                    $resumen_vacacion[$val_resl] = null;
                    $resumen_hx_diurna[$val_resl] = null;
                    $resumen_hx_nocturna[$val_resl] = null;
                    $bono_nocturno[$val_resl] = null;
                    $adicionales[$val_resl] =  null;          
                    $resumen_gd_sabado[$val_resl] = null;               
                    $resumen_gd_domingo[$val_resl] = null;
                    $resumen_reposo[$val_resl] = null;
                    $resumen_permiso[$val_resl] = null;
                }else{
                    $registros_det = $new[0]->registro_det[0];

                    $resumen_gd_totales[$registros_det->cedula]   = null;
            
                    $hist_regi = RegistroCab::historico($mes, $registros_det->cedula);
                    
                    if (count($hist_regi) > 0) {
                        foreach ($hist_regi as $key => $val_ht) {
                            if ($val_ht->id_evaluacion == 1) {
                                $resumen_asistencia[$val_ht->cedula]   = ($val_ht->asistencia != 0) ? $val_ht->asistencia : null;
                                $resumen_faltajust[$val_ht->cedula]    = ($val_ht->falta != 0) ? $val_ht->falta : null;
                                $resumen_vacacion[$val_ht->cedula]     = ($val_ht->vacacion != 0) ? $val_ht->vacacion : null;
                                $resumen_reposo[$val_ht->cedula]       = ($val_ht->reposo != 0) ? $val_ht->reposo : null;
                                $resumen_permiso[$val_ht->cedula]      = ($val_ht->permiso != 0) ? $val_ht->permiso : null;
                            }
                            if ($val_ht->id_evaluacion == 2) {
                                $resumen_hx_diurna[$val_ht->cedula]    = ($val_ht->asistencia != 0) ? $val_ht->asistencia : null;
                            }
                            if ($val_ht->id_evaluacion == 3) {
                                $resumen_hx_nocturna[$val_ht->cedula]  = ($val_ht->asistencia != 0) ? $val_ht->asistencia : null;
                            }
                            if ($val_ht->id_evaluacion == 4) {
                                $bono_nocturno[$val_ht->cedula]        = ($val_ht->asistencia != 0) ? $val_ht->asistencia : null;
                            }
                        }
                    }else{
                        $resumen_asistencia[$registros_det->cedula]   = null; 
                        $resumen_hx_diurna[$registros_det->cedula]    = null; 
                        $resumen_hx_nocturna[$registros_det->cedula]  = null; 
                        $bono_nocturno[$registros_det->cedula]        = null;
                        $resumen_faltajust[$registros_det->cedula]    = null;
                        $resumen_vacacion[$registros_det->cedula]     = null;
                        $resumen_reposo[$registros_det->cedula]       = null;
                        $resumen_permiso[$registros_det->cedula]      = null;
                        $adicionales[$registros_det->cedula]          = null;
                    }

                    $hist_gd_adicional = RegistroCab::guardiaAdional($registros_det->cedula, $mes);
                    
                    if (count($hist_gd_adicional) > 0) {
                        foreach ($hist_gd_adicional as $key => $val_adic) {
                            if ($val_adic->id_evaluacion == 11) {
                                $adicionales[$value->cedula]  = ($val_adic->asistencia != 0) ? $val_adic->asistencia : null;
                            }
                        }
                    }else{
                        $adicionales[$registros_det->cedula]   = null;
                    }

                    $sabado = $fin_sem['sabado'];
                    $resumen_sabado = RegistroCab::resumenFinSemana($registros_det->cedula, $sabado, $mes);
                    $resumen_gd_sabado[$registros_det->cedula] = ($resumen_sabado != 0) ? $resumen_sabado : null;
                    
                    $domingo = $fin_sem['domingo'];
                    $resumen_domingo = RegistroCab::resumenFinSemana($registros_det->cedula, $domingo, $mes);
                    $resumen_gd_domingo[$registros_det->cedula] = ($resumen_domingo != 0) ? $resumen_domingo : null;
                }
            }
        }

        //CALCULO DE GUARDIAS TOTALES
        foreach ($resumen_gd_totales as $key => $gd_total) {
            $resultado = $resumen_gd_sabado[$key] + ($resumen_gd_domingo[$key]*2);
            $resumen_gd_totales[$key]   = ($resultado == 0) ? null : $resultado;
        }

        $data['resumen_gd_totales'] = $resumen_gd_totales;
        $data['resumen_asistencia'] = $resumen_asistencia;
        $data['resumen_faltajust'] = $resumen_faltajust;
        $data['resumen_vacacion'] = $resumen_vacacion;
        $data['resumen_hx_diurna'] = $resumen_hx_diurna;
        $data['resumen_hx_nocturna'] = $resumen_hx_nocturna;
        $data['bono_nocturno'] = $bono_nocturno;
        $data['adicionales'] = $adicionales;                
        $data['resumen_gd_sabado'] = $resumen_gd_sabado;               
        $data['resumen_gd_domingo'] = $resumen_gd_domingo;
        $data['resumen_reposo'] = $resumen_reposo;
        $data['resumen_permiso'] = $resumen_permiso;
        $data['fecha_max'] = $fecha_max;
       
        return $data;
    }

    public function scopeResumenNomina($query, $id_reg)
    {
        $registro_cab = RegistroCab::with(["registro_det" => function($a){
                        $a->orderby('empresa', 'asc');
                    }])->find($id_reg);
        
        $fecha_max = RegistroCab::fechaMax($registro_cab->fecha);

        $mes = date("m", strtotime($registro_cab->fecha));

        $fin_sem = RegistroCab::finSemana($registro_cab->fecha);
        
        foreach ($registro_cab->registro_det as $key => $det) {
            
            $trab_empresa[$det->cedula]   = $det->empresa;

            $trabajador[$det->gerencia][$det->cedula]   = $det->nombre;
            
            $resumen_gd_totales[$det->gerencia][$det->cedula]   = null;
        
            $hist_regi = RegistroCab::historico($mes, $det->cedula);
             
            if (count($hist_regi) > 0) {
                foreach ($hist_regi as $key => $value) {
                    
                    if ($value->id_evaluacion == 1) {
                        $resumen_asistencia[$det->gerencia][$value->cedula]   = ($value->asistencia != 0) ? $value->asistencia : null;
                        $resumen_faltajust[$det->gerencia][$value->cedula]    = ($value->falta != 0) ? $value->falta : null;
                        $resumen_vacacion[$det->gerencia][$value->cedula]     = ($value->vacacion != 0) ? $value->vacacion : null;
                        $resumen_reposo[$det->gerencia][$value->cedula]       = ($value->reposo != 0) ? $value->reposo : null;
                        $resumen_permiso[$det->gerencia][$value->cedula]      = ($value->permiso != 0) ? $value->permiso : null;
                    }
                    if ($value->id_evaluacion == 2) {
                        $resumen_hx_diurna[$det->gerencia][$value->cedula]    = ($value->asistencia != 0) ? $value->asistencia : null;
                    }
                    if ($value->id_evaluacion == 3) {
                        $resumen_hx_nocturna[$det->gerencia][$value->cedula]  = ($value->asistencia != 0) ? $value->asistencia : null;
                    }
                    if ($value->id_evaluacion == 4) {
                        $bono_nocturno[$det->gerencia][$value->cedula]        = ($value->asistencia != 0) ? $value->asistencia : null;
                    }
                }
            }else{
                $resumen_asistencia[$det->gerencia][$value->cedula]   = null; 
                $resumen_hx_diurna[$det->gerencia][$value->cedula]    = null; 
                $resumen_hx_nocturna[$det->gerencia][$value->cedula]  = null; 
                $bono_nocturno[$det->gerencia][$value->cedula]        = null;
                $resumen_faltajust[$det->gerencia][$value->cedula]    = null;
                $resumen_vacacion[$det->gerencia][$value->cedula]     = null;
                $resumen_reposo[$det->gerencia][$value->cedula]       = null;
                $resumen_permiso[$det->gerencia][$value->cedula]      = null;
                $adicionales[$det->gerencia][$value->cedula]          = null;
            }

            $hist_gd_adicional = RegistroCab::guardiaAdional($value->cedula, $mes);
            
            if (count($hist_gd_adicional) > 0) {
                foreach ($hist_gd_adicional as $key => $val_adic) {
                    if ($val_adic->id_evaluacion == 11) {
                        $adicionales[$det->gerencia][$det->cedula]  = ($val_adic->asistencia != 0) ? $val_adic->asistencia : null;
                    }
                }
            }else{
                $adicionales[$det->gerencia][$det->cedula]   = null;
            }

            $sabado = $fin_sem['sabado'];
            $resumen_sabado = RegistroCab::resumenFinSemana($det->cedula, $sabado, $mes);
            $resumen_gd_sabado[$det->gerencia][$det->cedula] = ($resumen_sabado != 0) ? $resumen_sabado : null;

            $domingo = $fin_sem['domingo'];
            $resumen_domingo = RegistroCab::resumenFinSemana($det->cedula, $domingo,$mes);
            $resumen_gd_domingo[$det->gerencia][$det->cedula] = ($resumen_domingo != 0) ? $resumen_domingo : null;
        }

        //CALCULO DE GUARDIAS TOTALES
        foreach ($resumen_gd_totales as $key => $gd_total) {
            foreach ($gd_total as $cedula => $val_gd) {
                $resultado = $resumen_gd_sabado[$key][$cedula] + ($resumen_gd_domingo[$key][$cedula]*2);
                $resumen_gd_totales[$key][$cedula]   = ($resultado == 0) ? null : $resultado;
            }
        }
        
        $data['resumen_gd_totales'] = $resumen_gd_totales;
        $data['resumen_asistencia'] = $resumen_asistencia;
        $data['resumen_faltajust'] = $resumen_faltajust;
        $data['resumen_vacacion'] = $resumen_vacacion;
        $data['resumen_hx_diurna'] = $resumen_hx_diurna;
        $data['resumen_hx_nocturna'] = $resumen_hx_nocturna;
        $data['bono_nocturno'] = $bono_nocturno;
        $data['adicionales'] = $adicionales;                
        $data['resumen_gd_sabado'] = $resumen_gd_sabado;               
        $data['resumen_gd_domingo'] = $resumen_gd_domingo;
        $data['resumen_reposo'] = $resumen_reposo;
        $data['resumen_permiso'] = $resumen_permiso;
        $data['fecha_max'] = $fecha_max;
        $data['trabajador'] = $trabajador;
        $data['trab_empresa'] = $trab_empresa;
        
        return $data;
    }
}