<?php

namespace App\Livewire;

use App\Models\Email;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use DB;

class SentAmount extends Component
{
    use WithPagination;
    public $SentAmount = 0, $search = '', $id = '', $from, $to;
    public function render()
    {
        // $this->reset();
        $query = Email::where('status', 'Completed')->where('appId', $this->id)
            ->where('payment_note', '!=', 'Market Purchase Order')
            ->where('payment_note', '!=', 'Market Sell Order');

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
        $TotalSentTransactions = $query->paginate(10);
        return view('livewire.sent-amount', compact('TotalSentTransactions'))->extends('layouts/master')->section('content');
    }
    public function mount()
    {
        $this->reset();
        $data = DB::table('app_id')->first();
        $this->id = $data->appId;
        $this->calculateSendAMount();

    }
    public function calculateSendAMount()
    {
        $TotalSentTransactions = Email::where('status', 'Completed')->where('appId', $this->id)
            ->where('payment_note', '!=', 'Market Purchase Order')
            ->where('payment_note', '!=', 'Market Sell Order')->get();
        foreach ($TotalSentTransactions as $total) {
            $amount = $this->number($total->amount);
            $this->SentAmount += (int) $amount;
        }
    }

    public function number($data)
    {
        return floatval(preg_replace('/[^0-9.]/', '', $data));
    }
}
