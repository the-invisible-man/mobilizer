@extends('layouts.app_with_header')

@section('content')
    <div id="app" about="ride-list"></div>
    <div class="gap"></div>
    <div class="container pad-header">
        <div class="row">
            <div class="col-md-10 col-md-offset-2">
                <h2>List My Ride</h2>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <form role="form" name="list_user_ride" method="post" action="/listings" id="list_user_ride_form">
                {!! csrf_field() !!}
                <input type="hidden" value="0" id="overview_path" name="overview_path"/>
                <input type="hidden" value="0" id="name" name="name"/>
                <input type="hidden" value="R" id="type" name="type"/>
                <div class="col-md-8 col-md-offset-2">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="well well-sm"><span class="glyphicon glyphicon-asterisk"></span>Fill out the form below, <strong>all fields are required.</strong></div>
                            <div class="form-group">
                                <label for="InputName">Party Name <span class="sub_text">(A fun little name to give your journey to the DNC)</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="party_name" id="party_name" placeholder="Bernin' The Road">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-daterange">
                            <div class="row" style="margin:2px;">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Date You're Leaving Your House</label>
                                        <input class="form-control" name="start" type="text" required/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label>Approximate Time Leaving</label>
                                    <select name="time_of_day" class="form-control" required>
                                        <option value="0">Early Morning</option>
                                        <option value="1" selected>Noon</option>
                                        <option value="2">Afternoon</option>
                                        <option value="3">Evening</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Date You're Leaving Philly</label>
                                        <input class="form-control" name="end" type="text" required/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="InputEmail">Max Passengers</label>
                                <div class="input-group">
                                    <input type="numeric" class="form-control bfh-number" name="max_occupants" placeholder="How many passengers can you fit?" required>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="InputEmail">Starting Point</label>
                                <div class="input-group">
                                    <input type="text" class="form-control bfh-number" id="autocomplete" name="location" placeholder="Address or ZIP Code You're Driving From" required title = "You have to enter a starting point. You can use your ZIP code.">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="route_results_holder">
                            <div class="col-md-12">
                                <strong><center>Please choose a preferred route. We will use this information to match passengers with the ride closest to them.</center></strong>
                            </div>
                            <div class="gap"></div>
                            <div class="col-md-12">
                                <strong>Found <span id="number_of_routes"></span> route<span id="route_plural"></span> coming from <span id="starting_address" style="color:green"></span> to Philly:</strong>
                                <ul class="booking-item-features booking-item-features-expand mb30 clearfix" id="route_results">

                                </ul>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div id="map-canvas" style="width:100%; height:400px;"></div>
                        </div>
                        <div class="gap"></div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="InputMessage">Additional Info</label>
                                <div class="input-group">
                                    <textarea name="additional_info" rows="7" cols="100" placeholder="A short intro" id="InputMessage" class="form-control" required></textarea>
                                </div>
                            </div>
                            <div class="col-md-12" style="margin-top:15px;">
                                <div class="alert alert-warning">
                                    <p class="text-small" style="text-align: justify;"><strong>DISCLAIMER:</strong>
                                        <br>By using this service you agree to the <a href="/tos" target="_blank">terms of service</a> and our <a href="/privacy" target="_blank">privacy policy</a>. You also certify that you, or the person who is driving, will <strong>have a valid driver's license</strong> by the day of the trip.<br>
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
                                <br>
                                <center><span class="help-block hidden" id="disclaimer_accept_error" style="color: #b90000;">You must agree to the terms above to continue</span></center>
                            </div>
                            <br><br>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="row" style="margin:10px;">
            <div class="col-md-3 col-md-offset-4">
                <center><input type="submit" name="submit" value="Submit" id="submit_listing" class="btn btn-info pull-right"></center>
            </div>
        </div>
    </div>
    <!-- Confirm Modal -->
    <div class="modal fade" id="listing_confirmation_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Confirm Your Listing</h4>
                </div>
                <div class="modal-body" id="listing_confirmation_content">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Go Back and Make Changes</button>
                    <button type="button" class="btn btn-primary" id="submit_listing_confirm_now">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="submit_error_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">(!)Validation Error!</h4>
                </div>
                <div class="modal-body" id="submit_error_modal_content">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">ok</button>
                </div>
            </div>
        </div>
    </div>
@endsection