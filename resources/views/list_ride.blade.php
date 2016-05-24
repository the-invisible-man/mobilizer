@extends('layouts.app')

@section('content')
    <div class="container pad-header">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <h2>List My Ride</h2>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <form role="form">
                <div class="col-md-8 col-md-offset-2">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="well well-sm"><span class="glyphicon glyphicon-asterisk"></span>Hey! Just fill out the form below, <strong>all fields are required.</strong></div>
                            <div class="form-group">
                                <label for="InputName">Party Name <span class="sub_text">(A fun little name to give your journey to the DNC)</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="party_name" id="party_name" placeholder="Bernin' The Road" required>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="InputEmail">Date You're Leaving Your House</label>
                                <div class="input-group">
                                    <input class="date-pick form-control" data-date-format="DD d MM yyyy" type="text" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="InputEmail">Date You're Leaving Philly</label>
                                <div class="input-group">
                                    <input class="date-pick form-control" data-date-format="DD d MM yyyy" type="text" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="InputEmail">Max Passengers</label>
                                <div class="input-group">
                                    <input type="numeric" class="form-control bfh-number" placeholder="How many passengers can you fit?">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="InputEmail">Starting Point</label>
                                <div class="input-group">
                                    <input type="text" class="form-control bfh-number" id="autocomplete" placeholder="Address or ZIP Code You're Driving From">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="route_results_holder">
                            <div class="col-md-12">
                                <center>Please choose a preferred route. We will use this information to match passengers with the ride closest to them.</center>
                            </div>
                            <div class="gap"></div>
                            <div class="col-md-11 col-md-offset-1">
                                <strong>Found <span id="number_of_routes"></span> route<span id="route_plural"></span> coming from <span id="starting_address" style="color:green"></span> to Philly:</strong>
                                <ul class="booking-item-features booking-item-features-expand mb30 clearfix" id="route_results">

                                </ul>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div id="map-canvas" style="width:100%; height:500px;"></div>
                        </div>
                        <div class="gap"></div>
                        <div class="col-md-12">
                            <div class="form-group">
                                Check if you'd be open to allowing passengers to bring their pet along. Be sure that pets are safe AT ALL times. I mean, make sure you're ALL safe... but the pets more.
                                <br><br>
                                <div class="row">
                                    <div class="checkbox col-md-8 col-md-offset-4">
                                        <label><input class="i-check" type="checkbox" />Passengers can bring a dog!</label>
                                    </div>
                                    <div class="checkbox col-md-8 col-md-offset-4">
                                        <label><input class="i-check" type="checkbox" />Passengers can bring a cat!</label>
                                    </div>
                                    <div class="checkbox col-md-8 col-md-offset-4">
                                        <label><input class="i-check" type="checkbox" />I like to have <strong><span style="color:#00cc00">fun</span></strong> :)</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="InputMessage">Additional Info</label>
                                <div class="input-group">
                                    <textarea name="InputMessage" rows="7" cols="100" placeholder="A short intro" id="InputMessage" class="form-control" required></textarea>
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