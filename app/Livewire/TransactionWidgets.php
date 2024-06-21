<?php

namespace App\Livewire;

use App\Models\Email;
use App\Models\EmailList;
use Livewire\Attributes\On;
use Livewire\Component;
use DB;

class TransactionWidgets extends Component
{
    public $totalAmount = 0, $id = 1, $cash = 0, $requests = 0, $disputeAmounts = 0, $totalSent = 0, $cashRefunded = 0, $BtcPurchased = 0, $Btcamount = 0, $Fee = 0, $Btcsell = 0;




    public function render()
    {
        return view('livewire.transaction-widgets');
    }
    public function mount()
    {
        $this->reset();
        $selectedEmail = DB::table('app_id')->first();
        $this->id = $selectedEmail->appId;
        $this->calculation();
    }
    // this listener is fired from adminDash componenet when someone changed email
    protected $listeners = ['emailChanged'];
    public function emailChanged($data)
    {

        $this->reset();
        $this->id = $data['id'];
        $this->getSelectedEmailData();
    }
    // When all email data fetch from gmail this listener is  called calculation to render fresh data on transaction widgets
    #[On('transactioncompleted')]
    public function calculation()
    {
        $this->calculateCashRefunded();
        $this->calculateTotalReceived();
        $this->calculateTotalSent();
        $this->calculateBitcoinTransactions();
        $this->disputeCash();
        $this->Requests();
        $total = 0;
        $total = ($this->BtcPurchased + $this->totalSent);
        $this->cash = $this->totalAmount - $total;
        $this->cash += $this->cashRefunded + $this->Btcamount;
    }
    public function getSelectedEmailData()
    {
        $this->calculateCashRefunded();
        $this->calculateTotalReceived();
        $this->calculateTotalSent();
        $this->calculateBitcoinTransactions();
        $this->disputeCash();
        $this->Requests();
        $total = 0;
        $total = ($this->BtcPurchased + $this->totalSent);
        $this->cash = $this->totalAmount - $total;
        $this->cash += $this->cashRefunded + $this->Btcamount;
    }

    private function calculateCashRefunded()
    {
        $test = Email::get()->count();
        $totalrefund = Email::where('status', 'Cash Refunded')->where('appId', $this->id)->select('amount')->get();
        foreach ($totalrefund as $total) {
            $amount = $this->number($total->amount);
            $this->cashRefunded += (int) $amount;
        }
    }
    public function Requests()
    {
        $req = Email::where('status', 'Waiting for you to accept this request')->count();
        // dd($req);
    }
    public function disputeCash()
    {
        $dispute = Email::where('status', 'Payment Refunded')->where('appId', $this->id)->select('subject')->get();
        // dd($dispute);
        foreach ($dispute as $item) {
            if (preg_match('/\$(\d+(\.\d{1,2})?)/', $item->subject, $matches)) {
                $this->disputeAmounts += (float) $matches[1];
                $this->disputeAmounts;
            } else {
                $this->disputeAmounts = "Zero Disputes ";
            }
        }
    }

    private function calculateTotalReceived()
    {
        $totalreceive = Email::where('status', 'Received')->where('appId', $this->id)->select('amount')->get();
        foreach ($totalreceive as $total) {
            $amount = $this->number($total->amount);
            $this->totalAmount += (int) $amount;
        }
    }

    private function calculateTotalSent()
    {
        $totalsent = Email::where('status', 'Completed')
            ->where('payment_note', '!=', 'Market Purchase Order')
            ->where('payment_note', '!=', 'Market Sell Order')
            ->where('appId', $this->id)
            ->select('amount')
            ->get();
        foreach ($totalsent as $total) {
            $amount = $this->number($total->amount);
            $this->totalSent += (int) $amount;
        }
    }

    private function calculateBitcoinTransactions()
    {
        $this->calculateBitcoinPurchased();
        $this->calculateBitcoinSold();
        $this->totalAmount += $this->Btcamount;
    }

    private function calculateBitcoinPurchased()
    {
        $bitcionPurchased = Email::where('status', 'Completed')
            ->where('appId', $this->id)
            ->where('payment_note', 'Market Purchase Order')
            ->select('amount')
            ->get();
        foreach ($bitcionPurchased as $total) {
            $amount = $this->number($total->amount);
            $this->BtcPurchased += (int) $amount;
        }

    }

    private function calculateBitcoinSold()
    {
        $bitcionSell = Email::where('status', 'Completed')
            ->where('appId', $this->id)
            ->where('payment_note', 'Market Sell Order')
            ->select('amount', 'sellorderBtc')
            ->get();
        foreach ($bitcionSell as $total) {
            $amount = $this->number($total->amount);
            $fee = json_decode($total->sellorderBtc);
            $this->Fee = $this->number($fee->Fee);
            $this->Btcsell += (int) $amount;
            $this->Btcamount = $this->Btcsell - $this->Fee;
        }
        // $this->cash += $this->Btcamount;
    }

    public function number($data)
    {
        return floatval(preg_replace('/[^0-9.]/', '', $data));
    }
}
