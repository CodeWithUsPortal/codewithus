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
    @include('layouts.student_menu') 
@endsection 
@section('rightbar-content')

<!-- Start XP Breadcrumbbar -->                    
<div class="xp-breadcrumbbar">
      
</div>
<!-- End XP Breadcrumbbar -->
<!-- Start XP Contentbar -->    
<div class="xp-contentbar">
    <div class="row"> 
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-header bg-white">
                    <h5 class="card-title text-black mb-0">Lectures</h5>
                </div>
                      
                <div class="card-body">
                       
                        @foreach($data as $key => $value)
                        <!-- <div class="col-md-6 col-lg-6 col-xl-3"> -->
                        <div class="col-lg-12">
                            <div class="text-center">
                                <!-- Secondary Border Card -->
                                <div class="card border-secondary m-b-30">
                                    <div class="card-header"><h2>{{ $value['category'] }}</h2></div>
                                    
                                    <div class="card-body text-secondary">
                                        <h5 class="card-title">{{ $value['subCategory'] }}</h5>
                                        @foreach($value['lectures'] as $lecture)
                                            <a href="{{ $lecture['link'] }}">{{ $lecture['title'] }}</a></p>
                                        @endforeach
                                    </div>
                                  
                                </div>
                                <!-- Secondary Border Card -->  
                            </div>    
                        </div>
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
<!-- Knob Chart JS -->
<script src="{{ asset('assets/plugins/jquery-knob/excanvas.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
<script src="{{ asset('assets/js/init/knob-init.js') }}"></script>
<!-- To Do List JS -->
<script src="{{ asset('assets/js/init/to-do-list-init.js') }}"></script>
@endsection 