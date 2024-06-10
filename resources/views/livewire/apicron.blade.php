<div>
   

<script>

    /* exported gapiLoaded */
    const CLIENT_ID = '{{ config('app.google.client_id') }}';
    const API_KEY = '{{ config('app.google.api_key') }}';
    // Discovery doc URL for APIs used by the quickstart
    const DISCOVERY_DOC = 'https://www.googleapis.com/discovery/v1/apis/gmail/v1/rest';
    
    // Authorization scopes required by the API; multiple scopes can be
    // included, separated by spaces.
    const SCOPES = 'https://www.googleapis.com/auth/gmail.readonly';
    
    let tokenClient;
    let gapiInited = false;
    let gisInited = false;
    let emailData = [];
    
    document.getElementById('authorize_button').style.visibility = 'visible';
    document.getElementById('signout_button').style.visibility = 'hidden';
    document.getElementById('refresh_button').style.visibility = 'hidden';
    document.getElementById('save_button').style.visibility = 'hidden';
    
    /**
     * Callback after api.js is loaded.
     */
    function gapiLoaded() {
        gapi.load('client', initializeGapiClient);
        handleAuthClick();
    }
    
    /**
     * Callback after the API client is loaded. Loads the
     * discovery doc to initialize the API.
     */
    async function initializeGapiClient() {
        await gapi.client.init({
            apiKey: API_KEY,
            discoveryDocs: [DISCOVERY_DOC],
        });
        gapiInited = true;
        maybeEnableButtons();
    }
    
    /**
     * Callback after Google Identity Services are loaded.
     */
    function gisLoaded() {
        tokenClient = google.accounts.oauth2.initTokenClient({
            client_id: CLIENT_ID,
            scope: SCOPES,
            callback: '', // defined later
        });
        console.log('mr token',tokenClient);
        // @this.token(tokenClient);
        console.log('ye cheeeeeeze',{$token})
        gisInited = true;
        maybeEnableButtons();
    }
    
    /**
     * Enables user interaction after all libraries are loaded.
     */
    function maybeEnableButtons() {
        if (gapiInited && gisInited) {
            document.getElementById('authorize_button').style.visibility = 'visible';
        }
    }
    
    /**
     *  Sign in the user upon button click.
     */
    function handleAuthClick() {
      
        tokenClient.callback = async (resp) => {
            if (resp.error !== undefined) {
                throw (resp);
            }
            document.getElementById('signout_button').style.visibility = 'visible';
            document.getElementById('refresh_button').style.visibility = 'visible';
            document.getElementById('save_button').style.visibility = 'visible';
            document.getElementById('authorize_button').innerText = 'Refresh Token';
            await listMessages();
            document.getElementById('saving-loader').style.display = 'none';
            document.getElementById('loading-wrapper').style.display = 'none';
        };
        const email = 'trestonforbusiness1@gmail.com';
        if (gapi.client.getToken() === null) {
            // Prompt the user to select a Google Account and ask for consent to share their data
            // when establishing a new session.
        tokenClient.requestAccessToken({prompt: '',login_hint: email});
         
            
        } else {
            // Skip display of account chooser and consent dialog for an existing session.
         tokenClient.requestAccessToken({prompt: ''});
     
        }
    }
    
    /**
     *  Sign out the user upon button click.
     */
    function handleSignoutClick() {
        const token = gapi.client.getToken();
        if (token !== null) {
            google.accounts.oauth2.revoke(token.access_token);
            gapi.client.setToken('');
            document.getElementById('content').innerText = '';
            document.getElementById('authorize_button').innerText = 'Authorize';
            document.getElementById('signout_button').style.visibility = 'hidden';
            document.getElementById('refresh_button').style.visibility = 'hidden';
            document.getElementById('save_button').style.visibility = 'hidden';
        }
    }
    
    async function listMessages() {
      console.log('list msges called----');
    const filterEmail = 'cash@square.com';
    let emailData = [];
    let emailId = [];
    var start = moment().subtract(7, 'days');
    var end = moment();
    
    function cb(start, end) {
    $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }
    
    $('#reportrange').daterangepicker({
    startDate: start,
    endDate: end,
    ranges: {
       'Today': [moment(), moment()],
       'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
       'Last 7 Days': [moment().subtract(6, 'days'), moment()],
       'Last 30 Days': [moment().subtract(29, 'days'), moment()],
       'This Month': [moment().startOf('month'), moment().endOf('month')],
       'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    }
    }, cb);
    
    cb(start, end);
    // const StartDate = start.format('YYYY/MM/DD');
    // const EndDate = end.format('YYYY/MM/DD');
    
    const startDate = '2024/01/25'; 
    const endDate = '2024/06/03';
    //  const startDate = start; 
    // const endDate = end;
    // console.log('-------',startDate);
    // console.log('-------',endDate);
    
    const dateRangeQuery = `after:${startDate} before:${endDate}`;
    let nextPageToken = null;
    
    try {
        do {
          
            // Make a request to list messages
            const response = await gapi.client.gmail.users.messages.list({
                'userId': 'me',
                'maxResults': 50,
                'pageToken': nextPageToken, 
                'q': `from:${filterEmail} ${dateRangeQuery}`
            });
    
    if(!response.result.messages){
    @this.emailNotFound();
    return;
    }
          const messages = response.result.messages;
            const messageCount = messages.length;
            if (!messages || messages.length === 0) {
                document.getElementById('content').innerText = 'No messages found.';
                return;
            }
            // Show loader
            // Process each message
            for (const message of messages) {
                const msg = await gapi.client.gmail.users.messages.get({
                    'userId': 'me',
                    'id': message.id
                });
    
                const headers = msg.result.payload.headers;
                const subject = headers.find(header => header.name === 'Subject').value;
                const from = headers.find(header => header.name === 'From').value;
    
                let body = '';
                let htmlBody = '';
                if (msg.result.payload.parts) {
                  document.getElementById('saving-loader').style.display = 'none';
                 document.getElementById('loading-wrapper').style.display = 'flex';
                    for (const part of msg.result.payload.parts) {
                        if (part.mimeType === 'text/plain') {
                            body = atob(part.body.data.replace(/-/g, '+').replace(/_/g, '/'));
                        } else if (part.mimeType === 'text/html') {
                            htmlBody = atob(part.body.data.replace(/-/g, '+').replace(/_/g, '/'));
                        }
                    }
                } else {
                    body = atob(msg.result.payload.body.data.replace(/-/g, '+').replace(/_/g, '/'));
                }
                emailId.push(message.id);
                emailData.push({ htmlBody: htmlBody });
                
            }
    
            // Update nextPageToken for the next iteration
            // console.log('---------->--->',messageCount)
            @this.emailDataParsed([emailData,emailId]);
            document.getElementById('loading-wrapper').style.display = 'none';
            document.getElementById('saving-loader').style.display = 'flex';
            
            console.log(emailData);
        console.log(emailId);
            emailData = [];
            emailId = [];
            nextPageToken = response.result.nextPageToken;
        } while (nextPageToken);
    
        // Once all pages are processed, emit the emailDataParsed event
        
    } catch (err) {
      console.error('Error:', err);
        document.getElementById('content').innerText = err.message;
    }
    }
    
    </script>
    <script async defer src="https://apis.google.com/js/api.js" onload="gapiLoaded()"></script>
    <script async defer src="https://accounts.google.com/gsi/client" onload="gisLoaded()"></script>
</div>
