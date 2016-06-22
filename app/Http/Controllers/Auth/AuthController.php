<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailer;
use Illuminate\Mail\Message;
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
     * @var Mailer
     */
    private $mailer;

    /**
     * Create a new authentication controller instance.
     *
     * @param Factory $validator
     * @param Mailer $mailer
     */
    public function __construct(Factory $validator, Mailer $mailer)
    {
        $this->middleware('guest', ['except' => 'logout']);

        $this->validator    = $validator;
        $this->mailer       = $mailer;
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function create(array $data)
    {
        $user = User::create([
            'first_name'            => $data['first_name'],
            'last_name'             => $data['last_name'],
            'email'                 => $data['email'],
            'password'              => bcrypt($data['password']),
            'confirmation_code'     => str_random()
        ]);

        return $this->sendEmailConfirmation($user);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function resendEmailConfirm(Request $request)
    {
        $user = User::where('email', '=', $request->get('email'))->first();

        if (!$user) {
            return view('home');
        }

        return $this->sendEmailConfirmation($user);
    }

    /**
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    private function sendEmailConfirmation(User $user)
    {
        $this->mailer->send('emails.confirm_email', $user->toArray(), function (Message $message) use($user) {
            $message->to($user->getEmail());
            $message->subject('Confirm Your SeeYouInPhilly.com Account');
        });

        return view('auth.signup_confirm', $user->toArray());
    }

    /**
     * @param string $confirmation_code
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function confirmEmail(string $confirmation_code)
    {
        /**
         * @var User $user
         */
        // Activate account linked to confirmation code
        $user = User::find($confirmation_code);

        // If no use is found response with error
        if (!$user) {
            return view('auth.bad_email_confirm_code.blade.php');
        }

        $user->setConfirmed(true);
        $user->save();

        return view('auth.email_confirm_success', $user->toArray());

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
