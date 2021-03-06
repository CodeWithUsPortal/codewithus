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
                    
                @if ($error != null && $error != "")
                   <p style="color:red">{{ $error }}</p>
                @endif
                    <form class="form-horizontal" method="POST" action="{{ route('promos.show') }}">
                    {{ csrf_field() }}
                        <div class="text-center mb-3">
                            <h4 class="text-black">Promo Code</h4>
                        </div>   
                        <div class="text-center mb-3">                                     
                        <div class="form-group ">
                            <input type="text" class="form-control" id="promo_code" name="promo_code" placeholder="Promo code" required>
                        </div>
                        </div>                
                        <button type="submit" class="btn btn-primary btn-rounded btn-lg btn-block">Choose</button>
                    </form>
                    <br/><br/><br/>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach($plans as $plan)
                            <li class="list-group-item clearfix">
                                <div>
                                    <h5>{{ $plan['product_name'] }}</h5>
                                    <a href="{{ route('plans.show', $plan['product_id']) }}" class="btn btn-outline-dark">Choose</a>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>

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