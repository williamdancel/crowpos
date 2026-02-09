<?php

namespace App\Livewire\Pos;

use App\Models\Sale;
use Livewire\Component;

class Receipt extends Component
{
    public Sale $sale;

    public function mount(Sale $sale): void
    {
        // Eager load items for display
        $this->sale = $sale->load('items');
    }

    public function render()
    {
        return view('livewire.pos.receipt');
    }
}
