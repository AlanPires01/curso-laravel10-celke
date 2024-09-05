<!DOCTYPE html>
    <html lang="pt-br">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Alan</title>
            <link rel="icon" href="{{asset('images/favicon.ico')}}" type="image/x-icon">

           
            @vite(['resources/sass/app.scss', 'resources/js/app.js','resources/css/app.css'])

{{--             <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
 --}}
            {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" /> --}}
            
            {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> --}}
            
            {{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}

            {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
        </head>
    <body>
        <header class="p-3 text-bg-primary">
            <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"></use></svg>
                </a>

                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                <li><a href="#" class="nav-link px-2 text-secondary">Home</a></li>
                <li><a href="#" class="nav-link px-2 text-white">Contas</a></li>
            
                </ul>


                <div class="text-end">
                <button type="button" class="btn btn-warning">Login</button>
                </div>


            </div>
            </div>
        </header>

        <div class="container">
           @yield('content')
        </div>
        
{{--         <script src="{{asset('/js/custom.js')}}"></script>--}}    
</body>

</html>