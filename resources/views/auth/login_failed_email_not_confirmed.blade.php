@extends('layouts.app_with_header')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12" style="padding-top:100px;">
                <center>We can't login you in until you have confirmed your email. If you have not received an email confirmation please <a href="/account/send_confirm?email={{$email}}">click here</a> to resend the email.</center>
            </div>
        </div>
    </div>
@endsection