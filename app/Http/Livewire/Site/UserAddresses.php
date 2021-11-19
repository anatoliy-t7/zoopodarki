<?php
namespace App\Http\Livewire\Site;

use App\Models\Address;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Usernotnull\Toast\Concerns\WireToast;

class UserAddresses extends Component
{
    use WireToast;

    public $address;
    public $addressId;
    public $addresses;
    public $newAddress = [
        'address' => '',
        'extra' => ''
    ];

    public function removeAddress($addressId)
    {
        Address::find($addressId)->delete();

        $this->getAddresses();

        toast()
            ->success('Адрес удален')
            ->push();
    }

    public function editAddress($addressId)
    {
        $this->newAddress = Address::find($addressId)->toArray();

        $this->addressId = $this->newAddress['id'];

        $this->dispatchBrowserEvent('edit-address');
    }

    public function addNewAddress()
    {
        $this->validate([
            'newAddress.address' => 'required',
        ]);


        DB::transaction(function () {
            if ($this->addressId) {
                $this->address = Address::find($this->addressId);

                $this->address->update([
                 //   'zip' => $this->newAddress['zip'],
                    'address' => $this->newAddress['address'],
                    'extra' => $this->newAddress['extra'],
                    'user_id' => auth()->user()->id,
                ]);
            } else {
                $this->address = Address::create([
                  //  'zip' => $this->newAddress['zip'],
                    'address' => $this->newAddress['address'],
                    'user_id' => auth()->user()->id,
                ]);

                if (array_key_exists('extra', $this->newAddress)) {
                    $this->address->extra = $this->newAddress['extra'];
                }

                $this->address->save();
            }

            $addressData = $this->getCustomerLocation($this->address['address']);

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

            $this->reset('newAddress');
            $this->dispatchBrowserEvent('close-modal');
            $this->dispatchBrowserEvent('close-form');
            $this->getAddresses();
        });
    }

    public function getAddresses()
    {
        if (auth()->user()) {
            $user = auth()->user();
            $user->load('addresses');

            if ($user->pref_address !== 0) {
                $this->address = $user->addresses->where('id', $user->pref_address)->first()->toArray();
                $this->addresses = $user->addresses;
            }

            $this->emitUp('getAddressFromComponent', $this->address);
        }
    }

    public function setAddress($addressId)
    {
        User::where('id', auth()->user()->id)->update([
            'pref_address' => $addressId,
        ]);
        $this->getAddresses();
        $this->dispatchBrowserEvent('close-modal');
        $this->dispatchBrowserEvent('close-form');
    }

    public function getCustomerLocation(String $address = '')
    {

        $defaultCity = '%2C+Санкт-Петербург%2C+Россия';

        $response = Http::retry(3, 100)->get(
            'https://geocode.search.hereapi.com/v1/geocode?q='
            . $address
            . $defaultCity
            . '&apiKey='.config('constants.here_com_token')
        );

        if ($response->successful()) {
           // dd($response->json());
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
        return view('livewire.site.user-addresses');
    }
}
