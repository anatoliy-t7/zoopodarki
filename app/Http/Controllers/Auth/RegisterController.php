<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\UserRegistration;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email'    => ['sometimes', 'required', 'string', 'email', 'max:50', 'unique:users,email'],
            'phone'    => ['sometimes', 'required', 'digits:10', 'unique:users,phone'],
            'password' => ['sometimes', 'required', 'string', 'min:8'],
        ]);
    }

    protected function create(array $data)
    {

        $user = User::create([
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        \Mail::to($user->email)
            ->send(new UserRegistration($data));

        return $user;
    }

    protected function createByPhone(array $data)
    {

        return User::create(
            [
                'phone' => $data['phone'],
            ]
        );

    }
}
