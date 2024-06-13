<?php

namespace App\Livewire;

use App\Models\Email;
use App\Models\EmailList;
use App\Models\YourEmail;
use Illuminate\Support\Carbon;
use Livewire\WithPagination;
use App\Models\Transaction;
use Livewire\Component;
use DB;

class ReceiveAmount extends Component
{
    use WithPagination;

    public $Receive = 0, $search = '', $id = 1, $test = "ok", $from = '', $to = '';
    public function render()
    {

        // $totalreceive = Email::where('status', 'Received')->orderBy('updated_at', 'desc')->paginate(10);
        $query = Email::where('status', 'Received')->where('appId', $this->id);

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

    public function mount()
    {
        $this->reset();
        $data = DB::table('app_id')->first();
        $this->id = $data->appId;
        $this->calculateReceivedAMount();

    }
    public function calculateReceivedAMount()
    {
        $totalreceive = Email::where('status', 'Received')->where('appId', $this->id)->get();
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
