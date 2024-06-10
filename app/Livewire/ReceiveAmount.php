<?php

namespace App\Livewire;

use App\Models\Email;
use Illuminate\Support\Carbon;
use Livewire\WithPagination;
use App\Models\Transaction;
use Livewire\Component;
use DB;

class ReceiveAmount extends Component
{
    use WithPagination;

    public $Receive = 0, $search = '', $test = "ok", $from = '', $to = '';
    public function render()
    {

        // $totalreceive = Email::where('status', 'Received')->orderBy('updated_at', 'desc')->paginate(10);
        $query = Email::where('status', 'Received');

        if ($this->search) {
            $query->where(function ($subquery) {
                $subquery->where('date', 'like', '%' . $this->search . '%')
                    ->orWhere('payment_note', 'like', '%' . $this->search . '%');
            });
        }
        if ($this->from) {
            $from = Carbon::parse($this->from)->toDateString();
            // dd($from);
            $query->whereDate('created_at', '>=', $from);
        }
        if ($this->to) {

            $to = Carbon::parse($this->to)->toDateString();
            $query->whereDate('created_at', '<=', $to);
        }

        $totalreceive = $query->paginate(10);


        return view('livewire.receive-amount', compact('totalreceive'))->extends('layouts/master')->section('content');
    }
    // public function updated()
    // {

    // }
    public function mount()
    {
        // $today = Carbon::today()->toDateString(); // Gets today's date in 'Y-m-d' format

        // $this->to = $today;
        $totalreceive = Email::where('status', 'Received')->get();
        foreach ($totalreceive as $total) {
            $amount = $this->number($total->amount);
            $this->Receive += (int) $amount;
        }
    }
    public function number($data)
    {
        return floatval(preg_replace('/[^0-9.]/', '', $data));
    }
}
