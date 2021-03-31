<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Rol;

class RolComponent extends Component
{
    public $rol, $id_rol;
    public $accion = 'store';

    public function render()
    {
        $data = Rol::orderBy('id_rol', 'desc')->get();
        return view('livewire.administracion.rol-component', compact('data'));
    }

    public function store()
    {
        $this->validate([
            'rol' => 'required|unique:roles'
        ]);

        Rol::create([
            'rol' => $this->rol
        ]);
        $this->reset(['rol']);    
    }

    public function edit(Rol $rol)
    {
        $this->rol    = $rol->rol;
        $this->id_rol = $rol->id_rol;
        $this->accion = 'update';
    }

    public function update()
    {
        $this->validate([
            'rol' => 'required|unique:roles'
        ]);
        
        $rol = Rol::find($this->id_rol);

        $rol->update([
            'rol' => $this->rol
        ]);
        $this->reset(['rol', 'id_rol']);  
        $this->accion    = 'store';  
    }    

    public function cancelar()
    {
        $this->reset(['rol']); 
        $this->accion = 'store';
    }

    public function delete(Rol $rol)
    {
        $rol->delete();
    }
}
