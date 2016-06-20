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
                <ul id="listings-list">
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
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="party_name" id="listing_party_name" pattern=".{5,}" required title="Make your party name at least 5 characters." placeholder="Bernin' The Road">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="InputEmail">Max Passengers</label>
                                    <div class="input-group">
                                        <input type="numeric" class="form-control bfh-number" name="max_occupants" id="listing_max_occupants" placeholder="How many passengers can you fit?" required>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="InputMessage">Additional Info</label>
                                    <div class="input-group">
                                        <textarea name="additional_info" rows="7" cols="100" placeholder="A short intro" id="listing_additional_info" class="form-control" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="col-md-12">
                            <center><a class="btn btn-primary font_depth" id="contact_user">SAVE</a></center><br>
                            <center><a class="btn btn-danger font_depth" id="contact_user">DEACTIVATE LISTING</a></center>
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