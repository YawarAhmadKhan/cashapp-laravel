<?php

namespace App\Livewire;

use App\Models\Email;
use Livewire\WithPagination;
use Livewire\Component;

class Refunds extends Component
{
    use WithPagination;
    public $cashRefunded = 0, $search = '';
    public function render()
    {
        // dd($this->search);
        $query = Email::where('status', 'Cash Refunded');


        if ($this->search) {

            $query->where(function ($subquery) {
                $subquery->where('date', 'like', '%' . $this->search . '%')
                    ->orWhere('payment_note', 'like', '%' . $this->search . '%');
            });
            // dd($query->get());
        }

        $data = $query->orderBy('updated_at', 'desc')->latest()->paginate(10);
        // $data = Email::where('status', 'Cash Refunded')->latest()->paginate(10);

        return view('livewire.refunds', compact('data'))->extends('layouts/master')->section('content');
    }
    public function mount()
    {
        $totalRefund = Email::where('status', 'Cash Refunded')->get();
        foreach ($totalRefund as $total) {
            $amount = $this->number($total->amount);
            $this->cashRefunded += (int) $amount;
        }
    }
    public function number($data)
    {
        return floatval(preg_replace('/[^0-9.]/', '', $data));
    }
}
