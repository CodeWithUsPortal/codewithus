@section('title') 
CodeWithUs - Update
@endsection 
@extends('layouts.update_layout')
@section('style')
<!-- Chartist Chart CSS -->
<link href="{{ asset('assets/plugins/chartist-js/chartist.min.css') }}" rel="stylesheet" type="text/css" />
<!-- Datepicker CSS -->
<link href="{{ asset('assets/plugins/datepicker/datepicker.min.css') }}" rel="stylesheet" type="text/css" />
@endsection 

<div class="xp-contentbar">

    <div class="row"> 
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <div class="xp-widget-box text-center">
                        <h4 class="xp-counter text-warning m-t-20">404</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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