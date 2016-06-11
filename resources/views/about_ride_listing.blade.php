@extends('layouts.app_with_no_header')

@section('content')
    <div class="global-wrap">
        <div id="app" about="home"></div>
        <div class="bg-holder">
            <!-- TOP AREA -->
            <div class="top-area show-onload">
                <div class="bg-holder full">
                    <div class="bg-mask"></div>
                    <div class="bg-parallax" style="background-image:url(/img/stock/AdobeStock_93542098.jpeg);"></div>
                    <div class="bg-content">
                        <div class="container">
                            @include('layouts.header_transparent')
                        </div>
                        <div class="gap"></div>
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12">
                                    <h1 style="color:#cccccc">Grassroots mobilizing made simple.</h1>
                                </div>
                            </div>
                        </div>
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12">
                                    <p style="color:#ffffff; text-align: center; margin-top:30px; margin-bottom: 30px;">When you list your ride we match you with other progressives going to the convention so you can split costs and save money in this economy.</p>
                                    <h2 style="color:#ffffff !important; margin-bottom: 30px; text-align: center; margin-top: 30px;">How it works</h2>
                                    <div style="color:#ffffff; font-size: 15px; text-align: center; align-content: center">
                                        <div class="row">
                                            <div class="col-md-4" style="margin-bottom:15px;">
                                                <p><i class="fa fa-map-marker box-icon-large box-icon-black box-icon-to-inverse box-icon-center animate-icon-border-fadeout"></i></p>
                                                <p>Tell us where you're coming from</p>
                                            </div>
                                            <div class="col-md-4" style="margin-bottom:15px;">
                                                <p><i class="fa fa-users box-icon-large box-icon-black box-icon-to-inverse box-icon-center animate-icon-border-fadeout"></i></p>
                                                <p>How many people you can bring?</p>
                                            </div>
                                            <div class="col-md-4" style="margin-bottom:15px;">
                                                <p><i class="fa fa-calendar box-icon-large box-icon-black box-icon-to-inverse box-icon-center animate-icon-border-fadeout"></i></p>
                                                <p>Tell us what dates you'll be gone</p>
                                            </div>
                                            <div class="col-md-6" style="margin-bottom:15px;">
                                                <p><i class="fa fa-envelope box-icon-large box-icon-black box-icon-to-inverse box-icon-center animate-icon-border-fadeout"></i></p>
                                                <p>You will receive ride requests to your email</p>
                                            </div>
                                            <div class="col-md-6" style="margin-bottom:15px;">
                                                <p><i class="fa fa-wechat box-icon-large box-icon-black box-icon-to-inverse box-icon-center animate-icon-border-fadeout"></i></p>
                                                <p>Get in contact with each other</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-bottom:30px;">
                                        <center><a href="add-listing?type=r"><span class="btn btn-primary">Start Now</span></a></center>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END TOP AREA  -->
        </div>
    </div>
@endsection
