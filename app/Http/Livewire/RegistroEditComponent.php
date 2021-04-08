<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\Nmdpto;
use Livewire\Component;
use App\Models\Evaluacion;
use App\Models\RegistroCab;
use App\Models\RegistroDet;
use App\Models\RegistroSubdet;
use App\Models\Nmtrabajdor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RegistroEditComponent extends Component
{
    public $data, $observacion, $select_val, $id_reg, $cedula_reg, $eval_reg, $asistencia, $resumen_edit, $fin_semana, $adicionales, $input_sel;
    public $hidden = 'hidden';
    public $recargar = null;
    public $dept; 

    public $comentario = array();
    public $evaluacion = array();
    public $adicionales_input = array();
    public $asistencia_input = array();
    public $cant_trabaj = array();
    public $empr_trabaj = array();    

    public function mount($id)
    {
        $this->id_reg  = $id;
        $this->data    = RegistroCab::with('registro_det.registro_sub.evalua')->find($id);
        $evaluaciones  = Evaluacion::whereIn('id_evaluacion', [1, 2, 3,4])->get();
        
        foreach ($this->data->registro_det as $key => $value) {
            
            $this->cedula_reg[] = $value->cedula;
            foreach ($evaluaciones as $key => $evaluacion) {
                $this->asistencia_input[$value->cedula][$evaluacion->id_evaluacion] = null;
            }
        }

        foreach ($this->data->registro_det[0]->registro_sub as $key => $value) {
            $this->eval_reg[] = $value->cedula;
        }

        if(date('l', strtotime($this->data->fecha)) == 'Saturday')//SABADO
        {
            $this->fin_semana = 'sabado';
        }else if(date('l', strtotime($this->data->fecha)) == 'Sunday')//DOMINGO
        {
            $this->fin_semana = 'domingo';
        }
    }

    public function render()
    {
        $breadcrumbs = [
            ['link'=>"/",'name'=>"Inicio"],
            ['link'=>"/historico",'name'=>"Registro"],
            ['link'=>"/historico/edit/$this->id_reg",'name'=>"Edicion"]
        ];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true];

        $dptos = Nmdpto::all();
        foreach ($dptos as $key => $value) {
            $this->dept[$value->DEP_CODIGO] =  $value->DEP_DESCRI;
        }

        $this->observacion  = $this->data->observacion;
        $evaluaciones       = Evaluacion::whereIn('id_evaluacion', [1, 2, 3,4])->get();
        $select             = Evaluacion::whereIn('id_evaluacion', [5,6,7,8])->get();
           
        //Guardias Adicionales
        if (date('d', strtotime($this->data->fecha)) == 15 || date('t', strtotime($this->data->fecha)) == date('d', strtotime($this->data->fecha))) {
            $this->hidden = '';
        }else{
            $this->hidden = 'hidden';
        }

        $oso = $this->data->registro_det;
        $user = User::with('permiso')->find(Auth::user()->id);
        foreach ($user->permiso as $key => $value) {
            $ubi[] = $value->empresa.'_'.$value->gerencia.'_'.$value->ubicacion;
        }
        
        $nmtrabajador = RegistroCab::trabajador()->whereIn('emp_dep', $ubi);

        foreach ($nmtrabajador as $key => $value) {
            $cedulas_nmtrab[] = $value->CEDULA;
            $nmtrab[$value->CEDULA] = $value->NOMBRE;
        }
        
        $resultado = array_diff($cedulas_nmtrab, $this->cedula_reg);

        if (count($resultado) >= 1) {
            $oso = $nmtrabajador;
        }
            
        if (!isset($this->recargar)) { 

            foreach ($this->data->registro_det as $key => $value) {
                
                $this->cant_trabaj[$value->cedula]   = $value->nombre;
                $this->empr_trabaj[$value->cedula][] = $value->empresa;
                $this->empr_trabaj[$value->cedula][] = $value->gerencia;
                $this->empr_trabaj[$value->cedula][] = $value->ubicacion;
                $this->comentario[$value->cedula]    = $value->comentario;
                $this->adicionales_input[$value->cedula] = null;
                
                foreach ($value->registro_sub as $key_sub => $val_sub) {
                    $this->evaluacion[$value->cedula][$val_sub->id_evaluacion] = ($val_sub->resultado != 0) ? $val_sub->resultado : null;
                    if ($val_sub->id_evaluacion == 1) {
                        $this->input_sel[$value->cedula] = $val_sub->resultado;
                        if ($val_sub->resultado === '0') {
                            $this->select_val[$value->cedula] = '0_'.$value->cedula;
                            $this->evaluacion[$value->cedula][1] = 0;
                        }elseif ($val_sub->resultado === '1') {
                            $this->select_val[$value->cedula] = '1_'.$value->cedula;
                            $this->evaluacion[$value->cedula][1] = 1;
                        }else {
                            $id_eva = Evaluacion::where('abrv', $val_sub->resultado)->first();    
                            $this->select_val[$value->cedula] = $id_eva->id_evaluacion.'_'.$value->cedula;
                            $this->evaluacion[$value->cedula][1] = $id_eva->id_evaluacion;
                        }
                    }
                }
            }
            //EXISTEN NUEVO TRABAJADOR
            if (count($resultado) >= 1) {

                foreach ($resultado as $key_resl => $val_resl) {

                    $new_trab = RegistroCab::trabajador()->whereIn('CEDULA', $val_resl)->first();
                    
                    $this->comentario[$val_resl] = null;
                    $this->adicionales_input[$val_resl] = null;
                    $this->cant_trabaj[$val_resl] = $nmtrab[$val_resl];

                    $this->empr_trabaj[$val_resl][] = $new_trab->empresa;
                    $this->empr_trabaj[$val_resl][] = $new_trab->depto;
                    $this->empr_trabaj[$val_resl][] = $new_trab->ubicacion;
                    
                    foreach ($evaluaciones as $key => $evaluacion) {
                        $this->asistencia_input[$val_resl][$evaluacion->id_evaluacion] = null;
                        $this->evaluacion[$val_resl][$evaluacion->id_evaluacion] = null;
                    }
                    
                    foreach ($this->eval_reg as $key => $value) {                       
                        $this->select_val[$val_resl] = '0_'.$val_resl;
                        $this->input_sel[$val_resl]  = null;
                    }
                }

                $resul = array_diff($nmtrab, $this->cant_trabaj);
                foreach ($resul as $key => $value) {
                    $this->cant_trabaj[$key] = $value;
                }
            }
            $this->resumen_edit = RegistroCab::resumenEdit($this->id_reg, $ubi);
        }

        foreach ($oso as $key => $value) {
            $value['descr'] = $this->dept[$value->gerencia];
        }

        return view('livewire.registro.registro-edit-component', compact('evaluaciones', 'select', 'oso'))
                ->layout('layouts.contentLayoutMaster', compact('pageConfigs', 'breadcrumbs'));
    }

    public function updatedAsistencia()
    {
        $this->recargar = 1;
        
        $porciones = explode("_", $this->asistencia);

        $porcion_select = explode("_", $this->select_val[$porciones[1]]);
        
        //Elimino
        if ($porcion_select[0] == '1') { // SI ES CERO NO ELIMINO NADA
            $this->resumen_edit['resumen_asistencia'][$porciones[1]] = $this->resumen_edit['resumen_asistencia'][$porciones[1]]-1;

            //FIN DE SEMANA
            if ($this->fin_semana == 'sabado') {
                $this->resumen_edit['resumen_gd_sabado'][$porciones[1]] = $this->resumen_edit['resumen_gd_sabado'][$porciones[1]]-1;
            }elseif ($this->fin_semana == 'domingo') {
                $this->resumen_edit['resumen_gd_domingo'][$porciones[1]] = $this->resumen_edit['resumen_gd_domingo'][$porciones[1]]-1;
            }
        }
        if ($porcion_select[0] == '5') { //FALTA NO JUSTIFICADA
            $this->resumen_edit['resumen_faltajust'][$porciones[1]] = $this->resumen_edit['resumen_faltajust'][$porciones[1]]-1;
        }
        if ($porcion_select[0] == '6') { //VACACION
            $this->resumen_edit['resumen_vacacion'][$porciones[1]] = $this->resumen_edit['resumen_vacacion'][$porciones[1]]-1;
        }
        if ($porcion_select[0] == '7') { //REPOSO
            $this->resumen_edit['resumen_reposo'][$porciones[1]] = $this->resumen_edit['resumen_reposo'][$porciones[1]]-1;
        }
        if ($porcion_select[0] == '8') { //PERMISO
            $this->resumen_edit['resumen_permiso'][$porciones[1]] = $this->resumen_edit['resumen_permiso'][$porciones[1]]-1;
        }
        
        //SUMO

        if ($porciones[0] == '0') { // SI ES CERO NO ELIMINO NADA pero guardo en el input
            $this->asistencia_input[$porciones[1]][1] = 0;
            $this->input_sel[$porciones[1]] = 0;
        }
        if ($porciones[0] == '1') { // SI ES CERO NO ELIMINO NADA
            $this->resumen_edit['resumen_asistencia'][$porciones[1]] = $this->resumen_edit['resumen_asistencia'][$porciones[1]]+1;
            $this->asistencia_input[$porciones[1]][1] = 1;
            $this->input_sel[$porciones[1]] = 1;

            //FIN DE SEMANA
            if ($this->fin_semana == 'sabado') {
                $this->resumen_edit['resumen_gd_sabado'][$porciones[1]] = $this->resumen_edit['resumen_gd_sabado'][$porciones[1]]+1;
            }elseif ($this->fin_semana == 'domingo') {
                $this->resumen_edit['resumen_gd_domingo'][$porciones[1]] = $this->resumen_edit['resumen_gd_domingo'][$porciones[1]]+1;
            }
        }
        if ($porciones[0] == '5') { //FALTA NO JUSTIFICADA
            $this->resumen_edit['resumen_faltajust'][$porciones[1]] = $this->resumen_edit['resumen_faltajust'][$porciones[1]]+1;
            $this->asistencia_input[$porciones[1]][1] = 'F';
            $this->input_sel[$porciones[1]] = 'F';
        }
        if ($porciones[0] == '6') { //VACACION
            $this->resumen_edit['resumen_vacacion'][$porciones[1]] = $this->resumen_edit['resumen_vacacion'][$porciones[1]]+1;
            $this->asistencia_input[$porciones[1]][1] = 'V';
            $this->input_sel[$porciones[1]] = 'V';
        }
        if ($porciones[0] == '7') { //REPOSO
            $this->resumen_edit['resumen_reposo'][$porciones[1]] = $this->resumen_edit['resumen_reposo'][$porciones[1]]+1;
            $this->asistencia_input[$porciones[1]][1] = 'R';
            $this->input_sel[$porciones[1]] = 'R';
        }
        if ($porciones[0] == '8') { //PERMISO
            $this->resumen_edit['resumen_permiso'][$porciones[1]] = $this->resumen_edit['resumen_permiso'][$porciones[1]]+1;
            $this->asistencia_input[$porciones[1]][1] = 'P';
            $this->input_sel[$porciones[1]] = 'P';
        }
        
        $this->select_val[$porciones[1]] = $this->asistencia;
        $this->dispatchBrowserEvent('contentChanged');
        $this->guardias_totales();        
    }

    private function guardias_totales()
    {
        foreach ($this->resumen_edit['resumen_gd_totales'] as $key => $value) {
            $resultado = $this->resumen_edit['resumen_gd_sabado'][$key] + ($this->resumen_edit['resumen_gd_domingo'][$key]*2);
            $this->resumen_edit['resumen_gd_totales'][$key]   = ($resultado == 0) ? null : $resultado;
        }
    }

    public function evaluacion($cedula, $id_evaluacion)
    {
        $reg_is = RegistroCab::with(["registro_det" => function($a) use ($cedula, $id_evaluacion){
            $a->where('cedula', '=', $cedula)
                ->with(["registro_sub" => function($q) use ($id_evaluacion){
                    $q->where('id_evaluacion', '=', $id_evaluacion);
                }]);
            }])->find($this->id_reg);
        
        $this->recargar = 1;
        
        if ($id_evaluacion == '2') {
            if (is_numeric($this->evaluacion[$cedula][$id_evaluacion])) {
                //Elimino
                $this->resumen_edit['resumen_hx_diurna'][$cedula] = $this->resumen_edit['resumen_hx_diurna'][$cedula] - $reg_is->registro_det[0]->registro_sub[0]->resultado;

                //Sumo
                $this->resumen_edit['resumen_hx_diurna'][$cedula] = $this->resumen_edit['resumen_hx_diurna'][$cedula] + $this->evaluacion[$cedula][$id_evaluacion];
            }
        }
        if ($id_evaluacion == '3') {
            if (is_numeric($this->evaluacion[$cedula][$id_evaluacion])) {
                //Elimino
                $this->resumen_edit['resumen_hx_nocturna'][$cedula] = $this->resumen_edit['resumen_hx_nocturna'][$cedula] - $reg_is->registro_det[0]->registro_sub[0]->resultado;

                //Sumo
                $this->resumen_edit['resumen_hx_nocturna'][$cedula] = $this->resumen_edit['resumen_hx_nocturna'][$cedula] + $this->evaluacion[$cedula][$id_evaluacion];
            }
        }
        if ($id_evaluacion == '4') {
            if (is_numeric($this->evaluacion[$cedula][$id_evaluacion])) {
                //Elimino
                $this->resumen_edit['bono_nocturno'][$cedula] = $this->resumen_edit['bono_nocturno'][$cedula] - $reg_is->registro_det[0]->registro_sub[0]->resultado;

                //Sumo
                $this->resumen_edit['bono_nocturno'][$cedula] = $this->resumen_edit['bono_nocturno'][$cedula] + $this->evaluacion[$cedula][$id_evaluacion];
            }
        }
    }

    public function adicionales($cedula)
    {
        $this->recargar = 1;
        $this->adicionales_input[$cedula] = $this->adicionales[$cedula];
    }

    public function update($estado)
    {
        $select = $this->input_sel;
        $evaluacion = $this->evaluacion;
        foreach ($this->adicionales_input as $key => $value) {
            $evaluacion[$key][1] = ($select[$key] == null) ? 0 : $select[$key];
            $evaluacion[$key][9] = ($value == null) ? 0 : $value ;
        }
        
        try {
            DB::beginTransaction();
        
            $registro_cab               = RegistroCab::with('registro_det.registro_sub.evalua')->find($this->id_reg);
            $registro_cab->id           = Auth::user()->id;
            $registro_cab->observacion  = $this->observacion;
            $registro_cab->id_estado    = $estado;
            $registro_cab->save();
            
            foreach ($this->cant_trabaj as $cedula => $value) {
                
                $det = RegistroDet::where('id_registro',$this->id_reg)->where('cedula',$cedula)->first();
                
                if (isset($det)) {
                    $registro_det                =RegistroDet::find($det->id_reg_det);
                }else{
                    $registro_det                = new RegistroDet();
                }
                $registro_det->id_registro   = $this->id_reg;
                $registro_det->cedula        = $cedula;
                $registro_det->nombre        = $value;
                $registro_det->comentario    = $this->comentario[$cedula];
                $registro_det->empresa       = $this->empr_trabaj[$cedula][0];
                $registro_det->gerencia      = $this->empr_trabaj[$cedula][1];
                $registro_det->ubicacion     = $this->empr_trabaj[$cedula][2];
                $registro_det->save();
                
                foreach ($evaluacion[$cedula] as $id_eval => $resul) {

                    $sub = RegistroSubdet::where('id_registro',$this->id_reg)->where('id_reg_det',$registro_det->id_reg_det)
                    ->where('id_evaluacion',$id_eval)->first();
                
                    if (isset($sub)) {
                        $registro_sub                = RegistroSubdet::find($sub->id_reg_sub);
                    }else{
                        $registro_sub                = new RegistroSubdet();
                    }

                    $registro_sub->id_registro      = $this->id_reg;
                    $registro_sub->id_reg_det       = $registro_det->id_reg_det;
                    $registro_sub->id_evaluacion    = $id_eval;
                    $registro_sub->resultado        = ($resul == null) ? 0 : $resul;
                    $registro_sub->save();
                }
            }

            DB::commit();
            return redirect()->to('/historico/show/'.$this->id_reg.'');
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

}
