<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Administracion extends Component
{
    public function render()
    {
        $breadcrumbs = [
            ['link'=>"/",'name'=>"Inicio"],['link'=>"/administracion",'name'=>"Administración"],
        ];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true];

        return view('livewire.administracion.administracion')->layout('layouts.contentLayoutMaster', compact('pageConfigs', 'breadcrumbs'));
    }
}
