@section('title') 
CodeWithUs - Register
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
                        
                            <form class="form-horizontal" method="POST" action="{{ route('students-register-page1') }}">
                                {{ csrf_field() }}
                                <input type="hidden" id="role_type"  name="role_type" value="student">
                                    <div class="text-center mb-3">
                                        <h4 class="text-black">Create New Account</h4>
                                        <p class="text-muted">Already have an account? <a href="{{ route('login') }}">Sign in</a> Here</p>
                                    </div>   
                                    @if ($message = Session::get('duplicate_user_name'))
                                        <div class="alert alert-danger alert-block">
                                        <button type="button" class="close" data-dismiss="alert">×</button>
                                        <strong>{{ $message }}</strong>
                                        </div>
                                    @endif
                                    <div class="form-group">
                                        <input placeholder="User Name" id="user_name"  type="text" class="form-control" name="user_name" value="{{ old('name') }}" required autofocus>
                                        @if ($errors->has('user_name'))
                                            <span class="help-block" style="color:red" >
                                                <strong>{{ $errors->first('user_name') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <input placeholder="Password" id="password" type="password" class="form-control" name="password" minlength=3 required>
                                    </div>

                                                        
                                  <button type="submit" class="btn btn-primary btn-rounded btn-lg btn-block">Create an Account</button>
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