@section('title') 
Codewithus 
@endsection
@extends('layouts.main')
@section('style')
<script src="https://unpkg.com/vue"></script>
<script src="https://unpkg.com/vue-cal"></script>
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
                                <a href="{{url('/')}}" class="xp-web-logo"><img src="{{ asset('assets/images/logo.png') }}" height="40" alt="logo"></a>
                            </h3>
                            <div class="text-center mb-3">
                                <h4 class="text-black">Forgot Username</h4>
                                <p class="text-muted">Wants to Sign In? <a href="{{ route('login') }}">Sign In</a> Here</p>
                            </div>     
                            <div class="p-3">
                                <div id="app_vue">
                                    <username-reset></username-reset>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End XP Auth Box -->
            </div>
            <script type="text/javascript" src="http://localhost/codewithus/public/js/app.js"></script> 
        </div>
        <!-- End XP Row -->
    </div>
    <!-- End Container -->
</div>
<!-- End XP Container -->
@section('script')

@endsection 