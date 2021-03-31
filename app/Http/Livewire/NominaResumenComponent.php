<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\Nmdpto;
use Livewire\Component;
use App\Models\RegistroCab;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Barryvdh\DomPDF\Facade as PDF;

class NominaResumenComponent extends Component
{
    public $quincena, $mes, $anio, $id_reg, $dept;

    public function mount($quincena, $mes, $anio)
    {
        $this->quincena = $quincena;
        $this->mes      = $mes;
        $this->anio     = $anio;
        
        $this->id_reg =  RegistroCab::select(DB::raw('MONTH(fecha) mes, IF( DAY(fecha)<=15,"I","II") AS quincena'),'fecha', 'id_registro')
                            ->whereMonth('fecha', $this->mes)
                            ->whereYear('fecha', $this->anio);
                            
                            if ($quincena == 'I') {
                                $this->id_reg = $this->id_reg->whereDay('fecha', '<=',15)->get()->last()->id_registro;
                            }else{
                                $this->id_reg = $this->id_reg->whereDay('fecha', '>',15)->get()->last()->id_registro;
                            }
        
        $dptos = Nmdpto::all();
        foreach ($dptos as $key => $value) {
            $this->dept[$value->DEP_CODIGO] =  $value->DEP_DESCRI;
        }
    }

    public function render()
    {
        $breadcrumbs = [
            ['link'=>"/",'name'=>"Inicio"],
            ['link'=>"/nomina",'name'=>"Nomina"],
            ['link'=>"/nomina/resumen/$this->quincena/$this->mes/$this->anio",'name'=>"Resumen Nomina"],
        ];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true];

        $user = User::with('permiso')->find(Auth::user()->id);
        foreach ($user->permiso as $key => $value) {            
            $gerencia[] = $value->gerencia;
            $ubicacion[] = $value->ubicacion;
        }

        $resumen = RegistroCab::resumenNomina($this->id_reg, $gerencia, $ubicacion);
        //dd($resumen['resumen_gd_totales']);
        return view('livewire.nomina.nomina-resumen-component', compact('resumen'))
                ->layout('layouts.contentLayoutMaster', compact('pageConfigs', 'breadcrumbs'));
    }

    public function pdf($id)
    {
        $user = User::with('permiso')->find(Auth::user()->id);
        foreach ($user->permiso as $key => $value) {            
            $gerencia[] = $value->gerencia;
            $ubicacion[] = $value->ubicacion;
        }

        $resumen = RegistroCab::resumenNomina($id, $gerencia, $ubicacion);

        $dptos = Nmdpto::all();
        foreach ($dptos as $key => $value) {
            $dept[$value->DEP_CODIGO] =  $value->DEP_DESCRI;
        }
        
        $pdf = PDF::loadView('livewire.nomina.nominapdf', compact('resumen', 'dept'));
        return $pdf->download('invoice.pdf');
    }
}
