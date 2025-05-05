<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>COE</title>
    
    <!-- CSS files -->
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/admin-styles.css')}}">
    
    <!-- jQuery -->
    <script src="{{asset('js/jquery-3.6.0.min.js')}}"></script>
    
    <!-- Additional head content -->
    @yield('styles')
</head>
<body>
    <!-- Include header -->
    @include('layoutsbootstrap.header')

    <!-- Include sidebar -->
    @include('layoutsbootstrap.sidebar')

    <!-- Main content area -->
    <div class="main-content">
        @yield('konten')
    </div>

    <!-- Include footer -->
    @include('layoutsbootstrap.footer')
    
    <!-- JavaScript files -->
    <script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>
    
    <!-- Additional scripts -->
    @yield('scripts')
</body>
</html>