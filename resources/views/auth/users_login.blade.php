@section('title') 
Codewithus - Login
@endsection
@extends('layouts.main')
@section('style')

@endsection
<div class="xp-authenticate-bg"></div>
<!-- Start XP Container -->
<div id="xp-container" class="xp-container">
    <!-- Start Container -->
    <div class="container">
        <!-- Start XP Row -->
        <div class="row vh-100 align-items-center">
            <!-- Start XP Col -->
            <div class="col-lg-12 ">
                <!-- Start XP Auth Box -->
                <div class="xp-auth-box">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="text-center mt-0 m-b-15">
                                <a href="{{url('/')}}" class="xp-web-logo"><img src="assets/images/logo.png" height="40" alt="logo"></a>
                            </h3>
                            <div class="text-center mb-3">
                                <h4 class="text-black">Sign In !</h4>
                                <p class="text-muted">New to CodeWithUs? <a href="{{ route('register-options') }}">Sign Up</a> Here</p>
                            </div>                                        
                                   
                            <div class="p-3">
                                <div id="app_vue">
                                    <login-component></login-component>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End XP Auth Box -->
            </div>
            <!-- End XP Col -->
        </div>
        <!-- End XP Row -->
    </div>
    <!-- End Container -->
</div>
<!-- End XP Container -->

@section('script')
<script type="text/javascript" src="{{ asset('js/app.js') }}"></script> 
@endsection 

