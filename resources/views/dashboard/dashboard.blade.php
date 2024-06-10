@extends('layouts.master')
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
           <div><h1 class="h3 mb-0 text-gray-800">Dashboard</h1></div>
        </div>
            <div class="row d-flex justify-content-md-end">
                <button id="authorize_button" class="col-md-2 col-sm-4 btn btn-sm btn-primary shadow-sm m-1"  onclick="handleAuthClick()"><i class="far fa-envelope"></i>&nbsp;Generate Report</button>
                <button id="signout_button" class="col-md-2 col-sm-4 btn btn-sm btn-primary shadow-sm m-1" onclick="handleSignoutClick()"><i class="fas fa-sign-out-alt fa-sm text-white"></i>&nbsp;Sign Out</button>
               <button id="refresh_button" class="col-md-2 col-sm-4  btn btn-sm btn-primary shadow-sm m-1" onclick="listMessages()"><i class="fas fa-sync fa-sm text-white"></i>&nbsp;Refresh Emails</button>
               <button id="save_button" class="col-md-2 col-sm-4  btn btn-sm btn-primary shadow-sm m-1" onclick="saveEmails()"><i class="far fa-save fa-sm text-white"></i>&nbsp;Save Emails</button>
            </div>
           
             
        

        <!-- Content Row -->
        <div class="row">

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Earnings (Monthly)</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">$40,000</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Earnings (Annual)</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">$215,000</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tasks
                                </div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">50%</div>
                                    </div>
                                    <div class="col">
                                        <div class="progress progress-sm mr-2">
                                            <div class="progress-bar bg-info" role="progressbar"
                                                style="width: 50%" aria-valuenow="50" aria-valuemin="0"
                                                aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Requests Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Pending Requests</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">18</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-comments fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Row -->

        <div class="row">

            <!-- Area Chart -->
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div
                        class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Earnings Overview</h6>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                aria-labelledby="dropdownMenuLink">
                                <div class="dropdown-header">Dropdown Header:</div>
                                <a class="dropdown-item" href="#">Action</a>
                                <a class="dropdown-item" href="#">Another action</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">Something else here</a>
                            </div>
                        </div>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-area">
                            <canvas id="myAreaChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pie Chart -->
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div
                        class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Revenue Sources</h6>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                aria-labelledby="dropdownMenuLink">
                                <div class="dropdown-header">Dropdown Header:</div>
                                <a class="dropdown-item" href="#">Action</a>
                                <a class="dropdown-item" href="#">Another action</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">Something else here</a>
                            </div>
                        </div>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-pie pt-4 pb-2">
                            <canvas id="myPieChart"></canvas>
                        </div>
                        <div class="mt-4 text-center small">
                            <span class="mr-2">
                                <i class="fas fa-circle text-primary"></i> Direct
                            </span>
                            <span class="mr-2">
                                <i class="fas fa-circle text-success"></i> Social
                            </span>
                            <span class="mr-2">
                                <i class="fas fa-circle text-info"></i> Referral
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Row -->
        <div class="row">

            <!-- Content Column -->
            <div class="col-lg-6 mb-4">

                <!-- Project Card Example -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Projects</h6>
                    </div>
                    <div class="card-body">
                        <h4 class="small font-weight-bold">Server Migration <span
                                class="float-right">20%</span></h4>
                        <div class="progress mb-4">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: 20%"
                                aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <h4 class="small font-weight-bold">Sales Tracking <span
                                class="float-right">40%</span></h4>
                        <div class="progress mb-4">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 40%"
                                aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <h4 class="small font-weight-bold">Customer Database <span
                                class="float-right">60%</span></h4>
                        <div class="progress mb-4">
                            <div class="progress-bar" role="progressbar" style="width: 60%"
                                aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <h4 class="small font-weight-bold">Payout Details <span
                                class="float-right">80%</span></h4>
                        <div class="progress mb-4">
                            <div class="progress-bar bg-info" role="progressbar" style="width: 80%"
                                aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <h4 class="small font-weight-bold">Account Setup <span
                                class="float-right">Complete!</span></h4>
                        <div class="progress">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 100%"
                                aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>

                <!-- Color System -->
                <div class="row">
                    <div class="col-lg-6 mb-4">
                        <div class="card bg-primary text-white shadow">
                            <div class="card-body">
                                Primary
                                <div class="text-white-50 small">#4e73df</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4">
                        <div class="card bg-success text-white shadow">
                            <div class="card-body">
                                Success
                                <div class="text-white-50 small">#1cc88a</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4">
                        <div class="card bg-info text-white shadow">
                            <div class="card-body">
                                Info
                                <div class="text-white-50 small">#36b9cc</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4">
                        <div class="card bg-warning text-white shadow">
                            <div class="card-body">
                                Warning
                                <div class="text-white-50 small">#f6c23e</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4">
                        <div class="card bg-danger text-white shadow">
                            <div class="card-body">
                                Danger
                                <div class="text-white-50 small">#e74a3b</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4">
                        <div class="card bg-secondary text-white shadow">
                            <div class="card-body">
                                Secondary
                                <div class="text-white-50 small">#858796</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4">
                        <div class="card bg-light text-black shadow">
                            <div class="card-body">
                                Light
                                <div class="text-black-50 small">#f8f9fc</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4">
                        <div class="card bg-dark text-white shadow">
                            <div class="card-body">
                                Dark
                                <div class="text-white-50 small">#5a5c69</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-lg-6 mb-4">

                <!-- Illustrations -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Illustrations</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;"
                                src="img/undraw_posting_photo.svg" alt="...">
                        </div>
                        <p>Add some quality, svg illustrations to your project courtesy of <a
                                target="_blank" rel="nofollow" href="https://undraw.co/">unDraw</a>, a
                            constantly updated collection of beautiful svg images that you can use
                            completely free and without attribution!</p>
                        <a target="_blank" rel="nofollow" href="https://undraw.co/">Browse Illustrations on
                            unDraw &rarr;</a>
                    </div>
                </div>

                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Development Approach</h6>
                    </div>
                    <div class="card-body">
                        <p>SB Admin 2 makes extensive use of Bootstrap 4 utility classes in order to reduce
                            CSS bloat and poor page performance. Custom CSS classes are used to create
                            custom components and custom utility classes.</p>
                        <p class="mb-0">Before working with this theme, you should become familiar with the
                            Bootstrap framework, especially the utility classes.</p>
                    </div>
                </div>

            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
    <script type="text/javascript">
        /* exported gapiLoaded */
    
        // TODO(developer): Set to client ID and API key from the Developer Console
        const CLIENT_ID = '59056185248-kh21c4v4n5fs7pirmvt9htj5a0fs8rn7.apps.googleusercontent.com';
        const API_KEY = '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';
    
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
        // function handleAuthClick() {
        //     tokenClient.callback = async (resp) => {
        //         if (resp.error !== undefined) {
        //             throw (resp);
        //         }
        //         document.getElementById('signout_button').style.visibility = 'visible';
        //         document.getElementById('refresh_button').style.visibility = 'visible';
        //         document.getElementById('save_button').style.visibility = 'visible';
        //         document.getElementById('authorize_button').innerText = 'Refresh Token';
        //         await listMessages();
        //     };
    
        //     if (gapi.client.getToken() === null) {
        //         // Prompt the user to select a Google Account and ask for consent to share their data
        //         // when establishing a new session.
        //         tokenClient.requestAccessToken({prompt: 'consent'});
        //     } else {
        //         // Skip display of account chooser and consent dialog for an existing session.
        //         tokenClient.requestAccessToken({prompt: ''});
        //     }
        // }
    
        /**
         *  Sign out the user upon button click.
         */
        // function handleSignoutClick() {
        //     const token = gapi.client.getToken();
        //     if (token !== null) {
        //         google.accounts.oauth2.revoke(token.access_token);
        //         gapi.client.setToken('');
        //         document.getElementById('content').innerText = '';
        //         document.getElementById('authorize_button').innerText = 'Authorize';
        //         document.getElementById('signout_button').style.visibility = 'hidden';
        //         document.getElementById('refresh_button').style.visibility = 'hidden';
        //         document.getElementById('save_button').style.visibility = 'hidden';
        //     }
        // }
    
        // async function listMessages() {
        //     const filterEmail = 'legionlad@gmail.com'; // Change this to the email or name you want to filter by
        //     let response;
        //     try {
        //         response = await gapi.client.gmail.users.messages.list({
        //             'userId': 'me',
        //             'maxResults': 10, // Change this number to retrieve more messages
        //             'q': `from:${filterEmail}`
        //         });
        //     } catch (err) {
        //         document.getElementById('content').innerText = err.message;
        //         return;
        //     }
    
        //     const messages = response.result.messages;
        //     if (!messages || messages.length == 0) {
        //         document.getElementById('content').innerText = 'No messages found.';
        //         return;
        //     }
    
        //     emailData = []; // Reset email data
    
        //     let output = 'Messages:\n';
        //     for (const message of messages) {
        //         const msg = await gapi.client.gmail.users.messages.get({
        //             'userId': 'me',
        //             'id': message.id
        //         });
    
        //         const headers = msg.result.payload.headers;
        //         const subject = headers.find(header => header.name === 'Subject').value;
        //         const from = headers.find(header => header.name === 'From').value;
    
        //         let body = '';
        //         let htmlBody = '';
        //         if (msg.result.payload.parts) {
        //             for (const part of msg.result.payload.parts) {
        //                 if (part.mimeType === 'text/plain') {
        //                     body = atob(part.body.data.replace(/-/g, '+').replace(/_/g, '/'));
        //                 } else if (part.mimeType === 'text/html') {
        //                     htmlBody = atob(part.body.data.replace(/-/g, '+').replace(/_/g, '/'));
        //                 }
        //             }
        //         } else {
        //             body = atob(msg.result.payload.body.data.replace(/-/g, '+').replace(/_/g, '/'));
        //         }
        //         // from: from, subject: subject, body: body,
        //         emailData.push({ htmlBody: htmlBody});
        //         output += ` ${htmlBody}\n\n`;
        //         // output += `From: ${from}\nSubject: ${subject}\nBody: ${body}\nHTML Body: ${htmlBody}\n\n`;
    
        //     }
        //     // document.getElementById('content').innerHTML = output;
        //     document.getElementById('content').innerText = output;
    
        // }
    
    
        /**
         * List emails in the user's inbox filtered by sender's email or name.
         */
        // async function listMessages() {
        //     const filterEmail = 'trestonforbusiness1@gmail.com'; // Change this to the email or name you want to filter by
        //     let response;
        //     try {
        //         response = await gapi.client.gmail.users.messages.list({
        //             'userId': 'me',
        //             'maxResults': 10, // Change this number to retrieve more messages
        //             'q': `from:${filterEmail}`
        //         });
        //     } catch (err) {
        //         document.getElementById('content').innerText = err.message;
        //         return;
        //     }
        //
        //     const messages = response.result.messages;
        //     if (!messages || messages.length == 0) {
        //         document.getElementById('content').innerText = 'No messages found.';
        //         return;
        //     }
        //
        //     emailData = []; // Reset email data
        //
        //     let output = 'Messages:\n';
        //     for (const message of messages) {
        //         const msg = await gapi.client.gmail.users.messages.get({
        //             'userId': 'me',
        //             'id': message.id
        //         });
        //
        //         const headers = msg.result.payload.headers;
        //         const subject = headers.find(header => header.name === 'Subject').value;
        //         const from = headers.find(header => header.name === 'From').value;
        //
        //         let body = '';
        //         if (msg.result.payload.parts) {
        //             const part = msg.result.payload.parts.find(part => part.mimeType === 'text/plain');
        //             if (part) {
        //                 body = atob(part.body.data.replace(/-/g, '+').replace(/_/g, '/'));
        //             }
        //         } else {
        //             body = atob(msg.result.payload.body.data.replace(/-/g, '+').replace(/_/g, '/'));
        //         }
        //
        //         emailData.push({from: from, subject: subject, body: body});
        //         output += `From: ${from}\nSubject: ${subject}\nBody: ${body}\n\n`;
        //     }
        //     document.getElementById('content').innerText = output;
        // }
    
        /**
         * Save emails to the backend.
         */
        // function saveEmails() {
        //     $.ajax({
        //         url: '/save-emails',
        //         method: 'POST',
        //         data: JSON.stringify(emailData),
        //         contentType: 'application/json',
        //         success: function(response) {
        //             alert('Emails saved successfully!');
        //         },
        //         error: function(jqXHR, textStatus, errorThrown) {
        //             console.error('Error saving emails:', textStatus, errorThrown);
        //         }
        //     });
        // }
    </script>
    <script async defer src="https://apis.google.com/js/api.js" onload="gapiLoaded()"></script>
    <script async defer src="https://accounts.google.com/gsi/client" onload="gisLoaded()"></script>
    <p>Gmail API Quickstart</p>
@endsection