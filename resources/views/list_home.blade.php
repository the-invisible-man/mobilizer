@extends('layouts.app')

@section('content')
    <div class="container pad-header">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <h2>List My Home</h2>
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
                                    <input type="text" class="form-control" name="party_name" id="party_name" placeholder="Enter Party Name" required>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="InputEmail">Starting Date</label>
                                <div class="input-group">
                                    <input class="date-pick form-control" data-date-format="DD d MM yyyy" type="text" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="InputEmail">Ending Date</label>
                                <div class="input-group">
                                    <input class="date-pick form-control" data-date-format="DD d MM yyyy" type="text" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="InputEmail">Max Guests</label>
                                <div class="input-group">
                                    <input type="text" class="form-control bfh-number" placeholder="Up to how many people can you have over?">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="InputEmail">ZIP Code</label>
                                <div class="input-group">
                                    <input type="text" class="form-control bfh-number" placeholder="ZIP Code">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="checkbox col-md-8 col-md-offset-3">
                                    <label><input class="i-check" type="checkbox" />Guests can bring a dog!</label>
                                </div>
                                <div class="checkbox col-md-8 col-md-offset-3">
                                    <label><input class="i-check" type="checkbox" />Guests can bring a cat!</label>
                                </div>
                            </div>
                            <label for="InputMessage">Additional Info</label>
                            <div class="input-group">
                                <textarea name="InputMessage" rows="7" cols="100" placeholder="A short intro" id="InputMessage" class="form-control" required></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                Check if you'd be open to allowing guests to bring their pet. Be sure that pets are safe AT ALL times. I mean, make sure you're ALL safe... but the pets more.
                                <br><br>
                                <div class="row">
                                    <div class="checkbox col-md-8 col-md-offset-3">
                                        <label><input class="i-check" type="checkbox" />Guests can bring a dog!</label>
                                    </div>
                                    <div class="checkbox col-md-8 col-md-offset-3">
                                        <label><input class="i-check" type="checkbox" />Guests can bring a cat!</label>
                                    </div>
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
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection