<?php

namespace App\Livewire;

use Livewire\WithPagination;
use App\Models\Transaction;
use Livewire\Attributes\On;
use Livewire\Component;


class Transactionlists extends Component
{
    use WithPagination;
    #[On('transactioncompleted')]
    public function render()
    {
        $list = Transaction::orderBy('updated_at', 'desc')->paginate(10);
        return view('livewire.transactionlists', compact('list'));
    }
}
