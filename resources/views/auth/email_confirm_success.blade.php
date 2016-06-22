@extends('layouts.app_with_header')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12" style="padding-top: 100px;">
                <center>Your email was successfully confirmed! <a href="{{url('/')}}/login">Click here</a> to login.</center>
            </div>
        </div>
    </div>
@endsection