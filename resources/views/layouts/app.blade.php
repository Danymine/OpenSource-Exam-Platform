<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>ExamSync</title>
        <link rel="icon" href="/system/Logo.jpg" type="image/png">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
        <!-- Fonts -->
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
        <script src="https://cdn.lordicon.com/lordicon.js"></script>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,500,700&display=swap">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

        <style>
            body {
                font-family: 'Roboto', sans-serif;
            }
            
            h1 {
                font-family: 'Roboto', sans-serif;
                font-weight: 700;
            }
       
            p {
                font-family: 'Roboto', sans-serif;
                font-weight: 400;
                color: #CCCCCC;
            }

            .navbar-toggler-icon {
                color: white !important;
            }

            ul.navbar-nav > li > a{

                color: #CCCCCC;
                font-size: 1.5em;
            }

            ul.navbar-nav > li > a:hover{

                font-weight: bold;
            }

            #iscriviti{

                background-color: #286445;
            }

            #iscriviti:hover{

                background-color: #183e2b;
            }

            .hide{

                display: none;
            }

            @media (max-width: 768px) {
                .navbar-nav-ms-auto {
                    display: none;
                }

                label{

                    margin-top: 10px;
                }

                #menu{

                    display: none;
                }

                .hide{

                    display: block;
                }

            }

            .overlay {
                height: 100%;
                width: 0;
                position: fixed;
                z-index: 1;
                top: 0;
                right: 0;
                background-color: rgb(0,0,0);
                background-color: rgba(0,0,0, 0.9);
                overflow-x: hidden;
                transition: 0.5s;
            }
            

            .overlay-content {
                position: relative;
                top: 25%;
                width: 100%;
                text-align: center;
                margin-top: 30px;
            }

            .overlay-content ul {
                list-style-type: none;
                padding: 0;
                margin: 0;
            }

            .overlay-content ul li {
                margin-bottom: 10px; /* Aggiungi spazio tra le voci di menu */
            }

            .overlay a {
                padding: 8px;
                text-decoration: none;
                font-size: 24px; /* Riduci la dimensione del testo per adattarsi meglio */
                color: #818181;
                display: block;
                transition: 0.3s;
            }

            .overlay a:hover, .overlay a:focus {
                color: #f1f1f1;
            }

            .overlay .closebtn {
                position: absolute;
                top: 20px;
                left: 45px;
                font-size: 60px;
            }

            .menu-divider {
                border-top: 1px solid #ccc; /* Cambia il colore a tuo piacimento */
                width: 80%;
            }

            .custom-container {
                max-width: 600px; /* Imposta la larghezza massima desiderata */
                margin-right: auto;
                margin-left: auto;
            }

            :root {
                --fc-border-color: none;
                --fc-event-bg-color: #ce502f;
                --fc-button-border-color: #ffffff;
                --fc-button-hover-bg-color: #218838;
                --fc-button-hover-border-color: #ffffff;
                --fc-button-bg-color: #28a745;
                --fc-daygrid-event-dot-width: 5px;
            }

            body{
                background-color:#f2f6fc;
            }

            .img-account-profile {
                height: 10rem;
            }

            .rounded-circle {
                border-radius: 50% !important;
            }

            .card {
                box-shadow: 0 0.15rem 1.75rem 0 rgb(33 40 50 / 15%);
            }

            .card .card-header {
                font-weight: 500;
            }

            .card-header:first-child {
                border-radius: 0.35rem 0.35rem 0 0;
            }

            .card-header {
                padding: 1rem 1.35rem;
                margin-bottom: 0;
                background-color: rgba(33, 40, 50, 0.03);
                border-bottom: 1px solid rgba(33, 40, 50, 0.125);
            }

            .form-control, .dataTable-input {
                display: block;
                width: 100%;
                padding: 0.875rem 1.125rem;
                font-size: 0.875rem;
                font-weight: 400;
                line-height: 1;
                color: #69707a;
                background-color: #fff;
                background-clip: padding-box;
                border: 1px solid #c5ccd6;
                -webkit-appearance: none;
                -moz-appearance: none;
                appearance: none;
                border-radius: 0.35rem;
                transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            }

            .nav-borders .nav-link.active {
                color: #0061f2;
                border-bottom-color: #0061f2;
            }
            
            .nav-borders .nav-link {
                color: #69707a;
                border-bottom-width: 0.125rem;
                border-bottom-style: solid;
                border-bottom-color: transparent;
                padding-top: 0.5rem;
                padding-bottom: 0.5rem;
                padding-left: 0;
                padding-right: 0;
                margin-left: 1rem;
                margin-right: 1rem;
            }
            
            .circle-container {

                display: flex;
                justify-content: space-between;
                align-items: center;
                position: relative;
            }

            .active-circle{

                background-color: #010039 !important;
            }

            .circle {

                width: 50px;
                height: 50px;
                border-radius: 50%;
                background-color: #007bff; /* colore dei cerchi */
                display: inline-flex;
                justify-content: center;
                align-items: center;
                color: white; /* colore del testo */
                z-index: 2;
            }

            .connector {

                height: 3px;
                background-color: #007bff; /* colore del collegamento */
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                z-index: 1;
            }

            .connector-line {

                width: calc(100% - 100px); /* Larghezza del connettore */
                height: 3px;
                background-color: #007bff; /* colore del collegamento */
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translateX(-50%);
                z-index: 0;
            }

            .connector-line:nth-child(1) {

                margin-left: 50px; /* Distanza tra cerchio 1 e 2 */
            }

            .connector-line:nth-child(2) {

                margin-right: 50px; /* Distanza tra cerchio 2 e 3 */
            }

            .small-container {

                max-width: 400px; /* Larghezza massima del container interno */
                margin: 0 auto; /* Centrare il container */
            }

            .filter-select {
                height: calc(2.25rem + 2px);
                padding: 0.375rem 0.75rem;
                font-size: 1rem;
                font-weight: 400;
                line-height: 1.5;
                color: #495057;
                background-color: #fff;
                background-image: none;
                border: 1px solid #ced4da;
                border-radius: 0.25rem;
                transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            }

            .range-labels {
                display: flex;
                justify-content: space-between;
                margin-top: 5px;
            }

            .range-label {
                font-size: 12px;
                position: absolute;
                pointer-events: none;
            }

            #punteggioMinLabel {
                left: 0;
            }

            #punteggioMaxLabel {
                right: 0;
            }

            .text-black{

                color: black !important;
            }

            .overlay-chart {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 999;
            }
            
            .aggiungiModulo {
                display: none;
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background-color: white;
                padding: 20px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                z-index: 1000;
                border-radius: 10px;
                max-width: 600px; /* Imposta la larghezza massima del riquadro */
                width: 100%;
            }

            .hide-total{

                display: none;
            }
            
            .waiting-container{
                max-width: 400px;
                margin: 100px auto;
                padding: 20px;
                background-color: #fff;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            .loader {
                border: 8px solid #f3f3f3; /* Light grey */
                border-top: 8px solid #3498db; /* Blue */
                border-radius: 50%;
                width: 50px;
                height: 50px;
                animation: spin 2s linear infinite;
                margin: 0 auto;
                margin-top: 20px;
            }
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }

            @media screen and (max-width: 425px) {
                .d-flex {
                    flex-direction: column;
                    align-items: center;
                    text-align: center;
                }
                .form-inline {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                }
                .form-control {
                    width: 100%;
                    margin-bottom: 10px;
                }
            }

            .equal-height {
                height: 38px;
            }

    </style>
</head>
<body>

        </style>
    </head>
    <body>
        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
            <header>
                <div class="container-fluid p-3">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>

        <script>
            function openNav() {
                document.getElementById("myNav").style.width = "20%";
            }

            function closeNav() {
                document.getElementById("myNav").style.width = "0%";
            }
        </script>

        <footer class="footer mt-5 py-3 text-white" style="background-color: #010039;">
            <div class="container text-center">
                <span style="color: #FFFFFF">{{ __('© 2024 ProgettoLaravel Tutti i diritti riservati') }}</span>
            </div>
        </footer>
        
       
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    
    </body>
</html>
