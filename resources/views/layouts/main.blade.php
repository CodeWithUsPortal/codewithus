<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="description" content="Neon is a Laravel Responsive Bootstrap 4 Admin Dashboard Template">
        <meta name="keywords" content="admin, admin template, admin panel, dashboard template, laravel, ui kits, web app, crm, cms, responsive, bootstrap 4, html, sass support, scss">
        <meta name="author" content="Themesbox">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title> @yield('title') </title>
        <!-- Fevicon -->
        <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">
        <!-- Start CSS -->   
        @yield('style')
        <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css">
   
        <!-- End CSS -->
        <!-- Vue Cal Scripts -->
        <script src="https://unpkg.com/vue"></script>
        <script src="https://unpkg.com/vue-cal"></script>
        <link href="https://unpkg.com/vue-cal/dist/vuecal.css" rel="stylesheet">
        <script src="https://js.stripe.com/v3/"></script>
    </head>
    <body class="xp-vertical">
        <!-- Search Modal -->
        <div class="modal search-modal fade" id="xpSearchModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="xp-searchbar">
                            <form>
                                <div class="input-group">
                                    <input type="search" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="button-addon2">
                                    <div class="input-group-append">
                                        <button class="btn" type="submit" id="button-addon2">GO</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Start XP Container -->
        <div id="xp-container">     
            <!-- Start XP Leftbar -->
            @yield('leftbar')
           
            <!-- End XP Leftbar -->
            <!-- Start XP Rightbar -->
            @include('layouts.rightbar')  
            @yield('content')
            <!-- End XP Rightbar -->  
        </div>
        <!-- End XP Container -->
        <!-- Start JS -->        
        <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/js/popper.min.js') }}"></script>
        <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('assets/js/modernizr.min.js') }}"></script>
        <script src="{{ asset('assets/js/detect.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.slimscroll.js') }}"></script>
        <script src="{{ asset('assets/js/sidebar-menu.js') }}"></script> 
        @yield('script')
        <!-- Main JS -->
        <script src="{{ asset('assets/js/main.js') }}"></script>
        <!-- End JS -->
    </body>
</html>    