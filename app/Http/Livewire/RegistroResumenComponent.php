<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\RegistroCab;
use Illuminate\Support\Facades\DB;

class RegistroResumenComponent extends Component
{
    public $id_registro;

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
    public $adicionales = array();

    public function mount($id_registro)
    {
        $this->id_registro = $id_registro;
    }
    
    public function render()
    {
        $registro_cab = RegistroCab::with('registro_det')->find($this->id_registro);

        $fecha_max = RegistroCab::fechaMax($registro_cab->fecha);

        $mes = date("m", strtotime($registro_cab->fecha));
        $anio = date("Y", strtotime($registro_cab->fecha));

        $fin_sem = RegistroCab::finSemana($registro_cab->fecha);
        
        foreach ($registro_cab->registro_det as $key => $value) {

            $this->resumen_gd_totales[$value->cedula]   = null;
            
            $hist_regi = RegistroCab::historico($fecha_max, $value->cedula);
            
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
                $this->adicionales[$value->CEDULA]          = null;
            }

            $hist_gd_adicional = RegistroCab::guardiaAdional($value->cedula, $mes);
            
            if (count($hist_gd_adicional) > 0) {
                foreach ($hist_gd_adicional as $key => $val_adic) {
                    if ($val_adic->id_evaluacion == 9) {
                        $this->adicionales[$value->cedula]  = ($val_adic->asistencia != 0) ? $val_adic->asistencia : null;
                    }
                }
            }else{
                $this->adicionales[$value->cedula]   = null;
            }

            $sabado = $fin_sem['sabado'];
            $resumen_sabado = RegistroCab::resumenFinSemana($value->cedula, $sabado, $fecha_max);
            $this->resumen_gd_sabado[$value->cedula] = ($resumen_sabado != 0) ? $resumen_sabado : null;

            $domingo = $fin_sem['domingo'];
            $resumen_domingo = RegistroCab::resumenFinSemana($value->cedula, $domingo, $fecha_max);
            $this->resumen_gd_domingo[$value->cedula] = ($resumen_domingo != 0) ? $resumen_domingo : null;
        }

        //CALCULO DE GUARDIAS TOTALES
        foreach ($this->resumen_gd_totales as $key => $gd_total) {
            $resultado = $this->resumen_gd_sabado[$key] + ($this->resumen_gd_domingo[$key]*2);
            $this->resumen_gd_totales[$key]   = ($resultado == 0) ? null : $resultado;
        }

        return view('livewire.registro.registro-resumen-component', compact('registro_cab', 'fecha_max'));
    }
}
