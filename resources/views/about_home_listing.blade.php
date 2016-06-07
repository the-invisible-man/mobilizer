@extends('layouts.app_with_no_header')

@section('content')
    <div class="global-wrap">
        <div id="app" about="home"></div>
        <div class="bg-holder">
            <!-- TOP AREA -->
            <div class="top-area show-onload">
                <div class="bg-holder full">
                    <div class="bg-mask"></div>
                    <div class="bg-parallax" style="background-image:url(/img/stock/philly.png);"></div>
                    <div class="bg-content">
                        <div class="container">
                            @include('layouts.header_transparent')
                        </div>
                        <div class="gap"></div>
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12">
                                    <h1 style="color:#cccccc">Share your home. Join the revolution.</h1>
                                </div>
                            </div>
                        </div>
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12">
                                    <p style="color:#ffffff; text-align: center; margin-top:30px; margin-bottom: 30px;">Give a fellow berner a cozy home to crash.</p>
                                    <h2 style="color:#ffffff !important; margin-bottom: 30px; text-align: center; margin-top: 30px;">How it works</h2>
                                    <div style="color:#ffffff; font-size: 15px; text-align: center; align-content: center">
                                        <div class="row">
                                            <div class="col-md-3" style="margin-bottom:15px;">
                                                <p>Enter the location of your home</p>
                                                <p><i class="fa fa-home box-icon-large box-icon-black box-icon-to-inverse box-icon-center animate-icon-border-fadeout"></i></p>
                                            </div>
                                            <div class="col-md-3" style="margin-bottom:15px;">
                                                <p>How many people can you host?</p>
                                                <p><i class="fa fa-users box-icon-large box-icon-black box-icon-to-inverse box-icon-center animate-icon-border-fadeout"></i></p>
                                            </div>
                                            <div class="col-md-3" style="margin-bottom:15px;">
                                                <p>Tell us what dates you can host</p>
                                                <p><i class="fa fa-calendar box-icon-large box-icon-black box-icon-to-inverse box-icon-center animate-icon-border-fadeout"></i></p>
                                            </div>
                                            <div class="col-md-3" style="margin-bottom:15px;">
                                                <p>Do you allow pets?</p>
                                                <p><i class="fa fa-paw box-icon-large box-icon-black box-icon-to-inverse box-icon-center animate-icon-border-fadeout"></i></p>
                                            </div>
                                            <div class="col-md-6" style="margin-bottom:15px;">
                                                <p>You will receive housing requests to your email</p>
                                                <p><i class="fa fa-envelope box-icon-large box-icon-black box-icon-to-inverse box-icon-center animate-icon-border-fadeout"></i></p>
                                            </div>
                                            <div class="col-md-6" style="margin-bottom:15px;">
                                                <p>Get in contact with each other</p>
                                                <p><i class="fa fa-wechat box-icon-large box-icon-black box-icon-to-inverse box-icon-center animate-icon-border-fadeout"></i></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-bottom:30px;">
                                        <center><a href="add-listing?type=h"><span class="btn btn-primary">Start Now</span></a></center>
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
