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
        
        $this->dept = Nmdpto::dptoArray();
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

        $resumen = RegistroCab::resumenNomina($this->id_reg);

        return view('livewire.nomina.nomina-resumen-component', compact('resumen'))
                ->layout('layouts.contentLayoutMaster', compact('pageConfigs', 'breadcrumbs'));
    }

    public function pdf($id)
    {
        $resumen = RegistroCab::resumenNomina($id);

        $dptos = Nmdpto::dptoArray();
        
        if (date("d", strtotime($resumen['fecha_max'])) <= '15') {
            $quincena = $this->meses[date("n", strtotime($resumen['fecha_max']))].' I';
        }else{
            $quincena = $this->meses[date("n", strtotime($resumen['fecha_max']))].' II';
        }
        
        $pdf = PDF::loadView('livewire.nomina.nominapdf', compact('resumen', 'dept', 'quincena'))->setPaper('a4', 'landscape');;
        return $pdf->stream(''.$quincena.'.pdf');
    }
}
