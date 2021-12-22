<?php

namespace App\Http\Livewire\Site\Pages;

use Artesaos\SEOTools\Facades\SEOMeta;
use Livewire\Component;
use Usernotnull\Toast\Concerns\WireToast;

class ContactPage extends Component
{
    use WireToast;

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

    public function render()
    {
        return view('livewire.site.pages.contact-page')
            ->extends('layouts.app')
            ->section('content');
    }
}
