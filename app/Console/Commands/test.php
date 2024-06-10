<?php

namespace App\Console\Commands;

use App\Models\Email;
use App\Models\Transaction;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use App\Jobs\ProcessEmailsJob;
use Illuminate\Http\Request;
use App\Http\Services\GmailServices;
use Google_Client;
use Google_Service_Gmail;
use Google\Client;
use Google\Service\Gmail;
use Google\Service\Drive;
use App\Models\User;
use App\Models\Token;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
class FetchEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fectching emails';
    
    /**
     * Execute the console command.
     */
    public function handle()
    {
      \Log::info('cron job');
       $client = new Google_Client();
        $clientSecretPath = public_path("client_secret_59776429822-k9dum4gna8bocf61j6otn841bapukla3.apps.googleusercontent.com(1).json");
        $redirect_uri = 'https://cashapp.trustechsol.co/token-php';
        $scopes = [
            Google_Service_Gmail::GMAIL_READONLY, // Adjust scopes as needed
        ];
        $client->setApplicationName('email');
        $client->setAuthConfig($clientSecretPath);
        $client->setAccessType("offline");
        $client->addScope($scopes);
        $state = bin2hex(random_bytes(16));
        $client->setState($state);
        Session::put('state', $state);
        $client->setLoginHint('trestonforbusiness1@gmail.com');
        // $this->client->setPrompt('consent');
        // dd($this->client);
        \Log::info('checking for cron working on background app');
        $client->setRedirectUri($redirect_uri);

        $auth_url = $client->createAuthUrl();
        // return redirect()->away(filter_var($auth_url, FILTER_SANITIZE_URL));
        $state = "69646eb4937eb258adce198f97c3c05e";
        $code = "4/0ATx3LY5-fSf_z8OqTZzEgWdjG1Wf0992lD-62ZO33fMWf5ppgRkF9fhnlb7zbvSnHKYbpw";
        $scope = "https://www.googleapis.com/auth/gmail.readonly";
        Session::put('state', $state);
        $client->setLoginHint('trestonforbusiness1@gmail.com');
        $client->setRedirectUri($redirect_uri);
        $auth_url = $client->createAuthUrl();
        $authCode = $code;





        $client->authenticate($code);
        $client->refreshToken($code);
        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($code);
            // Save the new access token and refresh token if it has changed
            $newAccessToken = $client->getAccessToken();
            // Save $newAccessToken['refresh_token'] if it exists and is different
        }
        $access_token = $newAccessToken;
        \Log::info('my token',[$access_token]);
        $mytoken = $access_token['access_token'];
        Session::put('access_token', $mytoken);
        
        // $service = new Google_Service_Gmail($client);

        $searchQuery = 'from:cash@square.com';
        $messages = [];
        $htmlBodies = [];
        $allHtmlBodies = [];
        $pageToken = null;
        
        do {
            $params = ['q' => $searchQuery, 'maxResults' => 50];
            if ($pageToken) {
                $params['pageToken'] = $pageToken;
            }
            // $service->users_messages->get('me', $mytoken);

            //  fetch messages
            if ($mytoken) {
                if ($client->isAccessTokenExpired()) {
                    $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                    session()->put('access_token', $client->getAccessToken());
                }
                \Log::info('token checked and not expired ');
\Log::info('service creating ');
                $service = new Google_Service_Gmail($client);
                \Log::info('created ');
                $searchQuery = 'from:cash@square.com';
                $messages = $service->users_messages->listUsersMessages('me', $params);
                // dd($messages);
                if ($messages->getMessages()) {
                    $currentHtmlBodies = [];
                    foreach ($messages->getMessages() as $message) {
                        $messageId = $message->getId();

                        $messageDetails = $service->users_messages->get('me', $messageId);
                        $messages[] = $messageDetails;
                        // Extract the HTML content
                        $htmlBody = $this->getHtmlBody($messageDetails);
                        $currentHtmlBodies[] = ['messageId' => $messageId, 'htmlBody' => $htmlBody];

                    }

                    // Append current batch to the final array
                    $allHtmlBodies = array_merge($allHtmlBodies, $currentHtmlBodies);
                    foreach ($currentHtmlBodies as $value) {

                        $EmailContent = $this->extractData($value['htmlBody']);

                        // dd('not ok');
                        $email = Email::updateOrCreate(
                            ['messageId' => $value['messageId']],
                            [
                                'messageId' => $value['messageId'],
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
                        // $this->emailDataParsed($currentHtmlBodies);
                        //   ProcessEmailsJob::dispatch($currentHtmlBodies);
                        // dump($currentHtmlBodies);
                    }
                } else {
                    dd('no msg');
                }
                $pageToken = $messages->getNextPageToken();
                // dump($pageToken);
            } else {
                return redirect($this->getAuthUrl());
            }


        } while ($pageToken);
 
        
        }

    public function accesstoken(Request $request)
{
    $client = new Google_Client();
    $clientSecretPath = public_path("client_secret_59776429822-k9dum4gna8bocf61j6otn841bapukla3.apps.googleusercontent.com(1).json");
    $redirect_uri = 'https://cashapp.trustechsol.co/token-php';
    $scopes = [
        Google_Service_Gmail::GMAIL_READONLY, // Adjust scopes as needed
    ];
        $client->setApplicationName('email');
        $client->setAuthConfig($clientSecretPath);
        $client->setAccessType("offline");
        $client->setPrompt('consent');
        $client->addScope($scopes);
        $state = bin2hex(random_bytes(16));
        $client->setState($state);
        Session::put('state', $state);
        $client->setLoginHint('trestonforbusiness1@gmail.com');
        $client->setRedirectUri($redirect_uri);
        $auth_url = $client->createAuthUrl();
         
         
        $authCode = $request->input('code');
  
        $client->authenticate($request->input('code'));
        $access_token = $client->getAccessToken();
        $mytoken =$access_token['access_token'];
        Session::put('access_token',$mytoken);
        $service = new Google_Service_Gmail($client);
        

        // Define search parameters
        $searchQuery = 'from:cash@square.com';
        $messages = [];
         $htmlBodies = [];
         $allHtmlBodies = [];
        $pageToken = null;
        do {
        $params = ['q' => $searchQuery, 'maxResults' => 50];
        if ($pageToken) {
            $params['pageToken'] = $pageToken;
        }
        // $service->users_messages->get('me', $mytoken);
        
    //  fetch messages
        if ($mytoken) {
            if ($client->isAccessTokenExpired()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                $request->session()->put('access_token', $client->getAccessToken());
            }

            $service = new Google_Service_Gmail($client);
            $searchQuery = 'from:cash@square.com';
            $messages = $service->users_messages->listUsersMessages('me', $params);
            // dd($messages);
            if ($messages->getMessages()) {
                $currentHtmlBodies = [];
             foreach ($messages->getMessages() as $message) {
                $messageId = $message->getId();
                
                $messageDetails = $service->users_messages->get('me', $messageId);
                $messages[] = $messageDetails;
                // Extract the HTML content
                $htmlBody = $this->getHtmlBody($messageDetails);
                $currentHtmlBodies[] = ['messageId' => $messageId, 'htmlBody' => $htmlBody];
             
            }
            
             // Append current batch to the final array
            $allHtmlBodies = array_merge($allHtmlBodies, $currentHtmlBodies);
            foreach ($currentHtmlBodies as $value) {
            
                $EmailContent = $this->extractData($value['htmlBody']);
    
                // dd('not ok');
                 $email = Email::updateOrCreate(
                    ['messageId' => $value['messageId']],
                    [
                        'messageId' => $value['messageId'],
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
            // $this->emailDataParsed($currentHtmlBodies);
    //   ProcessEmailsJob::dispatch($currentHtmlBodies);
            // dump($currentHtmlBodies);
        }}
        else{
            dd('no msg');
        }
        $pageToken =$messages->getNextPageToken();
        // dump($pageToken);
        } else {
            return redirect($this->getAuthUrl());
        }
        
        
    } while ($pageToken);
    dump($allHtmlBodies);
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
         // For the sender, look for the text "From" and get the adjacent value
        $identifier = $this->getNodeValue($crawler, '//div[contains(text(), "#")]');      
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
     public function extractAmount($string)
    {
        $pattern = '/\$\d+(?:,\d{3})*(?:\.\d{2})?/';
        preg_match($pattern, $string, $matches);
        return $matches[0] ?? null;
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
public function getHtmlBody($message)
{
    $payload = $message->getPayload();
    $body = '';
    $htmlBody = '';

    if ($payload->getParts()) {
        foreach ($payload->getParts() as $part) {
            if ($part->getMimeType() == 'text/plain') {
                $bodyPart = $part->getBody();
                $data = $bodyPart->getData();
                $body = base64_decode(str_replace(['-', '_'], ['+', '/'], $data));
            } elseif ($part->getMimeType() == 'text/html') {
                $bodyPart = $part->getBody();
                $data = $bodyPart->getData();
                $htmlBody = base64_decode(str_replace(['-', '_'], ['+', '/'], $data));
            }
        }
    } else {
        $bodyPart = $payload->getBody();
        $data = $bodyPart->getData();
        $body = base64_decode(str_replace(['-', '_'], ['+', '/'], $data));
    }

    return $htmlBody ?: $body;
}
}
