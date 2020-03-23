<div class="xp-leftbar">
    <!-- Start XP Sidebar -->
    <div class="xp-sidebar">
        <!-- Start XP Logobar -->
        <div class="xp-logobar text-center">
            <a href="{{url('/')}}" class="xp-logo"><img src="{{ asset('assets/images/logo.png') }}" class="img-fluid" alt="logo"></a>
        </div>
        <!-- End XP Logobar -->
        <!-- Start XP Navigationbar -->
        <div class="xp-navigationbar">
            <ul class="xp-vertical-menu">
                <li class="xp-vertical-header">Main</li>
                <li>
                    <a href="{{url('/teacher/dashboard')}}">
                      <i class="icon-speedometer"></i><span>Dashboard</span>
                  </a>
                </li>
               
                <li>
                    <a href="javaScript:void();">
                        <i class="icon-envelope"></i><span>Updates</span><i class="icon-arrow-right pull-right"></i>
                    </a>
                    <ul class="xp-vertical-submenu">                                
                        <li><a href="{{url('/teacher/update')}}">Create Update</a></li>
                        <li><a href="{{url('/teacher/updates')}}">Updates</a></li>
                    </ul>
                </li>
                <li>
                    <a href="javaScript:void();">
                        <i class="icon-social-dropbox"></i><span>Lectures</span><i class="icon-arrow-right pull-right"></i>
                    </a>
                    <ul class="xp-vertical-submenu">                                
                        <li><a href="{{url('/teacher/categories')}}">Lectures</a></li>
                    </ul>
                </li> 
                <li>
                    <a href="javaScript:void();">
                        <i class="icon-event"></i><span>Classes</span><i class="icon-arrow-right pull-right"></i>
                    </a>
                    <ul class="xp-vertical-submenu">                                
                        <li><a href="{{url('/teacher/calendar')}}">Calendar</a></li>
                        <li><a href="{{url('/task_class')}}">Add Class</a></li>
                        <li><a href="{{url('/add_student_form')}}">Assign Students</a></li>
                    </ul>
                </li>
                <li>
                    <a href="javaScript:void();">
                        <i class="icon-social-dropbox"></i><span>Students</span><i class="icon-arrow-right pull-right"></i>
                    </a>
                    <ul class="xp-vertical-submenu">                                
                        <li><a href="{{url('/add_student_form_by_user')}}">Add Students</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javaScript:void();">
                        <i class="icon-social-dropbox"></i><span>Traning</span><i class="icon-arrow-right pull-right"></i>
                    </a>
                    <ul class="xp-vertical-submenu">
                        <li><a href="{{route('lessons.categories')}}">Categories</a></li>
                        <li><a href="{{route('lessons.subcategories')}}">Sub Categories</a></li>
                        <li><a href="{{route('lessons.index')}}">Training</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javaScript:void();">
                        <i class="icon-social-dropbox"></i><span>Online Class</span><i class="icon-arrow-right pull-right"></i>
                    </a>
                    <ul class="xp-vertical-submenu">                                
                        <li><a href="{{url('/online_meeting_room')}}">Online Classroom</a></li>
                    </ul>
                </li>

            </ul>
        </div>
        <!-- End XP Navigationbar -->
    </div>
    <!-- End XP Sidebar -->
</div>