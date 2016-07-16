@extends('layouts.app_transparent')

@section('content')
<div class="global-wrap">
    <div id="app" about="home"></div>
    <div class="bg-holder">
        <!-- TOP AREA -->
        <div class="top-area show-onload" style="height:100%;">
            <div class="bg-holder full">
                <div class="bg-mask"></div>
                <div class="bg-parallax" style="background-image:url(/img/backgrounds/1-g5p9kriQfjqPYyBTYv15bQ.jpeg);"></div>
                <div class="bg-content" style="height:100%;">
                    <div class="container">
                        @include('layouts.header_transparent')
                        <div class="gap"></div>
                        <div class="col-md-12">
                            <h1 style="font-size: 40px; font-weight: 700; color:white; text-align: center">RIDES COMING OUT OF 20 US CITIES</h1>
                        </div>
                        <div class="gap"></div>
                        <div class="col-md-12">
                            <h1 style="font-size: 40px; font-weight: 700; color:white; text-align: center;">OVER 25 EVENTS HAPPENING IN PHILLY</h1>
                            <p style="text-align: center; font-size:20px;"><a href="/blog/events/" style="color:lightblue;">View Events in Phily</a></p>
                        </div>
                    </div>
                    <div class="gap"></div>
                    <div class="container" style="width: 100%; padding:0; background-color: white;">
                        <div class="container">
                            <h2 style="margin-bottom:25px; padding-top:10px;">Find Someone Driving Near You</h2>
                            <div class="search-tabs search-tabs-bg search-tabs-nobox search-tabs-lift-top">
                                <div class="tabbable">
                                    <div class="tab-content">
                                        <div class="tab-pane fade in active" id="tab-1">
                                            <form method="get" action="/search" id="ride_search_form">
                                                <input type="hidden" name="type" value="R">
                                                <input type="hidden" name="total_people" value="1" id="ride_total_people">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group form-group-lg form-group-icon-left"><i class="fa fa-map-marker input-icon"></i>
                                                            <label>Where will you be picked up from?</label>
                                                            <input class="form-control" placeholder="Pickup Address or U.S./Canada City or ZIP Code" type="text" name="location" id="autocomplete" title="Enter a pick up location"/>
                                                            <span class="help-block" id="ride_search_error" style="color: #b90000;">
                                                                @if(session()->has('error')) {{session()->get('error')}} @endif
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5 col-md-offset-1">
                                                        <div class="form-group form-group-lg form-group-select-plus">
                                                            <label>How many people are coming along?</label>
                                                            <div class="btn-group btn-group-select-num" id="ride_total_people_radio" data-toggle="buttons">
                                                                <label class="btn btn-primary active">
                                                                    <input type="radio" name="ride_total_people_radio" value="1"/>1</label>
                                                                <label class="btn btn-primary">
                                                                    <input type="radio" name="ride_total_people_radio" value="2"/>2</label>
                                                                <label class="btn btn-primary">
                                                                    <input type="radio" name="ride_total_people_radio" value="3"/>3</label>
                                                                <label class="btn btn-primary">
                                                                    <input type="radio" name="ride_total_people_radio" value="4"/>4+</label>
                                                            </div>
                                                            <input type="text" value="4" class="form-control hidden" id="ride_total_people_select">
                                                        </div>
                                                    </div>
                                                </div>
                                                <button class="btn btn-primary btn-lg" type="submit">Search for Rides</button>
                                            </form>
                                        </div>
                                        <div class="tab-pane fade" id="tab-3">
                                            <h2>Crash a Cozy Home</h2>
                                            <form>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group form-group-lg form-group-icon-left"><i class="fa fa-map-marker input-icon"></i>
                                                            <label>Where will you be picked up from?</label>
                                                            <input class="typeahead form-control" placeholder="City, Airport, Point of Interest or U.S. Zip Code" type="text" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="input-daterange" data-date-format="M d, D">
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <div class="form-group form-group-lg form-group-icon-left"><i class="fa fa-calendar input-icon input-icon-highlight"></i>
                                                                        <label>Check-in</label>
                                                                        <input class="form-control" name="start" type="text" />
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group form-group-lg form-group-icon-left"><i class="fa fa-calendar input-icon input-icon-highlight"></i>
                                                                        <label>Check-out</label>
                                                                        <input class="form-control" name="end" type="text" />
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group form-group-lg form-group-select-plus">
                                                                        <label>Rooms</label>
                                                                        <div class="btn-group btn-group-select-num" data-toggle="buttons">
                                                                            <label class="btn btn-primary active">
                                                                                <input type="radio" name="options" />1</label>
                                                                            <label class="btn btn-primary">
                                                                                <input type="radio" name="options" />2</label>
                                                                            <label class="btn btn-primary">
                                                                                <input type="radio" name="options" />3</label>
                                                                            <label class="btn btn-primary">
                                                                                <input type="radio" name="options" />3+</label>
                                                                        </div>
                                                                        <select class="form-control hidden">
                                                                            <option>1</option>
                                                                            <option>2</option>
                                                                            <option>3</option>
                                                                            <option selected="selected">4</option>
                                                                            <option>5</option>
                                                                            <option>6</option>
                                                                            <option>7</option>
                                                                            <option>8</option>
                                                                            <option>9</option>
                                                                            <option>10</option>
                                                                            <option>11</option>
                                                                            <option>12</option>
                                                                            <option>13</option>
                                                                            <option>14</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group form-group-lg form-group-select-plus">
                                                                        <label>Guests</label>
                                                                        <div class="btn-group btn-group-select-num" data-toggle="buttons">
                                                                            <label class="btn btn-primary active">
                                                                                <input type="radio" name="options" />1</label>
                                                                            <label class="btn btn-primary">
                                                                                <input type="radio" name="options" />2</label>
                                                                            <label class="btn btn-primary">
                                                                                <input type="radio" name="options" />3</label>
                                                                            <label class="btn btn-primary">
                                                                                <input type="radio" name="options" />3+</label>
                                                                        </div>
                                                                        <select class="form-control hidden">
                                                                            <option>1</option>
                                                                            <option>2</option>
                                                                            <option>3</option>
                                                                            <option selected="selected">4</option>
                                                                            <option>5</option>
                                                                            <option>6</option>
                                                                            <option>7</option>
                                                                            <option>8</option>
                                                                            <option>9</option>
                                                                            <option>10</option>
                                                                            <option>11</option>
                                                                            <option>12</option>
                                                                            <option>13</option>
                                                                            <option>14</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button class="btn btn-primary btn-lg" type="submit">Search for Vacation Rentals</button>
                                            </form>
                                        </div>
                                        <div class="tab-pane fade" id="tab-5">
                                            <h2>Search for Activities</h2>
                                            <form>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="form-group form-group-lg form-group-icon-left"><i class="fa fa-map-marker input-icon"></i>
                                                            <label>Where are you going?</label>
                                                            <input class="typeahead form-control" placeholder="City, Airport, Point of Interest or U.S. Zip Code" type="text" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="input-daterange" data-date-format="M d, D">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group form-group-lg form-group-icon-left"><i class="fa fa-calendar input-icon input-icon-highlight"></i>
                                                                        <label>From</label>
                                                                        <input class="form-control" name="start" type="text" />
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group form-group-lg form-group-icon-left"><i class="fa fa-calendar input-icon input-icon-highlight"></i>
                                                                        <label>To</label>
                                                                        <input class="form-control" name="end" type="text" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button class="btn btn-primary btn-lg" type="submit">Search for Activities</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container" style="margin-top:120px; margin-bottom: 80px;">
                        <p style="text-align:center; line-height: 1.3;"><span style=" color: white; font-weight: 700; font-size:40px;"><span style="font-style: italic;">"Those who love peace must learn to organize as effectively as those who love war"</span><br> - MLK Jr.</span></p>
                    </div>
                    <footer id="main-footer" style="background: none; border-top:none;">
                        <div class="container">
                            <div class="row row-wrap">
                                <div class="col-md-4" style="text-align: left;">
                                    <h4>Need Help?</h4>
                                    <h4><a href="#" class="text-color">support@seeyouinphilly.com</a></h4>
                                    <p>#SeeYouInPhilly | &copy;<?=date('Y');?> SeeYouInPhilly.com</p>
                                </div>
                                <div class="col-md-3 col-md-offset-1" id="footer_links">
                                    <span style="color:lightgrey; font-size: 13px; margin-bottom: 10px;"><strong>Links</strong></span>
                                    <ul class="list list-footer">
                                        <li><a href="/about">About</a>
                                        </li>
                                        <li><a href="/about_rides">List Your Ride</a>
                                        </li>
                                    </ul>
                                    <br>
                                </div>
                                <div class="col-md-4" style="text-align: center;">
                                    <a class="logo" href="http://polivet.org/" target="_blank">
                                        <img src="/img/assets/polivet.png" alt="Coders For Sanders" title="Built With Lots of Love By Polivet.org" />
                                    </a>
                                    <ul class="list list-horizontal social-icons">
                                        <li style="">
                                            <a class="fa fa-facebook box-icon-normal round animate-icon-bottom-to-top" target="_blank" href="https://www.facebook.com/seeyouinphilly"></a>
                                        </li>
                                        <li>
                                            <a class="fa fa-twitter box-icon-normal round animate-icon-bottom-to-top" target="_blank" href="https://twitter.com/seeyouinp"></a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-12">
                                    <center>Your usage of this site is in accordance with our <a href="/tos" style="color:#0066b8 !important">terms of service</a> and our <a href="/privacy" style="color:#0066b8 !important">privacy policy</a>.</center>
                                </div>
                            </div>
                        </div>
                    </footer>
                </div>
            </div>
        </div>
        <!-- END TOP AREA  -->
    </div>

    <script src="/js/jquery.js"></script>
    <script src="/js/bootstrap.js"></script>
    <script src="/js/slimmenu.js"></script>
    <script src="/js/bootstrap-datepicker.js"></script>
    <script src="/js/bootstrap-timepicker.js"></script>
    <script src="/js/nicescroll.js"></script>
    <script src="/js/dropit.js"></script>
    <script src="/js/ionrangeslider.js"></script>
    <script src="/js/icheck.js"></script>
    <script src="/js/fotorama.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB7-veSR-bmwjhQNXSPPIKAJRTRG5CzZZ8&v=3.exp&sensor=false&libraries=places,geometry"></script>
    <script src="/js/typeahead.js"></script>
    <script src="/js/card-payment.js"></script>
    <script src="/js/magnific.js"></script>
    <script src="/js/owl-carousel.js"></script>
    <script src="/js/fitvids.js"></script>
    <script src="/js/tweet.js"></script>
    <script src="/js/countdown.js"></script>
    <script src="/js/gridrotator.js"></script>
    <script src="/js/custom.js?v=1"></script>
    <script src="/js/pace.min.js"></script>
    <script src="/js/jquery-deparam.js"></script>
    <script src="/js/jsonhttprequest.js"></script>

    <!-- APP Components -->
    <script src="/js/mobilizer/login.js?v=1"></script>
    <script src="/js/mobilizer/config.js?v=1"></script>
    <script src="/js/mobilizer/mobilizer-api.js?v=1"></script>
    <script src="/js/mobilizer/ride-list.js?v=1"></script>
    <script src="/js/mobilizer/listings.js?v=1"></script>
    <script src="/js/mobilizer/requests.js?v=1"></script>
    <script src="/js/mobilizer/bookings.js?v=1"></script>
    <script src="/js/mobilizer/search.js?v=2"></script>
    <script src="/js/mobilizer/home.js?v=1"></script>
    <!-- End APP Components -->

</div>
</body>

</html>
@endsection