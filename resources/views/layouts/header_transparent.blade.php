
    <div class="row" style="margin-top:30px;">
        <div class="col-md-6">
            <a href="/"><img src="/img/assets/logo.png" id="logo_home"></a>
        </div>
        <div class="col-md-6" id="home_top_links">
            <a href="/about_rides">List My Ride</a> |
            @if (isset($auth) && $auth['status'])
                <span class="top-user-area-avatar list nav-drop">
                    <a href="#">
                        Hi, {{$auth['userInfo']['first_name']}} <i class="fa fa-angle-down" style="padding-top:2px;"></i><i class="fa fa-angle-up" style="padding-top:2px;"></i></i>
                    </a>
                    @include('layouts.user_drop_down')
                </span>
            @else
                <a href="/login">Sign In</a>
            @endif
        </div>
    </div>
