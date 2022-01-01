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

    public $contact;
    public $contactId;
    public $newContact = [];
    public $contacts;

    public function removeContact($contactId)
    {
        $user = auth()->user();
        $user->load('contacts');

        if ($user->pref_contact === $contactId) {
            toast()
            ->success('Для удаления контакта сначала выберите другой контакт для заказа')
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
        $this->newContact = Contact::find($contactId)->toArray();
        $this->contactId = $this->newContact['id'];
        $this->dispatchBrowserEvent('edit-contact');
    }

    public function addNewContact()
    {
        $this->validate([
            'newContact.name' => 'required',
            'newContact.phone' => 'required|numeric|digits:10',
            'newContact.email' => 'nullable|email',
        ]);

        if (!Arr::has($this->newContact, 'email')) {
            $this->newContact['email'] = null;
        }

        DB::transaction(function () {
            if ($this->contactId) {
                $this->contact = Contact::find($this->contactId);

                $this->contact->update([
                    'name' => $this->newContact['name'],
                    'phone' => $this->newContact['phone'],
                    'email' => $this->newContact['email'],
                    'user_id' => auth()->user()->id,
                ]);
            } else {
                $this->contact = Contact::create([
                    'name' => $this->newContact['name'],
                    'phone' => $this->newContact['phone'],
                    'email' => $this->newContact['email'],
                    'user_id' => auth()->user()->id,
                ]);
            }

            User::where('id', auth()->user()->id)->update([
                'pref_contact' => $this->contact->id,
            ]);

            $this->reset('newContact');
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
                $this->contacts = $user->contacts;
            }

            $this->emitUp('getContactsforCheckout', $this->contact);
            // $this->dispatchBrowserEvent('close-modal');
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
