<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    
    <!-- Lineicons CDN -->
    <link rel="stylesheet" href="https://cdn.lineicons.com/4.0/lineicons.css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            background: #111;
            color: #ccc;
            font-family: sans-serif;
            overflow-x: hidden; /* Mencegah scroll horizontal */
        }

        /* Misalnya sidebar collapse width = 70px, expanded width = 260px.
           Kita akan atur main-content agar menyesuaikan.
        */

        /* Body dengan sidebar collapse */
        body:not(.sidebar-open) .main-content {
            margin-left: 70px; /* Ruang untuk sidebar collapse */
            width: calc(100% - 70px);
        }

        /* Body dengan sidebar open */
        body.sidebar-open .main-content {
            margin-left: 260px; /* Jika sidebar expand menjadi 260px */
            width: calc(100% - 260px);
        }

        .main-content {
            transition: margin-left 0.3s ease, width 0.3s ease;
            padding: 1rem;
            box-sizing: border-box;
            position: relative;
            /* Tidak perlu absolute jika layout normal */
        }

        /* Overlay efek jika sidebar expanded, jika diinginkan */
        body.sidebar-open .main-content::before {
            content: "";
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 5000;
        }
    </style>
</head>
<body>
    @include('layouts.sidebar') 

    <div class="main-content">
        @yield('content')
    </div>
</body>
</html>
