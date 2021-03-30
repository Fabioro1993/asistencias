<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\RegistroCab;
use App\Models\Nmdpto;

class CalendarioComponent extends Component
{
    public $registro_modal = array(); 
    public $dept; 
    
    public function render()
    {
        //TODO
        //Agrupar por empresa y detallar los cargados
        //$registros = RegistroCab::with('registro_det')->where('id_estado', 4)->get();

        $dptos = Nmdpto::all();
        foreach ($dptos as $key => $value) {
            $this->dept[$value->DEP_CODIGO] =  $value->DEP_DESCRI;
        }
        
        $registros = RegistroCab::with(["registro_det" => function($a){
                        //$a->select('id_registro', 'empresa', 'gerencia', 'ubicacion')
                        $a->select('id_registro', 'empresa')
                        ->groupby('empresa');
                        //->groupby('empresa', 'gerencia', 'ubicacion');
                    }])
                    ->where('id_estado', 4)
                    ->get();

                    //$registro_modal = $registros;

        // foreach ($registros as $key => $value) {
        //     foreach ($value->registro_det as $key => $det) {
                
        //         $det['text_geren'] = $dept[$det->gerencia];
        //     }
        // }
        return view('livewire.calendario-component', compact('registros'));
    }

    public function modal($empresa, $fecha)
    {
        $this->registro_modal = RegistroCab::with(["registro_det" => function($a) use ($empresa){
                    $a->select('id_registro', 'gerencia', 'ubicacion')
                        ->groupby('gerencia', 'ubicacion')
                        ->where('empresa', $empresa);
                    }])
                    ->with('responsable')
                    ->where('fecha', date("Y-m-d", strtotime($fecha)))
                    ->where('id_estado', 4)
                    ->get();

        foreach ($this->registro_modal as $key => $value) {
            foreach ($value->registro_det as $key => $det) {
                
                $det['text_geren'] = $this->dept[$det->gerencia];
            }
        }
        //dd($this->registro_modal, $this->dept);
       $this->dispatchBrowserEvent('contentChanged');  
    }
}
