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
    @include('layouts.teacher_menu') 
@endsection 
@section('rightbar-content')

<!-- Start XP Breadcrumbbar -->                    
<div class="xp-breadcrumbbar">
<div class="row">
        <div class="col-md-6 col-lg-6">
            <h4 class="xp-page-title">Lectures Sub Categories</h4>
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
                <div class="card-header bg-white">
                    <h5 class="card-title text-black mb-0">Lecture Sub Categories</h5>
                </div>
                <div class="card-body">
                @foreach($subCategories as $key => $value)
                    <a href="{{  $value['url'] }}">{{ $value['name'] }}</a><br/>
                @endforeach
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