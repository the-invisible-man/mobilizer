@extends('layouts.app_with_header')
@section('content')
    <div id="app" about="my-bookings" data-token="{!! csrf_token() !!}"></div>
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="/">Home</a>
            </li>
            <li class="active">My Bookings</li>
        </ul>
        <h3 class="booking-title">My Bookings</h3>
        <div class="row">
            <div class="col-md-3 hidden-xs">
                @include('layouts.user_side_bar')
            </div>
            <div class="col-md-9 col-xs-12">
                <ul class="booking-list">
                    @if (count($listings))
                        @foreach ($listings as $listing)
                            <li class="booking_result booking-item" about="{{$listing['id']}}">
                                <div class="row">
                                    <div class="col-md-4 col-xs-12">
                                        <h5>{{$listing['party_name']}}</h5>
                                        <ul class="booking-item-features booking-item-features-sign clearfix">
                                            <li rel="tooltip" data-placement="top" title="Cancel Your Trip" about="{{$booking['id']}}" data-text="{{$booking['listing']['host_first_name']}}" class="cancel_trip"><i class="fa fa-times"></i><span class="booking-item-feature-sign">Cancel</span>
                                            </li>
                                            <li rel="tooltip" data-placement="top" title="Contact The Driver" about="{{$booking['listing']['id']}}" class="contact_driver"><i class="fa fa-envelope"></i><span class="booking-item-feature-sign">Contact</span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-5" style="font-size: small">
                                        <div class="col-md-6 col-xs-6">
                                            Driver:<br>
                                            Pick Up Location:<br>
                                            Passengers:<br>
                                            Coming Back:<br>
                                            Approx. Pickup:
                                        </div>
                                        <div class="col-md-6 col-xs-6" style="font-weight: bold">
                                            {{$booking['listing']['host_first_name']}} {{$booking['listing']['host_last_name']}}<br>
                                            {{$booking['user_location']['city']}}, {{$booking['user_location']['state']}}<br>
                                            {{$booking['total_people']}}<br>
                                            {{$booking['listing']['ending_date']}}<br>
                                            {{$booking['pickup_date']}} @ {{$booking['pickup_time']}}
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-xs-12" style="margin-top: 30px;">
                                        <center><span class="btn btn-ghost">{{strtoupper($booking['status'])}}</span></center>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    @else
                        <p>You have no bookings right now. Go find someone to ride with!</p>
                    @endif
                </ul>
            </div>
        </div>
        <div class="gap"></div>
    </div>

    <!-- CONTACT MODAL -->
    <div class="modal fade" id="contact_window" tabindex="-1" role="dialog" aria-labelledby="contact">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header listing-modal-top" style="height:80px;">
                    <h4 class="modal-title" id="party_name_placeholder">Contact Driver</h4>
                </div>

                <div class="modal-body" id="listing_content">
                    <div class="row">
                        <div class="col-md-12" style="margin-top:15px;">
                            <div class="alert alert-warning">
                                <h4>How to Contact</h4>
                                <p class="text-small" style="text-align: justify;">We use an email relay system that masks your real email address so that it is never exposed to the recipient. Below you will find the email address at which you can contact the driver. <br><br><strong>NOTE:</strong> In order for you to contact this user you <strong>MUST</strong> send your email from the address that you used to sign up to this site. All other mail will be printed onto paper and then shredded into tiny pieces. No joke.</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <center><strong>Contact Name:</strong> <span id="driver_contact_name"></span></center><br>
                            <center><strong>Contact Email</strong></center><br>
                            <div class="hidden-md hidden-sm hidden-xs">
                                <center><input type="text" value="" style="width:100%;" id="driver_contact_email" readonly/></center>
                            </div>
                            <div class="hidden-lg">
                                <center><span id="driver_contact_email_2"></span></center>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- END CONTACT MODAL -->

    <!-- CANCEL MODAL -->
    <div class="modal fade" id="cancel_window" tabindex="-1" role="dialog" aria-labelledby="cancel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header listing-modal-top" style="height:80px;">
                    <h4 class="modal-title" id="party_name_placeholder" style="color:#B90000;">CANCEL BOOKING</h4>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" style="margin-top:15px;">
                            <div class="alert alert-danger">
                                <p style="text-align: justify;">You are about to cancel your booking with <strong><span id="cancel_window_host_name"></span></strong>. Are you sure you wish to continue?</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <center><a class="btn btn-danger confirm_cancel">CANCEL BOOKING</a> <a class="btn btn-info" data-dismiss="modal">TAKE ME BACK</a></center>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END CANCEL MODAL -->

@endsection