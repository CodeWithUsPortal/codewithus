@section('title') 
CodeWithUs - Dashboard
@endsection 
@extends('layouts.main')
@section('style')
<!-- Chartist Chart CSS -->
<link href="{{ asset('assets/plugins/chartist-js/chartist.min.css') }}" rel="stylesheet" type="text/css" />
<!-- Datepicker CSS -->
<link href="{{ asset('assets/plugins/datepicker/datepicker.min.css') }}" rel="stylesheet" type="text/css" />
@endsection 
@section('leftbar')
    @include('layouts.parent_menu') 
@endsection 
@section('rightbar-content')
<!-- Start XP Breadcrumbbar -->                    
<div class="xp-breadcrumbbar">
    <div class="row">
        <div class="col-md-6 col-lg-6">
            <h4 class="xp-page-title">Billing And Payment</h4>
        </div>
        <div class="col-md-6 col-lg-6">
        </div>
    </div>          
</div>
<!-- End XP Breadcrumbbar -->
<!-- Start XP Contentbar -->    

<div class="xp-contentbar">
    <div class="row">              
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <p style="color:red">You need to provide an email address before you can continue to billing</p>
                    <br/>
                   
                        <form class="form-horizontal" method="POST" action="{{ route('add_parents_email_id') }}">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email Id" required>
                            </div>
                                            
                            <button type="submit" class="btn btn-primary btn-rounded btn-lg btn-block">Add Email Id</button>
                        </form>
                    
                    <br/><br/><h6 style="color:black">Please, remember this email address as it will be used as your stripe email address. </h6>
                </div> 
            </div> 
        </div>   
    </div>
</div>        

<!-- End XP Contentbar -->
@endsection 
@section('script')
<!-- Chartist Chart JS -->
<script src="{{ asset('assets/plugins/chartist-js/chartist.min.js') }}"></script>
<script src="{{ asset('assets/plugins/chartist-js/chartist-plugin-tooltip.min.js') }}"></script>
<!-- To Do List JS -->
<script src="{{ asset('assets/js/init/to-do-list-init.js') }}"></script>
<!-- Datepicker JS -->
<script src="{{ asset('assets/plugins/datepicker/datepicker.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datepicker/i18n/datepicker.en.js') }}"></script>
<!-- Dashboard JS -->
<script src="{{ asset('assets/js/init/dashborad.js') }}"></script>
@endsection 