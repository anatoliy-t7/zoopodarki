<?php

namespace App\Http\Livewire\Site;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Usernotnull\Toast\Concerns\WireToast;

class UserContacts extends Component
{
    use WireToast;

    public $contact = [];
    public $editContact = [];
    public $contacts = [];

    public function mount()
    {
        $this->getContacts();
    }

    public function removeContact($contactId)
    {
        if (auth()->user()->pref_contact === (int)$contactId) {
            toast()
            ->warning('Для удаления контакта сначала выберите другой контакт для заказа')
            ->push();
        } else {
            Contact::find($contactId)->delete();

            $this->getContacts();

            toast()
            ->success('Контакт удален')
            ->push();
        }
    }

    public function editContact($contactId)
    {
        $this->editContact = Contact::find($contactId)->toArray();
        $this->dispatchBrowserEvent('edit-contact');
    }

    public function addNewContact()
    {
        $this->validate([
            'editContact.name' => 'required',
            'editContact.phone' => 'required|numeric|digits:10',
            'editContact.email' => 'nullable|email',
        ]);

        if (!Arr::has($this->editContact, 'email')) {
            $this->editContact['email'] = null;
        }

        DB::transaction(function () {
            if ($this->contactId) {
                $this->contact = Contact::find($this->contactId);

                $this->contact->update([
                    'name' => $this->editContact['name'],
                    'phone' => $this->editContact['phone'],
                    'email' => $this->editContact['email'],
                    'user_id' => auth()->user()->id,
                ]);
            } else {
                $this->contact = Contact::create([
                    'name' => $this->editContact['name'],
                    'phone' => $this->editContact['phone'],
                    'email' => $this->editContact['email'],
                    'user_id' => auth()->user()->id,
                ]);
            }

            User::where('id', auth()->user()->id)->update([
                'pref_contact' => $this->contact->id,
            ]);

            $this->reset('editContact');
            $this->dispatchBrowserEvent('close-modal');
            $this->dispatchBrowserEvent('close-form');
            $this->getContacts();
        });
    }

    public function getContacts()
    {
        if (auth()->user()) {
            $user = auth()->user();
            $user->load('contacts');

            if ($user->pref_contact !== 0) {
                $this->contact = $user->contacts->where('id', $user->pref_contact)->first()->toArray();
                $this->contacts = $user->contacts->toArray();
            }

            $this->emitUp('getContactsforCheckout');
            $this->dispatchBrowserEvent('close-modal');
        }
    }

    public function setContact($contactId)
    {
        User::where('id', auth()->user()->id)->update([
            'pref_contact' => $contactId,
        ]);
        $this->getContacts();
    }

    public function render()
    {
        return view('livewire.site.user-contacts');
    }
}
