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
                    <a href="{{url('/admin/dashboard')}}">
                      <i class="icon-speedometer"></i><span>Dashboard</span>
                  </a>
                </li>
               
                <li>
                    <a href="javaScript:void();">
                        <i class="icon-envelope"></i><span>Updates</span><i class="icon-arrow-right pull-right"></i>
                    </a>
                    <ul class="xp-vertical-submenu">                                
                        <li><a href="{{url('/admin/update')}}">Create Update</a></li>
                        <li><a href="{{url('/admin/updates')}}">Updates</a></li>
                    </ul>
                </li>
                <li>
                    <a href="javaScript:void();">
                        <i class="icon-social-dropbox"></i><span>Lectures</span><i class="icon-arrow-right pull-right"></i>
                    </a>
                    <ul class="xp-vertical-submenu">                                
                        <li><a href="{{url('/category')}}">Categories</a></li>
                        <li><a href="{{url('/subcategory')}}">Sub Categories</a></li>
                        <li><a href="{{url('/lecture')}}">Lectures</a></li>
                    </ul>
                </li>
                <li>
                    <a href="javaScript:void();">
                        <i class="icon-event"></i><span>Classes</span><i class="icon-arrow-right pull-right"></i>
                    </a>
                    <ul class="xp-vertical-submenu">                                
                        <li><a href="{{url('/admin/calendar')}}">Calendar</a></li>
                        <li><a href="{{url('/task_class')}}">Add Class</a></li>
                        <li><a href="{{url('/add_student_form')}}">Assign Students</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javaScript:void();">
                        <i class="icon-event"></i><span>Topics</span><i class="icon-arrow-right pull-right"></i>
                    </a>
                    <ul class="xp-vertical-submenu">                                
                        <li><a href="{{url('/topics')}}">Topics</a></li>
                    </ul>
                </li>
                <li>
                    <a href="javaScript:void();">
                        <i class="icon-event"></i><span>Teachers</span><i class="icon-arrow-right pull-right"></i>
                    </a>
                    <ul class="xp-vertical-submenu">                                
                        <li><a href="{{url('/teacher_profile')}}">Search Teacher</a></li>
                    </ul>
                </li>
               
            </ul>
        </div>
        <!-- End XP Navigationbar -->
    </div>
    <!-- End XP Sidebar -->
</div>