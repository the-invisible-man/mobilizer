@extends('layouts.app_with_header')
@section('content')
    <div class="container" style="text-align: center;">
        <br><br><br><br><br>
        Awesome! We'll give you a heads up at <strong>{{$email}}</strong> as soon as something becomes available in your area!
        <br><br>
        <a href="/" class="btn btn-primary">Go Home</a>
    </div>
@endsection