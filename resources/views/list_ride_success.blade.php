@extends('layouts.app_with_header')

@section('content')
    <div class="gap"></div>

    <div id="fb-root"></div>
    <script>(function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.6";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <i class="fa fa-check round box-icon-large box-icon-center box-icon-success mb30"></i>
                <h2 class="text-center">Your listing was successful!</h2>
                <h5 class="text-center mb30">Listing details have been sent to <strong>{{$user_email}}</strong></h5>
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="text-center mb30">Let the world know you're going:</h5>
                    </div>
                    <div class="col-md-3">
                        <a href="https://twitter.com/share" class="twitter-share-button" data-url="https://www.seeyouinphilly.com/" data-text="I just listed my ride to the DNC this July! Come along" data-hashtags="SeeYouInPhilly">Tweet</a>
                    </div>
                    <div class="col-md-3">
                        <div class="fb-share-button" data-href="https://www.seeyouinphilly.com/" data-layout="button" data-mobile-iframe="true"></div>
                    </div>
                </div>
                <ul class="order-payment-list list mb30">
                    <li>
                        <div class="row">
                            <div class="col-xs-9">
                                <h5><i class="fa fa-car"></i> Driving from {{$location['city']}}, {{$location['state']}} to Philadelphia, PA</h5>
                                <p><small>{{$starting_date['text']}}</small>
                                </p>
                            </div>
                            <div class="col-xs-3">
                                <p class="text-right"><span class="text-lg">{{$leaving}}</span>
                                </p>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="row">
                            <div class="col-xs-9">
                                <h5><i class="fa fa-car"></i> Driving from Philadelphia, PA to {{$location['city']}}, {{$location['state']}}</h5>
                                <p><small>{{$ending_date['text']}}</small>
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
                    <div class="row"> <div class="col-md-4"> <strong>Party Name</strong> </div> <div class="col-md-8" style="text-align:left;">{{$party_name}}</div></div>
                </div><hr>
                <div class="row">
                    <div class="row"> <div class="col-md-4"> <strong>Max Passengers</strong> </div> <div class="col-md-8" style="text-align:left;">{{$max_occupants}}</div></div>
                </div><hr>
                <div class="row">
                    <div class="row"> <div class="col-md-4"> <strong>Additional Info</strong> </div> <div class="col-md-8" style="text-align:left;">{{$additional_info}}</div></div>
                </div><hr>
                @if ($metadata['dog_friendly'] == 1 || $metadata['cat_friendly'] == 1)
                    <div class="row">
                        <div class="row"> <div class="col-md-4"> <strong>Bringing pets on board?</strong> </div> <div class="col-md-8" style="text-align:left;"><a href="http://www.amazon.com/s/ref=nb_sb_noss?url=search-alias%3Daps&field-keywords=amazon+pet+car+harness" target="_blank">Checkout Pet Harnesses</a></div></div>
                    </div><hr>
                    <div class="row">
                        <div class="row"> <div class="col-md-4"> <strong>Pets Allowed</strong> </div><div class="col-md-8" style="text-align:left;">
                                @if ($metadata['cat_friendly'] == 1)
                                    Cats
                                @endif
                                @if ($metadata['dog_friendly'] == 1)
                                    Dogs
                                @endif
                            </div></div>
                    </div><hr>
                @endif
                <h4 class="text-center">You might also need in Philly</h4>
                <ul class="list list-inline list-center">
                    <li><a class="btn btn-primary" href="#"><i class="fa fa-building-o"></i> Housing</a>
                    </li>
                    <li><a class="btn btn-primary" href="#"><i class="fa fa-bolt"></i> Activities</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="gap"></div>
    </div>
@endsection