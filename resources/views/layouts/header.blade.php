 <!-- FACEBOOK WIDGET -->
    <div id="fb-root"></div>
    <script>
        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s);
            js.id = id;
            js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>
    <!-- /FACEBOOK WIDGET -->
    <div class="global-wrap">
        <header id="main-header" style="background-image:url(/img/photography/1-sLZF6kIWiyaNytLqWjP_HA.jpeg); background-position: center 70%;">
            <div class="header-top">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4">
                            <a class="logo" href="index.html">
                                <a href="/" ><img src="/img/assets/logo.png" id="logo_home"></a>
                            </a>
                        </div>
                        <div class="col-md-4 col-md-offset-4">
                            <div class="top-user-area clearfix">
                                <ul class="top-user-area-list list list-horizontal list-border social-icons">
                                    @if (isset($auth) && $auth['status'])
                                        <li class="top-user-area-avatar list nav-drop">
                                            <a href="#">
                                                <img class="origin round" src="/img/40x40.png" alt="{{$auth['userInfo']['first_name']}}" title="{{$auth['userInfo']['first_name']}}" />Hi, {{$auth['userInfo']['first_name']}} <i class="fa fa-angle-down" style="padding-top:2px;"></i><i class="fa fa-angle-up" style="padding-top:2px;"></i></i>
                                            </a>
                                            @include('layouts.user_drop_down')
                                        </li>
                                    @else
                                        <li><a href="/login" ><span class="btn btn-primary">Login</span></a></li>
                                    @endif
                                    <li><a href="/about_rides"><span class="btn btn-primary">List Ride</span></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>