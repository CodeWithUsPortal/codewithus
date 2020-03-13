@section('title') 
CodeWithUs - Login
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
                            <div class="p-3">
                          
                                <div class="text-center mb-3">
                                    <h4 class="text-black">Free Session !</h4>
                                    <p class="text-muted">Already have an account? <a href="{{ route('login') }}">Sign In</a> Here</p>
                                </div>                                        
                                <a href="{{ route('free-session-signup') }}" type="button" class="btn btn-primary btn-rounded btn-lg btn-block">Sign-Up for a Free Session</a>
                                <br/>           
                                <a href="{{ route('free-session-signin') }}" type="button" class="btn btn-primary btn-rounded btn-lg btn-block">Sign-In for the Free Session</a>
                                <br/>
                                
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

@endsection 