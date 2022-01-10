<?php

namespace App\Http\Livewire\Site;

use App\Models\Address;
use App\Models\User;
use App\Traits\Delivery;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Usernotnull\Toast\Concerns\WireToast;

class UserAddressMap extends Component
{
    use WireToast;
    use Delivery;

    public $query = '';
    public array $sugestionAddresses = [];
    public int $highlightIndex = 0;
    public bool $showDropdown = true;

    public $address = [];
    public $addressId;
    public $addresses;
    public $editAddress = [
        'address' => '',
        'building' => '',
        'apartment' => '',
        'extra' => '',
    ];

    public function mount()
    {
        $this->getAddresses();
    }

    public function resetSearch(...$properties)
    {
        $this->reset('sugestionAddresses', 'highlightIndex', 'query', 'showDropdown', 'editAddress');
    }

    public function hideDropdown()
    {
        $this->showDropdown = false;
    }

    public function incrementHighlight()
    {
        if ($this->highlightIndex === count($this->sugestionAddresses) - 1) {
            $this->highlightIndex = 0;

            return;
        }

        $this->highlightIndex++;
    }

    public function decrementHighlight()
    {
        if ($this->highlightIndex === 0) {
            $this->highlightIndex = count($this->sugestionAddresses) - 1;

            return;
        }

        $this->highlightIndex--;
    }

    public function selectAddress($id = null)
    {
        $id = $id ?: $this->highlightIndex;

        if ($this->sugestionAddresses) {
            $this->showDropdown = true;
            $this->query = $this->sugestionAddresses[$id];
            $this->editAddress['address'] = $this->sugestionAddresses[$id];
        }
    }

    public function updatedQuery()
    {
        $defaultCity = 'Санкт-Петербург+';
        $response = Http::get(
            'https://autocomplete.search.hereapi.com/v1/autocomplete?q='
            . $defaultCity
            . $this->query
            . '&at=59.934261%2C30.334933'
            . '&limit=20'
            . '&lang=ru-RU'
            . '&in=countryCode%3ARUS'
            . '&apiKey=' . config('constants.here_com_token')
        );

        if ($response->successful()) {
            $this->sugestionAddresses = $response->collect()->flatten(1)->pluck('address.street')->toArray();
        }
    }

    public function removeAddress($addressId)
    {
        if (auth()->user()->pref_address === (int)$addressId) {
            toast()
            ->warning('Для удаления адреса сначала выберите другой адрес для заказа')
            ->push();
        } else {
            Address::find($addressId)->delete();

            $this->getAddresses();

            toast()
            ->success('Адрес удален')
            ->push();
        }
    }

    public function editAddress($addressId)
    {
        $this->editAddress = Address::find($addressId)->toArray();
        $this->query = $this->editAddress['address'];
        $this->addressId = $this->editAddress['id'];

        $this->dispatchBrowserEvent('edit-address');
    }

    public function addNewAddress()
    {
        $this->validate([
            'editAddress.address' => 'required|string|max:255',
            'editAddress.building' => 'required|string|max:255',
        ]);

        $addressData = $this->getCustomerLocation($this->address['address'] . ', ' . $this->address['building']);

        if ($addressData !== false) {
            if ($this->checkIfAddressInCad($addressData['lat'], $addressData['lng']) == false) {
                return toast()
                    ->warning('Пожалуйста укажите адрес в пределах СПБ КАД')
                    ->push();
            } else {
                DB::transaction(function () use ($addressData) {
                    if ($this->addressId) {
                        $this->address = Address::find($this->addressId);

                        $this->address->update([
                            'address' => $this->editAddress['address'],
                            'building' => $this->editAddress['building'],
                            'apartment' => $this->editAddress['apartment'],
                            'extra' => $this->editAddress['extra'],
                            'user_id' => auth()->user()->id,
                        ]);
                    } else {
                        $this->address = Address::create([
                            'address' => $this->editAddress['address'],
                            'building' => $this->editAddress['building'],
                            'apartment' => $this->editAddress['apartment'],
                            'extra' => $this->editAddress['extra'],
                            'user_id' => auth()->user()->id,
                        ]);

                        $this->address->save();
                    }



                    if ($addressData === false) {
                        $this->address->zip = null;
                        $this->address->lat = null;
                        $this->address->lng = null;
                    } else {
                        $this->address->zip = $addressData['zip'];
                        $this->address->lat = $addressData['lat'];
                        $this->address->lng = $addressData['lng'];
                    }

                    $this->address->save();

                    User::where('id', auth()->user()->id)->update([
                        'pref_address' => $this->address->id,
                    ]);
                });

                $this->reset('editAddress');
                $this->dispatchBrowserEvent('close-modal');
                $this->getAddresses();
            }
        } else {
            return toast()
                    ->warning('Пожалуйста укажите адрес в пределах СПБ КАД')
                    ->push();
        }
    }

    public function getAddresses()
    {
        $user = auth()->user();
        $user->load('addresses');

        if ($user->pref_address !== 0) {
            $this->address = $user->addresses->where('id', $user->pref_address)->first()->toArray();
            $this->addresses = $user->addresses->toArray();

            $this->emitUp('getAddressesforCheckout');
            $this->dispatchBrowserEvent('close-modal');
        }
    }

    public function setAddress($addressId)
    {
        User::where('id', auth()->user()->id)->update([
            'pref_address' => $addressId,
        ]);

        $this->getAddresses();
    }

    public function getCustomerLocation(string $address = '')
    {
        $defaultCity = 'Санкт-Петербург, ';
        $response = Http::retry(3, 100)->get(
            'https://geocode-maps.yandex.ru/1.x/?'
            . '&geocode=' . $defaultCity . $address
            . '&format=json'
            . '&results=1'
            . '&language=ru'
            . '&apikey=' . config('constants.yandex_map_key')
        );


        if ($response->successful()) {
            dd($response->json());

            return [
                'zip' => $response->json()['items'][0]['address']['postalCode'],
                'lat' => $response->json()['items'][0]['position']['lat'],
                'lng' => $response->json()['items'][0]['position']['lng'],
            ];
        } elseif ($response->failed()) {
            \Log::error('getCustomerLocation the status code is >= 400');

            return false;
        } elseif ($response->clientError()) {
            \Log::error('getCustomerLocation the response has a 400 level status code');

            return false;
        } elseif ($response->serverError()) {
            \Log::error('getCustomerLocation the response has a 500 level status code');

            return false;
        }
    }

    public function render()
    {
        return view('livewire.site.user-address-map');
    }
}