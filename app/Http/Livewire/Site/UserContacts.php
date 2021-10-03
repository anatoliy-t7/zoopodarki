<?php
namespace App\Http\Livewire\Site;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Arr;

class UserContacts extends Component
{
    public $contact;
    public $contactId;
    public $newContact = [];
    public $contacts;

    public function removeContact($contactId)
    {
        Contact::find($contactId)->delete();

        $this->getContacts();

        $this->dispatchBrowserEvent('toaster', ['message' => 'Контакт удален']);
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
            $user->load('addresses');

            if ($user->pref_contact !== 0) {
                $this->contact = $user->contacts->where('id', $user->pref_contact)->first()->toArray();
                $this->contacts = $user->contacts;
            }

            $this->emitUp('getContactFromComponent', $this->contact);
        }
    }

    public function setContact($contactId)
    {
        User::where('id', auth()->user()->id)->update([
            'pref_contact' => $contactId,
        ]);
        $this->getContacts();
        $this->dispatchBrowserEvent('close-modal');
        $this->dispatchBrowserEvent('close-form');
    }

    public function render()
    {
        return view('livewire.site.user-contacts');
    }
}
