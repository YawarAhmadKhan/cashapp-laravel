<?php

namespace App\Livewire;

use App\Models\Email;
use Livewire\WithPagination;
use Livewire\Component;

class BitcionSell extends Component
{
    use WithPagination;
    public $bitcionSell = 0, $BtcSell = 0;
    public function render()
    {
        $this->reset();
        $data = Email::where('status', 'Completed')
            ->where('payment_note', 'Market Sell Order')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);
        foreach ($data as $total) {
            $amount = $this->number($total->amount);
            $this->BtcSell += (int) $amount;
        }

        return view('livewire.bitcion-sell', compact('data'))->extends('layouts/master')->section('content');
    }

    public function number($data)
    {
        return floatval(preg_replace('/[^0-9.]/', '', $data));
    }
}
