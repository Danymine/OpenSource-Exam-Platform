<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <!--Da capire come integrare bootstrap dopo <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">-->
        <style>
            .button {
                background-color: #04AA6D; /* Green */
                border: none;
                color: white;
                padding: 15px 32px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;
                margin: 0px 0px 1em 0px;
            }

            .row-practice{

                display: none;
            }

            tr{

                cursor: pointer;
            }

            tr:hover {
                background-color: rgba(255, 255, 255, 0.1); /* Sostituisci con il colore desiderato */
            }
        </style>
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        <script>
            function showExams() {

                @if ( Auth::user()->roles == "Teacher" || Auth::user()->roles == "Admin")

                    var story = document.getElementById('StoricoEsercitazioni');
                    if( story != null ){

                        story.style.display = "none";
                        story = document.getElementById('StoricoEsami').style.display = "block";
                    }
                    showRows("exame");
                @else

                    var chart = document.getElementById("Chartpractice");
                    if( chart != null ){

                        chart.style.display = "none";
                        chart = document.getElementById("Chartexame").style.display = "block";
                    }
                    showRows("exame");
        
                @endif
            }

            function showPractices() {

                @if ( Auth::user()->roles == "Teacher" || Auth::user()->roles == "Admin")

                    var story = document.getElementById('StoricoEsami');
                    if( story != null ){

                        story.style.display = "none";
                        story = document.getElementById('StoricoEsercitazioni').style.display = "block";
                    }
                    showRows("practice");
                @else

                    var chart = document.getElementById("Chartexame");
                    if( chart != null ){

                        chart.style.display = "none";
                        chart = document.getElementById("Chartpractice").style.display = "block";
                    }
                    showRows("practice");
                @endif
            }

            function showRows(type) {
                var rows = document.querySelectorAll(".row-type");

                rows.forEach(function(row) {

                    if (row.classList.contains("row-" + type)) {

                        row.style.display = "table-row";
                    } else {
                        row.style.display = "none";
                    }
                });
            }
        </script>
        <!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>-->
    </body>
</html>
