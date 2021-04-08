<?php

namespace App\Http\Livewire;

use Livewire\Component;

class InicioComponent extends Component
{
    public function render()
    {
        $breadcrumbs = [
            ['link'=>"/",'name'=>"Inicio"],['link'=>"/",'name'=>"Calendario"]
        ];
        
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true];

        return view('livewire.inicio-component')->layout('layouts.contentLayoutMaster', compact('pageConfigs', 'breadcrumbs'));
    }
}
