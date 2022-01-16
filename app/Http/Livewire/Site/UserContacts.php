<?php

namespace App\Http\Livewire\Site;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Usernotnull\Toast\Concerns\WireToast;

class UserContacts extends Component
{
    use WireToast;

    public $editContact = [
        'id' => null,
        'name' => null,
        'phone' => null,
        'email' => null,
    ];
    public $contacts = [];


    public function mount()
    {
        if (auth()->user()->pref_contact !== 0) {
            $this->getContacts(auth()->user()->pref_contact);
        }
    }

    public function removeContact($contactId)
    {
        if (auth()->user()->pref_contact === (int)$contactId) {
            toast()
            ->warning('Для удаления контакта сначала выберите другой контакт для заказа')
            ->push();
        } else {
            Contact::find($contactId)->delete();

            $this->getContacts($contactId);
            $this->reset('editContact');
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

        DB::transaction(function () {
            if ($this->editContact['id'] && Address::find($this->editContact['id'])) {
                $this->editContact = Contact::find($this->editContact['id']);

                $contact->update([
                    'name' => $this->editContact['name'],
                    'phone' => $this->editContact['phone'],
                    'email' => $this->editContact['email'],
                    'user_id' => auth()->user()->id,
                ]);
            } else {
                $contact = Contact::create([
                    'name' => $this->editContact['name'],
                    'phone' => $this->editContact['phone'],
                    'email' => $this->editContact['email'],
                    'user_id' => auth()->user()->id,
                ]);
            }

            User::where('id', auth()->user()->id)->update([
                'pref_contact' => $contact->id,
            ]);

            $this->reset('editContact');
            $this->dispatchBrowserEvent('close-modal');
            $this->dispatchBrowserEvent('close-form');
            $this->getContacts($contact->id);
        });
    }

    public function getContacts($contactId)
    {
        if (auth()->user()) {
            $user = auth()->user();
            $user->load('contacts');

            if ($user->contacts->firstWhere('id', $contactId)) {
                $this->editContact = $user->contacts->firstWhere('id', $contactId)->toArray();
            }

            $this->contacts = $user->contacts->toArray();
            $this->emitUp('getContactsforCheckout');
            $this->dispatchBrowserEvent('close-modal');
        }
    }

    public function setContact($contactId)
    {
        User::where('id', auth()->user()->id)->update([
            'pref_contact' => $contactId,
        ]);
        $this->getContacts($contactId);
    }

    public function resetEditContact()
    {
        $this->reset('editContact');
    }

    public function render()
    {
        return view('livewire.site.user-contacts');
    }
}
