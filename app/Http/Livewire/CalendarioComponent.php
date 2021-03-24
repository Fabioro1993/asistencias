<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\RegistroCab;

class CalendarioComponent extends Component
{
    public function render()
    {
        //TODO
        //Agrupar por empresa y detallar los cargados
        $registros = RegistroCab::where('id_estado', 4)->get();
        return view('livewire.calendario-component', compact('registros'));
    }
}
