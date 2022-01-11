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

    public $editAddress = [
        'id' => '',
        'address' => '',
        'extra' => '',
        'zip' => '',
        'lat' => '',
        'lng' => '',
        'delivery_zone' => '',
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

    public function editAddress($addressId) //TODO
    {
        $this->editAddress = Address::find($addressId)->toArray();
        $this->query = $this->editAddress['address'];
        $this->addressId = $this->editAddress['id'];

        $this->dispatchBrowserEvent('edit-address');
    }

    public function addNewAddress($editAddress)
    {
        $this->editAddress = $editAddress;

        $this->validate([
            'editAddress.address' => 'required|string|max:255',
            'editAddress.extra' => 'string|max:255',
        ]);

        // return toast()
        //   ->warning('Пожалуйста укажите адрес в пределах СПБ КАД')
        //   ->push();

        DB::transaction(function () {
            if ($this->editAddress['id'] && Address::find($this->editAddress['id'])) {
                $editAddress = Address::where('id', $this->editAddress['id'])->first()->update([
                    'address' => $this->editAddress['address'],
                    'lat' => $this->editAddress['lat'],
                    'lng' => $this->editAddress['lng'],
                    'user_id' => auth()->user()->id,
                ]);
            } else {
                $editAddress = Address::Create([
                    'address' => $this->editAddress['address'],
                    'lat' => $this->editAddress['lat'],
                    'lng' => $this->editAddress['lng'],
                    'user_id' => auth()->user()->id,
                ]);
            }

            if (array_key_exists('delivery_zone', $this->editAddress)) {
                $editAddress->delivery_zone = $this->editAddress['delivery_zone'];
            }

            if (array_key_exists('extra', $this->editAddress)) {
                $editAddress->extra = $this->editAddress['extra'];
            }

            if (array_key_exists('zip', $this->editAddress)) {
                $editAddress->zip = $this->editAddress['zip'];
            }

            if ($editAddress->isDirty()) {
                $editAddress->save();
            }

            User::where('id', auth()->user()->id)->update([
                'pref_address' => $editAddress->id,
            ]);
        });

        $this->reset('editAddress');
        $this->dispatchBrowserEvent('close-modal');
        $this->getAddresses();
    }

    public function getAddresses()
    {
        $user = auth()->user();
        $user->load('addresses');

        if ($user->pref_address !== 0) {
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

    public function render()
    {
        return view('livewire.site.user-addresses');
    }
}
