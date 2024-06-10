<?php

namespace App\Livewire;

use App\Models\Email;
use Livewire\Attributes\On;
use Livewire\Component;

class TransactionWidgets extends Component
{
    public $totalAmount = 0, $cash = 0, $totalSent = 0, $cashRefunded = 0, $BtcPurchased = 0, $Btcamount = 0, $Fee = 0, $Btcsell = 0;

    #[On('transactioncompleted')]
    public function calculation()
    {
        $this->reset();

        $this->calculateCashRefunded();
        $this->calculateTotalReceived();
        $this->calculateTotalSent();
        $this->calculateBitcoinTransactions();

        $this->cash = $this->totalAmount - ($this->BtcPurchased + $this->totalSent);
        // Sent Refund
        // $totalrefund = Email::where('status', 'Cash Refunded')->select('amount')->get();
        // foreach ($totalrefund as $total) {
        //     $amount = $this->number($total->amount);
        //     $this->cashRefunded += (int) $amount;
        //     $this->totalAmount = $this->totalAmount + $this->cashRefunded;
        // }
        // // dump($this->totalAmount);


        // //  Receive
        // $totalreceive = Email::where('status', 'Received')->select('amount')->get();
        // foreach ($totalreceive as $total) {
        //     $amount = $this->number($total->amount);
        //     // dump($amount);
        //     $this->totalAmount += (int) $amount;
        // }
        // // dd($this->totalAmount);
        // // Sent amount   
        // $totalsent = Email::where('status', 'Completed')->where('payment_note', '!=', 'Market Purchase Order')->where('payment_note', '!=', 'Market Sell Order')->select('amount')->get();
        // foreach ($totalsent as $total) {
        //     $amount = $this->number($total->amount);
        //     $this->totalSent += (int) $amount;
        // }
        // //    Bitcion Purchased
        // $bitcionPurchased = Email::where('status', 'Completed')->where('payment_note', 'Market Purchase Order')->select('amount')->get();
        // foreach ($bitcionPurchased as $total) {
        //     $amount = $this->number($total->amount);
        //     $this->BtcPurchased += (int) $amount;
        // }
        // $bitcionSell = Email::where('status', 'Completed')->where('payment_note', 'Market Sell Order')->select('amount', 'sellorderBtc')->get();
        // foreach ($bitcionSell as $total) {
        //     $amount = $this->number($total->amount);
        //     $fee = json_decode($total->sellorderBtc);
        //     $this->Fee = $this->number($fee->Fee);
        //     $this->Btcsell += (int) $amount;
        //     $this->Btcamount = $this->Btcsell - $this->Fee;
        // }
        // $this->totalAmount = $this->totalAmount + $this->Btcamount;
        // $this->cash = $this->totalAmount - ($this->BtcPurchased + $this->totalSent);
    }

    public function render()
    {
        return view('livewire.transaction-widgets');
    }
    public function mount()
    {
        $this->calculation();
    }
    private function calculateCashRefunded()
    {
        $test = Email::get()->count();
        // dd($test);
        $totalrefund = Email::where('status', 'Cash Refunded')->select('amount')->get();
        foreach ($totalrefund as $total) {
            $amount = $this->number($total->amount);
            $this->cashRefunded += (int) $amount;
        }
        $this->totalAmount += $this->cashRefunded;
    }

    private function calculateTotalReceived()
    {
        $totalreceive = Email::where('status', 'Received')->select('amount')->get();
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
    }

    public function number($data)
    {
        return floatval(preg_replace('/[^0-9.]/', '', $data));
    }
}
