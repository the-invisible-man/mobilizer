@extends('layouts.app_with_header')
@section('content')
    <div id="app" about="my-listings" data-token="{!! csrf_token() !!}"></div>
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="/">Home</a>
            </li>
            <li class="active">My Listings</li>
        </ul>
        <h3 class="booking-title">My Listings</h3>
        <div class="row">
            <div class="col-md-3 hidden-xs">
                @include('layouts.user_side_bar')
            </div>
            <div class="col-md-9 col-xs-12">
                <ul id="listings-list" style="padding-left: 0;">
                    Loading...
                </ul>
            </div>
        </div>
        <div class="gap"></div>
    </div>

    <!-- REQUEST VIEW MODAL -->
    <div class="modal fade" id="edit_window" tabindex="-1" role="dialog" aria-labelledby="contact">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header listing-modal-top" style="height:120px;">
                    <h4 class="modal-title" id="party_name_placeholder">Edit Listing</h4>
                </div>

                <div class="modal-body">
                    <!-- MAIN REQUEST VIEW SECTION -->
                    <div class="row">
                        <!-- END CONTACT SECTION -->
                        <form id="edit_listing_form">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="InputName">Party Name <span class="sub_text">(A fun little name to give your journey to the DNC)</span></label>
                                    <input type="text" class="form-control" name="party_name" id="listing_party_name" placeholder="Bernin' The Road">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <span class="help-block hidden" id="party_name_error" style="color: #b90000;"></span>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="InputEmail">Max Passengers</label>
                                    <input type="numeric" class="form-control bfh-number" name="max_occupants" id="listing_max_occupants" placeholder="How many passengers can you fit?" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <span class="help-block hidden" id="max_occupants_error" style="color: #b90000;"></span>
                            </div>

                            <div class="col-xs-4">
                                <label>Additional info:</label>
                            </div>
                            <div class="col-xs-8" style="text-align: right">
                                <strong>50 Character Minimum: <span id="additional_info_character_count">0</span>/500</strong>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="input-group" style="width: 100%;">
                                        <textarea name="additional_info" rows="7" style="width:100%;" placeholder="A short intro" id="listing_additional_info" class="form-control" required></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <span class="help-block hidden" id="additional_info_error" style="color: #b90000;"></span>
                            </div>
                        </form>
                        <div class="col-md-12">
                            <center><a class="btn btn-primary font_depth" id="save_edits">SAVE</a></center><br>
                            <center><a class="btn btn-danger font_depth" id="deactivate_listing">DEACTIVATE LISTING</a></center>
                        </div>
                        <div class="col-md-12" id="deactivate_confirm">
                            <div class="alert alert-danger">
                                <p class="text-small" style="text-align: justify;">
                                    <center><strong>WARNING</strong></center>
                                    You are about to take down the listing "<strong><span id="deactivate_party_name"></span></strong>". This action cannot be undone. If you have accepted any requests for this listing, we will notify the users.
                                </p>
                                <br><center><a class="btn btn-danger font_depth" id="deactivate_confirm_button">I UNDERSTAND</a>&nbsp;<a class="btn btn-primary font_depth" id="deactivate_cancel">CANCEL</a></center>
                            </div>
                        </div>
                        <div class="gap"></div>
                        <hr>
                        <div class="col-md-12">
                            <h3>Trip Summary</h3>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-12" style="margin-top:15px;">
                                <div class="alert alert-warning">
                                    <p class="text-small" style="text-align: justify;">
                                        This information cannot be edited. If you need to edit any of the following fields you will need to deactivate this listing and list it again with the new information.<br><br>
                                        <strong>NOTE:</strong> If you have already accepted ride requests from any user, they will lose their booking and will have to rebook their trip with the new listing.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-6">
                            <strong>Date Leaving</strong><br>
                            <strong>Time of Day</strong><br>
                            <strong>Date Returning</strong><br>
                            <strong>Route</strong>
                        </div>
                        <div class="col-md-6 col-xs-6">
                            <span id="listing_date_leaving"></span><br>
                            <span id="listing_time_of_day"></span><br>
                            <span id="listing_date_returning"></span><br>
                            <span id="listing_route_name"></span><br>
                        </div>
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