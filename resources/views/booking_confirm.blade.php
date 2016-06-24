@extends('layouts.app_with_header')
@section('content')
    <div class="gap"></div>
    <div id="fb-root"></div>
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <i class="fa fa-check round box-icon-large box-icon-center box-icon-success mb30"></i>
                <h2 class="text-center">Your booking request was successful!</h2>
                <h5 class="text-center mb30">A confirmation email has been sent <strong>{{$user_email}}</strong></h5>
                <h6 style="text-align: center">You can manage your bookings at any time from the "<a href="">my bookings</a>" page.</h6><br>
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="text-center mb30">Let the world know you're going:</h5>
                    </div>
                    <div class="col-md-3">
                        <center><a href="https://twitter.com/share" class="twitter-share-button" data-url="https://www.seeyouinphilly.com/" data-text="I just listed my ride to the DNC this July! Come along" data-hashtags="SeeYouInPhilly">Tweet</a></center>
                    </div>
                    <div class="col-md-3">
                        <div class="fb-share-button" style="margin:0 auto;left:42%;padding-bottom:20px;" data-href="https://www.seeyouinphilly.com/" data-layout="button" data-mobile-iframe="true"></div>
                    </div>
                </div>
                <ul class="order-payment-list list mb30">
                    <li>
                        <div class="row">
                            <div class="col-xs-9">
                                <h5><i class="fa fa-car"></i> Picked up from {{$metadata['location']['city']}}, {{$metadata['location']['state']}} to Philadelphia, PA</h5>
                                <p><small>{{$starting_date}}</small>
                                </p>
                            </div>
                            <div class="col-xs-3">
                                <p class="text-right"><span class="text-lg"></span>
                                </p>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="row">
                            <div class="col-xs-9">
                                <h5><i class="fa fa-car"></i> Driving back from Philadelphia, PA to {{$metadata['location']['city']}}, {{$metadata['location']['state']}}</h5>
                                <p><small>{{$ending_date}}</small>
                                </p>
                            </div>
                            <div class="col-xs-3">
                                <p class="text-right"><span class="text-lg"></span>
                                </p>
                            </div>
                        </div>
                    </li>
                </ul>
                <div class="row">
                    <div class="row"> <div class="col-md-6"> <strong>Riding With</strong> </div> <div class="col-md-6" style="text-align:left;">{{$listing['host']}}</div></div>
                </div><hr>
                <div class="row">
                    <div class="row"> <div class="col-md-6"> <strong>Passengers</strong> </div> <div class="col-md-6" style="text-align:left;">{{$total_people}}</div></div>
                </div><hr>
                <h4 class="text-center">You might also need in Philly</h4>
                <ul class="list list-inline list-center">
                    <li><a class="btn btn-primary" href="http://www.berniebnb.com/" target="_blank"><i class="fa fa-building-o"></i> Housing</a>
                    </li>
                    <li><a class="btn btn-primary" href="https://occupydncconvention.com/" target="_blank"><i class="fa fa-bolt"></i> Activities</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="gap"></div>
    </div>
@endsection