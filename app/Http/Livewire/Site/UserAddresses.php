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
        if (auth()->user()->pref_address !== 0) {
            $this->getAddresses(auth()->user()->pref_address);
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

            $this->getAddresses($addressId);

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
                $deliveryPlace = Address::find('id', $this->deliveryPlace['id']);

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
        $this->getAddresses($deliveryPlace->id);
    }

    public function getAddresses($addressId)
    {
        $user = auth()->user();
        $user->load('addresses');

        if ($user->addresses->firstWhere('id', $addressId)) {
            $this->deliveryPlace = $user->addresses->firstWhere('id', $addressId)->toArray();
        }

        $this->addresses = $user->addresses->toArray();
        $this->emitUp('getAddressesforCheckout');
        $this->dispatchBrowserEvent('close-modal');
    }

    public function setAddress($addressId)
    {
        User::where('id', auth()->user()->id)->update([
            'pref_address' => $addressId,
        ]);

        $this->getAddresses($addressId);
    }

    public function render()
    {
        return view('livewire.site.user-addresses');
    }
}
