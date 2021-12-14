<?php

namespace App\Http\Livewire\Site\Account;

use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Usernotnull\Toast\Concerns\WireToast;

class ProfilePage extends Component
{
    use WireToast;

    public $user = [
        'id' => '',
        'name' => '',
        'phone' => '',
        'email' => '',
        'password' => '',
        'discount' => '',
    ];

    public function mount()
    {
        $user = auth()->user();

        $this->user = [
            'id' => $user->id,
            'name' => $user->name,
            'phone' => $user->phone,
            'email' => $user->email,
            'password' => '',
            'discount' => $user->discount,
        ];
    }

    public function save()
    {
        $this->validate([
            'user.name' => ['required', 'string', 'max:50'],
            'user.email' => ['required', 'unique:users,email,' . $this->user['id']],
            'user.phone' => ['nullable', 'digits:10', 'unique:users,phone,' . $this->user['id']],
        ]);


        $user = auth()->user();

        $user->update([
            'name' => $this->user['name'],
            'email' => $this->user['email'],
            'phone' => $this->user['phone'],
        ]);


        if (!empty($this->user['password'])) {
            $this->validate([
                'user.password' => ['required', 'string', 'min:8'],
            ]);

            $user->update([
                'password' => Hash::make($this->user['password']),
            ]);
        }

        toast()
            ->success('Данные обновлены.')
            ->push();
    }

    public function render()
    {
        return view('livewire.site.account.profile-page')
            ->extends('layouts.app')
            ->section('content');
    }
}
