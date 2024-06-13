<?php

namespace App\Livewire;

use App\Models\EmailList;
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
        // Waiting for you to accept this request.
        // Payment Failed
        $list = $this->transactionList();
        return view('livewire.transactionlists', compact('list'));
    }
    public function mount()
    {
        $this->reset();
        $data = EmailList::first();
        $this->id = $data->id;
        // $this->transactionList();
    }
    protected $listeners = ['emailupdated'];
    public function emailupdated($data)
    {

        $this->reset();
        $this->id = $data['id'];
        $this->transactionList();
        // $this->secondApp();
    }
    public function transactionList()
    {
        $statuses = ['Cash Refunded', 'Completed', 'Received', 'Payment Refunded', 'Bitcoin Withdrawal'];
        $query = Transaction::whereIn('status', $statuses)->where('appId', $this->id)->orderBy('updated_at', 'desc');
        if ($this->search) {
            $query->where(function ($subquery) {
                $subquery->where('amount', 'like', '%' . $this->search . '%')
                    ->orWhere('notes', 'like', '%' . $this->search . '%')
                    ->orWhere('transaction_type', 'like', '%' . $this->search . '%');
            });
        }
        $list = $query->paginate(10);
        return $list;
    }
}
