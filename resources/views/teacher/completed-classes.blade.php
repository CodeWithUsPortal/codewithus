@section('title')
    CodeWithUs - Dashboard
@endsection
@extends('layouts.main')
@section('style')
    <!-- Chartist Chart CSS -->
    <link href="{{ asset('assets/plugins/chartist-js/chartist.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Datepicker CSS -->
    <link href="{{ asset('assets/plugins/datepicker/datepicker.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Vue Cal Scripts -->
<script>
    import StudentCompletedClasses from "../../assets/js/components/CompletedClasses";
    export default {
        components: {StudentCompletedClasses}
    }
</script>
    <script src="https://unpkg.com/vue-cal"></script>
    <link href="https://unpkg.com/vue-cal/dist/vuecal.css" rel="stylesheet">
@endsection
<style>
    .vuecal__event.leisure {margin-bottom:5px ;background-color: rgba(253, 156, 66, 0.9);border: 1px solid rgb(233, 136, 46);color: #fff;}
    .vuecal__event.sport {background-color: rgba(255, 102, 102, 0.9);border: 1px solid rgb(235, 82, 82);color: #fff;}
</style>
@section('leftbar')
    @include('layouts.teacher_menu')
@endsection
@section('rightbar-content')

    <!-- Start XP Breadcrumbbar -->
    <div class="xp-breadcrumbbar">
        <div class="row">
            <div class="col-md-6 col-lg-6">
                <h4 class="xp-page-title">Student's Completed Classes</h4>
            </div>
            <div class="col-md-6 col-lg-6">
                <div class="xp-breadcrumb">
                    <ol class="breadcrumb">
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- End XP Breadcrumbbar -->
    <!-- Start XP Contentbar -->
    <div class="xp-contentbar">
        <div id="app_vue">
            <student-completed-classes :student_id="'{{ $id }}'"> </student-completed-classes>
        </div>
    </div>
    <!-- End XP Contentbar -->
@endsection
@section('script')
    <!-- VueJS scripts -->
    <script type="text/javascript" src="{{ asset('public/js/app.js') }}"></script>

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