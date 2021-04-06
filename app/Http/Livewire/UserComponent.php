<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Rol;
use App\Models\Estado;
use Illuminate\Support\Facades\Auth;


use App\Models\Nmdpto;
use App\Models\Nmtrabajdor;
use App\Ldap\User as ldapUS;
use App\Rules\NoNull;

class UserComponent extends Component
{
    public $id_usuario, $name, $username, $email, $rol_id, $estado_id, $rol_input, $estado_input, $indicador, $new_name, $new_email, $estado, $rol;
    public $accion = 'ver';

    protected $listeners = ['User'];

    public function render()
    {
        $data = User::with('estado', 'rol')->get();
        return view('livewire.administracion.users.user-component', compact('data'));
    }
    
    //Enviar ID de usuario a Component UserRegistro newPost
    public function showPost($id)
    {
        $this->emit('editUser', $id); //USER REGISTRO
        //$this->emit('PestActiv');
    }

    public function showUser($id)
    {
        //$this->emit('editUser', $id); //USER REGISTRO
        $this->emit('PestActiv');
    }
    
    public function User()
    {
        //actualizar componente de lista de usuarios (este)
        $this->emit('PestActiv');
    }
}
