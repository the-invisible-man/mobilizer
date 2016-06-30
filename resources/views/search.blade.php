@extends('layouts.app_with_header')
@section('content')
    <div id="app" about="search" data-token="{!! csrf_token() !!}"></div>
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="/">Home</a>
            </li>
            <li class="active">Results for rides near @if(strlen($query_info['search_term']['geocoded']['city'])) {{$query_info['search_term']['geocoded']['city']}}, @endif{{$query_info['search_term']['geocoded']['state']}}</li>
        </ul>
        <h3 class="booking-title">{{$number_of_hits}}@if($number_of_hits > 1) Rides @else Ride @endif Near <span style="color:#0066b8">@if(strlen($query_info['search_term']['geocoded']['city'])) {{$query_info['search_term']['geocoded']['city']}}, @endif{{$query_info['search_term']['geocoded']['state']}}</span></h3>
        <div class="row">
            <div class="col-md-3">
                <div class="booking-item-dates-change mb30">
                    <form method="get" action="/search" id="ride_search_form">
                        <input type="hidden" name="original_query" value="{{$query_info['search_term']['raw']}}" id="original_query"/>
                        <input type="hidden" name="type" value="{{$query_info['type']}}" />
                        <div class="form-group form-group-icon-left"><i class="fa fa-map-marker input-icon input-icon-hightlight"></i>
                            <label>Picked up from</label>
                            <input class="form-control" id="autocomplete" name="location" value="{{$query_info['search_term']['composed']}}" placeholder="Address, City, or U.S. Zip Code" type="text" />
                            <span class="help-block" id="ride_search_error"></span>
                        </div>
                        <div class="form-group form-group-icon-left"><i class="fa fa-users input-icon input-icon-hightlight"></i>
                            <label>Passengers</label>
                            <input class="form-control" name="total_people" id="total_people" value="{{$query_info['search_term']['filters']['total_people']}}" type="text" />
                        </div>
                        <input class="btn btn-primary" type="submit" value="Update Search" />
                    </form>
                </div>
            </div>
            <div class="col-md-9">
                <ul class="booking-list">
                    @if (count($results))
                        @foreach ($results as $result)
                            @if($result['booking']['status'] == \App\Lib\Packages\Bookings\Contracts\BaseBooking::STATUS_REJECTED) @continue @endif
                            <li class="listing_result" about="{{$result['id']}}">
                                <a class="booking-item" href="#">
                                    <div class="row">
                                        <div class="col-md-4 col-xs-12">
                                            <h5>{{$result['party_name']}}</h5>
                                            <p>{{$result['additional_info']}}</p>
                                        </div>
                                        <div class="col-md-5" style="font-size: small">
                                            <div class="col-md-6 col-xs-6">
                                                Leaving:<br>
                                                Coming Back:<br>
                                                Driver:<br>
                                                Coming From:<br>
                                                Seats Remaining:
                                            </div>
                                            <div class="col-md-6 col-xs-6" style="font-weight: bold">
                                                {{$result['starting_date']}}<br>
                                                {{$result['ending_date']}}<br>
                                                {{$result['host']}}<br>
                                                {{$result['location']['city']}}, {{$result['location']['state']}}<br>
                                                {{$result['remaining_slots']}}
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-xs-12" style="margin-top: 30px;">
                                            <center><span class="btn btn-primary">Request Ride</span></center>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    @else
                        <p>Sorry we found no matches around your area in {{$query_info['search_term']['geocoded']['state']}}, but you can enter your email below and we will notify you when there's something available.</p>
                        <form action="/notifications" method="POST" id="email_notify_form">
                            <input type="hidden" name="query" value="{{$query_info['search_term']['raw']}}"/>
                            {!! csrf_field() !!}
                            <input type="text" name="email" id="email_notify_field" placeholder="Enter Your Email"/> <button type="submit" class="btn btn-primary">Notify Me!</button><br>
                            <span class="help-block" id="email_notify_error"></span>
                        </form>
                    @endif
                </ul>
            </div>
        </div>
        <div class="gap"></div>
    </div>
    <div class="modal fade" id="listing_info_window" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header listing-modal-top">
                    <h4 class="modal-title" id="party_name_placeholder"></h4>
                    <div class="additional_info_placeholder">
                        <strong>About this ride:</strong><br>
                        <span id="listing_info_window_additional_info"></span>
                    </div>
                </div>

                <div class="modal-body" id="listing_content">
                    <div class="row">
                        <div class="col-md-6" style="margin-bottom:10px">
                            <strong>You'll be riding with:</strong><br>
                        </div>
                        <div class="col-md-6" style="margin-bottom:10px"><span id="host_"></span> <br></div>


                        <div class="col-md-6" style="margin-bottom:10px">
                            <strong>Driving From:</strong><br>
                        </div>
                        <div class="col-md-6" style="margin-bottom:10px"><span id="location_"></span><br></div>


                        <div class="col-md-6" style="margin-bottom:10px">
                            <strong>Driver Leaving Home:</strong><br>
                        </div>
                        <div class="col-md-6" style="margin-bottom:10px"><span id="starting_date_raw_"></span> <strong>@</strong> <span id="time_of_day_string_"></span></div>


                        <div class="col-md-6" style="margin-bottom:10px">
                            <strong>Passing By Your Town:</strong><br>
                        </div>
                        <div class="col-md-6" style="margin-bottom:10px"><span id="pickup_date_"></span> <strong>@</strong> <span id="pickup_time_"></span></div>


                        <div class="col-md-6" style="margin-bottom:10px">
                            <strong>Seats Remaining:</strong><br>
                        </div>
                        <div class="col-md-6" style="margin-bottom:10px"><span id="data_remaining_slots_"></span></div>
                    </div>
                    <hr>

                    <!-- Dynamic Views-->
                    <div class="col-md-12 hidden" id="login_window"><a href="/login">Login to book this ride</a></div>

                    <div class="col-md-12 hidden" id="booked_window">You already requested this ride</div>

                    <div id="booking_form_window" class="hidden">
                        <form method="POST" action="/bookings" id="booking_form" name="booking_request">
                            <div class="row" id="booking_form_box">
                                <input type="hidden" id="booking_form_token_" name="_token" value=""/>
                                <input type="hidden" id="booking_form_fk_listing_id_" name="fk_listing_id" value=""/>
                                <input type="hidden" id="booking_form_type_" name="type" value=""/>
                                <div class="col-md-6">
                                    <label>Pickup location:</label><input class="form-control" id="booking_pickup_location" placeholder="Address, City, or U.S. Zip Code" value="" disabled="disabled"/>
                                    <input type="hidden" id="booking_form_window_location" value="" name="location"/>
                                </div>
                                <div class="col-md-6">
                                    <label>Number of Passengers:</label><input class="form-control" id="total_people_field" placeholder="How many people are coming along?" value="" name="total_people"/><span class="help-block hidden" id="total_people_error" style="color: #b90000;"></span>
                                </div>
                                <div class="col-md-12" style="margin-top:15px;">
                                    <div class="alert alert-warning">
                                        <p class="text-small" style="text-align: justify;">We will not show <span id="host_inline"></span> your address at any time. We do not save your street address, we simply use this information to acquire your geo-location. To learn more about what information we keep please refer to our <a href="/privacy" target="_blank">privacy policy</a>.</p>
                                    </div>
                                    <span class="help-block hidden" id="booking_additional_info_error" style="color: #b90000;"></span>
                                </div>
                                <div class="col-xs-4">
                                    <label>Additional info:</label>
                                </div>
                                <div class="col-xs-8" style="text-align: right">
                                    <strong>50 Character Minimum: <span id="additional_info_character_count">0</span>/500</strong>
                                </div>
                                <div class="col-md-12">
                                    <textarea name="additional_info" rows="7" cols="100" maxlength="500" placeholder="Say hi, introduce yourself." id="booking_additional_info" class="form-control" required></textarea>
                                </div>

                                <div class="col-md-12" style="margin-top:15px;">
                                    <div class="alert alert-warning">
                                        <p class="text-small" style="text-align: justify;"><strong>DISCLAIMER:</strong>
                                            <br>SeeYouInPhilly.com matches drivers with people looking to carpool. We don't run background checks and aren't responsible for any actions of the drivers or carpoolers. Get to know the other party before sharing rides! Be safe and report any suspicious activity to 911. Wear a seat belt at all times and don't drink and drive or ride with anyone driving under the influence of any substance.
                                            <br><br>We are in no way associated with the official Bernie Sanders campaign.
                                            <br><br><strong>You must be at least 18 years of age or older to use this service.</strong>
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-12" style="padding-left:17px;padding-right:17px;text-align: center;">
                                    <label class="">
                                        <div>
                                            <input type="checkbox" id="disclaimer_accept"/><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins>
                                        </div>
                                        I am 18 years of age or older and I've read and fully understood the disclaimer above.
                                    </label>
                                    <br><span class="help-block hidden" id="disclaimer_accept_error" style="color: #b90000;"></span>
                                </div>

                                <div class="col-md-12" style="padding-left:17px;padding-right:17px;text-align: center;">
                                    <label class="">
                                        <div>
                                            <input type="checkbox" id="tos_accept"/><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-12">
                                    I've read and agree to the <a href="/tos" target="_blank">terms of service</a> (But no seriously, read that stuff)
                                    <br><span class="help-block hidden" id="tos_accept_error" style="color: #b90000;"></span>
                                </div>
                                <div class="col-md-12" style="margin-top:20px">
                                    <span class=""><center><button class="btn btn-primary btn-lg" type="submit">Send Ride Request</button></center></span>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- End Dynamic Views-->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection