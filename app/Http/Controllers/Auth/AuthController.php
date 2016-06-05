<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Validation\Factory;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Validation\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * @var Factory
     */
    protected $validator;

    /**
     * Create a new authentication controller instance.
     *
     * @param Factory $validator
     */
    public function __construct(Factory $validator)
    {
        $this->middleware('guest', ['except' => 'logout']);
        $this->validator = $validator;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return Validator
     */
    protected function validator(array $data) : Validator
    {
        return $this->validator->make($data, [
            'first_name'    => 'required|max:255',
            'last_name'     => 'required|max:255',
            'email'         => 'required|email|max:255|unique:users',
            'password'      => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'first_name'    => $data['first_name'],
            'last_name'     => $data['last_name'],
            'email'         => $data['email'],
            'password'      => bcrypt($data['password'])
        ]);
    }

    protected function facebook()
    {
        Socialite::with('facebook')->redirect();
    }

    protected function callback()
    {
        $user = Socialite::with('facebook')->user();

        return $user;
    }
}
