@extends('layouts.app_with_header')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12" style="padding-top: 100px;">
                <center>Hey thanks for signing up! We sent a confirmation to <strong>{{$email}}</strong>. If it's not there yet give it a couple of minutes or check your spam box.</center>
            </div>
        </div>
    </div>
@endsection