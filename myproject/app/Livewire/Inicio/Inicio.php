<?php

namespace App\Livewire\Inicio;

use Livewire\Component;

class Inicio extends Component
{
    public function render()
    {
        return view('livewire.inicio.inicio')->layout('layouts.app');
    }
}
