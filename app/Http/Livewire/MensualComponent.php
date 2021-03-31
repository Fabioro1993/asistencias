<?php

namespace App\Http\Livewire;

use App\Models\User;

use Livewire\Component;
use App\Models\RegistroCab;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MensualComponent extends Component
{
    public $mes, $anio, $mes_text;

    private $meses = array(
		1  => 'Enero',
		2  => 'Febrero',
		3  => 'Marzo',
		4  => 'Abril',
		5  => 'Mayo',
		6  => 'Junio',
		7  => 'Julio',
		8  => 'Agosto',
		9  => 'Septiembre',
		10 => 'Octubre',
		11 => 'Noviembre',
		12 => 'Diciembre'
    );

    public function mount($mes, $anio)
    {
        $this->mes = $mes;
        $this->anio = $anio;
        $this->mes_text = $this->meses[$mes];
    }

    public function render()
    {
        $breadcrumbs = [
            ['link'=>"/",'name'=>"Inicio"],
            ['link'=>"/historico",'name'=>"Historico"],
            ['link'=>"/historico/mensual/$this->mes/$this->anio",'name'=>"Mensual"]
        ];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true];
        
        $user = User::with('permiso')->find(Auth::user()->id);
        
        foreach ($user->permiso as $key => $value) { 
            $gerencia[] = $value->gerencia;
            $ubicacion[] = $value->ubicacion;
        }
        
        $data = RegistroCab::select(DB::raw('MONTH(fecha) mes, IF( DAY(fecha)<=15,"I","II") AS quincena'),'fecha', 'id_registro', 'id', 'observacion')
                            ->with(["registro_det" => function($a)  use ($gerencia, $ubicacion){
                                $a->whereIn('gerencia', $gerencia)->whereIn('ubicacion', $ubicacion);;
                            }])
                            ->whereMonth('fecha', $this->mes)
                            ->whereYear('fecha', $this->anio)->get();
                            
        return view('livewire.mensual-component', compact('data'))
                ->layout('layouts.contentLayoutMaster', compact('pageConfigs', 'breadcrumbs'));
    }
}
