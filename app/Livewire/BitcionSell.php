<?php

namespace App\Livewire;

use App\Models\Email;
use Carbon\Carbon;
use Livewire\WithPagination;
use Livewire\Component;

class BitcionSell extends Component
{
    use WithPagination;
    public $bitcionSell = 0, $BtcSell = 0, $search = '', $from = '', $to = '';
    public function render()
    {
        // $this->reset();
        // $data = Email::where('status', 'Completed')
        //     ->where('payment_note', 'Market Sell Order')
        //     ->orderBy('updated_at', 'desc')
        //     ->paginate(10);
        // foreach ($data as $total) {
        //     $amount = $this->number($total->amount);
        //     $this->BtcSell += (int) $amount;
        // }
        $query = Email::where('status', 'Completed')->where('payment_note', 'Market Sell Order');
        if ($this->from) {
            $from = Carbon::parse($this->from)->toDateString();
            // dd($from);
            $query->whereDate('created_at', '>=', $from);
        }
        if ($this->to) {

            $to = Carbon::parse($this->to)->toDateString();
            $query->whereDate('created_at', '<=', $to);
        }


        if ($this->search) {

            $query->where(function ($subquery) {
                $subquery->where('amount', 'like', '%' . $this->search . '%')
                    ->orWhere('subject', 'like', '%' . $this->search . '%')
                    ->orWhere('payment_note', 'like', '%' . $this->search . '%');
            });

            // dd($query->get());
        }
        $data = $query->paginate(10);
        return view('livewire.bitcion-sell', compact('data'))->extends('layouts/master')->section('content');
    }
    public function mount()
    {
        $BtcSell = Email::where('status', 'Completed')
            ->where('payment_note', 'Market Sell Order')
            ->select('amount')
            ->get();
        foreach ($BtcSell as $total) {
            $amount = $this->number($total->amount);
            $this->BtcSell += (int) $amount;
        }
    }
   

    public function number($data)
    {
        return floatval(preg_replace('/[^0-9.]/', '', $data));
    }
}
