<?php

namespace App\Livewire;

use App\Models\Email;
use App\Models\Token;
use App\Models\Transaction;
use Symfony\Component\DomCrawler\Crawler;
use Livewire\Component;
use Carbon\Carbon;

class AdminDash extends Component
{
    public $loader = '', $token = '';
    protected $listeners = ['emailDataParsed' => 'handleEmailDataParsed'];

    public function render()
    {
        // return redirect()->to(filter_var('https://accounts.google.com/o/oauth2/v2/auth?response_type=code&access_type=offline&client_id=59056185248-kh21c4v4n5fs7pirmvt9htj5a0fs8rn7.apps.googleusercontent.com&redirect_uri=http%3A%2F%2Flocalhost&state=0d91397d794db1e528a59321786af245&scope=https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fgmail.readonly&login_hint=trestonforbusiness1%40gmail.com&prompt=consent', FILTER_SANITIZE_URL));
        $data = Token::first();
        $this->token = json_decode($data->token);
        return view('livewire.admin-dash', )->extends('layouts/master')->section('content');

    }
    // ProcessEmailsJob::dispatch($data);
    public function emailDataParsed($data)
    {
        // return redirect()->to(filter_var('https://accounts.google.com/o/oauth2/v2/auth?response_type=code&access_type=offline&client_id=59056185248-kh21c4v4n5fs7pirmvt9htj5a0fs8rn7.apps.googleusercontent.com&redirect_uri=http%3A%2F%2Flocalhost&state=0d91397d794db1e528a59321786af245&scope=https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fgmail.readonly&login_hint=trestonforbusiness1%40gmail.com&prompt=consent', FILTER_SANITIZE_URL));
        // dd($data);
        foreach ($data[0] as $key => $value) {
            // dump($data[1][$key]);
            $EmailContent = $this->extractData($value);

            // $identifier = $EmailContent['identifier'] ?? uniqid();
            $email = Email::updateOrCreate(
                ['messageId' => $data[1][$key]],
                [
                    'messageId' => $data[1][$key],
                    'recipient' => $EmailContent['recipient'],
                    'amount' => $EmailContent['amount'],
                    'payment_note' => $EmailContent['payment_note'],
                    'identifier' => $EmailContent['identifier'],
                    'status' => $EmailContent['status'],
                    'from' => $EmailContent['from'],
                    'source' => $EmailContent['Source'],
                    'destination' => $EmailContent['Destination'],
                    'to' => $EmailContent['To'],
                    // 'dispute_title' => $EmailContent['dispute_title'],
                    // 'dispute_message' => $EmailContent['dispute_message'],
                    'sellorderBtc' => $EmailContent['sellorderBtc'],
                    'refundnote' => $EmailContent['refund-note'],
                    'refundamount' => $EmailContent['refund-amount'],
                    // 'app' => $EmailContent['app'],
                    'subject' => $EmailContent['subject'],
                    'date' => $EmailContent['date']
                ]
            );
            Transaction::updateOrcreate(
                ['email_id' => $email->id],

                [
                    'email_id' => $email->id,
                    'transaction_type' => $EmailContent['payment_note'],
                    'amount' => $EmailContent['amount'] ?? 0,
                    'status' => $EmailContent['status'] ?? 'N/A',
                    'notes' => $EmailContent['subject'] ?? 'N/A',
                    'btcdetails' => $email->sellorderBtc ?? ' '
                ]
            );
        }
        // dd('check');

        $this->dispatch('transactioncompleted');
        flash()->success('Emails Operation completed successfully.');

        return response()->json('win');
    }
    public function token($object)
    {
        // dd($object);
        Token::updateOrCreate(
            ['token' => json_encode($object)],
            ['token' => json_encode($object)]
        );
    }
    // public function emailDataParsed($data)
    // {
    //     for ($i = 0; $i < min(10, count($data[0])); $i++) {
    //         \Log::info('Email Data:', ['index' => $i, 'data' => $data[0][$i]]);
    //         \Log::info('Email ID:', ['index' => $i, 'id' => $data[1][$i]]);
    //     }
    //     $emailData = $data[0];
    //     $emailIds = $data[1];

    //     // Define the chunk size
    //     $chunkSize = 100;

    //     // Process the data in chunks
    //     foreach (array_chunk($emailData, $chunkSize, true) as $chunkIndex => $chunk) {
    //         foreach ($chunk as $key => $value) {
    //             $EmailContent = $this->extractData($value);
    //             $messageId = $emailIds[$key];

    //             // Perform database operations within a transaction
    //             DB::transaction(function () use ($EmailContent, $messageId) {
    //                 $email = Email::updateOrCreate(
    //                     ['messageId' => $messageId],
    //                     [
    //                         'messageId' => $messageId,
    //                         'recipient' => $EmailContent['recipient'] ?? null,
    //                         'amount' => $EmailContent['amount'] ?? 0,
    //                         'payment_note' => $EmailContent['payment_note'] ?? null,
    //                         'identifier' => $EmailContent['identifier'] ?? uniqid(),
    //                         'status' => $EmailContent['status'] ?? 'N/A',
    //                         'from' => $EmailContent['from'] ?? null,
    //                         'source' => $EmailContent['Source'] ?? null,
    //                         'destination' => $EmailContent['Destination'] ?? null,
    //                         'to' => $EmailContent['To'] ?? null,
    //                         'sellorderBtc' => $EmailContent['sellorderBtc'] ?? null,
    //                         'refundnote' => $EmailContent['refund-note'] ?? null,
    //                         'refundamount' => $EmailContent['refund-amount'] ?? 0,
    //                         'subject' => $EmailContent['subject'] ?? null,
    //                         'date' => $EmailContent['date'] ?? null,
    //                     ]
    //                 );

    //                 Transaction::updateOrCreate(
    //                     ['email_id' => $email->id],
    //                     [
    //                         'email_id' => $email->id,
    //                         'transaction_type' => $EmailContent['payment_note'] ?? null,
    //                         'amount' => $EmailContent['amount'] ?? 0,
    //                         'status' => $EmailContent['status'] ?? 'N/A',
    //                         'notes' => $EmailContent['subject'] ?? 'N/A',
    //                         'btcdetails' => $EmailContent['sellorderBtc'] ?? ' '
    //                     ]
    //                 );
    //             });
    //         }
    //         // Free memory explicitly after each chunk
    //         unset($chunk);
    //     }

    //     $this->dispatch('transactioncompleted');
    //     flash()->success('Emails Operation completed successfully.');
    // }
    public function emailNotFound()
    {
        toastr()->error('Zero Transactions Found.');
        return;
    }
    public function bitcoinData($emailContent)
    {

        $crawler = new Crawler($emailContent);

        // Extract the required data
        $recipient = $this->getNodeValue($crawler, '//td/div[contains(text(), "Bitcoin")]', 'Bitcoin');
        $amount = $this->getNodeValue($crawler, '//td/span[contains(@style, "font-size:65px")]', '$0.00');
        $paymentNote = $this->getNodeValue($crawler, '//td/div[contains(text(), "Market Sell Order")]', 'No Payment Note');
        $identifier = ''; // Assuming identifier needs custom logic to extract
        $status = $this->getNodeValue($crawler, '//td/div[contains(text(), "Completed") or contains(text(), "Received") or contains(text(), "Cash Refunded")]', 'No Status');
        $from = $this->getNodeValue($crawler, '//td/div[contains(text(), "May 5 at 6:35 PM")]', 'No Date');
        $bitcoinAmount = $this->getNodeValue($crawler, '//td/div[contains(text(), "Bitcoin Amount")]/../td[2]', '0 BTC');
        $exchangeRate = $this->getNodeValue($crawler, '//td/div[contains(text(), "Exchange Rate")]/../td[2]', '$0.00');
        $totalSaleAmount = $this->getNodeValue($crawler, '//td/div[contains(text(), "Total Sale Amount")]/../td[2]', '$0.00');
        $fee = $this->getNodeValue($crawler, '//td/div[contains(text(), "Fee")]/../td[2]', '$0.00');
        $total = $this->getNodeValue($crawler, '//td/div[contains(text(), "Total")]/../td[2]', '$0.00');

        return [
            'recipient' => $recipient,
            'amount' => $amount,
            'payment_note' => $paymentNote,
            'identifier' => $identifier,
            'status' => $status,
            'from' => $from,
            'bitcoinAmount' => $bitcoinAmount,
            'exchangeRate' => $exchangeRate,
            'totalSaleAmount' => $totalSaleAmount,
            'fee' => $fee,
            'total' => $total
        ];

    }
    public function extractAmount($string)
    {
        $pattern = '/\$\d+(?:,\d{3})*(?:\.\d{2})?/';
        preg_match($pattern, $string, $matches);
        return $matches[0] ?? null;
    }
    private function extractData($emailContent)
    {
        // Create a new Crawler instance and load the email content
        $crawler = new Crawler($emailContent);
        $status = $this->getNodeValue($crawler, '//td[@align="center" and contains(@class, "title")]/div');

        // Extract the main message about the dispute
        $message = $this->getNodeValue($crawler, '//td[@align="center" and contains(@class, "secondary")]/div');

        $title = $this->getNodeValue($crawler, '//div[contains(@class, "profile-name") and contains(@style, "font-size: 18px")]');

        // Extract the amount $2,500.00
        $amount = $this->getNodeValue($crawler, '//td[@align="center" and contains(@class, "amount-text")]/span');

        // Extract the main message about the cash out status
        $statusMessage = $this->getNodeValue($crawler, '//td[@align="center" and contains(@class, "title")]/div');

        // Extract the additional message "The funds have been returned to your Cash App"
        $addon = $this->getNodeValue($crawler, '//td[@align="center" and contains(@class, "secondary")]/div');
        $paymentNote = $this->getNodeValue($crawler, '//td[@class="profile-description"]/div/span');


        $source = $this->getNodeValue($crawler, '//td/div[contains(text(), "Source")]/../following-sibling::td[1]/div');
        $source2 = $this->getNodeValue($crawler, '//td/div[contains(text(), "Destination")]/../following-sibling::td[1]/div');
        $to = $this->getNodeValue($crawler, '//td/div[contains(text(), "To")]/../following-sibling::td[1]/div');





        // dump($refundedAmount);
        // dump($description);
        // dump($status);

        // Use XPath to extract the necessary data
        // $recipient = $this->getNodeValue($crawler, '//div[contains(@style, "overflow:hidden;display:inline-block;font-size:18px;font-weight:500;line-height:24px;letter-spacing:0.2px;color:#333;font-family:-apple-system,BlinkMacSystemFont,Helvetica Neue,Helvetica,Arial,sans-serif;vertical-align:middle")]');
        // dump($recipient);
        // $amount = $this->getNodeValue($crawler, '//td[@align="center"]/span');

        // $paymentNote = $this->getNodeValue($crawler, '//td[contains(@style, "font-size:16px")]/div');
        // $status = $this->getNodeValue($crawler, '//td[contains(@style, "font-size:16px")]/div[contains(text(), "Completed") or contains(text(), "Received") or contains(text(), "Cash Refunded")]');

        $identifier = $this->getNodeValue($crawler, '//div[contains(text(), "#")]');       // For the sender, look for the text "From" and get the adjacent value
        $from = $this->getNodeValue($crawler, '//td/div[contains(text(), "From")]/../following-sibling::td[1]/div');
        // $date = $this->getNodeValue($crawler, '//div[contains(@class, "gmail_attr")]/text()[contains(., "Date:")]');
        $dateString = $this->getNodeValue($crawler, '//div[contains(@class, "gmail_attr") and .//b[contains(text(), "Cash App")]]/text()[contains(., "Date:")]');
        $subjectraw = $this->getNodeValue($crawler, '//div[contains(@class, "gmail_attr")]/text()[contains(., "Subject:")]');
        $subject = str_replace('Subject: Fwd:', '', $subjectraw);

        // $image = $this->getNodeValue($crawler, '//img', 'src');
        $refundNote = $this->getNodeValue($crawler, '//td[contains(@style, "color:#999;font-family:-apple-system,BlinkMacSystemFont,Helvetica Neue,Helvetica,Arial,sans-serif;font-size:16px;line-height:24px;font-weight:400")]/div');
        $refundamount = $this->extractAmount($refundNote);
        // $app = $this->getNodeValue($crawler, '//b[contains(@class, "Cash")]');
        $emailfrom = $this->getNodeValue($crawler, '//div[contains(@class, "gmail_attr") and .//b[contains(text(), "Cash App")]]//a[contains(@href, "mailto:")]/@href');
        // $app = str_replace('mailto:', '', $emailfrom);
        $dateString = str_replace('Date:', '', $dateString);
        $date = strstr($dateString, ' at', true);
        $carbonDate = Carbon::parse($date)->format('Y-m-d');

        // bitcion//
        $bitcoinAmount = $this->getNodeValue($crawler, '//td[contains(div/text(), "Bitcoin Amount")]/following-sibling::td/div');
        $exchangeRate = $this->getNodeValue($crawler, '//td[contains(div/text(), "Exchange Rate")]/following-sibling::td/div');
        $totalSaleAmount = $this->getNodeValue($crawler, '//td[contains(div/text(), "Total Sale Amount")]/following-sibling::td/div');
        $fee = $this->getNodeValue($crawler, '//td[contains(div/text(), "Fee")]/following-sibling::td/div');
        $total = $this->getNodeValue($crawler, '//td[contains(div/text(), "Total")]/following-sibling::td/div');
        if ($bitcoinAmount && $exchangeRate && $fee != null) {
            $data = [
                "BitcoinAmount" => $bitcoinAmount,
                "ExchangeRate" => $exchangeRate,
                "TotalSaleAmount" => $totalSaleAmount,
                "Fee" => $fee,
                "total" => $total
            ];
        } else {
            $data = '';
        }

        $sellorderBtc = json_encode($data);
        return [
            'recipient' => $title,
            'amount' => $amount,
            'payment_note' => $paymentNote,
            'identifier' => $identifier,
            'status' => $status,
            // 'dispute_title' => $title,
            // 'dispute_message' => $message,
            'from' => $from,
            'Source' => $source,
            'To' => $to,
            'Destination' => $source2,
            'refund-note' => $statusMessage,
            'refund-amount' => $amount,
            'subject' => ($subject ? $subject : $message),
            'date' => ($title === "Market Sell Order" || $title === "Market Purchase Order") ? $addon : null,
            'sellorderBtc' => $sellorderBtc
        ];
    }

    private function getNodeValue($crawler, $xpath)
    {

        $node = $crawler->filterXPath($xpath)->first();
        if ($node->count() > 0) {
            return $node->text();
        } else {
            return null;
        }
    }
}
