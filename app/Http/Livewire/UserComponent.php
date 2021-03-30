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
       // dd('a');
        $data = User::with('estado', 'rol')->get();
        $roles = Rol::all();
        $estados = Estado::whereIn('id_estado', [1, 2])->get();

        $ubicacion = Nmtrabajdor::select('UBICACION')->where('UBICACION', '!=', null)->groupby('UBICACION')->get();

        $dptos = Nmdpto::all();
        
        return view('livewire.user-component', compact('data', 'roles', 'estados', 'ubicacion', 'dptos'));
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

     // public function buscar()
    // {
    //     $this->reset(['new_name', 'new_email', 'accion']);
    //     $users = ldapUS::where('samaccountname', $this->username)->first();

    //     $this->validate([
    //         'username' => ['required', new NoNull($users)]
    //     ]);
        
    //     $this->new_name         = $users->displayname[0];
    //     $this->new_email        = str_replace(".net", ".com", $users->userprincipalname[0]); 
    //     $this->accion    = 'store'; 
    // }

    // public function store()
    // {
    //     $userldap = ldapUS::where('samaccountname', $this->username)->first();

    //     $this->validate([
    //         'username' => ['required', new NoNull($userldap), 'unique:users'],
    //         'estado' => 'required',
    //         'rol' => 'required',            
    //     ]);
        
    //     $users              = new User();        
    //     $users->name        = $this->new_name;
    //     $users->username    = strtolower($this->username);
    //     $users->email       = $this->new_email;
    //     $users->id_estado   = $this->estado;
    //     $users->id_rol      = $this->rol;
    //     $users->guid        = $userldap->getConvertedGuid('objectguid');
    //     $users->domain      = 'default';
    //     $users->save();

    //     $this->accion = 'ver';
    //     $this->reset(['estado', 'rol', 'new_email', 'username', 'new_name']); 
    //     $this->dispatchBrowserEvent('contentChanged');
    // }
}
