<?php

namespace App\Livewire;

use Livewire\WithPagination;
use App\Models\Transaction;
use Livewire\Attributes\On;
use Livewire\Component;


class Transactionlists extends Component
{
    public $search = '';
    use WithPagination;
    #[On('transactioncompleted')]
    public function render()
    {
        $query = Transaction::orderBy('updated_at', 'desc');
        if ($this->search) {

            $query->where(function ($subquery) {
                $subquery->where('amount', 'like', '%' . $this->search . '%')
                    ->orWhere('notes', 'like', '%' . $this->search . '%')
                    ->orWhere('transaction_type', 'like', '%' . $this->search . '%');
            });

            // dd($query->get());
        }
        $list = $query->paginate(10);
        return view('livewire.transactionlists', compact('list'));
    }
}
