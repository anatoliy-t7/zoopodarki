<?php
namespace App\Http\Livewire\Site;

use App\Models\Address;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class UserAddresses extends Component
{
    public $address;
    public $addressId;
    public $addresses;
    public $newAddress;

    public function removeAddress($addressId)
    {
        Address::find($addressId)->delete();

        $this->getAddresses();

        $this->dispatchBrowserEvent('toaster', ['message' => 'Адрес удален']);
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
            'newAddress.zip' => 'required',
            'newAddress.address' => 'required',
        ]);

        DB::transaction(function () {
            if ($this->addressId) {
                $this->address = Address::find($this->addressId);

                $this->address->update([
                    'zip' => $this->newAddress['zip'],
                    'address' => $this->newAddress['address'],
                    'extra' => $this->newAddress['extra'],
                    'user_id' => auth()->user()->id,
                ]);
            } else {
                $this->address = Address::create([
                    'zip' => $this->newAddress['zip'],
                    'address' => $this->newAddress['address'],
                    'user_id' => auth()->user()->id,
                ]);

                if (array_key_exists('extra', $this->newAddress)) {
                    $this->address->extra = $this->newAddress['extra'];
                }

                $this->address->save();
            }

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

    public function render()
    {
        return view('livewire.site.user-addresses');
    }
}
