<?php
namespace App\Http\Livewire\Auth;

use App\Jobs\GetUserDiscountFrom1C;
use App\Models\User;
use App\Notifications\SendOTP;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;
use Seshac\Otp\Otp;

class In extends Component
{
    public $phone;
    public $enteredOtp;
    public $email;
    public $password;
    protected $user;
    protected $otp;
    public $token;
    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];
    protected $listeners = ['createOtp', 'checkUser'];

    public function createOtp($phone = null)
    {
        if ($phone !== null) {
            $this->phone = $phone;
        }

        $functionOtp = Otp::generate($this->phone);

        if ($functionOtp->status === false) {
            $this->dispatchBrowserEvent('toaster', ['class' => 'bg-red-500', 'message' => 'Вы превысили лимит СМС, попробуйте через 15 минут']);
        } else {
            $this->token = $functionOtp->token;

            $this->sendSMS();
        }
    }

    public function checkUser($enteredOtp)
    {
        $this->enteredOtp = $enteredOtp;

        if (User::where('phone', $this->phone)->first()) {
            if ($this->checkOtp()) {
                $this->authUser();
            }
        } else {
            if ($this->checkOtp()) {
                $this->createUser();
                $this->authUser();
            }
        }
    }

    public function authUser()
    {
        $functionUser = User::where('phone', $this->phone)->first();

        Auth::login($functionUser, $remember = true);

        // Проверка на дисконтную карту
        if ($functionUser->discount == 0) {
            GetUserDiscountFrom1C::dispatch($functionUser);
        }

        $this->dispatchBrowserEvent('reloadPage');
    }

    public function checkOtp()
    {
        $expires = Otp::expiredAt($this->phone);

        if ($expires->status === false) {
            $this->dispatchBrowserEvent('toaster', ['class' => 'bg-red-500', 'message' => 'Вышел срок OTP']);

            return false;
        }

        $verify = Otp::validate($this->phone, $this->enteredOtp);

        if ($verify->status === false) {
            if ($verify->message == 'OTP does not match') {
                $this->dispatchBrowserEvent('toaster', ['class' => 'bg-red-500', 'message' => 'OTP не соответствует']);
            }

            if ($verify->message == 'Reached the maximum allowed attempts') {
                $this->dispatchBrowserEvent('toaster', ['class' => 'bg-red-500', 'message' => 'Достигнуто максимально допустимое количество попыток, попробуйте через 15 минут', 'timeout' => '2000']);
            }

            return false;
        }

        return true;
    }

    public function createUser()
    {
        $this->user = User::create([
            'phone' => $this->phone,
            'password' => Hash::make(Str::random(8)),
        ]);
    }

    public function sendSMS()
    {
        try {
            (new AnonymousNotifiable())
                ->route('smscru', '+7' . $this->phone)
                ->notify(new SendOTP($this->token));

            $this->dispatchBrowserEvent('toaster', ['message' => 'OTP отправлен']);
        } catch (\Throwable $th) {
            \Log::error($th);

            $this->dispatchBrowserEvent('toaster', ['message' => 'OTP не отправлен']);
        }
    }

    public function loginByEmail()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            if (auth()->user()->discount === 0) {
                GetUserDiscountFrom1C::dispatch(auth()->user());
            }

            $this->dispatchBrowserEvent('reloadPage');
        }
    }

    public function render()
    {
        return view('livewire.auth.in');
    }
}
