<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Estado;

class EstadoComponent extends Component
{
    public $estado, $id_estado;
    public $accion = 'store';

    public function render()
    {
        $data = Estado::orderBy('id_estado', 'desc')->get();
        return view('livewire.estado-component', compact('data'));
    }

    public function store()
    {
        $this->validate([
            'estado' => 'required|unique:estados'
        ]);

        Estado::create([
            'estado' => $this->estado
        ]);
        $this->reset(['estado']);    
    }

    public function edit(Estado $estado)
    {
        $this->estado    = $estado->estado;
        $this->id_estado = $estado->id_estado;
        $this->accion    = 'update';
    }

    public function update()
    {
        $this->validate([
            'estado' => 'required|unique:estados'
        ]);
        
        $estado = Estado::find($this->id_estado);

        $estado->update([
            'estado' => $this->estado
        ]);
        $this->reset(['estado', 'id_estado']);  
        $this->accion    = 'store';  
    }    

    public function cancelar()
    {
        $this->reset(['estado']); 
        $this->accion = 'store';
    }

    public function delete(Estado $estado)
    {
        $estado->delete();
    }
}
