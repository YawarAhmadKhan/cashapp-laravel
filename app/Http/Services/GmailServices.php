<?php
namespace App\Http\Services;


use Google_Client;
use Google_Service_Gmail;
use Illuminate\Http\Request;
use Google\Client;
use Google\Service\Gmail;
use Google\Service\Drive;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;



class GmailServices
{
    public $client, $clientSecretPath, $scopes;
    public function __construct()
    {
        $this->clientSecretPath = public_path("client_secret_59776429822-k9dum4gna8bocf61j6otn841bapukla3.apps.googleusercontent.com(2).json");

        $this->scopes = [
            Google_Service_Gmail::GMAIL_READONLY, // Adjust scopes as needed
        ];
        // $this->client = new Google_Client();
    }

    public function getClient()
    {

        $this->client = new Google_Client();
        $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php';
        $scopes = 'https://www.googleapis.com/auth/gmail.addons.current.message.readonly';
        //  $this->client->setClientId('59056185248-kh21c4v4n5fs7pirmvt9htj5a0fs8rn7.apps.googleusercontent.com');
        //  $this->client->setClientSecret('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');
        $this->client->setApplicationName('upwork');
        $this->client->setAuthConfig($this->clientSecretPath);
        $this->client->setAccessType('offline');
        $this->client->setRedirectUri($redirect_uri);

        $this->client->addScope($this->scopes);
        // $client->addScope($this-scopes);

        $this->client->setLoginHint('trestonforbusiness1@gmail.com');
        $this->client->setPrompt('consent');
        // $this->client->setIncludeGrantedScopes(true);
        $auth_url = $this->client->createAuthUrl();
        return redirect()->away(filter_var($auth_url, FILTER_SANITIZE_URL));
        // Handle callback from Google
        $this->client->authenticate($_GET['code']);
        $access_token = $this->client->getAccessToken();
        dd($access_token);


        dd('nothing work');

        // dd($this->client);


        //   $this->client->setScope($scopes);

        //  return
        dd('gone and verified');

        //   dd(l);
        // $client->fetchAccessTokenWithAssertion();

        // Get the Gmail service
        $service = new Google_Service_Gmail($this->client);


        // Define search parameters
        $searchQuery = 'from:cash@square.com';
        $messages = [];
        $pageToken = null;
        do {
            $params = ['q' => $searchQuery, 'maxResults' => 10];
            if ($pageToken) {
                $params['pageToken'] = $pageToken;
            }
            dd($service->users_messages->get('me', $params));

            $response = $service->users_messages->listMessages('me', $params);
            if ($response->getMessages()) {
                $messages = array_merge($messages, $response->getMessages());
                dump($messages);
            } else {
                dd('no msg');
            }
            $pageToken = $response->getNextPageToken();
        } while ($pageToken);


        // $messages = $service->users_messages->listMessages('me', ['q' => $searchQuery]);
        // dd($messages);
        // foreach ($messages->getMessages() as $message) {
        //     $messageId = $message->getId();
        //     $message = $service->users_messages->get('me', $messageId);

        //     // Process the message as needed
        //     // For example, you can access the message content using $message->getSnippet() or $message->getPayload()
        // }


        // Initialize the Google client
        // $client->setAuthConfig($this->clientSecretPath);
        // $client->addScope(Gmail::GMAIL_READONLY);
        // $client->setAccessType('offline');

        // // Get authorization code from redirect URL
        // if (isset($_GET['code'])) {
        //     $client->authenticate($_GET['code']);
        //     $_SESSION['access_token'] = $client->getAccessToken();
        //     header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
        //     return;
        // }

        // // Check if access token is available in the session
        // if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
        //     $client->setAccessToken($_SESSION['access_token']);

        //     // Refresh the access token if it's expired
        //     if ($client->isAccessTokenExpired()) {
        //         $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        //         $_SESSION['access_token'] = $client->getAccessToken();
        //     }

        //     // Create Gmail service
        //     $service = new Gmail($client);

        //     // Example: List users messages
        //     $results = $service->users_messages->listUsers('me');

        //     // Process $results as needed
        // } else {
        //     // If not authenticated, redirect to authorization URL
        //     $authUrl = $client->createAuthUrl();
        //     header('Location: ' . $authUrl);
        //     return;
        // }
    }
    public function getAuthUrl()
    {

        $client = $this->client();  // Get the client
        dd($client);
        $authUrl = $client->createAuthUrl();  // Create the auth URL
        dd($authUrl);
        dd('memory exceeded');
    }

    public function authenticate(Request $request)
    {
        $client = $this->getClient();

        if ($request->has('code')) {
            $client->authenticate($request->input('code'));
            $accessToken = $client->getAccessToken();
            $request->session()->put('access_token', $accessToken);
        }
    }
    public function listMessages(Request $request)
    {

        $client = $this->getClient();
        $accessToken = $request->session()->get('access_token');

        if ($accessToken) {
            $client->setAccessToken($accessToken);

            if ($client->isAccessTokenExpired()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                $request->session()->put('access_token', $client->getAccessToken());
            }

            $service = new Google_Service_Gmail($client);
            $searchQuery = 'from:cash@square.com';
            $messages = $service->users_messages->listUsersMessages('me', ['q' => $searchQuery]);

            return $messages->getMessages();
        } else {
            return redirect($this->getAuthUrl());
        }
    }
}