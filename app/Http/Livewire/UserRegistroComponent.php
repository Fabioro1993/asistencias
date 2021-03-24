<?php

namespace App\Http\Livewire;

use App\Models\Permisos;
use App\Models\Rol;
use App\Models\User;
use App\Rules\NoNull;
use App\Models\Nmdpto;
use App\Models\Estado;
use Livewire\Component;
use App\Models\Nmtrabajdor;
use App\Ldap\User as ldapUS;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserRegistroComponent extends Component
{
    //$id_usuario, $name, $email, $rol_id,  $estado_id,  $rol_input, $estado_input, $indicador, 
    public $username, $new_name, $new_email, $estado, $rol, $permiso;
    public $accion = 'ver';

    protected $listeners = ['newPost'];

    public function render()
    {
        $data = User::with('estado', 'rol')->get();
        $roles = Rol::all();
        $estados = Estado::whereIn('id_estado', [1, 2])->get();

        $ubicacion = Nmtrabajdor::select('UBICACION')->where('UBICACION', '!=', null)->groupby('UBICACION')->get();

        $dptos = Nmdpto::all();
        
        return view('livewire.user-registro-component', compact('data', 'roles', 'estados', 'ubicacion', 'dptos'));
    }

    public function buscar()
    {
        $this->reset(['new_name', 'new_email', 'accion']);
        $users = ldapUS::where('samaccountname', $this->username)->first();

        $this->validate([
            'username' => ['required', new NoNull($users)]
        ]);
        
        $this->new_name         = $users->displayname[0];
        $this->new_email        = str_replace(".net", ".com", $users->userprincipalname[0]); 
        $this->accion           = 'store'; 
    }

    public function store()
    {
        try {
            DB::beginTransaction();

            $userldap = ldapUS::where('samaccountname', $this->username)->first();

            $this->validate([
                'username' => ['required', new NoNull($userldap), 'unique:users'],
                'estado' => 'required',
                'rol' => 'required', 
                'permiso' => 'required',            
            ]);
        
            $users              = new User();        
            $users->name        = $this->new_name;
            $users->username    = strtolower($this->username);
            $users->email       = $this->new_email;
            $users->id_estado   = $this->estado;
            $users->id_rol      = $this->rol;
            $users->guid        = $userldap->getConvertedGuid('objectguid');
            $users->domain      = 'default';
            $users->save();
    
            foreach ($this->permiso as $dpto => $ubic) {
                
                foreach ($ubic as $key => $value) {
                    
                    $permiso                = new Permisos();
                    $permiso->id            = $users->id;
                    $permiso->gerencia      = $dpto;
                    $permiso->ubicacion     = $key;
                    $permiso->save();
                }
            }
    
            $this->accion = 'ver';
            $this->reset(['estado', 'rol', 'new_email', 'username', 'new_name']); 
            $this->dispatchBrowserEvent('contentChanged');

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

   
    public function newPost(User $usuario)
    {
        //$this->id_usuario   = $usuario->id;
        $this->new_name     = $usuario->name;
        $this->username     = $usuario->username;
        $this->new_email    = $usuario->email;
        $this->rol          = $usuario->id_rol;
        $this->estado       = $usuario->id_estado;
        $this->accion       = 'update'; 

        $this->dispatchBrowserEvent('contentChanged');
        
    }
}
