<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <style>
        * {
            font-size: 10px;
            /* font-family: monospace; Use a monospaced font */
            font-family: 'Ubuntu', sans-serif;
            margin: 0;
            padding: 0;
            line-height: 24px;

        }

        .container {
            width: 70mm; /* Standard thermal paper width */
            margin: 0 auto;
            padding: 5px;
        }

        .header {
            margin-top:0px;
            text-align: center;
        }
        .header div {
            font-size: 14px;
            line-height: 20px;
            font-weight: bold;
        }
        .header img{
            width: 270px; 
            height: auto;
        }

        .title {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .date {
            font-size: 10px;
            margin-bottom: 5px;
        }

        .divider {
            border-top: 1px dashed #000;
        }

        
        .register-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            border: 1px solid black;
            border-bottom: 1px dashed black;
        }

        .register-table th,
        .register-table td {
            /* border: 1px solid #000; */
            padding: 3px;
            font-size: 10px;
        }

        .register-table th {
            background-color: #f0f0f0;
        }

        @media print {
            .hidden-print {
                display: none !important;
            }
            @page {
                size: 70mm auto; /* Width 80mm, height auto */

            }
        }
    </style>
</head>
<body>
    <div class="header">
        @if($company && $company->logo)
            <img src="{{ asset('company/' . $company->logo) }}" alt="Company Logo">
        @else
        <img  alt="Logo">
        @endif
        @if($company)
            <div>{{ $company->name }}</div>
        @endif
    </div>
    @yield('content')
</body>
</html>