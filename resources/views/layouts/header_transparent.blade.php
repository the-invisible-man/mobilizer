
    <div class="row" style="margin-top:30px;">
        <div class="col-md-6">
            <a href="/"><img src="/img/assets/logo.png" id="logo_home"></a>
        </div>
        <div class="col-md-6" id="home_top_links">
            <a href="/about_rides">List My Ride</a> | <a href="/about_housing">List My Home</a> |
            @if (isset($auth) && $auth['status'])
                <span class="top-user-area-avatar list nav-drop">
                    <a href="#">
                        Hi, {{$auth['userInfo']['first_name']}} <i class="fa fa-angle-down"></i></i>
                    </a>
                    <ul class="list nav-drop-menu" style="font-weight: 400; font-size:12px;">
                        <li><a href="#">My Bookings</a>
                        </li>
                        <li><a href="#">My Listings</a>
                        </li>
                        <li><a href="#">Settings</a>
                        </li>
                        <li><a href="/logout">Sign Out</a>
                        </li>
                    </ul>
                </span>
            @else
                <a href="/login">Sign In</a>
            @endif
        </div>
    </div>
