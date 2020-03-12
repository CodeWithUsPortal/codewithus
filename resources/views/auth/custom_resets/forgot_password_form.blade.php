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
                                <a href="{{url('/')}}" class="xp-web-logo"><img src="{{asset('assets/images/logo.png')}}" height="40" alt="logo"></a>
                            </h3>
                            <div class="p-3">
                                 @if ($error != null && $error != "")
                                <div class="alert alert-danger alert-block">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    <strong>{{ $error }}</strong>
                                </div>
                                @endif
                            <form class="form-horizontal" method="POST" action="{{ route('reset-password') }}">
                                {{ csrf_field() }}

                                    <div class="text-center mb-3">
                                        <h4 class="text-black">Set a New Password !</h4>
                                        <p class="text-muted">Remember your credentials? <a href="{{ route('login') }}"> Sign In</a> Here</p>
                                    </div>                                        
                                   
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="user_name" name="user_name" placeholder="Username" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required minlength="3">
                                    </div>
                                    <div class="form-group col-6">
                                        <label class="forgot-psw"> 
                                           <a id="forgot-psw" href="{{ route('reset-username') }}">Forgot Username?</a>
                                        </label>
                                    </div>              
                                    <button type="submit" class="btn btn-primary btn-rounded btn-lg btn-block">Submit</button>
                                
                                </form>
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