<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use App\Models\Nmdpto;
use Livewire\Component;
use App\Models\RegistroCab;
use Illuminate\Support\Facades\DB;

use Barryvdh\DomPDF\Facade as PDF;

class Historico extends Component
{
    public $meses = array(
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

    public $cedula, $nombre, $mes, $anio, $quincena, $mes_select;

    public function render()
    {
        $breadcrumbs = [
            ['link'=>"/",'name'=>"Inicio"],['link'=>"/historico",'name'=>"Historico"]
        ];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true];

        $data = RegistroCab::select(DB::raw('MONTH(fecha) mes'), DB::raw('YEAR(fecha) anio'))
                            //->where('empresa', session('empresa'))
                            ->groupby('mes', 'anio')->get();

        foreach ($data as $key => $value) {
            $value['text_mes'] = $this->meses[$value->mes];
        }                           

        return view('livewire.historico', compact('data'))->layout('layouts.contentLayoutMaster', compact('pageConfigs', 'breadcrumbs'));
    }

    public function modal($mes, $anio)
    {
        return redirect()->to('/historico/mensual/'.$mes.'/'.$anio.'');
    }

    public function resumen()
    {
        $this->validate([
            'cedula' => 'required',
            'mes_select' => 'required|in:1,2,3,4,5,6,7,8,9,10,11,12',
            'quincena' => 'required',
            'anio' => 'required',
            'quincena' => 'required'            
        ]);
        
        $mes = $this->mes_select;
        $anio = $this->anio;
        $cedula = $this->cedula;
        $quincena = $this->quincena;

        $data = RegistroCab::whereMonth('fecha', $mes)
                        ->whereYear('fecha', $anio);
                        if ($quincena == 'I') {
                            $data = $data->whereDay('fecha', '<=',15);
                        }else{
                            $data = $data->whereDay('fecha', '>',15);
                        }
        $data = $data
                ->whereHas('registro_det', function($q) use ($cedula){
                    $q->where('cedula',$cedula);
                })->with(['registro_det.registro_sub', 'registro_det'=>function ($a) use ($cedula){
                    $a->where('cedula', $cedula);
                }])->get();

        if (count($data) == 0) {
            $this->dispatchBrowserEvent('contentChanged');  
            
        }else{
            return redirect()->to('/historico/mensual/trabajador/'.$quincena.'/'.$mes.'/'.$anio.'/'.$cedula);
        }
    }

    public function pdf($quincena, $mes, $anio, $cedula)
    {
        $mes_text = $this->meses[$mes];

        $dept = Nmdpto::dptoArray();

        $data = RegistroCab::whereMonth('fecha', $mes)
                        ->whereYear('fecha', $anio);
                        if ($quincena == 'I') {
                            $data = $data->whereDay('fecha', '<=',15);
                        }else{
                            $data = $data->whereDay('fecha', '>',15);
                        }
                        $data = $data
                                ->whereHas('registro_det', function($q) use ($cedula){
                                    $q->where('cedula',$cedula);
                                })->with(['registro_det.registro_sub', 'registro_det'=>function ($a) use ($cedula){
                                    $a->where('cedula', $cedula);
                                }])->get();
                        
        $pdf = PDF::loadView('livewire.resumentrabajadorpdf', compact('data', 'quincena', 'dept', 'mes_text'))->setPaper('a4', 'landscape');
        return $pdf->stream(''.$quincena.'.pdf');
    }
}
