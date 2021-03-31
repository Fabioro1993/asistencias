<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\RegistroCab;
use Illuminate\Support\Facades\DB;

class NominaComponent extends Component
{
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

    public function render()
    {
        $breadcrumbs = [
            ['link'=>"/",'name'=>"Inicio"],['link'=>"/nomina",'name'=>"Nomina"],
        ];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true];
        
        $data = RegistroCab::select(DB::raw('MONTH(fecha) mes, IF( DAY(fecha)<=15,"I","II") AS quincena'),
                             DB::raw('YEAR(fecha) anio'))
                            ->groupby('quincena','mes', 'anio')->get();

        foreach ($data as $key => $value) {
            $value['text_mes'] = $this->meses[$value->mes];
        }

        return view('livewire.nomina.nomina-component', compact('data'))
            ->layout('layouts.contentLayoutMaster', compact('pageConfigs', 'breadcrumbs'));
    }
}
