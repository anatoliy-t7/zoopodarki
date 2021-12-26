<?php

namespace App\Http\Livewire\Site\Pages;

use App\Mail\ContactForm;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Lukeraymonddowning\Honey\Traits\WithRecaptcha;
use Usernotnull\Toast\Concerns\WireToast;

class ContactPage extends Component
{
    use WithRecaptcha;
    use WireToast;
    public $captcha = 0;
    public $data;

    protected $rules = [
        'data.content' => 'required|string|min:10',
        'data.name' => 'required|string',
        'data.email' => 'required|email',
        'data.phone' => 'digits:10',
    ];

    public function mount()
    {
        $this->setSeo();
    }

    public function setSeo()
    {
        //SEO TITLE
        // Адреса и телефоны зоомагазинов на карте Спб *галочка* Невский район, Калининский район. Зооподарки у метро пр. Большевиков, метро Ладожская и метро Гражданский проспект. доставка по городу по Спб и самовывоз из магазинов рядом с вами!

        $metaTitle = 'Адреса и телефоны зоомагазинов на карте Спб ❗ Невский район, Калининский район. Зооподарки у метро пр. Большевиков, метро Ладожская и метро Гражданский проспект. доставка по городу по Спб и самовывоз из магазинов рядом с вами!';

        // SEO description
        // Адреса и телефоны зоомагазинов на карте Спб *галочка* Невский район, Калининский район. Зооподарки у метро пр. Большевиков, метро Ладожская и метро Гражданский проспект. доставка по городу по Спб и самовывоз из магазинов рядом с вами! ТЦ Оккервиль, ТЦ Ладога.

        $metaDescription = 'Адреса и телефоны зоомагазинов на карте Спб ❗ Невский район, Калининский район. Зооподарки у метро пр. Большевиков, метро Ладожская и метро Гражданский проспект. доставка по городу по Спб и самовывоз из магазинов рядом с вами! ТЦ Оккервиль, ТЦ Ладога';

        SEOMeta::setTitle($metaTitle)->setDescription($metaDescription);
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function submit()
    {
        try {
            Mail::to(config('constants.manager_mail'))->send(new ContactForm($this->data));

            toast()
                    ->success('Спасибо за ваше сообщение. Мы ответим вам в течении 48 часов.')
                    ->push();
        } catch (\Throwable $th) {
            \Log::error($th);

            toast()
                    ->danger('Сообщение не отправлено, попробуйте перегрузить страницу и отправить еще раз.')
                    ->push();
        }
    }

    public function checkCaptcha()
    {
        $this->validate();
        if ($this->recaptchaPasses()) {
            $this->submit();
        } else {
            toast()
                ->info('Google считает вас ботом, обновите страницу и попробуйте еще раз.')
                ->push();
        }
    }

    public function render()
    {
        return view('livewire.site.pages.contact-page')
            ->extends('layouts.app')
            ->section('content');
    }
}
