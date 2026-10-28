<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books app</title>
    {{--CDN TABLER --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.5.1/dist/css/tabler.min.css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.1/css/all.min.css"
        integrity="sha512-QeR2VH+lsBE5LSAe1Q5EnTBbe7XTBubt8dG93Y7gidSgdMCr8nVqKcfKAMyN96SV8KDbZVTDXChatu5G2KQGzg=="
        crossorigin="anonymous" referrerpolicy="no-referrer">

    <!-- CDN JQUERY -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- CDN SLICKJS -->
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />
    {{-- CDN DataTables --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/3.0.4/css/dataTables.dataTables.min.css" />
    <!-- CDN Quill js -->
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
    <style>
        html,
        body {
            overflow-x: hidden
        }
    </style>
    @stack('styles')
</head>

<body>
    <x-navbar />
    @yield('content')
    <x-footer />
    {{--CDN TABLER --}}
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.5.1/dist/js/tabler.min.js"></script>
    {{-- CDN DataTables --}}
    <script src="https://cdn.datatables.net/3.0.4/js/dataTables.min.js"></script>
    <!-- CDN SLICKJS -->
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <!-- CDN Quill js -->
     <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
      
    @stack('scripts')
</body>


</html>