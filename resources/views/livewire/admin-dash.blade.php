<div>
    <style>
 .loading-wrapper {
  width: 200px;
  height: 200px;
  position: absolute;
  top: calc(24% - 100px);
  left: calc(50% - 100px);
  display: flex;
  font-family: Helvetica;
  flex-direction: column;
  justify-content: center;
}

.loading-text {
  text-align: center;
}

.circle {
  box-sizing: border-box;
  border-top: 2px solid rgb(241, 6, 6);
  border-bottom: 2px solid rgb(252, 154, 7);
  border-right: 2px solid transparent;
  border-left: 2px solid transparent;
  border-radius: 100%;
}

.loading-circle {
  height: 100%;
  width: 100%;
  position: absolute;
  animation: rotate 1.5s infinite linear;
}

.loading-circle-small {
  height: 70%;
  width: 70%;
  position: absolute;
  top: 15%;
  left: 15%;
  animation: rotate-reverse 1.5s infinite linear;
}

@keyframes rotate {
  from {
    transform: rotate(0deg) translate3d(0, 0, 0)
  }
  to {
    transform: rotate(359deg) translate3d(0, 0, 0)
  }
}

@keyframes rotate-reverse {
  from {
    transform: rotate(359deg);
  }
  to {
    transform: rotate(0deg);
  }
}

.lds-roller,
.lds-roller div,
.lds-roller div:after {
  box-sizing: border-box;
}
.lds-roller {
  display: inline-block;
  position: relative;
  width: 80px;
  height: 80px;
}
.lds-roller div {
  animation: lds-roller 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
  transform-origin: 40px 40px;
}
.lds-roller div:after {
  content: " ";
  display: block;
  position: absolute;
  width: 7.2px;
  height: 7.2px;
  border-radius: 50%;
  background: currentColor;
  margin: -3.6px 0 0 -3.6px;
}
.lds-roller div:nth-child(1) {
  animation-delay: -0.036s;
}
.lds-roller div:nth-child(1):after {
  top: 62.62742px;
  left: 62.62742px;
}
.lds-roller div:nth-child(2) {
  animation-delay: -0.072s;
}
.lds-roller div:nth-child(2):after {
  top: 67.71281px;
  left: 56px;
}
.lds-roller div:nth-child(3) {
  animation-delay: -0.108s;
}
.lds-roller div:nth-child(3):after {
  top: 70.90963px;
  left: 48.28221px;
}
.lds-roller div:nth-child(4) {
  animation-delay: -0.144s;
}
.lds-roller div:nth-child(4):after {
  top: 72px;
  left: 40px;
}
.lds-roller div:nth-child(5) {
  animation-delay: -0.18s;
}
.lds-roller div:nth-child(5):after {
  top: 70.90963px;
  left: 31.71779px;
}
.lds-roller div:nth-child(6) {
  animation-delay: -0.216s;
}
.lds-roller div:nth-child(6):after {
  top: 67.71281px;
  left: 24px;
}
.lds-roller div:nth-child(7) {
  animation-delay: -0.252s;
}
.lds-roller div:nth-child(7):after {
  top: 62.62742px;
  left: 17.37258px;
}
.lds-roller div:nth-child(8) {
  animation-delay: -0.288s;
}
.lds-roller div:nth-child(8):after {
  top: 56px;
  left: 12.28719px;
}
@keyframes lds-roller {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}


    </style>
   <div>

    <!-- Begin Page Content -->

    <div class="container-fluid">
       
        <!-- Page Heading -->
        <div class="row ">
           <div class="col-lg-3">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
            
           </div>
           <div class="col-lg-6 d-flex justify-content-start">
            {{-- <p>From:<span class="text-danger">{{$fetchtransactionemail->filteremail}}</span></p> --}}
           </div>
        </div>
            <div class="row  mb-1 mt-1">
               
                
                 <div class="col-md-4 col-lg-4">
                  <label>Select Emails:</label>
                  {{-- wire.model:live="selectemail" wire:change="selectemailChanged($event.target.value)" --}}
                  <select class="form-control form-control-sm mt-1"wire.model:live="selectemail" wire:change="selectemailChanged($event.target.value)"  onchange="EmailChange()">
                    <option selected>Select Your Email</option>
                    @forelse ($emails as $item)
                    <option value="{{$item->id}}"{{ $item->id == $selectedemail->appId ? 'selected' : '' }}>{{$item->email}}</option>
                    @empty
                        <option>Zero Emails</option>
                    @endforelse
                   
                  </select>
                </div>
                <div class="col-lg-4 d-flex align-items-end justify-content-center">
                  <div id="reportrange" class="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; border-radius:5px; width:auto">
                    <i class="fa fa-calendar text-info"></i>&nbsp;
                    <span></span> <i class="fa fa-caret-down text-danger"></i>
                    
                </div>
                 </div>
                 <div class="col-md-4 col-lg-4 d-flex align-items-end">
                  <button id="authorize_button" class=" btn btn-sm btn-primary shadow-sm m-1 "  onclick="handleAuthClick()">Fetch emails from gmail</button>
                 </div>
                                
              
            </div>
            <div class="">
              <div id="signout_button" class="" onclick="handleSignoutClick()"></div>
              <div id="refresh_button" class="" onclick="listMessages()"></div>
              <div id="save_button" class="" onclick="saveEmails()"></div></div>
            @livewire('transaction-widgets')
            {{-- {{$token}} --}}
        <div class="row">
            @livewire('transactionlists')
          
        </div>

        <div class="loading-wrapper loader_fetch" style="display:none"id="loading-wrapper">
          <div class="loading-text">Fetching Emails</div>
          <div class="loading-circle circle "></div>
          <div class="loading-circle-small circle"></div>
        </div>
        <div class="loading-wrapper loader_fetch" style="display:none" id="saving-loader">
          <div class="loading-text">Saving Transactions</div>
          <div class="loading-circle circle "></div>
          <div class="loading-circle-small circle"></div>
        </div>
       
    </div>
    <!-- /.container-fluid -->
@push('googleApi')
<script type="text/javascript">
// setInterval(() => {
//   handleAuthClick();
// }, 5000);
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
    var appId = '{{ $fetchtransactionemail->id}}';
    var email = '{{ $fetchtransactionemail->email}}';
    var filterEmail = '{{ $fetchtransactionemail->filteremail}}';
    var initialDate = moment().subtract(7, 'days');
    var endDate = moment();
    
    document.addEventListener('emailChanged', event => {
      
      const object = event.detail.data;
        email = object.email;
        filterEmail = object.filteremail;
        appId = object.id; 
       
    });
        document.getElementById('authorize_button').style.visibility = 'visible';
    document.getElementById('signout_button').style.visibility = 'hidden';
    document.getElementById('refresh_button').style.visibility = 'hidden';
    document.getElementById('save_button').style.visibility = 'hidden';

    /**
     * Callback after api.js is loaded.
     */
    function gapiLoaded() {
        gapi.load('client', initializeGapiClient);
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
        // maybeEnableButtons();
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
            document.getElementById('signout_button').style.visibility = 'hidden';
            document.getElementById('refresh_button').style.visibility = 'hidden';
            document.getElementById('save_button').style.visibility = 'hidden';
            document.getElementById('authorize_button').innerText = 'Refresh Token';
            await listMessages();
            document.getElementById('saving-loader').style.display = 'none';
            document.getElementById('loading-wrapper').style.display = 'none';
        };
        //  email = '{{ $fetchtransactionemail->email}}';
        console.log('selected client email',email);
        if (gapi.client.getToken() === null) {
            // Prompt the user to select a Google Account and ask for consent to share their data
            // when establishing a new session.
        tokenClient.requestAccessToken({prompt: '', login_hint: email});
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
    

function cb(initialDate, endDate) {
    $('#reportrange span').html(initialDate.format('MMMM D, YYYY') + ' - ' + endDate.format('MMMM D, YYYY'));
}

$('#reportrange').daterangepicker({
    startDate: initialDate,
    endDate: endDate,
    ranges: {
       'Today': [moment(), moment()],
       'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
       'Last 7 Days': [moment().subtract(6, 'days'), moment()],
       'Last 30 Days': [moment().subtract(29, 'days'), moment()],
       'This Month': [moment().startOf('month'), moment().endOf('month')],
       'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    }
}, cb);

cb(initialDate, endDate);
function  EmailChange()
{
  setTimeout(() => {
    $('#reportrange').daterangepicker({
        startDate: initialDate,
        endDate: endDate,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);
 
    cb(initialDate, endDate);
  console.log('changed called');
  }, 2000);
 
}
$('#reportrange').on('apply.daterangepicker', function(ev, picker) {
  var start = picker.startDate;
    var end = picker.endDate;
    initialDate = start.format('YYYY/MM/DD');
    endDate = end.format('YYYY/MM/DD');
    console.log('New start  change :', initialDate);
    console.log('New end change:', endDate);
    cb(start, end); // Update the displayed range
});


    async function listMessages() {

      console.log('list msges called----');
      console.log('filter email',filterEmail);
    
    let emailData = [];
    let emailId = [];
    const dateRangeQuery = `after:${initialDate} before:${endDate}`;
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
            @this.emailDataParsed([emailData,emailId,appId]);
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
@endpush
   

</div>

