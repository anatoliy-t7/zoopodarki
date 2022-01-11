<?php

namespace App\Http\Livewire\Site;

use App\Models\Address;
use App\Models\User;
use App\Traits\Delivery;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Usernotnull\Toast\Concerns\WireToast;

class UserAddresses extends Component
{
    use WireToast;
    use Delivery;

    public $deliveryPlace = [
        'id' => null,
        'address' => null,
        'extra' => null,
        'zip' => null,
        'lat' => null,
        'lng' => null,
        'delivery_zone' => null,
    ];
    public $addresses;

    public function mount()
    {
        $this->getAddresses();
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
        $this->deliveryPlace = Address::find($addressId)->toArray();

        $this->dispatchBrowserEvent('set-address', $this->deliveryPlace);
    }

    public function addNewAddress($deliveryPlace)
    {
        $this->deliveryPlace = $deliveryPlace;
        $this->validate([
            'deliveryPlace.address' => 'required|string|max:255',
        ]);

        DB::transaction(function () {
            if ($this->deliveryPlace['id'] && Address::find($this->deliveryPlace['id'])) {
                $deliveryPlace = Address::where('id', $this->deliveryPlace['id'])->first();

                $deliveryPlace->update([
                    'address' => $this->deliveryPlace['address'],
                    'lat' => $this->deliveryPlace['lat'],
                    'lng' => $this->deliveryPlace['lng'],
                    'user_id' => auth()->user()->id,
                ]);
            } else {
                $deliveryPlace = Address::Create([
                    'address' => $this->deliveryPlace['address'],
                    'lat' => $this->deliveryPlace['lat'],
                    'lng' => $this->deliveryPlace['lng'],
                    'user_id' => auth()->user()->id,
                ]);
            }

            if (array_key_exists('delivery_zone', $this->deliveryPlace)) {
                $deliveryPlace->delivery_zone = $this->deliveryPlace['delivery_zone'];
            }

            if (array_key_exists('extra', $this->deliveryPlace)) {
                $deliveryPlace->extra = $this->deliveryPlace['extra'];
            }

            if (array_key_exists('zip', $this->deliveryPlace)) {
                $deliveryPlace->zip = $this->deliveryPlace['zip'];
            }

            if ($deliveryPlace->isDirty()) {
                $deliveryPlace->save();
            }

            User::where('id', auth()->user()->id)->update([
                'pref_address' => $deliveryPlace->id,
            ]);
        });

        $this->reset('deliveryPlace');
        $this->dispatchBrowserEvent('close-modal');
        $this->getAddresses();
    }

    public function getAddresses()
    {
        $user = auth()->user();
        $user->load('addresses');

        if ($user->pref_address !== 0) {
            $this->addresses = $user->addresses->toArray();
            $this->deliveryPlace = $user->addresses->where('id', $user->pref_address)->first()->toArray();
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

    public function render()
    {
        return view('livewire.site.user-addresses');
    }
}
