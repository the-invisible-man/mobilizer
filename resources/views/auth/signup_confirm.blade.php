@extends('layouts.app_with_header')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12" style="padding-top: 100px;">
                Hey thanks for signing up! We sent a confirmation to email to {{$email}}. If it's not there yet give it a couple of minutes or check your spam box.
            </div>
        </div>
    </div>
@endsection