<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/png" href="{{ URL('/') }}/assets/images/favicon.ico" />
    <title>@yield('title')</title>
    <meta name="description" content="Point of Sale System">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    {{-- <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" type="text/css"> --}}

    <!-- Font Awesome CSS (for icons) -->
    {{-- <link rel="stylesheet" href="{{ asset('assets/vendor/font-awesome/css/font-awesome.min.css') }}" type="text/css"> --}}

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">

    <!-- Bootstrap JS -->
    {{-- <script type="text/javascript" src="{{ asset('assets/vendor/bootstrap/js/bootstrap.min.js') }}"></script> --}}

    <!-- Custom Stylesheet (for POS styling) -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" type="text/css">

    <!-- Toastr (for notifications) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- POS-specific custom script -->
    {{-- <script type="text/javascript" src="{{ asset('js/front.js') }}"></script> --}}

</head>

<body>

    @yield('content')

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

    <!-- DataTables Initialization -->

    <script>
        $(document).ready(function() {
            $('#product-table').DataTable({
                "paging": true,              // Enable pagination
                "lengthChange": false,       // Disable page length change (if needed)
                "searching": true,           // Enable search functionality
                "ordering": false,           // Enable column sorting
                "info": false,                // Show info about current page and total
                "autoWidth": false,          // Disable auto-width
                "responsive": true,          // Ensure responsiveness on smaller screens
                "pageLength": 3,             // Number of rows per page
            });
        });

    </script>
    @include('pos.js')

</body>

</html>
