<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Evaluacion;
use App\Models\Nmtrabajdor;
use App\Models\RegistroCab;
use App\Models\RegistroDet;
use App\Models\RegistroSubdet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RegistroComponent extends Component
{
    public $oso, $fecha, $fecha_min, $fecha_max, $fin_semana, $observacion, $adicionales, $data, $select_val; 
    public $hidden = 'hidden';   
    public $comparar = null;
    
    public $bono = array();
    public $comentario = array();
    public $asistencia = array();
    public $asistencia_input = array();
    public $adicionales_input = array();
    public $evaluacion = array();
    public $cant_trabaj = array();

    public $resumen_asistencia = array(); 
    public $resumen_faltajust = array(); 
    public $resumen_vacacion = array(); 
    public $resumen_reposo = array(); 
    public $resumen_permiso = array(); 
    public $resumen_hx_diurna = array(); 
    public $resumen_hx_nocturna = array(); 
    public $bono_nocturno = array();   
    public $resumen_gd_sabado = array();   
    public $resumen_gd_domingo = array();
    public $resumen_gd_totales = array();


    public $old_resm_asistencia = array(); 
    public $old_resm_faltajust = array(); 
    public $old_resm_vacacion = array(); 
    public $old_resm_reposo = array(); 
    public $old_resm_permiso = array(); 
    public $old_resm_hx_diurna = array(); 
    public $old_resm_hx_nocturna = array(); 
    public $old_bono_nocturno = array();   
    public $old_resm_gd_sabado = array();   
    public $old_resm_gd_domingo = array();
    public $old_resm_gd_totales = array();

    public function render()
    {
        $breadcrumbs = [
            ['link'=>"/",'name'=>"Inicio"],['link'=>"/registro",'name'=>"Registro"]
        ];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true];
        
        
        $fin_sem = RegistroCab::finSemana(null);

        //$oso = Oso::all();
        $this->oso    = Nmtrabajdor::all();
        $evaluaciones = Evaluacion::whereIn('id_evaluacion', [1, 2, 3,4])->get();
        $select       = Evaluacion::whereIn('id_evaluacion', [5,6,7,8])->get();
        
        if (!isset($this->comparar)) {  

            //CALCULO DE FECHA DE REGISTRO SELECT-MIN-MAX
            $registro_abierto = RegistroCab::orderBy('fecha','desc')->where('id_estado', 3)->get()->take(1);
            $registro = RegistroCab::orderBy('fecha','desc')->get()->take(1);

            if (count($registro_abierto)>0) {
                $registro = $registro_abierto;
            }else if(count($registro)>0){
                $registro = $registro;
            }
            
            $this->fecha_min = (count($registro)>0) ? date("Y-m-d",strtotime($registro[0]->fecha."+ 1 days")) : date("Y-m-d",strtotime('first day of this month'));
            $this->fecha_max = RegistroCab::fechaMax(null);
            $this->fecha     = $this->fecha_min;            

            foreach ($this->oso as $key => $value) {
                $this->cant_trabaj[$value->CEDULA]          = $value->NOMBRE.' '.$value->APELLIDO;
                $this->resumen_gd_totales[$value->CEDULA]   = null;
                $this->comentario[$value->CEDULA]           = null;
                $this->adicionales[$value->CEDULA]          = null;
                $this->adicionales_input[$value->CEDULA]    = null;
                $this->select_val[$value->CEDULA] = '0_'.$value->CEDULA;
                foreach ($evaluaciones as $key => $evaluacion) {
                    $this->asistencia_input[$value->CEDULA][$evaluacion->id_evaluacion] = 0;
                }

                $sabado = $fin_sem['sabado'];
                $hist_regi_sabd = RegistroCab::resumenFinSemana($value->CEDULA, $sabado, null);

                if (count($hist_regi_sabd) > 0) {
                    foreach ($hist_regi_sabd as $key => $val_sab) {
                        if ($val_sab->id_evaluacion == 1) {
                            $this->resumen_gd_sabado[$val_sab->cedula]   = ($val_sab->asistencia != 0) ? $val_sab->asistencia : null;
                        }
                    }
                }else{
                    $this->resumen_gd_sabado[$value->CEDULA]   = null;
                }

                $domingo = $fin_sem['domingo'];
                $hist_regi_domg = RegistroCab::resumenFinSemana($value->CEDULA, $domingo, null);

                if (count($hist_regi_domg) > 0) {
                    foreach ($hist_regi_domg as $key => $val_domg) {
                        if ($val_domg->id_evaluacion == 1) {
                            $this->resumen_gd_domingo[$val_domg->cedula]   = ($val_domg->asistencia != 0) ? $val_domg->asistencia : null;
                        }
                    }
                }else{
                    $this->resumen_gd_domingo[$value->CEDULA]   = null;
                }
                
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
                ->whereMonth('fecha', date("m"))
                ->where('cedula', $value->CEDULA)
                ->groupBy('id_evaluacion', 'cedula')
                ->get();

                if (count($hist_regi) > 0) {
                    foreach ($hist_regi as $key => $value) {
                        if ($value->id_evaluacion == 1) {
                            $this->resumen_asistencia[$value->cedula]   = ($value->asistencia != 0) ? $value->asistencia : null;
                            $this->resumen_faltajust[$value->cedula]    = ($value->falta != 0) ? $value->falta : null;
                            $this->resumen_vacacion[$value->cedula]     = ($value->vacacion != 0) ? $value->vacacion : null;
                            $this->resumen_reposo[$value->cedula]       = ($value->reposo != 0) ? $value->reposo : null;
                            $this->resumen_permiso[$value->cedula]      = ($value->permiso != 0) ? $value->permiso : null;
                        }
                        if ($value->id_evaluacion == 2) {
                            $this->resumen_hx_diurna[$value->cedula]    = ($value->asistencia != 0) ? $value->asistencia : null;
                        }
                        if ($value->id_evaluacion == 3) {
                            $this->resumen_hx_nocturna[$value->cedula]  = ($value->asistencia != 0) ? $value->asistencia : null;
                        }
                        if ($value->id_evaluacion == 4) {
                            $this->bono_nocturno[$value->cedula]        = ($value->asistencia != 0) ? $value->asistencia : null;
                        }
                    }
                }else{
                    $this->resumen_asistencia[$value->CEDULA]   = null; 
                    $this->resumen_hx_diurna[$value->CEDULA]    = null; 
                    $this->resumen_hx_nocturna[$value->CEDULA]  = null; 
                    $this->bono_nocturno[$value->CEDULA]        = null;
                    $this->resumen_faltajust[$value->CEDULA]    = null;
                    $this->resumen_vacacion[$value->CEDULA]     = null;
                    $this->resumen_reposo[$value->CEDULA]       = null;
                    $this->resumen_permiso[$value->CEDULA]      = null;
                }
            }
            
            //CALCULO DE GUARDIAS TOTALES
            foreach ($this->resumen_gd_totales as $key => $gd_total) {
                $resultado = $this->resumen_gd_sabado[$key] + ($this->resumen_gd_domingo[$key]*2);
                $this->resumen_gd_totales[$key]   = ($resultado == 0) ? null : $resultado;
            }

            $this->comparar = $this->cant_trabaj;
            $this->old_resm_asistencia = $this->resumen_asistencia; 
            $this->old_resm_faltajust = $this->resumen_faltajust; 
            $this->old_resm_vacacion = $this->resumen_vacacion; 
            $this->old_resm_reposo = $this->resumen_reposo; 
            $this->old_resm_permiso = $this->resumen_permiso; 
            $this->old_resm_hx_diurna = $this->resumen_hx_diurna; 
            $this->old_resm_hx_nocturna = $this->resumen_hx_nocturna; 
            $this->old_bono_nocturno = $this->bono_nocturno;   
            $this->old_resm_gd_sabado = $this->resumen_gd_sabado;   
            $this->old_resm_gd_domingo = $this->resumen_gd_domingo;
            $this->old_resm_gd_totales = $this->resumen_gd_totales;
        }
        
        return view('livewire.registro.registro-component',
                [ 'oso' => $this->oso,
                'evaluaciones'  => $evaluaciones,
                'select'  => $select,
                ])->layout('layouts.contentLayoutMaster', compact('pageConfigs', 'breadcrumbs'));
    }
    
    public function updatedFecha()
    {
        $this->comparar = $this->cant_trabaj;
        if(date('l', strtotime($this->fecha)) == 'Saturday')//SABADO
        {
            $this->fin_semana = 'sabado';
            foreach ($this->resumen_gd_sabado as $key => $value) {
                $this->resumen_gd_sabado[$key] = ($this->asistencia_input[$key][1] == 1) ? $value+1 : $this->resumen_gd_sabado[$key];
                $this->resumen_gd_domingo[$key] = $this->old_resm_gd_domingo[$key];
            }
        }else if(date('l', strtotime($this->fecha)) == 'Sunday')//DOMINGO
        {
            $this->fin_semana = 'domingo';

            foreach ($this->resumen_gd_domingo as $key => $value) {
                $this->resumen_gd_domingo[$key] = ($this->asistencia_input[$key][1] == 1) ? $value+1 : $this->resumen_gd_domingo[$key];
                $this->resumen_gd_sabado[$key] = $this->old_resm_gd_sabado[$key];
            }
        }else{
            $this->resumen_gd_sabado = $this->old_resm_gd_sabado;
            $this->resumen_gd_domingo = $this->old_resm_gd_domingo;
        }
        $this->guardias_totales();

        if (date('d', strtotime($this->fecha)) == 15 || date('t', strtotime($this->fecha)) == date('d', strtotime($this->fecha))) {
            $this->hidden = '';
        }else{
            $this->hidden = 'hidden';
        }
    }

    public function updatedAsistencia()
    {
        $this->comparar = $this->cant_trabaj;
        $porciones = explode("_", $this->asistencia);
        
        if ($porciones[0] == '0') {//FALTA
            $this->resumen_asistencia[$porciones[1]] = $this->old_resm_asistencia[$porciones[1]];
            $this->resumen_faltajust[$porciones[1]] = $this->old_resm_faltajust[$porciones[1]];           
            $this->resumen_vacacion[$porciones[1]] = $this->old_resm_vacacion[$porciones[1]];
            $this->resumen_reposo[$porciones[1]] = $this->old_resm_reposo[$porciones[1]];
            $this->resumen_permiso[$porciones[1]] = $this->old_resm_permiso[$porciones[1]];
            $this->asistencia_input[$porciones[1]][1] = 0;

            $this->resumen_gd_sabado[$porciones[1]] = $this->old_resm_gd_sabado[$porciones[1]];
            $this->resumen_gd_domingo[$porciones[1]] = $this->old_resm_gd_domingo[$porciones[1]];
        }

        if ($porciones[0] == '1') {//ASISTIO
            $this->resumen_asistencia[$porciones[1]] = $this->resumen_asistencia[$porciones[1]]+1;
            $this->resumen_faltajust[$porciones[1]] = $this->old_resm_faltajust[$porciones[1]];           
            $this->resumen_vacacion[$porciones[1]] = $this->old_resm_vacacion[$porciones[1]];
            $this->resumen_reposo[$porciones[1]] = $this->old_resm_reposo[$porciones[1]];
            $this->resumen_permiso[$porciones[1]] = $this->old_resm_permiso[$porciones[1]];
            $this->asistencia_input[$porciones[1]][1] = 1;

            //FIN DE SEMANA
            if ($this->fin_semana == 'sabado') {
                $this->resumen_gd_sabado[$porciones[1]] = $this->resumen_gd_sabado[$porciones[1]]+1;                
            }elseif ($this->fin_semana == 'domingo') {
                $this->resumen_gd_domingo[$porciones[1]] = $this->resumen_gd_domingo[$porciones[1]]+1;
            }else {
                $this->resumen_gd_sabado[$porciones[1]] = $this->old_resm_gd_sabado[$porciones[1]];
                $this->resumen_gd_domingo[$porciones[1]] = $this->old_resm_gd_domingo[$porciones[1]];
            }

            $this->guardias_totales();
        }

        if ($porciones[0] == '5') {//FALTA NO JUSTIFICADA
            $this->resumen_faltajust[$porciones[1]] = $this->resumen_faltajust[$porciones[1]]+1;
            $this->resumen_asistencia[$porciones[1]] = $this->old_resm_asistencia[$porciones[1]];        
            $this->resumen_vacacion[$porciones[1]] = $this->old_resm_vacacion[$porciones[1]];
            $this->resumen_reposo[$porciones[1]] = $this->old_resm_reposo[$porciones[1]];
            $this->resumen_permiso[$porciones[1]] = $this->old_resm_permiso[$porciones[1]];
            $this->asistencia_input[$porciones[1]][1] = 'F';

            $this->resumen_gd_sabado[$porciones[1]] = $this->old_resm_gd_sabado[$porciones[1]];
            $this->resumen_gd_domingo[$porciones[1]] = $this->old_resm_gd_domingo[$porciones[1]];
        }
        if ($porciones[0] == '6') {//VACACION
            $this->resumen_vacacion[$porciones[1]] = $this->resumen_vacacion[$porciones[1]]+1;
            $this->resumen_asistencia[$porciones[1]] = $this->old_resm_asistencia[$porciones[1]];
            $this->resumen_faltajust[$porciones[1]] = $this->old_resm_faltajust[$porciones[1]];
            $this->resumen_reposo[$porciones[1]] = $this->old_resm_reposo[$porciones[1]];
            $this->resumen_permiso[$porciones[1]] = $this->old_resm_permiso[$porciones[1]];
            $this->asistencia_input[$porciones[1]][1] = 'V';

            $this->resumen_gd_sabado[$porciones[1]] = $this->old_resm_gd_sabado[$porciones[1]];
            $this->resumen_gd_domingo[$porciones[1]] = $this->old_resm_gd_domingo[$porciones[1]];
        }
        if ($porciones[0] == '7') {//REPOSO
            $this->resumen_reposo[$porciones[1]] = $this->resumen_reposo[$porciones[1]]+1;
            $this->resumen_asistencia[$porciones[1]] = $this->old_resm_asistencia[$porciones[1]];
            $this->resumen_faltajust[$porciones[1]] = $this->old_resm_faltajust[$porciones[1]];           
            $this->resumen_vacacion[$porciones[1]] = $this->old_resm_vacacion[$porciones[1]];
            $this->resumen_permiso[$porciones[1]] = $this->old_resm_permiso[$porciones[1]];
            $this->asistencia_input[$porciones[1]][1] = 'R';

            $this->resumen_gd_sabado[$porciones[1]] = $this->old_resm_gd_sabado[$porciones[1]];
            $this->resumen_gd_domingo[$porciones[1]] = $this->old_resm_gd_domingo[$porciones[1]];
        }
        if ($porciones[0] == '8') {//PERMISO            
            $this->resumen_permiso[$porciones[1]] = $this->resumen_permiso[$porciones[1]]+1;
            $this->resumen_asistencia[$porciones[1]] = $this->old_resm_asistencia[$porciones[1]];
            $this->resumen_faltajust[$porciones[1]] = $this->old_resm_faltajust[$porciones[1]];           
            $this->resumen_vacacion[$porciones[1]] = $this->old_resm_vacacion[$porciones[1]];
            $this->resumen_reposo[$porciones[1]] = $this->old_resm_reposo[$porciones[1]];
            $this->asistencia_input[$porciones[1]][1] = 'P';

            $this->resumen_gd_sabado[$porciones[1]] = $this->old_resm_gd_sabado[$porciones[1]];
            $this->resumen_gd_domingo[$porciones[1]] = $this->old_resm_gd_domingo[$porciones[1]];
        }
        $this->guardias_totales();
        $this->select_val[$porciones[1]] = $this->asistencia;
        $this->dispatchBrowserEvent('contentChanged');
    }

    public function evaluacion($cedula, $id_evaluacion)
    {
        $this->comparar = $this->cant_trabaj;
        
        if (isset($this->evaluacion[$cedula.$id_evaluacion])) {
            if ($id_evaluacion == '2') {

                $this->asistencia_input[$cedula][2] = $this->evaluacion[$cedula.$id_evaluacion];

                $this->resumen_hx_diurna[$cedula] = $this->evaluacion[$cedula.$id_evaluacion];
            }
            if ($id_evaluacion == '3') {
                $this->asistencia_input[$cedula][3] = $this->evaluacion[$cedula.$id_evaluacion];

                $this->resumen_hx_nocturna[$cedula] = $this->evaluacion[$cedula.$id_evaluacion];
            }
            if ($id_evaluacion == '4') {
                $this->asistencia_input[$cedula][4] = $this->evaluacion[$cedula.$id_evaluacion];

                $this->bono_nocturno[$cedula] = $this->evaluacion[$cedula.$id_evaluacion];
            }
        }
    }

    public function adicionales($cedula)
    {
        $this->comparar = $this->cant_trabaj;
        $this->adicionales_input[$cedula] = $this->adicionales[$cedula];
    }
    
    private function guardias_totales()
    {
        foreach ($this->resumen_gd_totales as $key => $value) {
            $resultado = $this->resumen_gd_sabado[$key] + ($this->resumen_gd_domingo[$key]*2);
            $this->resumen_gd_totales[$key]   = ($resultado == 0) ? null : $resultado;
        }
    }
    
    public function store($estado)
    {
        foreach ($this->adicionales_input as $key => $value) {
            $this->asistencia_input[$key][11] = ($value == null) ? 0 : $value ;
        }
        if (session('empresa') == null ) {
            return redirect()->to('/');
        }
        
        try {
            DB::beginTransaction();
        
            $registro_cab               = new RegistroCab();
            $registro_cab->id           =  Auth::user()->id;
            $registro_cab->fecha        = $this->fecha;
            $registro_cab->observacion  = $this->observacion;
            $registro_cab->id_estado    = $estado;
            $registro_cab->empresa      = session('empresa');
            $registro_cab->save();
            
            foreach ($this->cant_trabaj as $cedula => $value) {
                $registro_det                = new RegistroDet();
                $registro_det->id_registro   =  $registro_cab->id_registro;
                $registro_det->cedula        = $cedula;
                $registro_det->nombre        = $value;
                $registro_det->comentario    = $this->comentario[$cedula];
                $registro_det->save();
                
                foreach ($this->asistencia_input[$cedula] as $id_eval => $resul) {

                    $registro_sub                   = new RegistroSubdet();
                    $registro_sub->id_registro      = $registro_cab->id_registro;
                    $registro_sub->id_reg_det       = $registro_det->id_reg_det;
                    $registro_sub->id_evaluacion    = $id_eval;
                    $registro_sub->resultado        = $resul;
                    $registro_sub->save();
                }
            }

            $this->comparar = null;
            $this->reset(['observacion']);

            DB::commit();
            return redirect()->to('/historico/show/'.$registro_cab->id_registro.'');
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
}