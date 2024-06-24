<?php

namespace App\Livewire;

use App\Models\Transaction;
use Livewire\WithPagination;
use App\Models\Email;
use Livewire\Component;
use DB;

class Transactions extends Component
{

    use WithPagination;

    public $amount = null, $subject = '', $transactionid = null, $id = '', $search = '';
    protected $rules = [
        'amount' => ['required', 'numeric'],
        'subject' => 'required'

    ];
    public function render()
    {
        $statuses = ['Cash Refunded', 'Completed', 'Received', 'Payment Refunded', 'Bitcoin Withdrawal'];
        $query = Email::whereIn('status', $statuses)->where('appId', $this->id);

        if ($this->search) {
            $query->where(function ($subquery) {
                $subquery->where('recipient', 'like', '%' . $this->search . '%')
                    ->orWhere('subject', 'like', '%' . $this->search . '%')
                    ->orWhere('payment_note', 'like', '%' . $this->search . '%');
            });
        }



        $data = $query->latest()->paginate(50);
        return view('livewire.transactions', compact('data'))->extends('layouts/master')->section('content');
    }
    public function mount()
    {
        $this->reset();
        $data = DB::table('app_id')->first();
        $this->id = $data->appId;
        // $this->calculteBitcionPurchsed();
    }
    public function edit($id)
    {

        $Singlemail = Email::where('id', $id)->first();
        $this->transactionid = $Singlemail->id;
        $this->subject = $Singlemail->subject;
        $this->amount = $this->number($Singlemail->amount);
        $this->dispatch('openModal');
    }
    public function updated($field)
    {
        $this->validateOnly($field, $this->rules);

    }
    public function update()
    {
        $this->dispatch('closeModal');
        $this->validate($this->rules);
        Email::where('id', $this->transactionid)->update([
            'amount' => $this->amount,
            'subject' => $this->subject
        ]);
        Transaction::where('email_id', $this->transactionid)->update([
            'amount' => $this->amount,
            'notes' => $this->subject
        ]);
        flash()->success('Record Updated');
        $this->reset();

    }
    public function delete($id)
    {
        Email::where('id', $id)->delete();
        flash()->success('Record Deleted');
    }
    public function number($data)
    {
        return floatval(preg_replace('/[^0-9.]/', '', $data));
    }
}
