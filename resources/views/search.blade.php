@extends('layouts.app_with_header')
@section('content')
    <div id="app" about="search"></div>
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="/">Home</a>
            </li>
            <li class="active">Results for rides near {{$query_info['search_term']['geocoded']['city']}}, {{$query_info['search_term']['geocoded']['state']}}</li>
        </ul>
        <h3 class="booking-title">{{$number_of_hits}} Rides Near <span style="color:#0066b8">{{$query_info['search_term']['geocoded']['city']}}, {{$query_info['search_term']['geocoded']['state']}}</span></h3>
        <div class="row">
            <div class="col-md-3">
                <div class="booking-item-dates-change mb30">
                    <form method="get" action="/search">
                        <input type="hidden" name="original_query" value="{{$query_info['search_term']['raw']}}" id="original_query"/>
                        <input type="hidden" name="type" value="{{$query_info['type']}}" />
                        <div class="form-group form-group-icon-left"><i class="fa fa-map-marker input-icon input-icon-hightlight"></i>
                            <label>Picked up from</label>
                            <input class="form-control" id="autocomplete" name="location" value="{{$query_info['search_term']['geocoded']['city']}}, {{$query_info['search_term']['geocoded']['state']}}" placeholder="Address, City, or U.S. Zip Code" type="text" />
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
                        <p>Sorry we found no matches for your query at this time :(</p>
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

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection