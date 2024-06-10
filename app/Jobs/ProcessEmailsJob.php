<?php

namespace App\Jobs;

use App\Models\Email;
use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Symfony\Component\DomCrawler\Crawler;

class ProcessEmailsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $data;
    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->data[0] as $key => $value) {
            // dump($data[1][$key]);
            $EmailContent = $this->extractData($value);

            // $identifier = $EmailContent['identifier'] ?? uniqid();
            $email = Email::updateOrCreate(
                ['messageId' => $this->data[1][$key]],
                [
                    'messageId' => $this->data[1][$key],
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
        // $carbonDate = Carbon::parse($date)->format('Y-m-d');

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
    public function extractAmount($string)
    {
        $pattern = '/\$\d+(?:,\d{3})*(?:\.\d{2})?/';
        preg_match($pattern, $string, $matches);
        return $matches[0] ?? null;
    }

}
