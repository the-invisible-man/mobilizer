@extends('layouts.app')

@section('content')
    <div class="gap"></div>


    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <i class="fa fa-check round box-icon-large box-icon-center box-icon-success mb30"></i>
                <h2 class="text-center">John, your payment was successful!</h2>
                <h5 class="text-center mb30">Booking details has been send to johndoe@gmail.com</h5>
                <ul class="order-payment-list list mb30">
                    <li>
                        <div class="row">
                            <div class="col-xs-9">
                                <h5><i class="fa fa-plane"></i> Flight from London to New York City</h5>
                                <p><small>April 24, 2014</small>
                                </p>
                            </div>
                            <div class="col-xs-3">
                                <p class="text-right"><span class="text-lg">$150</span>
                                </p>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="row">
                            <div class="col-xs-9">
                                <h5><i class="fa fa-plane"></i> Flight from New York City to London</h5>
                                <p><small>April 28, 2014</small>
                                </p>
                            </div>
                            <div class="col-xs-3">
                                <p class="text-right"><span class="text-lg">$187</span>
                                </p>
                            </div>
                        </div>
                    </li>
                </ul>
                <h4 class="text-center">You might also need in New York</h4>
                <ul class="list list-inline list-center">
                    <li><a class="btn btn-primary" href="#"><i class="fa fa-building-o"></i> Hotels</a>
                        <p class="text-center lh1em mt5"><small>398 offers<br /> from $139</small>
                        </p>
                    </li>
                    <li><a class="btn btn-primary" href="#"><i class="fa fa-home"></i> Rentlas</a>
                        <p class="text-center lh1em mt5"><small>229 offers<br /> from $143</small>
                        </p>
                    </li>
                    <li><a class="btn btn-primary" href="#"><i class="fa fa-dashboard"></i> Cars</a>
                        <p class="text-center lh1em mt5"><small>180 offers<br /> from $73</small>
                        </p>
                    </li>
                    <li><a class="btn btn-primary" href="#"><i class="fa fa-bolt"></i> Activities</a>
                        <p class="text-center lh1em mt5"><small>274 offers<br /> from $131</small>
                        </p>
                    </li>
                </ul>
            </div>
        </div>
        <div class="gap"></div>
    </div>
@endsection