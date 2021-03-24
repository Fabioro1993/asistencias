<?php

namespace App\Http\Livewire;

use App\Models\Oso;
use App\Models\Agrense;
use Livewire\Component;
use Illuminate\Http\Request;

class EmpresaComponent extends Component
{
    public function render(Request $request)
    {
        $oso = Oso::count();
        $agrense = Agrense::count();
        
        return view('livewire.empresa-component', compact('oso', 'agrense'));
    }

    public function empresa($empresa)
    {
        session(['empresa' => $empresa]);
        return redirect()->to('/');
    }
}
