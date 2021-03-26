<?php

namespace App\Http\Livewire;

use Livewire\Component;

class UserBase extends Component
{
    protected $listeners = ['PestActiv'];

    public function render()
    {
        return view('livewire.user-base');
    }

    public function PestActiv()
    {
        $this->dispatchBrowserEvent('contentChanged');
    }
}