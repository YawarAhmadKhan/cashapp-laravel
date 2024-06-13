<?php

namespace App\Livewire;

use App\Models\Email;
use Carbon\Carbon;
use Livewire\WithPagination;
use Livewire\Component;
use DB;

class Refunds extends Component
{
    use WithPagination;
    public $cashRefunded = 0, $search = '', $id = '', $from, $to;
    public function render()
    {
        $query = Email::where('status', 'Cash Refunded')->where('appId', $this->id);
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



        return view('livewire.refunds', compact('data'))->extends('layouts/master')->section('content');
    }
    public function mount()
    {
        $this->reset();
        $data = DB::table('app_id')->first();
        $this->id = $data->appId;
        $this->calculateRefund();
    }
    public function calculateRefund()
    {
        $totalRefund = Email::where('status', 'Cash Refunded')->where('appId', $this->id)->get();
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
