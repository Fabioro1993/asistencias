<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\RegistroCab;
use App\Models\Evaluacion;


class RegistroShowComponent extends Component
{
    public $data, $data_det;
    public $array = 'show';

    public function mount($id)
    {
        //$this->data = RegistroCab::with('registro_det.registro_sub')->find($id);

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

        $data = $this->data;
        $evaluaciones = Evaluacion::whereIn('id_evaluacion', [1, 2, 3,4])->get();

       //dd($this->data);
        
        return view('livewire.registro.registro-show-component', compact('data', 'evaluaciones'))
            ->layout('layouts.contentLayoutMaster', compact('pageConfigs', 'breadcrumbs'));
    }
}
