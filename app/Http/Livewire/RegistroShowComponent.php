<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\RegistroCab;
use App\Models\Evaluacion;
use App\Models\Nmdpto;

class RegistroShowComponent extends Component
{
    public $data, $data_det, $dept;
    public $array = 'show';

    public function mount($id)
    {
        $this->data = RegistroCab::with(["registro_det" => function($a){
            $a->with(["registro_sub" => function($q){
                $q->where('id_evaluacion', '!=', 11);
            }]);
        }])->find($id);

    }

    public function render()
    {
        $breadcrumbs = [
            ['link'=>"/",'name'=>"Inicio"],['link'=>"/",'name'=>"Registro"]
        ];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true];

        $dptos = Nmdpto::all();
        foreach ($dptos as $key => $value) {
            $this->dept[$value->DEP_CODIGO] =  $value->DEP_DESCRI;
        }

        $data = $this->data;
        foreach ($data->registro_det as $key => $det) {
            $det['text_geren'] = $this->dept[$det->gerencia];
        }

        $evaluaciones = Evaluacion::whereIn('id_evaluacion', [1, 2, 3,4])->get();
        
        return view('livewire.registro.registro-show-component', compact('data', 'evaluaciones'))
            ->layout('layouts.contentLayoutMaster', compact('pageConfigs', 'breadcrumbs'));
    }
}
