<script
    src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
    integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer"
></script>;
$(function () {
    var start = moment().subtract(3, "days");
    var end = moment();

    function cb(start, end) {
        $("#reportrange span").html(
            start.format("MMMM D, YYYY") + " - " + end.format("MMMM D, YYYY")
        );
    }

    $("#reportrange").daterangepicker(
        {
            startDate: start,
            endDate: end,
            ranges: {
                Today: [moment(), moment()],
                Yesterday: [
                    moment().subtract(1, "days"),
                    moment().subtract(1, "days"),
                ],
                "Last 7 Days": [moment().subtract(6, "days"), moment()],
                "Last 30 Days": [moment().subtract(29, "days"), moment()],
                "This Month": [
                    moment().startOf("month"),
                    moment().endOf("month"),
                ],
                "Last Month": [
                    moment().subtract(1, "month").startOf("month"),
                    moment().subtract(1, "month").endOf("month"),
                ],
            },
        },
        cb
    );

    cb(start, end);
});

const CLIENT_ID =
    "59056185248-kh21c4v4n5fs7pirmvt9htj5a0fs8rn7.apps.googleusercontent.com";
const API_KEY = "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";
const DISCOVERY_DOC =
    "https://www.googleapis.com/discovery/v1/apis/gmail/v1/rest";
const SCOPES = "https://www.googleapis.com/auth/gmail.readonly";

let tokenClient;
let gapiInited = false;
let gisInited = false;
let emailData = [];

document.getElementById("authorize_button").style.visibility = "visible";
document.getElementById("signout_button").style.visibility = "hidden";
document.getElementById("refresh_button").style.visibility = "hidden";
document.getElementById("save_button").style.visibility = "hidden";

function gapiLoaded() {
    gapi.load("client", initializeGapiClient);
    $.ajax({
        url: "/submit-data", // URL of the endpoint on your server
        type: "POST", // HTTP method
        contentType: "application/json", // Content type of the data being sent
        data: JSON.stringify({ key1: "value1", key2: "value2" }), // Data to be sent (JSON string)
        success: function (response) {
            // Function to handle a successful response from the server
            console.log("Data submitted successfully");
        },
        error: function (xhr, status, error) {
            // Function to handle errors
            console.error("Request failed:", error);
        },
    });
}

async function initializeGapiClient() {
    await gapi.client.init({
        apiKey: API_KEY,
        discoveryDocs: [DISCOVERY_DOC],
    });
    gapiInited = true;
    maybeEnableButtons();
}

function gisLoaded() {
    tokenClient = google.accounts.oauth2.initTokenClient({
        client_id: CLIENT_ID,
        scope: SCOPES,
        callback: "",
    });
    gisInited = true;
    console.log("ima run2");
    maybeEnableButtons();

    console.log("ima run3");
}

function maybeEnableButtons() {
    if (gapiInited && gisInited) {
        document.getElementById("authorize_button").style.visibility =
            "visible";
    }
}

function handleAuthClick() {
    tokenClient.callback = async (resp) => {
        if (resp.error !== undefined) {
            throw resp;
        }
        document.getElementById("signout_button").style.visibility = "visible";
        document.getElementById("refresh_button").style.visibility = "visible";
        document.getElementById("save_button").style.visibility = "visible";
        document.getElementById("authorize_button").innerText = "Refresh Token";
        await listMessages();
        document.getElementById("saving-loader").style.display = "none";
        document.getElementById("loading-wrapper").style.display = "none";
    };
    const email = "trestonforbusiness1@gmail.com";
    if (gapi.client.getToken() === null) {
        tokenClient.requestAccessToken({ prompt: "", login_hint: email });
    } else {
        tokenClient.requestAccessToken({ prompt: "" });
    }
}

function handleSignoutClick() {
    const token = gapi.client.getToken();
    if (token !== null) {
        google.accounts.oauth2.revoke(token.access_token);
        gapi.client.setToken("");
        document.getElementById("content").innerText = "";
        document.getElementById("authorize_button").innerText = "Authorize";
        document.getElementById("signout_button").style.visibility = "hidden";
        document.getElementById("refresh_button").style.visibility = "hidden";
        document.getElementById("save_button").style.visibility = "hidden";
    }
}

async function listMessages() {
    console.log("list msges called----");
    const filterEmail = "cash@square.com";
    let emailData = [];
    let emailId = [];
    var start = moment().subtract(7, "days");
    var end = moment();

    function cb(start, end) {
        $("#reportrange span").html(
            start.format("MMMM D, YYYY") + " - " + end.format("MMMM D, YYYY")
        );
    }

    $("#reportrange").daterangepicker(
        {
            startDate: start,
            endDate: end,
            ranges: {
                Today: [moment(), moment()],
                Yesterday: [
                    moment().subtract(1, "days"),
                    moment().subtract(1, "days"),
                ],
                "Last 7 Days": [moment().subtract(6, "days"), moment()],
                "Last 30 Days": [moment().subtract(29, "days"), moment()],
                "This Month": [
                    moment().startOf("month"),
                    moment().endOf("month"),
                ],
                "Last Month": [
                    moment().subtract(1, "month").startOf("month"),
                    moment().subtract(1, "month").endOf("month"),
                ],
            },
        },
        cb
    );

    cb(start, end);

    const startDate = "2024/01/25";
    const endDate = "2024/06/03";

    const dateRangeQuery = `after:${startDate} before:${endDate}`;
    let nextPageToken = null;

    try {
        do {
            const response = await gapi.client.gmail.users.messages.list({
                userId: "me",
                maxResults: 50,
                pageToken: nextPageToken,
                q: `from:${filterEmail} ${dateRangeQuery}`,
            });

            if (!response.result.messages) {
                // @this.emailNotFound();
                alert("no email found");
                return;
            }
            const messages = response.result.messages;
            const messageCount = messages.length;
            if (!messages || messages.length === 0) {
                document.getElementById("content").innerText =
                    "No messages found.";
                return;
            }
            for (const message of messages) {
                const msg = await gapi.client.gmail.users.messages.get({
                    userId: "me",
                    id: message.id,
                });

                const headers = msg.result.payload.headers;
                const subject = headers.find(
                    (header) => header.name === "Subject"
                ).value;
                const from = headers.find(
                    (header) => header.name === "From"
                ).value;

                let body = "";
                let htmlBody = "";
                if (msg.result.payload.parts) {
                    document.getElementById("saving-loader").style.display =
                        "none";
                    document.getElementById("loading-wrapper").style.display =
                        "flex";
                    for (const part of msg.result.payload.parts) {
                        if (part.mimeType === "text/plain") {
                            body = atob(
                                part.body.data
                                    .replace(/-/g, "+")
                                    .replace(/_/g, "/")
                            );
                        } else if (part.mimeType === "text/html") {
                            htmlBody = atob(
                                part.body.data
                                    .replace(/-/g, "+")
                                    .replace(/_/g, "/")
                            );
                        }
                    }
                } else {
                    body = atob(
                        msg.result.payload.body.data
                            .replace(/-/g, "+")
                            .replace(/_/g, "/")
                    );
                }
                emailId.push(message.id);
                emailData.push({ htmlBody: htmlBody });
            }

            // @this.emailDataParsed([emailData, emailId]);
            document.getElementById("loading-wrapper").style.display = "none";
            document.getElementById("saving-loader").style.display = "flex";

            emailData = [];
            emailId = [];
            nextPageToken = response.result.nextPageToken;
        } while (nextPageToken);
    } catch (err) {
        console.error("Error:", err);
        document.getElementById("content").innerText = err.message;
    }
}
gapiLoaded();
gisLoaded();
