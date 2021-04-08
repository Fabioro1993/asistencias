<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\RegistroCab;
use App\Models\Nmdpto;

class CalendarioComponent extends Component
{
    public $dept, $registro_modal = array(); 
    
    public function render()
    {
        $registros = RegistroCab::with(["registro_det" => function($a){
                        $a->select('id_registro', 'empresa')
                        ->groupby('empresa');
                    }])
                    ->where('id_estado', 4)
                    ->get();
        
        return view('livewire.calendario-component', compact('registros'));
    }

    public function modal($empresa, $fecha)
    {
        $dptos = Nmdpto::dpto();
        foreach ($dptos as $key => $value) {
            $this->dept[$value->empresa][$value->DEP_CODIGO] =  $value->DEP_DESCRI;
        }
        
        $this->registro_modal = RegistroCab::with(["registro_det" => function($a) use ($empresa){
                    $a->select('id_registro', 'gerencia', 'ubicacion','empresa')
                        ->groupby('empresa','gerencia', 'ubicacion')
                        ->where('empresa', $empresa);
                    }])
                    ->with('responsable')
                    ->where('fecha', date("Y-m-d", strtotime($fecha)))
                    ->where('id_estado', 4)
                    ->get();

        foreach ($this->registro_modal as $key => $value) {
            foreach ($value->registro_det as $key => $det) {
                $det['text_geren'] = $this->dept[$det->empresa][$det->gerencia];
            }
        }
        $this->dispatchBrowserEvent('contentChanged');  
    }
}
