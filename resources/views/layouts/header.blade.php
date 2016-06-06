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
        <header id="main-header">
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
                                <ul class="top-user-area-list list list-horizontal list-border">
                                    <li class="top-user-area-avatar list nav-drop">
                                        <a href="#">
                                            <img class="origin round" src="/img/40x40.png" alt="Image Alternative text" title="John" />Hi, John <i class="fa fa-angle-down"></i></i>
                                        </a>
                                        <ul class="list nav-drop-menu">
                                            <li><a href="#">My Bookings</a>
                                            </li>
                                            <li><a href="#">My Listings</a>
                                            </li>
                                            <li><a href="#">Settings</a>
                                            </li>
                                            <li><a href="#">Sign Out</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li><span class="btn btn-primary">List Ride</span></li>
                                    <li><span class="btn btn-primary">List Home</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>