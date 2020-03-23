@section('title') 
CodeWithUs - Training
@endsection 
@extends('layouts.main')
@section('style')
<!-- Chartist Chart CSS -->
<link href="{{ asset('assets/plugins/chartist-js/chartist.min.css') }}" rel="stylesheet" type="text/css" />
<!-- Datepicker CSS -->
<link href="{{ asset('assets/plugins/datepicker/datepicker.min.css') }}" rel="stylesheet" type="text/css" />
<script src="https://unpkg.com/vue"></script>
<script src="https://unpkg.com/vue-cal"></script>
@endsection 
@section('leftbar')
    @include('layouts.teacher_menu')
@endsection 
@section('rightbar-content')

<!-- Start XP Breadcrumbbar -->                    
<div class="xp-breadcrumbbar">
    <div class="row">
        <div class="col-md-6 col-lg-6">
            <h4 class="xp-page-title">Training</h4>
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
                    <div id="app_vue">

                        <table class="table" id="table">
                            <thead>
                            <tr>
                                <td><h5>Lesson Id</h5></td>
                                <td><h5>Lesson Title</h5></td>
                                <td><h5>Lesson Link</h5></td>
                                <td><h5>Sub Category Id</h5></td>
                                <td><h5>Sub Category Name</h5></td>
                            </tr>
                            </thead>
                            @foreach($lessons as $lesson)
                            <tr>
                                <td>{{$lesson->id}}</td>
                                <td class="text-capitalize">{{$lesson->title}}</td>
                                <td>{{$lesson->link}}</td>
                                <td class="text-capitalize">{{$lesson->sub_category->name}}</td>
                                <td class="text-capitalize">{{$lesson->sub_category->category->name}}</td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div> 
            </div> 
        </div>   
    </div>
</div>        
<script type="text/javascript" src="{{ asset('public/js/app.js') }}"></script>
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