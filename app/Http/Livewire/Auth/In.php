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
use Usernotnull\Toast\Concerns\WireToast;

class In extends Component
{
    use WireToast;

    public $phone;
    public $enteredOtp;
    public $email;
    public $password;
    protected $user;
    protected $otp;
    public $token;
    public $subscribed = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];
    protected $listeners = ['createOtp', 'checkUser'];

    public function createOtp($phone = null)
    {
        if ($phone !== null) {
            $this->phone = (int)$phone;

             $this->validate([
                 'phone' => 'required|digits:10',
             ]);
        }

        $functionOtp = Otp::generate($this->phone);

        if ($functionOtp->status === false) {
            toast()
                ->danger('Вы превысили лимит СМС, попробуйте через 15 минут')
                ->push();
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
            toast()
                ->info('Вышел срок OTP')
                ->push();

            return false;
        }

        $verify = Otp::validate($this->phone, $this->enteredOtp);

        if ($verify->status === false) {
            if ($verify->message == 'OTP does not match') {
                toast()
                    ->info('OTP не соответствует')
                    ->push();
            }

            if ($verify->message == 'Reached the maximum allowed attempts') {
                toast()
                    ->info('Достигнуто максимально допустимое количество попыток, попробуйте через 15 минут')
                    ->push();
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
            'subscribed' => $this->subscribed,
        ]);
    }

    public function sendSMS()
    {
        try {
            (new AnonymousNotifiable())
                ->route('smscru', '+7' . $this->phone)
                ->notify(new SendOTP($this->token));

            toast()
                ->success('OTP отправлен')
                ->push();
        } catch (\Throwable$th) {
            \Log::error($th);

            toast()
                ->warning('OTP не отправлен')
                ->push();
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
