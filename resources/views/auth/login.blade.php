@extends('layouts.app_with_no_header')

@section('content')
    <div class="global-wrap">
        <div id="app" about="home"></div>
        <div class="bg-holder">
            <!-- TOP AREA -->
            <div class="top-area show-onload login_wrap">
                <div class="bg-holder full">
                    <div class="bg-mask"></div>
                    <div class="bg-parallax" style="background-image:url(/img/photography/1-sctffvDbRnImiQhvNppFYA.jpeg);"></div>
                    <div class="bg-content">
                        <div class="container">
                            @include('layouts.header_transparent')
                        </div>
                        <div class="container">
                            <h1 class="page-title" style="color:#ffffff !important;">Login/Register</h1>
                        </div>

                        <div class="container" style="color:#ffffff !important;">
                            <div class="row" data-gutter="60">
                                <div class="col-md-4" style="text-align: justify">
                                    <h3 style="color:#ffffff !important;">Get Mobilized</h3>
                                    <p>Welcome to the <strong>Mobilizer</strong> platform by <strong>Polivet</strong>. We are a political grassroots software powerhouse that delivers solutions for the benefit of the 99%.</p>
                                    <p>Sign up to use our powerful tools for planning, booking, or listing your journey to the Democratic National Convention. #FeelTheBern #SeeYouInPhilly</p>
                                </div>
                                <div class="col-md-4" style="margin-bottom: 30px">
                                    <h3 style="color:#ffffff !important;">Login</h3>
                                    <form role="form" method="POST" action="{{ url('/login') }}">
                                        {!! csrf_field() !!}
                                        <div class="form-group form-group-icon-left{{ $errors->has('email') && Session::get('last_auth_attempt') == 'login' ? ' has-error' : '' }}"><i class="fa fa-user input-icon input-icon-show"></i>
                                            <label>E-mail </label>
                                            <input class="form-control" placeholder="e.g. johndoe@gmail.com" type="text" name="email" value="{{ old('email') }}"/>
                                            @if ($errors->has('email') && Session::get('last_auth_attempt') == 'login')
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group form-group-icon-left{{ $errors->has('password') && Session::get('last_auth_attempt') == 'login' ? ' has-error' : '' }}"><i class="fa fa-lock input-icon input-icon-show"></i>
                                            <label>Password</label>
                                            <input class="form-control" type="password" placeholder="my secret password" name="password"/>
                                            @if ($errors->has('password') && Session::get('last_auth_attempt') == 'login')
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-btn fa-sign-in"></i>Login
                                        </button>
                                        <a class="btn btn-link" href="{{ url('/password/reset') }}">Forgot Your Password?</a>
                                    </form>
                                </div>
                                <div class="col-md-4" style="margin-bottom: 30px">
                                    <h3 style="color:#ffffff !important;">New to #SeeYouInPhilly?</h3>
                                    <form role="form" method="POST" id="sign_up_form" action="{{ url('/register') }}">
                                        {!! csrf_field() !!}
                                        <div class="form-group form-group-icon-left{{ $errors->has('first_name') ? ' has-error' : '' }}"><i class="fa fa-user input-icon input-icon-show"></i>
                                            <label>First Name</label>
                                            <input class="form-control" type="text" name="first_name" placeholder="Jane" value="{{ old('first_name') }}"/>
                                            @if ($errors->has('first_name'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('first_name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group form-group-icon-left{{ $errors->has('last_name') ? ' has-error' : '' }}"><i class="fa fa-user input-icon input-icon-show"></i>
                                            <label>Last Name</label>
                                            <input class="form-control" type="text" name="last_name" placeholder="Doe" value="{{ old('last_name') }}"/>
                                            @if ($errors->has('last_name'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('last_name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group form-group-icon-left{{ $errors->has('email') && Session::get('last_auth_attempt') == 'register' ? ' has-error' : '' }}"><i class="fa fa-envelope input-icon input-icon-show"></i>
                                            <label>Email</label>
                                            <input class="form-control" placeholder="e.g. janedoe@gmail.com" type="text" name="email" value="{{ old('email') }}"/>
                                            @if ($errors->has('email') && Session::get('last_auth_attempt') == 'register' )
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group form-group-icon-left{{ $errors->has('password') && Session::get('last_auth_attempt') == 'register' ? ' has-error' : '' }}"><i class="fa fa-lock input-icon input-icon-show"></i>
                                            <label>Password</label>
                                            <input class="form-control" type="password" placeholder="my secret password" name="password"/>
                                            @if ($errors->has('password') && Session::get('last_auth_attempt') == 'register')
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group form-group-icon-left{{ $errors->has('password_confirmation') && Session::get('last_auth_attempt') == 'register' ? ' has-error' : '' }}"><i class="fa fa-lock input-icon input-icon-show"></i>
                                            <label>Confirm Password</label>
                                            <input class="form-control" type="password" placeholder="my secret password, again" name="password_confirmation"/>
                                            @if ($errors->has('password_confirmation') && Session::get('last_auth_attempt') == 'register')
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="alert alert-warning">
                                            <p class="text-small" style="text-align: justify;">
                                                <input type="checkbox" id="disclaimer_accept"/>
                                                I am <strong>18 years of age or older</strong> and I agree with the <a href="{{url('/')}}/tos" target="_blank">terms of service</a> and the <a href="{{url('/')}}/privacy" target="_blank">privacy policy</a> of SeeYouInPhilly.com
                                                <br><br>
                                                <strong>**NOTICE**</strong> Keep your password safe at all times. We will <strong>NEVER</strong> under any circumstance ask you for your password, neither by email or by private message on social media. Do not enter your password on any other site other than this site. Always check that your address bar says <strong>https://www.seeyouinphilly.com/</strong>
                                            </p>
                                            <span style="color:#B90000" id="accept_tos"></span>
                                        </div>
                                        <div class="form-group form-group-icon-left"><i class="fa fa-lock input-icon input-icon-show"></i>
                                            <div style="margin: 0 auto;">
                                            {!! Recaptcha::render() !!}
                                            </div>
                                            @if ($errors->has('g-recaptcha-response') && Session::get('last_auth_attempt') == 'register')
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-btn fa-user"></i>Register
                                        </button>
                                    </form>
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
