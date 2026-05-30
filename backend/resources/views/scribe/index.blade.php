<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Laravel API Documentation</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.style.css") }}" media="screen">
    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.print.css") }}" media="print">

    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>

    <link rel="stylesheet"
          href="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/styles/obsidian.min.css">
    <script src="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/highlight.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jets/0.14.1/jets.min.js"></script>

    <style id="language-style">
        /* starts out as display none and is replaced with js later  */
                    body .content .bash-example code { display: none; }
                    body .content .javascript-example code { display: none; }
            </style>

    <script>
        var tryItOutBaseUrl = "http://localhost";
        var useCsrf = Boolean();
        var csrfUrl = "/sanctum/csrf-cookie";
    </script>
    <script src="{{ asset("/vendor/scribe/js/tryitout-5.10.0.js") }}"></script>

    <script src="{{ asset("/vendor/scribe/js/theme-default-5.10.0.js") }}"></script>

</head>

<body data-languages="[&quot;bash&quot;,&quot;javascript&quot;]">

<a href="#" id="nav-button">
    <span>
        MENU
        <img src="{{ asset("/vendor/scribe/images/navbar.png") }}" alt="navbar-image"/>
    </span>
</a>
<div class="tocify-wrapper">
    
            <div class="lang-selector">
                                            <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                            <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                    </div>
    
    <div class="search">
        <input type="text" class="search" id="input-search" placeholder="Search">
    </div>

    <div id="toc">
                    <ul id="tocify-header-introduction" class="tocify-header">
                <li class="tocify-item level-1" data-unique="introduction">
                    <a href="#introduction">Introduction</a>
                </li>
                            </ul>
                    <ul id="tocify-header-authenticating-requests" class="tocify-header">
                <li class="tocify-item level-1" data-unique="authenticating-requests">
                    <a href="#authenticating-requests">Authenticating requests</a>
                </li>
                            </ul>
                    <ul id="tocify-header-cars-endpoints-for-managing-slotcars-in-the-collection" class="tocify-header">
                <li class="tocify-item level-1" data-unique="cars-endpoints-for-managing-slotcars-in-the-collection">
                    <a href="#cars-endpoints-for-managing-slotcars-in-the-collection">Cars - Endpoints for managing slotcars in the collection.</a>
                </li>
                                    <ul id="tocify-subheader-cars-endpoints-for-managing-slotcars-in-the-collection" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="cars-endpoints-for-managing-slotcars-in-the-collection-GETapi-cars">
                                <a href="#cars-endpoints-for-managing-slotcars-in-the-collection-GETapi-cars">GET /api/cars - List all registered cars with their respective owners.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="cars-endpoints-for-managing-slotcars-in-the-collection-POSTapi-cars">
                                <a href="#cars-endpoints-for-managing-slotcars-in-the-collection-POSTapi-cars">POST /api/cars - Register a new car in the collection, optionally associating it with a driver.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="cars-endpoints-for-managing-slotcars-in-the-collection-GETapi-cars--id-">
                                <a href="#cars-endpoints-for-managing-slotcars-in-the-collection-GETapi-cars--id-">GET /api/cars/{id} - Retrieve details of a specific car and its owner.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="cars-endpoints-for-managing-slotcars-in-the-collection-PUTapi-cars--id-">
                                <a href="#cars-endpoints-for-managing-slotcars-in-the-collection-PUTapi-cars--id-">PUT /api/cars/{id} - Update a car's details, brand, model, scale, or owner.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="cars-endpoints-for-managing-slotcars-in-the-collection-DELETEapi-cars--id-">
                                <a href="#cars-endpoints-for-managing-slotcars-in-the-collection-DELETEapi-cars--id-">DELETE /api/cars/{id} - Delete a registered car from the collection.</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-drivers-endpoints-for-managing-drivers" class="tocify-header">
                <li class="tocify-item level-1" data-unique="drivers-endpoints-for-managing-drivers">
                    <a href="#drivers-endpoints-for-managing-drivers">Drivers - Endpoints for managing drivers.</a>
                </li>
                                    <ul id="tocify-subheader-drivers-endpoints-for-managing-drivers" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="drivers-endpoints-for-managing-drivers-GETapi-drivers">
                                <a href="#drivers-endpoints-for-managing-drivers-GETapi-drivers">GET /api/drivers - List all registered drivers with aggregated statistics.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="drivers-endpoints-for-managing-drivers-POSTapi-drivers">
                                <a href="#drivers-endpoints-for-managing-drivers-POSTapi-drivers">POST /api/drivers - Register a new driver on the platform.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="drivers-endpoints-for-managing-drivers-GETapi-drivers--id-">
                                <a href="#drivers-endpoints-for-managing-drivers-GETapi-drivers--id-">GET /api/drivers/{id} - Retrieve details of a specific driver including their cars and best lap times.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="drivers-endpoints-for-managing-drivers-PUTapi-drivers--id-">
                                <a href="#drivers-endpoints-for-managing-drivers-PUTapi-drivers--id-">PUT /api/drivers/{id} - Update an existing driver's registration details.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="drivers-endpoints-for-managing-drivers-DELETEapi-drivers--id-">
                                <a href="#drivers-endpoints-for-managing-drivers-DELETEapi-drivers--id-">DELETE /api/drivers/{id} - Delete a driver and all their associated records from the database.</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-races-endpoints-for-creation-state-control-telemetry-and-leaderboard-rankings-of-slotcar-races" class="tocify-header">
                <li class="tocify-item level-1" data-unique="races-endpoints-for-creation-state-control-telemetry-and-leaderboard-rankings-of-slotcar-races">
                    <a href="#races-endpoints-for-creation-state-control-telemetry-and-leaderboard-rankings-of-slotcar-races">Races - Endpoints for creation, state control, telemetry, and leaderboard rankings of slotcar races.</a>
                </li>
                                    <ul id="tocify-subheader-races-endpoints-for-creation-state-control-telemetry-and-leaderboard-rankings-of-slotcar-races" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="races-endpoints-for-creation-state-control-telemetry-and-leaderboard-rankings-of-slotcar-races-GETapi-races">
                                <a href="#races-endpoints-for-creation-state-control-telemetry-and-leaderboard-rankings-of-slotcar-races-GETapi-races">GET /api/races - List race history and active registered GPs.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="races-endpoints-for-creation-state-control-telemetry-and-leaderboard-rankings-of-slotcar-races-POSTapi-races">
                                <a href="#races-endpoints-for-creation-state-control-telemetry-and-leaderboard-rankings-of-slotcar-races-POSTapi-races">POST /api/races - Create a new slotcar race defining track, type, limits, and participants.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="races-endpoints-for-creation-state-control-telemetry-and-leaderboard-rankings-of-slotcar-races-GETapi-races--id-">
                                <a href="#races-endpoints-for-creation-state-control-telemetry-and-leaderboard-rankings-of-slotcar-races-GETapi-races--id-">GET /api/races/{id} - Retrieve details of a specific race including track, participants, and telemetry log.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="races-endpoints-for-creation-state-control-telemetry-and-leaderboard-rankings-of-slotcar-races-PUTapi-races--id-">
                                <a href="#races-endpoints-for-creation-state-control-telemetry-and-leaderboard-rankings-of-slotcar-races-PUTapi-races--id-">PUT /api/races/{id} - Update basic race registration details.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="races-endpoints-for-creation-state-control-telemetry-and-leaderboard-rankings-of-slotcar-races-DELETEapi-races--id-">
                                <a href="#races-endpoints-for-creation-state-control-telemetry-and-leaderboard-rankings-of-slotcar-races-DELETEapi-races--id-">DELETE /api/races/{id} - Delete a race and its telemetry history from the database.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="races-endpoints-for-creation-state-control-telemetry-and-leaderboard-rankings-of-slotcar-races-POSTapi-races--id--start">
                                <a href="#races-endpoints-for-creation-state-control-telemetry-and-leaderboard-rankings-of-slotcar-races-POSTapi-races--id--start">POST /api/races/{id}/start - Start the race (changes status to in_progress and enables telemetry).</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="races-endpoints-for-creation-state-control-telemetry-and-leaderboard-rankings-of-slotcar-races-POSTapi-races--id--lap">
                                <a href="#races-endpoints-for-creation-state-control-telemetry-and-leaderboard-rankings-of-slotcar-races-POSTapi-races--id--lap">POST /api/races/{id}/lap - Telemetry: Record a new lap time for a driver/lane, updating leaderboard and records.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="races-endpoints-for-creation-state-control-telemetry-and-leaderboard-rankings-of-slotcar-races-POSTapi-races--id--finish">
                                <a href="#races-endpoints-for-creation-state-control-telemetry-and-leaderboard-rankings-of-slotcar-races-POSTapi-races--id--finish">POST /api/races/{id}/finish - Manually finish an ongoing race.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="races-endpoints-for-creation-state-control-telemetry-and-leaderboard-rankings-of-slotcar-races-POSTapi-races--id--pause">
                                <a href="#races-endpoints-for-creation-state-control-telemetry-and-leaderboard-rankings-of-slotcar-races-POSTapi-races--id--pause">POST /api/races/{id}/pause - Pause an ongoing race.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="races-endpoints-for-creation-state-control-telemetry-and-leaderboard-rankings-of-slotcar-races-POSTapi-races--id--resume">
                                <a href="#races-endpoints-for-creation-state-control-telemetry-and-leaderboard-rankings-of-slotcar-races-POSTapi-races--id--resume">POST /api/races/{id}/resume - Resume a paused race.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="races-endpoints-for-creation-state-control-telemetry-and-leaderboard-rankings-of-slotcar-races-GETapi-races--id--leaderboard">
                                <a href="#races-endpoints-for-creation-state-control-telemetry-and-leaderboard-rankings-of-slotcar-races-GETapi-races--id--leaderboard">GET /api/races/{id}/leaderboard - Real-time leaderboard standings sorted by laps completed and lowest accumulated time.</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-tracks-endpoints-for-managing-slotcar-tracks-and-their-absolute-records" class="tocify-header">
                <li class="tocify-item level-1" data-unique="tracks-endpoints-for-managing-slotcar-tracks-and-their-absolute-records">
                    <a href="#tracks-endpoints-for-managing-slotcar-tracks-and-their-absolute-records">Tracks - Endpoints for managing slotcar tracks and their absolute records.</a>
                </li>
                                    <ul id="tocify-subheader-tracks-endpoints-for-managing-slotcar-tracks-and-their-absolute-records" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="tracks-endpoints-for-managing-slotcar-tracks-and-their-absolute-records-GETapi-tracks">
                                <a href="#tracks-endpoints-for-managing-slotcar-tracks-and-their-absolute-records-GETapi-tracks">GET /api/tracks - List all registered tracks and their absolute lap records.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="tracks-endpoints-for-managing-slotcar-tracks-and-their-absolute-records-POSTapi-tracks">
                                <a href="#tracks-endpoints-for-managing-slotcar-tracks-and-their-absolute-records-POSTapi-tracks">POST /api/tracks - Register a new track with lane count and length.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="tracks-endpoints-for-managing-slotcar-tracks-and-their-absolute-records-GETapi-tracks--id-">
                                <a href="#tracks-endpoints-for-managing-slotcar-tracks-and-their-absolute-records-GETapi-tracks--id-">GET /api/tracks/{id} - Retrieve details of a specific track with its recent race history.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="tracks-endpoints-for-managing-slotcar-tracks-and-their-absolute-records-PUTapi-tracks--id-">
                                <a href="#tracks-endpoints-for-managing-slotcar-tracks-and-their-absolute-records-PUTapi-tracks--id-">PUT /api/tracks/{id} - Update track data and configure or reset lap records.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="tracks-endpoints-for-managing-slotcar-tracks-and-their-absolute-records-DELETEapi-tracks--id-">
                                <a href="#tracks-endpoints-for-managing-slotcar-tracks-and-their-absolute-records-DELETEapi-tracks--id-">DELETE /api/tracks/{id} - Delete a registered track and its records from the database.</a>
                            </li>
                                                                        </ul>
                            </ul>
            </div>

    <ul class="toc-footer" id="toc-footer">
                    <li style="padding-bottom: 5px;"><a href="{{ route("scribe.postman") }}">View Postman collection</a></li>
                            <li style="padding-bottom: 5px;"><a href="{{ route("scribe.openapi") }}">View OpenAPI spec</a></li>
                <li><a href="http://github.com/knuckleswtf/scribe">Documentation powered by Scribe ✍</a></li>
    </ul>

    <ul class="toc-footer" id="last-updated">
        <li>Last updated: May 28, 2026</li>
    </ul>
</div>

<div class="page-wrapper">
    <div class="dark-box"></div>
    <div class="content">
        <h1 id="introduction">Introduction</h1>
<aside>
    <strong>Base URL</strong>: <code>http://localhost</code>
</aside>
<pre><code>This documentation aims to provide all the information you need to work with our API.

&lt;aside&gt;As you scroll, you'll see code examples for working with the API in different programming languages in the dark area to the right (or as part of the content on mobile).
You can switch the language used with the tabs at the top right (or from the nav menu at the top left on mobile).&lt;/aside&gt;</code></pre>

        <h1 id="authenticating-requests">Authenticating requests</h1>
<p>This API is not authenticated.</p>

        <h1 id="cars-endpoints-for-managing-slotcars-in-the-collection">Cars - Endpoints for managing slotcars in the collection.</h1>

    

                                <h2 id="cars-endpoints-for-managing-slotcars-in-the-collection-GETapi-cars">GET /api/cars - List all registered cars with their respective owners.</h2>

<p>
</p>



<span id="example-requests-GETapi-cars">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/cars" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/cars"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-cars">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">[
    {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;McLaren MP4/4&quot;,
        &quot;brand&quot;: &quot;Slot.it&quot;,
        &quot;model&quot;: &quot;MP4/4&quot;,
        &quot;scale&quot;: &quot;1:32&quot;,
        &quot;driver_id&quot;: null,
        &quot;created_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
        &quot;driver&quot;: null
    },
    {
        &quot;id&quot;: 2,
        &quot;name&quot;: &quot;Ferrari F2004&quot;,
        &quot;brand&quot;: &quot;Carrera&quot;,
        &quot;model&quot;: &quot;F2004&quot;,
        &quot;scale&quot;: &quot;1:32&quot;,
        &quot;driver_id&quot;: 3,
        &quot;created_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
        &quot;driver&quot;: {
            &quot;id&quot;: 3,
            &quot;name&quot;: &quot;Michael Schumacher&quot;,
            &quot;nickname&quot;: &quot;Schumi&quot;,
            &quot;avatar&quot;: null,
            &quot;created_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;
        }
    },
    {
        &quot;id&quot;: 3,
        &quot;name&quot;: &quot;Williams FW14B&quot;,
        &quot;brand&quot;: &quot;NSR&quot;,
        &quot;model&quot;: &quot;FW14B&quot;,
        &quot;scale&quot;: &quot;1:32&quot;,
        &quot;driver_id&quot;: 2,
        &quot;created_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
        &quot;driver&quot;: {
            &quot;id&quot;: 2,
            &quot;name&quot;: &quot;Alain Prost&quot;,
            &quot;nickname&quot;: &quot;Prost&quot;,
            &quot;avatar&quot;: null,
            &quot;created_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;
        }
    },
    {
        &quot;id&quot;: 4,
        &quot;name&quot;: &quot;Mercedes W11&quot;,
        &quot;brand&quot;: &quot;Scalextric&quot;,
        &quot;model&quot;: &quot;W11&quot;,
        &quot;scale&quot;: &quot;1:32&quot;,
        &quot;driver_id&quot;: 4,
        &quot;created_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
        &quot;driver&quot;: {
            &quot;id&quot;: 4,
            &quot;name&quot;: &quot;Lewis Hamilton&quot;,
            &quot;nickname&quot;: &quot;Hamilton&quot;,
            &quot;avatar&quot;: null,
            &quot;created_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;
        }
    }
]</code>
 </pre>
    </span>
<span id="execution-results-GETapi-cars" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-cars"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-cars"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-cars" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-cars">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-cars" data-method="GET"
      data-path="api/cars"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-cars', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-cars"
                    onclick="tryItOut('GETapi-cars');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-cars"
                    onclick="cancelTryOut('GETapi-cars');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-cars"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/cars</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-cars"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-cars"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="cars-endpoints-for-managing-slotcars-in-the-collection-POSTapi-cars">POST /api/cars - Register a new car in the collection, optionally associating it with a driver.</h2>

<p>
</p>



<span id="example-requests-POSTapi-cars">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/cars" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"b\",
    \"brand\": \"n\",
    \"model\": \"g\",
    \"scale\": \"z\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/cars"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "b",
    "brand": "n",
    "model": "g",
    "scale": "z"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-cars">
</span>
<span id="execution-results-POSTapi-cars" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-cars"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-cars"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-cars" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-cars">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-cars" data-method="POST"
      data-path="api/cars"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-cars', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-cars"
                    onclick="tryItOut('POSTapi-cars');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-cars"
                    onclick="cancelTryOut('POSTapi-cars');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-cars"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/cars</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-cars"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-cars"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-cars"
               value="b"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>b</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>brand</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="brand"                data-endpoint="POSTapi-cars"
               value="n"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>n</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>model</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="model"                data-endpoint="POSTapi-cars"
               value="g"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>g</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>scale</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="scale"                data-endpoint="POSTapi-cars"
               value="z"
               data-component="body">
    <br>
<p>Must not be greater than 50 characters. Example: <code>z</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>driver_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="driver_id"                data-endpoint="POSTapi-cars"
               value=""
               data-component="body">
    <br>
<p>The <code>id</code> of an existing record in the drivers table.</p>
        </div>
        </form>

                    <h2 id="cars-endpoints-for-managing-slotcars-in-the-collection-GETapi-cars--id-">GET /api/cars/{id} - Retrieve details of a specific car and its owner.</h2>

<p>
</p>



<span id="example-requests-GETapi-cars--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/cars/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/cars/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-cars--id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;id&quot;: 1,
    &quot;name&quot;: &quot;McLaren MP4/4&quot;,
    &quot;brand&quot;: &quot;Slot.it&quot;,
    &quot;model&quot;: &quot;MP4/4&quot;,
    &quot;scale&quot;: &quot;1:32&quot;,
    &quot;driver_id&quot;: null,
    &quot;created_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
    &quot;updated_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
    &quot;driver&quot;: null
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-cars--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-cars--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-cars--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-cars--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-cars--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-cars--id-" data-method="GET"
      data-path="api/cars/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-cars--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-cars--id-"
                    onclick="tryItOut('GETapi-cars--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-cars--id-"
                    onclick="cancelTryOut('GETapi-cars--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-cars--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/cars/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-cars--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-cars--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-cars--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the car. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="cars-endpoints-for-managing-slotcars-in-the-collection-PUTapi-cars--id-">PUT /api/cars/{id} - Update a car&#039;s details, brand, model, scale, or owner.</h2>

<p>
</p>



<span id="example-requests-PUTapi-cars--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost/api/cars/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"b\",
    \"brand\": \"n\",
    \"model\": \"g\",
    \"scale\": \"z\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/cars/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "b",
    "brand": "n",
    "model": "g",
    "scale": "z"
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-cars--id-">
</span>
<span id="execution-results-PUTapi-cars--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-cars--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-cars--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-cars--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-cars--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-cars--id-" data-method="PUT"
      data-path="api/cars/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-cars--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-cars--id-"
                    onclick="tryItOut('PUTapi-cars--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-cars--id-"
                    onclick="cancelTryOut('PUTapi-cars--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-cars--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/cars/{id}</code></b>
        </p>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/cars/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-cars--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-cars--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="PUTapi-cars--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the car. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="PUTapi-cars--id-"
               value="b"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>b</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>brand</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="brand"                data-endpoint="PUTapi-cars--id-"
               value="n"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>n</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>model</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="model"                data-endpoint="PUTapi-cars--id-"
               value="g"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>g</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>scale</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="scale"                data-endpoint="PUTapi-cars--id-"
               value="z"
               data-component="body">
    <br>
<p>Must not be greater than 50 characters. Example: <code>z</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>driver_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="driver_id"                data-endpoint="PUTapi-cars--id-"
               value=""
               data-component="body">
    <br>
<p>The <code>id</code> of an existing record in the drivers table.</p>
        </div>
        </form>

                    <h2 id="cars-endpoints-for-managing-slotcars-in-the-collection-DELETEapi-cars--id-">DELETE /api/cars/{id} - Delete a registered car from the collection.</h2>

<p>
</p>



<span id="example-requests-DELETEapi-cars--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost/api/cars/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/cars/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-cars--id-">
</span>
<span id="execution-results-DELETEapi-cars--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-cars--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-cars--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-cars--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-cars--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-cars--id-" data-method="DELETE"
      data-path="api/cars/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-cars--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-cars--id-"
                    onclick="tryItOut('DELETEapi-cars--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-cars--id-"
                    onclick="cancelTryOut('DELETEapi-cars--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-cars--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/cars/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-cars--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-cars--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="DELETEapi-cars--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the car. Example: <code>1</code></p>
            </div>
                    </form>

                <h1 id="drivers-endpoints-for-managing-drivers">Drivers - Endpoints for managing drivers.</h1>

    

                                <h2 id="drivers-endpoints-for-managing-drivers-GETapi-drivers">GET /api/drivers - List all registered drivers with aggregated statistics.</h2>

<p>
</p>



<span id="example-requests-GETapi-drivers">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/drivers" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/drivers"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-drivers">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">[
    {
        &quot;id&quot;: 2,
        &quot;name&quot;: &quot;Alain Prost&quot;,
        &quot;nickname&quot;: &quot;Prost&quot;,
        &quot;avatar&quot;: null,
        &quot;created_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
        &quot;races_count&quot;: 2,
        &quot;total_laps&quot;: 15
    },
    {
        &quot;id&quot;: 3,
        &quot;name&quot;: &quot;Michael Schumacher&quot;,
        &quot;nickname&quot;: &quot;Schumi&quot;,
        &quot;avatar&quot;: null,
        &quot;created_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
        &quot;races_count&quot;: 2,
        &quot;total_laps&quot;: 27
    },
    {
        &quot;id&quot;: 4,
        &quot;name&quot;: &quot;Lewis Hamilton&quot;,
        &quot;nickname&quot;: &quot;Hamilton&quot;,
        &quot;avatar&quot;: null,
        &quot;created_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
        &quot;races_count&quot;: 2,
        &quot;total_laps&quot;: 15
    },
    {
        &quot;id&quot;: 5,
        &quot;name&quot;: &quot;Joanna&quot;,
        &quot;nickname&quot;: &quot;Banana&quot;,
        &quot;avatar&quot;: null,
        &quot;created_at&quot;: &quot;2026-05-27T23:05:51.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-27T23:05:51.000000Z&quot;,
        &quot;races_count&quot;: 1,
        &quot;total_laps&quot;: 26
    }
]</code>
 </pre>
    </span>
<span id="execution-results-GETapi-drivers" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-drivers"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-drivers"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-drivers" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-drivers">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-drivers" data-method="GET"
      data-path="api/drivers"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-drivers', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-drivers"
                    onclick="tryItOut('GETapi-drivers');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-drivers"
                    onclick="cancelTryOut('GETapi-drivers');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-drivers"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/drivers</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-drivers"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-drivers"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="drivers-endpoints-for-managing-drivers-POSTapi-drivers">POST /api/drivers - Register a new driver on the platform.</h2>

<p>
</p>



<span id="example-requests-POSTapi-drivers">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/drivers" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"b\",
    \"nickname\": \"n\",
    \"avatar\": \"g\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/drivers"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "b",
    "nickname": "n",
    "avatar": "g"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-drivers">
</span>
<span id="execution-results-POSTapi-drivers" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-drivers"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-drivers"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-drivers" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-drivers">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-drivers" data-method="POST"
      data-path="api/drivers"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-drivers', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-drivers"
                    onclick="tryItOut('POSTapi-drivers');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-drivers"
                    onclick="cancelTryOut('POSTapi-drivers');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-drivers"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/drivers</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-drivers"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-drivers"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-drivers"
               value="b"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>b</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>nickname</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="nickname"                data-endpoint="POSTapi-drivers"
               value="n"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>n</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>avatar</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="avatar"                data-endpoint="POSTapi-drivers"
               value="g"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>g</code></p>
        </div>
        </form>

                    <h2 id="drivers-endpoints-for-managing-drivers-GETapi-drivers--id-">GET /api/drivers/{id} - Retrieve details of a specific driver including their cars and best lap times.</h2>

<p>
</p>



<span id="example-requests-GETapi-drivers--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/drivers/2" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/drivers/2"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-drivers--id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;id&quot;: 2,
    &quot;name&quot;: &quot;Alain Prost&quot;,
    &quot;nickname&quot;: &quot;Prost&quot;,
    &quot;avatar&quot;: null,
    &quot;created_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
    &quot;updated_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
    &quot;races_count&quot;: 2,
    &quot;cars&quot;: [
        {
            &quot;id&quot;: 3,
            &quot;name&quot;: &quot;Williams FW14B&quot;,
            &quot;brand&quot;: &quot;NSR&quot;,
            &quot;model&quot;: &quot;FW14B&quot;,
            &quot;scale&quot;: &quot;1:32&quot;,
            &quot;driver_id&quot;: 2,
            &quot;created_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;
        }
    ],
    &quot;lap_times&quot;: [
        {
            &quot;id&quot;: 78,
            &quot;race_id&quot;: 5,
            &quot;driver_id&quot;: 2,
            &quot;lane_number&quot;: 2,
            &quot;lap_number&quot;: 5,
            &quot;lap_time_seconds&quot;: 4.882,
            &quot;created_at&quot;: &quot;2026-05-28T03:00:39.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-05-28T03:00:39.000000Z&quot;
        },
        {
            &quot;id&quot;: 84,
            &quot;race_id&quot;: 5,
            &quot;driver_id&quot;: 2,
            &quot;lane_number&quot;: 2,
            &quot;lap_number&quot;: 8,
            &quot;lap_time_seconds&quot;: 4.945,
            &quot;created_at&quot;: &quot;2026-05-28T03:00:52.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-05-28T03:00:52.000000Z&quot;
        },
        {
            &quot;id&quot;: 8,
            &quot;race_id&quot;: 1,
            &quot;driver_id&quot;: 2,
            &quot;lane_number&quot;: 2,
            &quot;lap_number&quot;: 4,
            &quot;lap_time_seconds&quot;: 5.211,
            &quot;created_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;
        },
        {
            &quot;id&quot;: 87,
            &quot;race_id&quot;: 5,
            &quot;driver_id&quot;: 2,
            &quot;lane_number&quot;: 2,
            &quot;lap_number&quot;: 10,
            &quot;lap_time_seconds&quot;: 5.232,
            &quot;created_at&quot;: &quot;2026-05-28T03:01:04.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-05-28T03:01:04.000000Z&quot;
        },
        {
            &quot;id&quot;: 86,
            &quot;race_id&quot;: 5,
            &quot;driver_id&quot;: 2,
            &quot;lane_number&quot;: 2,
            &quot;lap_number&quot;: 9,
            &quot;lap_time_seconds&quot;: 5.234,
            &quot;created_at&quot;: &quot;2026-05-28T03:01:03.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-05-28T03:01:03.000000Z&quot;
        },
        {
            &quot;id&quot;: 10,
            &quot;race_id&quot;: 1,
            &quot;driver_id&quot;: 2,
            &quot;lane_number&quot;: 2,
            &quot;lap_number&quot;: 5,
            &quot;lap_time_seconds&quot;: 5.245,
            &quot;created_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;
        },
        {
            &quot;id&quot;: 6,
            &quot;race_id&quot;: 1,
            &quot;driver_id&quot;: 2,
            &quot;lane_number&quot;: 2,
            &quot;lap_number&quot;: 3,
            &quot;lap_time_seconds&quot;: 5.29,
            &quot;created_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;
        },
        {
            &quot;id&quot;: 4,
            &quot;race_id&quot;: 1,
            &quot;driver_id&quot;: 2,
            &quot;lane_number&quot;: 2,
            &quot;lap_number&quot;: 2,
            &quot;lap_time_seconds&quot;: 5.34,
            &quot;created_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;
        },
        {
            &quot;id&quot;: 73,
            &quot;race_id&quot;: 5,
            &quot;driver_id&quot;: 2,
            &quot;lane_number&quot;: 2,
            &quot;lap_number&quot;: 3,
            &quot;lap_time_seconds&quot;: 5.345,
            &quot;created_at&quot;: &quot;2026-05-28T02:59:47.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-05-28T02:59:47.000000Z&quot;
        },
        {
            &quot;id&quot;: 76,
            &quot;race_id&quot;: 5,
            &quot;driver_id&quot;: 2,
            &quot;lane_number&quot;: 2,
            &quot;lap_number&quot;: 4,
            &quot;lap_time_seconds&quot;: 5.409,
            &quot;created_at&quot;: &quot;2026-05-28T03:00:03.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-05-28T03:00:03.000000Z&quot;
        }
    ]
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-drivers--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-drivers--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-drivers--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-drivers--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-drivers--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-drivers--id-" data-method="GET"
      data-path="api/drivers/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-drivers--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-drivers--id-"
                    onclick="tryItOut('GETapi-drivers--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-drivers--id-"
                    onclick="cancelTryOut('GETapi-drivers--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-drivers--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/drivers/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-drivers--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-drivers--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-drivers--id-"
               value="2"
               data-component="url">
    <br>
<p>The ID of the driver. Example: <code>2</code></p>
            </div>
                    </form>

                    <h2 id="drivers-endpoints-for-managing-drivers-PUTapi-drivers--id-">PUT /api/drivers/{id} - Update an existing driver&#039;s registration details.</h2>

<p>
</p>



<span id="example-requests-PUTapi-drivers--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost/api/drivers/2" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"b\",
    \"nickname\": \"n\",
    \"avatar\": \"g\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/drivers/2"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "b",
    "nickname": "n",
    "avatar": "g"
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-drivers--id-">
</span>
<span id="execution-results-PUTapi-drivers--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-drivers--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-drivers--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-drivers--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-drivers--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-drivers--id-" data-method="PUT"
      data-path="api/drivers/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-drivers--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-drivers--id-"
                    onclick="tryItOut('PUTapi-drivers--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-drivers--id-"
                    onclick="cancelTryOut('PUTapi-drivers--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-drivers--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/drivers/{id}</code></b>
        </p>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/drivers/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-drivers--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-drivers--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="PUTapi-drivers--id-"
               value="2"
               data-component="url">
    <br>
<p>The ID of the driver. Example: <code>2</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="PUTapi-drivers--id-"
               value="b"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>b</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>nickname</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="nickname"                data-endpoint="PUTapi-drivers--id-"
               value="n"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>n</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>avatar</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="avatar"                data-endpoint="PUTapi-drivers--id-"
               value="g"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>g</code></p>
        </div>
        </form>

                    <h2 id="drivers-endpoints-for-managing-drivers-DELETEapi-drivers--id-">DELETE /api/drivers/{id} - Delete a driver and all their associated records from the database.</h2>

<p>
</p>



<span id="example-requests-DELETEapi-drivers--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost/api/drivers/2" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/drivers/2"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-drivers--id-">
</span>
<span id="execution-results-DELETEapi-drivers--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-drivers--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-drivers--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-drivers--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-drivers--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-drivers--id-" data-method="DELETE"
      data-path="api/drivers/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-drivers--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-drivers--id-"
                    onclick="tryItOut('DELETEapi-drivers--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-drivers--id-"
                    onclick="cancelTryOut('DELETEapi-drivers--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-drivers--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/drivers/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-drivers--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-drivers--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="DELETEapi-drivers--id-"
               value="2"
               data-component="url">
    <br>
<p>The ID of the driver. Example: <code>2</code></p>
            </div>
                    </form>

                <h1 id="races-endpoints-for-creation-state-control-telemetry-and-leaderboard-rankings-of-slotcar-races">Races - Endpoints for creation, state control, telemetry, and leaderboard rankings of slotcar races.</h1>

    

                                <h2 id="races-endpoints-for-creation-state-control-telemetry-and-leaderboard-rankings-of-slotcar-races-GETapi-races">GET /api/races - List race history and active registered GPs.</h2>

<p>
</p>



<span id="example-requests-GETapi-races">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/races" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/races"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-races">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">[
    {
        &quot;id&quot;: 5,
        &quot;track_id&quot;: 1,
        &quot;name&quot;: &quot;Teste 3&quot;,
        &quot;status&quot;: &quot;finished&quot;,
        &quot;type&quot;: &quot;lap_race&quot;,
        &quot;laps_limit&quot;: 10,
        &quot;duration_seconds&quot;: null,
        &quot;created_at&quot;: &quot;2026-05-28T02:59:35.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-28T03:01:08.000000Z&quot;,
        &quot;participants_count&quot;: 2,
        &quot;track&quot;: {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Interlagos (Fenda 2)&quot;,
            &quot;lanes_count&quot;: 2,
            &quot;length_meters&quot;: 12.5,
            &quot;best_lap_time&quot;: 1.694,
            &quot;best_lap_driver_id&quot;: 5,
            &quot;created_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-05-27T23:23:05.000000Z&quot;
        }
    },
    {
        &quot;id&quot;: 4,
        &quot;track_id&quot;: 1,
        &quot;name&quot;: &quot;Corrida Teste 2&quot;,
        &quot;status&quot;: &quot;finished&quot;,
        &quot;type&quot;: &quot;lap_race&quot;,
        &quot;laps_limit&quot;: 5,
        &quot;duration_seconds&quot;: null,
        &quot;created_at&quot;: &quot;2026-05-28T02:58:30.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-28T02:59:08.000000Z&quot;,
        &quot;participants_count&quot;: 2,
        &quot;track&quot;: {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Interlagos (Fenda 2)&quot;,
            &quot;lanes_count&quot;: 2,
            &quot;length_meters&quot;: 12.5,
            &quot;best_lap_time&quot;: 1.694,
            &quot;best_lap_driver_id&quot;: 5,
            &quot;created_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-05-27T23:23:05.000000Z&quot;
        }
    },
    {
        &quot;id&quot;: 3,
        &quot;track_id&quot;: 1,
        &quot;name&quot;: &quot;Corrida teste 1&quot;,
        &quot;status&quot;: &quot;finished&quot;,
        &quot;type&quot;: &quot;time_trial&quot;,
        &quot;laps_limit&quot;: null,
        &quot;duration_seconds&quot;: null,
        &quot;created_at&quot;: &quot;2026-05-27T23:18:26.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-27T23:23:33.000000Z&quot;,
        &quot;participants_count&quot;: 2,
        &quot;track&quot;: {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Interlagos (Fenda 2)&quot;,
            &quot;lanes_count&quot;: 2,
            &quot;length_meters&quot;: 12.5,
            &quot;best_lap_time&quot;: 1.694,
            &quot;best_lap_driver_id&quot;: 5,
            &quot;created_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-05-27T23:23:05.000000Z&quot;
        }
    },
    {
        &quot;id&quot;: 1,
        &quot;track_id&quot;: 1,
        &quot;name&quot;: &quot;Desafio Cl&aacute;ssico - Senna vs Prost&quot;,
        &quot;status&quot;: &quot;finished&quot;,
        &quot;type&quot;: &quot;lap_race&quot;,
        &quot;laps_limit&quot;: 5,
        &quot;duration_seconds&quot;: null,
        &quot;created_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
        &quot;participants_count&quot;: 1,
        &quot;track&quot;: {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Interlagos (Fenda 2)&quot;,
            &quot;lanes_count&quot;: 2,
            &quot;length_meters&quot;: 12.5,
            &quot;best_lap_time&quot;: 1.694,
            &quot;best_lap_driver_id&quot;: 5,
            &quot;created_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-05-27T23:23:05.000000Z&quot;
        }
    }
]</code>
 </pre>
    </span>
<span id="execution-results-GETapi-races" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-races"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-races"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-races" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-races">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-races" data-method="GET"
      data-path="api/races"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-races', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-races"
                    onclick="tryItOut('GETapi-races');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-races"
                    onclick="cancelTryOut('GETapi-races');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-races"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/races</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-races"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-races"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="races-endpoints-for-creation-state-control-telemetry-and-leaderboard-rankings-of-slotcar-races-POSTapi-races">POST /api/races - Create a new slotcar race defining track, type, limits, and participants.</h2>

<p>
</p>



<span id="example-requests-POSTapi-races">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/races" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"track_id\": \"architecto\",
    \"name\": \"n\",
    \"type\": \"time_trial\",
    \"laps_limit\": 67,
    \"duration_seconds\": 66,
    \"participants\": [
        {
            \"driver_id\": \"architecto\",
            \"car_id\": \"architecto\",
            \"lane_number\": 22
        }
    ]
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/races"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "track_id": "architecto",
    "name": "n",
    "type": "time_trial",
    "laps_limit": 67,
    "duration_seconds": 66,
    "participants": [
        {
            "driver_id": "architecto",
            "car_id": "architecto",
            "lane_number": 22
        }
    ]
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-races">
</span>
<span id="execution-results-POSTapi-races" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-races"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-races"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-races" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-races">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-races" data-method="POST"
      data-path="api/races"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-races', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-races"
                    onclick="tryItOut('POSTapi-races');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-races"
                    onclick="cancelTryOut('POSTapi-races');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-races"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/races</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-races"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-races"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>track_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="track_id"                data-endpoint="POSTapi-races"
               value="architecto"
               data-component="body">
    <br>
<p>The <code>id</code> of an existing record in the tracks table. Example: <code>architecto</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-races"
               value="n"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>n</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="type"                data-endpoint="POSTapi-races"
               value="time_trial"
               data-component="body">
    <br>
<p>Example: <code>time_trial</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>time_trial</code></li> <li><code>lap_race</code></li> <li><code>endurance</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>laps_limit</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="laps_limit"                data-endpoint="POSTapi-races"
               value="67"
               data-component="body">
    <br>
<p>Must be at least 1. Example: <code>67</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>duration_seconds</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="duration_seconds"                data-endpoint="POSTapi-races"
               value="66"
               data-component="body">
    <br>
<p>Must be at least 1. Example: <code>66</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>participants</code></b>&nbsp;&nbsp;
<small>object[]</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Must have at least 1 items.</p>
            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>driver_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="participants.0.driver_id"                data-endpoint="POSTapi-races"
               value="architecto"
               data-component="body">
    <br>
<p>The <code>id</code> of an existing record in the drivers table. Example: <code>architecto</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>car_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="participants.0.car_id"                data-endpoint="POSTapi-races"
               value="architecto"
               data-component="body">
    <br>
<p>The <code>id</code> of an existing record in the cars table. Example: <code>architecto</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>lane_number</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="participants.0.lane_number"                data-endpoint="POSTapi-races"
               value="22"
               data-component="body">
    <br>
<p>Must be at least 1. Example: <code>22</code></p>
                    </div>
                                    </details>
        </div>
        </form>

                    <h2 id="races-endpoints-for-creation-state-control-telemetry-and-leaderboard-rankings-of-slotcar-races-GETapi-races--id-">GET /api/races/{id} - Retrieve details of a specific race including track, participants, and telemetry log.</h2>

<p>
</p>



<span id="example-requests-GETapi-races--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/races/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/races/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-races--id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;id&quot;: 1,
    &quot;track_id&quot;: 1,
    &quot;name&quot;: &quot;Desafio Cl&aacute;ssico - Senna vs Prost&quot;,
    &quot;status&quot;: &quot;finished&quot;,
    &quot;type&quot;: &quot;lap_race&quot;,
    &quot;laps_limit&quot;: 5,
    &quot;duration_seconds&quot;: null,
    &quot;created_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
    &quot;updated_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
    &quot;track&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Interlagos (Fenda 2)&quot;,
        &quot;lanes_count&quot;: 2,
        &quot;length_meters&quot;: 12.5,
        &quot;best_lap_time&quot;: 1.694,
        &quot;best_lap_driver_id&quot;: 5,
        &quot;created_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-27T23:23:05.000000Z&quot;
    },
    &quot;participants&quot;: [
        {
            &quot;id&quot;: 2,
            &quot;race_id&quot;: 1,
            &quot;driver_id&quot;: 2,
            &quot;car_id&quot;: 3,
            &quot;lane_number&quot;: 2,
            &quot;status&quot;: &quot;finished&quot;,
            &quot;created_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
            &quot;driver&quot;: {
                &quot;id&quot;: 2,
                &quot;name&quot;: &quot;Alain Prost&quot;,
                &quot;nickname&quot;: &quot;Prost&quot;,
                &quot;avatar&quot;: null,
                &quot;created_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;
            },
            &quot;car&quot;: {
                &quot;id&quot;: 3,
                &quot;name&quot;: &quot;Williams FW14B&quot;,
                &quot;brand&quot;: &quot;NSR&quot;,
                &quot;model&quot;: &quot;FW14B&quot;,
                &quot;scale&quot;: &quot;1:32&quot;,
                &quot;driver_id&quot;: 2,
                &quot;created_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;
            }
        }
    ],
    &quot;lap_times&quot;: [
        {
            &quot;id&quot;: 2,
            &quot;race_id&quot;: 1,
            &quot;driver_id&quot;: 2,
            &quot;lane_number&quot;: 2,
            &quot;lap_number&quot;: 1,
            &quot;lap_time_seconds&quot;: 5.61,
            &quot;created_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
            &quot;driver&quot;: {
                &quot;id&quot;: 2,
                &quot;name&quot;: &quot;Alain Prost&quot;,
                &quot;nickname&quot;: &quot;Prost&quot;,
                &quot;avatar&quot;: null,
                &quot;created_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;
            }
        },
        {
            &quot;id&quot;: 4,
            &quot;race_id&quot;: 1,
            &quot;driver_id&quot;: 2,
            &quot;lane_number&quot;: 2,
            &quot;lap_number&quot;: 2,
            &quot;lap_time_seconds&quot;: 5.34,
            &quot;created_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
            &quot;driver&quot;: {
                &quot;id&quot;: 2,
                &quot;name&quot;: &quot;Alain Prost&quot;,
                &quot;nickname&quot;: &quot;Prost&quot;,
                &quot;avatar&quot;: null,
                &quot;created_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;
            }
        },
        {
            &quot;id&quot;: 6,
            &quot;race_id&quot;: 1,
            &quot;driver_id&quot;: 2,
            &quot;lane_number&quot;: 2,
            &quot;lap_number&quot;: 3,
            &quot;lap_time_seconds&quot;: 5.29,
            &quot;created_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
            &quot;driver&quot;: {
                &quot;id&quot;: 2,
                &quot;name&quot;: &quot;Alain Prost&quot;,
                &quot;nickname&quot;: &quot;Prost&quot;,
                &quot;avatar&quot;: null,
                &quot;created_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;
            }
        },
        {
            &quot;id&quot;: 8,
            &quot;race_id&quot;: 1,
            &quot;driver_id&quot;: 2,
            &quot;lane_number&quot;: 2,
            &quot;lap_number&quot;: 4,
            &quot;lap_time_seconds&quot;: 5.211,
            &quot;created_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
            &quot;driver&quot;: {
                &quot;id&quot;: 2,
                &quot;name&quot;: &quot;Alain Prost&quot;,
                &quot;nickname&quot;: &quot;Prost&quot;,
                &quot;avatar&quot;: null,
                &quot;created_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;
            }
        },
        {
            &quot;id&quot;: 10,
            &quot;race_id&quot;: 1,
            &quot;driver_id&quot;: 2,
            &quot;lane_number&quot;: 2,
            &quot;lap_number&quot;: 5,
            &quot;lap_time_seconds&quot;: 5.245,
            &quot;created_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
            &quot;driver&quot;: {
                &quot;id&quot;: 2,
                &quot;name&quot;: &quot;Alain Prost&quot;,
                &quot;nickname&quot;: &quot;Prost&quot;,
                &quot;avatar&quot;: null,
                &quot;created_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;
            }
        }
    ]
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-races--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-races--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-races--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-races--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-races--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-races--id-" data-method="GET"
      data-path="api/races/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-races--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-races--id-"
                    onclick="tryItOut('GETapi-races--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-races--id-"
                    onclick="cancelTryOut('GETapi-races--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-races--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/races/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-races--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-races--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-races--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the race. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="races-endpoints-for-creation-state-control-telemetry-and-leaderboard-rankings-of-slotcar-races-PUTapi-races--id-">PUT /api/races/{id} - Update basic race registration details.</h2>

<p>
</p>



<span id="example-requests-PUTapi-races--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost/api/races/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"b\",
    \"status\": \"in_progress\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/races/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "b",
    "status": "in_progress"
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-races--id-">
</span>
<span id="execution-results-PUTapi-races--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-races--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-races--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-races--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-races--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-races--id-" data-method="PUT"
      data-path="api/races/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-races--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-races--id-"
                    onclick="tryItOut('PUTapi-races--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-races--id-"
                    onclick="cancelTryOut('PUTapi-races--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-races--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/races/{id}</code></b>
        </p>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/races/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-races--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-races--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="PUTapi-races--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the race. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="PUTapi-races--id-"
               value="b"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>b</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="status"                data-endpoint="PUTapi-races--id-"
               value="in_progress"
               data-component="body">
    <br>
<p>Example: <code>in_progress</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>pending</code></li> <li><code>in_progress</code></li> <li><code>paused</code></li> <li><code>finished</code></li></ul>
        </div>
        </form>

                    <h2 id="races-endpoints-for-creation-state-control-telemetry-and-leaderboard-rankings-of-slotcar-races-DELETEapi-races--id-">DELETE /api/races/{id} - Delete a race and its telemetry history from the database.</h2>

<p>
</p>



<span id="example-requests-DELETEapi-races--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost/api/races/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/races/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-races--id-">
</span>
<span id="execution-results-DELETEapi-races--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-races--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-races--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-races--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-races--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-races--id-" data-method="DELETE"
      data-path="api/races/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-races--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-races--id-"
                    onclick="tryItOut('DELETEapi-races--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-races--id-"
                    onclick="cancelTryOut('DELETEapi-races--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-races--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/races/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-races--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-races--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="DELETEapi-races--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the race. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="races-endpoints-for-creation-state-control-telemetry-and-leaderboard-rankings-of-slotcar-races-POSTapi-races--id--start">POST /api/races/{id}/start - Start the race (changes status to in_progress and enables telemetry).</h2>

<p>
</p>



<span id="example-requests-POSTapi-races--id--start">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/races/1/start" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/races/1/start"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-races--id--start">
</span>
<span id="execution-results-POSTapi-races--id--start" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-races--id--start"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-races--id--start"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-races--id--start" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-races--id--start">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-races--id--start" data-method="POST"
      data-path="api/races/{id}/start"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-races--id--start', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-races--id--start"
                    onclick="tryItOut('POSTapi-races--id--start');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-races--id--start"
                    onclick="cancelTryOut('POSTapi-races--id--start');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-races--id--start"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/races/{id}/start</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-races--id--start"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-races--id--start"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="POSTapi-races--id--start"
               value="1"
               data-component="url">
    <br>
<p>The ID of the race. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="races-endpoints-for-creation-state-control-telemetry-and-leaderboard-rankings-of-slotcar-races-POSTapi-races--id--lap">POST /api/races/{id}/lap - Telemetry: Record a new lap time for a driver/lane, updating leaderboard and records.</h2>

<p>
</p>



<span id="example-requests-POSTapi-races--id--lap">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/races/1/lap" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"lane_number\": 16,
    \"lap_time_seconds\": 39
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/races/1/lap"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "lane_number": 16,
    "lap_time_seconds": 39
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-races--id--lap">
</span>
<span id="execution-results-POSTapi-races--id--lap" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-races--id--lap"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-races--id--lap"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-races--id--lap" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-races--id--lap">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-races--id--lap" data-method="POST"
      data-path="api/races/{id}/lap"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-races--id--lap', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-races--id--lap"
                    onclick="tryItOut('POSTapi-races--id--lap');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-races--id--lap"
                    onclick="cancelTryOut('POSTapi-races--id--lap');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-races--id--lap"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/races/{id}/lap</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-races--id--lap"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-races--id--lap"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="POSTapi-races--id--lap"
               value="1"
               data-component="url">
    <br>
<p>The ID of the race. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>lane_number</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="lane_number"                data-endpoint="POSTapi-races--id--lap"
               value="16"
               data-component="body">
    <br>
<p>Example: <code>16</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>driver_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="driver_id"                data-endpoint="POSTapi-races--id--lap"
               value=""
               data-component="body">
    <br>
<p>The <code>id</code> of an existing record in the drivers table.</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>lap_time_seconds</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="lap_time_seconds"                data-endpoint="POSTapi-races--id--lap"
               value="39"
               data-component="body">
    <br>
<p>Must be at least 0.001. Example: <code>39</code></p>
        </div>
        </form>

                    <h2 id="races-endpoints-for-creation-state-control-telemetry-and-leaderboard-rankings-of-slotcar-races-POSTapi-races--id--finish">POST /api/races/{id}/finish - Manually finish an ongoing race.</h2>

<p>
</p>



<span id="example-requests-POSTapi-races--id--finish">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/races/1/finish" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/races/1/finish"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-races--id--finish">
</span>
<span id="execution-results-POSTapi-races--id--finish" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-races--id--finish"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-races--id--finish"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-races--id--finish" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-races--id--finish">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-races--id--finish" data-method="POST"
      data-path="api/races/{id}/finish"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-races--id--finish', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-races--id--finish"
                    onclick="tryItOut('POSTapi-races--id--finish');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-races--id--finish"
                    onclick="cancelTryOut('POSTapi-races--id--finish');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-races--id--finish"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/races/{id}/finish</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-races--id--finish"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-races--id--finish"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="POSTapi-races--id--finish"
               value="1"
               data-component="url">
    <br>
<p>The ID of the race. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="races-endpoints-for-creation-state-control-telemetry-and-leaderboard-rankings-of-slotcar-races-POSTapi-races--id--pause">POST /api/races/{id}/pause - Pause an ongoing race.</h2>

<p>
</p>



<span id="example-requests-POSTapi-races--id--pause">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/races/1/pause" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/races/1/pause"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-races--id--pause">
</span>
<span id="execution-results-POSTapi-races--id--pause" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-races--id--pause"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-races--id--pause"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-races--id--pause" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-races--id--pause">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-races--id--pause" data-method="POST"
      data-path="api/races/{id}/pause"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-races--id--pause', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-races--id--pause"
                    onclick="tryItOut('POSTapi-races--id--pause');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-races--id--pause"
                    onclick="cancelTryOut('POSTapi-races--id--pause');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-races--id--pause"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/races/{id}/pause</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-races--id--pause"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-races--id--pause"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="POSTapi-races--id--pause"
               value="1"
               data-component="url">
    <br>
<p>The ID of the race. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="races-endpoints-for-creation-state-control-telemetry-and-leaderboard-rankings-of-slotcar-races-POSTapi-races--id--resume">POST /api/races/{id}/resume - Resume a paused race.</h2>

<p>
</p>



<span id="example-requests-POSTapi-races--id--resume">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/races/1/resume" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/races/1/resume"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-races--id--resume">
</span>
<span id="execution-results-POSTapi-races--id--resume" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-races--id--resume"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-races--id--resume"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-races--id--resume" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-races--id--resume">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-races--id--resume" data-method="POST"
      data-path="api/races/{id}/resume"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-races--id--resume', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-races--id--resume"
                    onclick="tryItOut('POSTapi-races--id--resume');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-races--id--resume"
                    onclick="cancelTryOut('POSTapi-races--id--resume');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-races--id--resume"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/races/{id}/resume</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-races--id--resume"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-races--id--resume"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="POSTapi-races--id--resume"
               value="1"
               data-component="url">
    <br>
<p>The ID of the race. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="races-endpoints-for-creation-state-control-telemetry-and-leaderboard-rankings-of-slotcar-races-GETapi-races--id--leaderboard">GET /api/races/{id}/leaderboard - Real-time leaderboard standings sorted by laps completed and lowest accumulated time.</h2>

<p>
</p>



<span id="example-requests-GETapi-races--id--leaderboard">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/races/1/leaderboard" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/races/1/leaderboard"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-races--id--leaderboard">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;race&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Desafio Cl&aacute;ssico - Senna vs Prost&quot;,
        &quot;status&quot;: &quot;finished&quot;,
        &quot;type&quot;: &quot;lap_race&quot;,
        &quot;laps_limit&quot;: 5,
        &quot;track&quot;: &quot;Interlagos (Fenda 2)&quot;
    },
    &quot;leaderboard&quot;: [
        {
            &quot;driver_id&quot;: 2,
            &quot;driver_name&quot;: &quot;Alain Prost&quot;,
            &quot;driver_nickname&quot;: &quot;Prost&quot;,
            &quot;car_name&quot;: &quot;Williams FW14B&quot;,
            &quot;lane_number&quot;: 2,
            &quot;status&quot;: &quot;finished&quot;,
            &quot;laps_completed&quot;: 5,
            &quot;best_lap&quot;: 5.211,
            &quot;total_time&quot;: 26.696,
            &quot;last_lap&quot;: 5.245,
            &quot;laps&quot;: [
                {
                    &quot;lap_number&quot;: 1,
                    &quot;lap_time_seconds&quot;: 5.61
                },
                {
                    &quot;lap_number&quot;: 2,
                    &quot;lap_time_seconds&quot;: 5.34
                },
                {
                    &quot;lap_number&quot;: 3,
                    &quot;lap_time_seconds&quot;: 5.29
                },
                {
                    &quot;lap_number&quot;: 4,
                    &quot;lap_time_seconds&quot;: 5.211
                },
                {
                    &quot;lap_number&quot;: 5,
                    &quot;lap_time_seconds&quot;: 5.245
                }
            ],
            &quot;position&quot;: 1
        }
    ]
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-races--id--leaderboard" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-races--id--leaderboard"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-races--id--leaderboard"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-races--id--leaderboard" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-races--id--leaderboard">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-races--id--leaderboard" data-method="GET"
      data-path="api/races/{id}/leaderboard"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-races--id--leaderboard', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-races--id--leaderboard"
                    onclick="tryItOut('GETapi-races--id--leaderboard');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-races--id--leaderboard"
                    onclick="cancelTryOut('GETapi-races--id--leaderboard');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-races--id--leaderboard"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/races/{id}/leaderboard</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-races--id--leaderboard"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-races--id--leaderboard"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-races--id--leaderboard"
               value="1"
               data-component="url">
    <br>
<p>The ID of the race. Example: <code>1</code></p>
            </div>
                    </form>

                <h1 id="tracks-endpoints-for-managing-slotcar-tracks-and-their-absolute-records">Tracks - Endpoints for managing slotcar tracks and their absolute records.</h1>

    

                                <h2 id="tracks-endpoints-for-managing-slotcar-tracks-and-their-absolute-records-GETapi-tracks">GET /api/tracks - List all registered tracks and their absolute lap records.</h2>

<p>
</p>



<span id="example-requests-GETapi-tracks">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/tracks" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/tracks"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-tracks">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">[
    {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Interlagos (Fenda 2)&quot;,
        &quot;lanes_count&quot;: 2,
        &quot;length_meters&quot;: 12.5,
        &quot;best_lap_time&quot;: 1.694,
        &quot;best_lap_driver_id&quot;: 5,
        &quot;created_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-27T23:23:05.000000Z&quot;,
        &quot;best_lap_driver&quot;: {
            &quot;id&quot;: 5,
            &quot;name&quot;: &quot;Joanna&quot;,
            &quot;nickname&quot;: &quot;Banana&quot;,
            &quot;avatar&quot;: null,
            &quot;created_at&quot;: &quot;2026-05-27T23:05:51.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-05-27T23:05:51.000000Z&quot;
        }
    },
    {
        &quot;id&quot;: 2,
        &quot;name&quot;: &quot;Monza Speed (Fenda 4)&quot;,
        &quot;lanes_count&quot;: 4,
        &quot;length_meters&quot;: 22.4,
        &quot;best_lap_time&quot;: null,
        &quot;best_lap_driver_id&quot;: null,
        &quot;created_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
        &quot;best_lap_driver&quot;: null
    }
]</code>
 </pre>
    </span>
<span id="execution-results-GETapi-tracks" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-tracks"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-tracks"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-tracks" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-tracks">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-tracks" data-method="GET"
      data-path="api/tracks"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-tracks', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-tracks"
                    onclick="tryItOut('GETapi-tracks');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-tracks"
                    onclick="cancelTryOut('GETapi-tracks');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-tracks"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/tracks</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-tracks"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-tracks"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="tracks-endpoints-for-managing-slotcar-tracks-and-their-absolute-records-POSTapi-tracks">POST /api/tracks - Register a new track with lane count and length.</h2>

<p>
</p>



<span id="example-requests-POSTapi-tracks">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/tracks" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"b\",
    \"lanes_count\": 4,
    \"length_meters\": 84
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/tracks"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "b",
    "lanes_count": 4,
    "length_meters": 84
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-tracks">
</span>
<span id="execution-results-POSTapi-tracks" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-tracks"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-tracks"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-tracks" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-tracks">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-tracks" data-method="POST"
      data-path="api/tracks"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-tracks', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-tracks"
                    onclick="tryItOut('POSTapi-tracks');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-tracks"
                    onclick="cancelTryOut('POSTapi-tracks');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-tracks"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/tracks</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-tracks"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-tracks"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-tracks"
               value="b"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>b</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>lanes_count</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="lanes_count"                data-endpoint="POSTapi-tracks"
               value="4"
               data-component="body">
    <br>
<p>Must be at least 1. Must not be greater than 8. Example: <code>4</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>length_meters</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="length_meters"                data-endpoint="POSTapi-tracks"
               value="84"
               data-component="body">
    <br>
<p>Must be at least 0. Example: <code>84</code></p>
        </div>
        </form>

                    <h2 id="tracks-endpoints-for-managing-slotcar-tracks-and-their-absolute-records-GETapi-tracks--id-">GET /api/tracks/{id} - Retrieve details of a specific track with its recent race history.</h2>

<p>
</p>



<span id="example-requests-GETapi-tracks--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/tracks/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/tracks/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-tracks--id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;id&quot;: 1,
    &quot;name&quot;: &quot;Interlagos (Fenda 2)&quot;,
    &quot;lanes_count&quot;: 2,
    &quot;length_meters&quot;: 12.5,
    &quot;best_lap_time&quot;: 1.694,
    &quot;best_lap_driver_id&quot;: 5,
    &quot;created_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
    &quot;updated_at&quot;: &quot;2026-05-27T23:23:05.000000Z&quot;,
    &quot;best_lap_driver&quot;: {
        &quot;id&quot;: 5,
        &quot;name&quot;: &quot;Joanna&quot;,
        &quot;nickname&quot;: &quot;Banana&quot;,
        &quot;avatar&quot;: null,
        &quot;created_at&quot;: &quot;2026-05-27T23:05:51.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-05-27T23:05:51.000000Z&quot;
    },
    &quot;races&quot;: [
        {
            &quot;id&quot;: 5,
            &quot;track_id&quot;: 1,
            &quot;name&quot;: &quot;Teste 3&quot;,
            &quot;status&quot;: &quot;finished&quot;,
            &quot;type&quot;: &quot;lap_race&quot;,
            &quot;laps_limit&quot;: 10,
            &quot;duration_seconds&quot;: null,
            &quot;created_at&quot;: &quot;2026-05-28T02:59:35.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-05-28T03:01:08.000000Z&quot;
        },
        {
            &quot;id&quot;: 4,
            &quot;track_id&quot;: 1,
            &quot;name&quot;: &quot;Corrida Teste 2&quot;,
            &quot;status&quot;: &quot;finished&quot;,
            &quot;type&quot;: &quot;lap_race&quot;,
            &quot;laps_limit&quot;: 5,
            &quot;duration_seconds&quot;: null,
            &quot;created_at&quot;: &quot;2026-05-28T02:58:30.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-05-28T02:59:08.000000Z&quot;
        },
        {
            &quot;id&quot;: 3,
            &quot;track_id&quot;: 1,
            &quot;name&quot;: &quot;Corrida teste 1&quot;,
            &quot;status&quot;: &quot;finished&quot;,
            &quot;type&quot;: &quot;time_trial&quot;,
            &quot;laps_limit&quot;: null,
            &quot;duration_seconds&quot;: null,
            &quot;created_at&quot;: &quot;2026-05-27T23:18:26.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-05-27T23:23:33.000000Z&quot;
        },
        {
            &quot;id&quot;: 1,
            &quot;track_id&quot;: 1,
            &quot;name&quot;: &quot;Desafio Cl&aacute;ssico - Senna vs Prost&quot;,
            &quot;status&quot;: &quot;finished&quot;,
            &quot;type&quot;: &quot;lap_race&quot;,
            &quot;laps_limit&quot;: 5,
            &quot;duration_seconds&quot;: null,
            &quot;created_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-05-27T22:07:49.000000Z&quot;
        }
    ]
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-tracks--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-tracks--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-tracks--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-tracks--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-tracks--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-tracks--id-" data-method="GET"
      data-path="api/tracks/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-tracks--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-tracks--id-"
                    onclick="tryItOut('GETapi-tracks--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-tracks--id-"
                    onclick="cancelTryOut('GETapi-tracks--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-tracks--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/tracks/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-tracks--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-tracks--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-tracks--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the track. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="tracks-endpoints-for-managing-slotcar-tracks-and-their-absolute-records-PUTapi-tracks--id-">PUT /api/tracks/{id} - Update track data and configure or reset lap records.</h2>

<p>
</p>



<span id="example-requests-PUTapi-tracks--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost/api/tracks/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"b\",
    \"lanes_count\": 4,
    \"length_meters\": 84,
    \"best_lap_time\": 12
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/tracks/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "b",
    "lanes_count": 4,
    "length_meters": 84,
    "best_lap_time": 12
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-tracks--id-">
</span>
<span id="execution-results-PUTapi-tracks--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-tracks--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-tracks--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-tracks--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-tracks--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-tracks--id-" data-method="PUT"
      data-path="api/tracks/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-tracks--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-tracks--id-"
                    onclick="tryItOut('PUTapi-tracks--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-tracks--id-"
                    onclick="cancelTryOut('PUTapi-tracks--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-tracks--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/tracks/{id}</code></b>
        </p>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/tracks/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-tracks--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-tracks--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="PUTapi-tracks--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the track. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="PUTapi-tracks--id-"
               value="b"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>b</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>lanes_count</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="lanes_count"                data-endpoint="PUTapi-tracks--id-"
               value="4"
               data-component="body">
    <br>
<p>Must be at least 1. Must not be greater than 8. Example: <code>4</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>length_meters</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="length_meters"                data-endpoint="PUTapi-tracks--id-"
               value="84"
               data-component="body">
    <br>
<p>Must be at least 0. Example: <code>84</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>best_lap_time</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="best_lap_time"                data-endpoint="PUTapi-tracks--id-"
               value="12"
               data-component="body">
    <br>
<p>Must be at least 0. Example: <code>12</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>best_lap_driver_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="best_lap_driver_id"                data-endpoint="PUTapi-tracks--id-"
               value=""
               data-component="body">
    <br>
<p>The <code>id</code> of an existing record in the drivers table.</p>
        </div>
        </form>

                    <h2 id="tracks-endpoints-for-managing-slotcar-tracks-and-their-absolute-records-DELETEapi-tracks--id-">DELETE /api/tracks/{id} - Delete a registered track and its records from the database.</h2>

<p>
</p>



<span id="example-requests-DELETEapi-tracks--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost/api/tracks/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/tracks/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-tracks--id-">
</span>
<span id="execution-results-DELETEapi-tracks--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-tracks--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-tracks--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-tracks--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-tracks--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-tracks--id-" data-method="DELETE"
      data-path="api/tracks/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-tracks--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-tracks--id-"
                    onclick="tryItOut('DELETEapi-tracks--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-tracks--id-"
                    onclick="cancelTryOut('DELETEapi-tracks--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-tracks--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/tracks/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-tracks--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-tracks--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="DELETEapi-tracks--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the track. Example: <code>1</code></p>
            </div>
                    </form>

            

        
    </div>
    <div class="dark-box">
                    <div class="lang-selector">
                                                        <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                                        <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                            </div>
            </div>
</div>
</body>
</html>
