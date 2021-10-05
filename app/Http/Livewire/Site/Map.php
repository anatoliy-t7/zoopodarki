<?php
namespace App\Http\Livewire\Site;

use Livewire\Component;

class Map extends Component
{
    public $addresses;
    public $storeId = 0;
    public $checkout = false;

    public function mount()
    {
        $jsonString = file_get_contents(public_path('/assets/json/address.json'));
        $this->addresses = json_decode($jsonString, true);
    }

    public function showStore($storeId)
    {
        $this->storeId = $storeId;

        foreach ($this->addresses as $address) {
            if ($address['id'] === $storeId) {
                $store = $address['adr'];
                $storeGUID = $address['guid'];
                $this->emit('setPickupStore', $store, $storeId, $storeGUID);
            }
        }
    }

    public function render()
    {
        return view('livewire.site.map');
    }
}
