<?php

namespace App\Http\Livewire;

use App\Models\RegistroCab;
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
    public $username, $new_name, $new_email, $estado, $rol, $permiso, $id_usuario,  $rol_input, $estado_input, $new_cedula;
    public $accion = 'ver';

    protected $listeners = ['editUser'];

    public function render()
    {
        $roles     = Rol::all();
        $dptos     = Nmdpto::dpto();
        $data      = User::with('estado', 'rol')->get();
        $estados   = Estado::whereIn('id_estado', [1, 2])->get();
        $ubicacion = Nmtrabajdor::Ubic()->groupby('UBICACION')->toArray();
      
        if ($this->accion == 'ver') {
            $this->permiso = null;
        }
        
        return view('livewire.administracion.users.user-registro-component', compact('data', 'roles', 'estados', 'ubicacion', 'dptos'));
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
                'new_cedula' => 'required',            
            ]);
            
            $users              = new User();        
            $users->name        = $this->new_name;
            $users->username    = strtolower($this->username);
            $users->email       = $this->new_email;
            $users->cedula      = $this->new_cedula;
            $users->id_estado   = $this->estado;
            $users->id_rol      = $this->rol;
            $users->guid        = $userldap->getConvertedGuid('objectguid');
            $users->domain      = 'default';
            $users->save();
    
            foreach ($this->permiso as $empresa => $dptos) {
                
                foreach ($dptos as $dept => $ubics) {
                    
                    foreach ($ubics as $ubic => $value) {
                    
                        $permiso                = new Permisos();
                        $permiso->id            = $users->id;
                        $permiso->empresa       = $empresa;
                        $permiso->gerencia      = $dept;
                        $permiso->ubicacion     = $ubic;
                        $permiso->save();
                    }
                }
            }
    
            $this->accion = 'ver';
            $this->reset(['estado', 'rol', 'new_email', 'username', 'new_name', 'permiso', 'new_cedula']); 
            $this->dispatchBrowserEvent('contentChanged'); //REINICIAR SELECT

            DB::commit();
            $this->emit('User');//ACTUALIZAR TABLA DE REGISTROS UserComponent
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function update()
    {
        try {
            DB::beginTransaction();
            $users = User::find($this->id_usuario);

            if ($this->new_cedula != null) {
                $users->cedula = $this->new_cedula;
            }

            if ($this->rol_input != null) {
                $users->id_rol = $this->rol_input;
            }

            if ($this->estado_input != null) {
                $users->id_estado = $this->estado_input;
            }
            
            foreach ($this->permiso as $empresa => $val) {
                
                foreach ($val as $dpto => $ubics) { 

                    foreach ($ubics as $ubic => $value) {
                        
                        $permiso = Permisos::where('id', $this->id_usuario)
                                        ->where('empresa', $empresa)
                                        ->where('gerencia', $dpto)
                                        ->where('ubicacion', $ubic)->first();
                        
                        if (isset($permiso)) {
                            if ($value == false) {
                                $permiso_delete = Permisos::find($permiso->id_permiso);
                                $permiso_delete->delete();
                            }
                        } else {
                            if ($value == true) {
                                $permiso_new                = new Permisos();
                                $permiso_new->id            = $users->id;
                                $permiso_new->empresa       = $empresa;
                                $permiso_new->gerencia      = $dpto;
                                $permiso_new->ubicacion     = $ubic;
                                $permiso_new->save();
                            }
                        }
                    }
                }
            }

            $users->save();
            
            DB::commit();
            $this->accion = 'ver';
            $this->reset(['estado', 'rol', 'new_email', 'username', 'new_name', 'new_cedula']); 
            $this->dispatchBrowserEvent('contentChanged'); //REINICIAR SELECT
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
    
    public function editUser(User $usuario)
    {
        $this->permiso = null;
        $permiso = Permisos::where('id', $usuario->id)->get();
        foreach ($permiso as $key => $value) {
            $this->permiso[$value->empresa][$value->gerencia][$value->ubicacion] = true;
        }
        
        $this->id_usuario   = $usuario->id;
        $this->new_name     = $usuario->name;
        $this->username     = $usuario->username;
        $this->new_email    = $usuario->email;
        $this->new_cedula   = $usuario->cedula;
        $this->rol          = $usuario->id_rol;
        $this->estado       = $usuario->id_estado;
        $this->accion       = 'update';
        
        $this->dispatchBrowserEvent('contentChanged');
    }

    public function cancelar()
    {
        $this->accion = 'ver';
        $this->reset(['estado', 'rol', 'new_email', 'username', 'new_name']); 
        $this->dispatchBrowserEvent('contentChanged'); //REINICIAR SELECT
    }
}
