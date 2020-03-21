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
                    <a href="{{url('/student/dashboard')}}">
                      <i class="icon-speedometer"></i><span>Dashboard</span>
                  </a>
                </li>
               
                <li>
                    <a href="javaScript:void();">
                        <i class="icon-social-dropbox"></i><span>Updates</span><i class="icon-arrow-right pull-right"></i>
                    </a>
                    <ul class="xp-vertical-submenu">                                
                        <li><a href="{{url('/student/update')}}">Create Update</a></li>
                        <li><a href="{{url('/student/updates')}}">Updates</a></li>
                    </ul>
                </li>
                <li>
                    <a href="javaScript:void();">
                        <i class="icon-social-dropbox"></i><span>Lectures</span><i class="icon-arrow-right pull-right"></i>
                    </a>
                    <ul class="xp-vertical-submenu">                                
                        <li><a href="{{url('/categories')}}">Lectures</a></li>
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