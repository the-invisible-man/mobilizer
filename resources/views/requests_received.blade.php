@extends('layouts.app_with_header')
@section('content')
    <div id="app" about="requests-received" data-token="{!! csrf_token() !!}"></div>
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="/">Home</a>
            </li>
            <li class="active">Requests</li>
        </ul>
        <h3 class="booking-title">Requests Received</h3>
        <div class="row">
            <div class="col-md-3 hidden-xs">
                @include('layouts.user_side_bar')
            </div>
            <div class="col-md-9 col-xs-12">
                <ul class="nav nav-tabs">
                    <li role="presentation" class="active requests_trigger" data-status="pending"><a href="#">Pending</a></li>
                    <li role="presentation" class="requests_trigger" data-status="accepted"><a href="#">Accepted</a></li>
                </ul>
            </div>
            <div class="col-md-9 col-xs-12" style="padding-top:20px;">
                <ul class="requests-list" id="requests-list" style="padding-left: 0;">
                    Loading...
                </ul>
            </div>
        </div>
        <div class="gap"></div>
    </div>

    <!-- REQUEST VIEW MODAL -->
    <div class="modal fade" id="request_window" tabindex="-1" role="dialog" aria-labelledby="contact">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header listing-modal-top" style="height:120px;">
                    <h4 class="modal-title" id="party_name_placeholder"><span id="request_party_name"></span></h4>
                </div>

                <div class="modal-body">
                    <!-- MAIN REQUEST VIEW SECTION -->
                    <div class="row hidden" style="padding-bottom: 20px;" id="request_options">
                        <div class="col-xs-12">
                            <center><a class="btn btn-danger font_depth" id="request_reject">DENY REQUEST</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-info font_depth" id="request_accept">ACCEPT REQUEST</a></center>
                        </div>
                    </div>
                    <div class="row hidden" style="padding-bottom: 20px" id="request_option_confirm">
                        <center><span id="request_option_confirm_message"></span></center><br>
                        <center><a class="btn btn-success font_depth" id="option_confirm">CONFIRM</a></center>
                    </div>
                    <div class="row hidden" style="padding-bottom: 20px;" id="no_request_options">
                        <center><strong><span style="color:#B90000;" id="no_request_option_message"></span></strong></center>
                    </div>
                    <div class="row hidden" style="padding-bottom: 20px;" id="cancel_request_holder">
                        <center><a class="btn btn-danger font_depth" id="cancel_request">CANCEL THIS REQUEST</a></center>
                    </div>
                    <div class="row">
                        <div class="col-sm-6"><strong>User</strong></div>
                        <div class="col-sm-6 mobile_padding"><span id="request_guest_name"></span></div>

                        <div class="col-sm-6"><strong>Passenger</strong></div>
                        <div class="col-sm-6 mobile_padding"><span id="request_total_people"></span></div>

                        <div class="col-sm-6"><strong>Pickup Location</strong></div>
                        <div class="col-sm-6 mobile_padding"><span id="request_pickup_location"></span></div>

                        <div class="col-sm-6"><strong>Date Submitted</strong></div>
                        <div class="col-sm-6 mobile_padding"><span id="request_date_submitted"></span></div>

                        <div class="col-sm-6"><strong>Message</strong></div>
                        <div class="col-sm-6 mobile_padding"><span id="request_additional_info"></span></div>

                        <div class="col-md-12" style="padding: 20px 0 20px 0;">
                            <center><a class="btn btn-success font_depth" id="contact_user">CONTACT USER</a></center>
                        </div>
                        <hr>
                        <!-- CONTACT SECTION -->
                        <div class="row" id="contact_section">
                            <div class="col-md-12">
                                <div class="alert alert-warning">
                                    <h4>How to Contact</h4>
                                    <p class="text-small" style="text-align: justify;">We use an email relay system that masks your real email address so that it is never exposed to the recipient. Below you will find the email address at which you can contact <span id="contact_name_short"></span>. <br><br><strong>BUT:</strong> In order for you to contact this user you <strong>MUST</strong> send your email from the address that you used to sign up to this site. All other mail will be printed onto paper and then shredded into tiny pieces. No joke.</p>
                                </div>
                            </div>
                            <div class="col-md-12" style="padding-bottom: 20px;">
                                <center><strong>Contact Email</strong></center>
                                <div class="hidden-md hidden-sm hidden-xs">
                                    <center><input type="text" value="" style="width:100%;" id="request_contact_email" readonly/></center>
                                </div>
                                <div class="hidden-lg">
                                    <center><span id="request_contact_email_mobile"></span></center>
                                </div>
                            </div>
                        </div>
                        <!-- END CONTACT SECTION -->

                        <div class="col-md-12">
                            <h4>Your Trip Overview</h4>
                        </div>
                        <div class="col-sm-6"><strong>Departing</strong></div>
                        <div class="col-sm-6 mobile_padding"><span id="listing_location"></span></div>

                        <div class="col-sm-6"><strong>Date</strong></div>
                        <div class="col-sm-6 mobile_padding"><span id="listing_starting_date"></span></div>

                        <div class="col-sm-6"><strong>Time Leaving</strong></div>
                        <div class="col-sm-6 mobile_padding"><span id="listing_leaving"></span></div>

                        <div class="col-sm-6"><strong>Max Passengers</strong></div>
                        <div class="col-sm-6 mobile_padding"><span id="listing_max_occupants"></span></div>

                        <div class="col-sm-6"><strong>Seats Remaining</strong></div>
                        <div class="col-sm-6 mobile_padding"><span id="listing_remaining_slots"></span></div>

                        <div class="gap"></div>

                        <div class="col-md-12">
                            <div id="map-canvas" style="width:100%; height:400px;"></div>
                        </div>
                    </div>
                    <!-- END MAIN REQUEST VIEW SECTION -->

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- END REQUEST VIEW MODAL -->

@endsection