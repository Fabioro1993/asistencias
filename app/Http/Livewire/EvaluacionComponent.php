<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Estado;
use App\Models\Evaluacion;

class EvaluacionComponent extends Component
{
    public $evaluacion, $formato, $abrv, $max, $id_evaluacion;
    public $accion = 'store';
    public $hidden = 'hidden';

    public function render()
    {
        $data = Evaluacion::orderBy('id_evaluacion', 'desc')->get();
        return view('livewire.evaluacion-component', compact('data')); 
    }

    public function updatedFormato()
    {
        if ($this->formato == 'numerico') {
            $this->hidden = '';
        }else{
            $this->hidden = 'hidden';
        }
    }

    public function store()
    {
        $this->validate([
            'evaluacion' => 'required|unique:evaluaciones',
            'formato'    => 'required',
            'max'        => 'required_if:formato,numerico',          
        ]);
        
        if ($this->formato == 'texto') {
            $this->abrv = strtoupper($this->evaluacion[0]);
        }
        
        Evaluacion::create([
            'evaluacion' => ucwords($this->evaluacion),
            'formato'    => $this->formato,
            'abrv'       => $this->abrv,
            'max'        => $this->max
        ]);
        $this->hidden = 'hidden';
        $this->reset(['evaluacion', 'formato', 'abrv', 'max']);  
        $this->dispatchBrowserEvent('contentChanged');  
    }

    public function edit(Evaluacion $evalua)
    {
        $this->id_evaluacion  = $evalua->id_evaluacion;
        $this->evaluacion     = $evalua->evaluacion;
        $this->formato        = $evalua->formato;
        $this->max            = $evalua->max;
        $this->accion         = 'update';
        $this->hidden         = '';
        $this->dispatchBrowserEvent('contentChanged');
    }

    public function update()
    {
        $this->validate([
            'evaluacion' => 'required',
            'formato'    => 'required',
            'max'        => 'required_if:formato,numerico',          
        ]);

        if ($this->formato == 'texto') {
            $this->abrv = strtoupper($this->evaluacion[0]);
        }
        
        $pregunta = Evaluacion::find($this->id_evaluacion);
        $pregunta->evaluacion =  ucwords($this->evaluacion);
        $pregunta->formato    = $this->formato;
        $pregunta->abrv       = $this->abrv;
        $pregunta->max        = $this->max;
        $pregunta->save();

        $this->reset(['evaluacion', 'formato', 'abrv', 'max']);
        $this->accion = 'store'; 
        $this->hidden = 'hidden';
        $this->dispatchBrowserEvent('contentChanged');
    }    

    public function cancelar()
    {
        $this->reset(['evaluacion', 'formato', 'abrv', 'max']);
        $this->accion = 'store';
        $this->dispatchBrowserEvent('contentChanged');
    }

    public function delete(Evaluacion $evalua)
    {
        $evalua->delete();
    }
}
