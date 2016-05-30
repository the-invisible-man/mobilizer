@extends('layouts.app')

@section('content')
    <div class="container pad-header">
        <div class="row">
            <div class="col-md-8 col-md-offset-4">
                <h2>List My Ride</h2>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <form role="form" name="list_user_ride" id="list_user_ride_form">
                <input type="hidden" value="0" id="selected_user_route" name="selected_user_route"/>
                <input type="hidden" value="R" id="type" name="type"/>
                <div class="col-md-8 col-md-offset-2">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="well well-sm"><span class="glyphicon glyphicon-asterisk"></span>Hey! Just fill out the form below, <strong>all fields are required.</strong></div>
                            <div class="form-group">
                                <label for="InputName">Party Name <span class="sub_text">(A fun little name to give your journey to the DNC)</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="party_name" id="party_name" pattern=".{5,}" required title="Make your party name at least 5 characters." placeholder="Bernin' The Road">
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
                                    <label>Approximate Time</label>
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
                                    <input type="text" class="form-control bfh-number" id="autocomplete" name="starting_location" placeholder="Address or ZIP Code You're Driving From" required title = "You have to enter a starting point. You can use your ZIP code.">
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
                                Check if you'd be open to allowing passengers to bring their pet along. Be sure that pets are safe AT ALL times. I mean, make sure you're ALL safe... but the pets more.
                                <br><br>
                                <div class="row" style="padding-left:20px;">
                                    <div class="checkbox col-md-8 col-md-offset-4">
                                        <label><input class="i-check" type="checkbox" name="dog_friendly"/>Passengers can bring a dog!</label>
                                    </div>
                                    <div class="checkbox col-md-8 col-md-offset-4">
                                        <label><input class="i-check" type="checkbox" name="cat_friendly"/>Passengers can bring a cat!</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="InputMessage">Additional Info</label>
                                <div class="input-group">
                                    <textarea name="additional_info" rows="7" cols="100" placeholder="A short intro" id="InputMessage" class="form-control" required></textarea>
                                </div>
                            </div>
                            <dix class="well well-sm col-md-12 text-center">
                                <row>
                                    <div class="col-md-11 col-md-offset-1">
                                        <label><input class="i-check" type="checkbox" />I've read the disclaimer/terms of service of ride sharing</label>
                                    </div>
                                </row>
                            </dix>
                            <input type="submit" name="submit" id="submit" value="Submit" class="btn btn-info pull-right">
                            <br><br>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection