<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class Users extends Component
{
    use RegistersUsers;
    use WithPagination;

    public $search;
    public $sortField     = 'id';
    public $sortDirection = 'asc';
    public $itemsPerPage  = 30;

    public $userId;
    public $name;
    public $email;
    public $phone;
    public $password;
    public $company   = 0;
    public $discount  = 0;
    public $userRoles = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'page'   => ['except' => 1],
        'sortField',
        'sortDirection',
        'itemsPerPage',
    ];

    protected $listeners = ['save'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->search = request()->query('search', $this->search);
    }

    public function sortBy($field)
    {

        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'desc' ? 'asc' : 'desc';
        } else {
            $this->sortDirection = 'desc';
        }
        $this->sortField = $field;

    }

    public function openForm($userId)
    {
        $user            = User::where('id', $userId)->firstOrFail();
        $this->userId    = $user->id;
        $this->name      = $user->name;
        $this->email     = $user->email;
        $this->phone     = $user->phone;
        $this->company   = $user->company;
        $this->discount  = $user->discount;
        $this->userRoles = collect($user->roles)->pluck('name');
    }

    public function save()
    {
        $this->validate([
            'name'    => 'required|string|max:255',
            'company' => 'boolean',
        ]);

        $this->validate([
            'email' => 'required|between:5,64|email|unique:users,email,' . $this->userId,
        ]);

        if ($this->phone) {
            $this->validate([
                'phone' => 'required|digits:10|unique:users,phone,' . $this->userId,
            ]);
        }

        DB::transaction(function () {

            $user = User::updateOrCreate(
                ['id' => $this->userId],
                [
                    'name'     => trim($this->name),
                    'email'    => $this->email,
                    'phone'    => $this->phone,
                    'company'  => $this->company,
                    'discount' => $this->discount,
                ]
            );

            if ($this->password) {
                $user->update([
                    'password' => Hash::make($this->password),
                ]);
            }

            $user->save();

            $user->syncRoles($this->userRoles);

            $this->dispatchBrowserEvent('toaster', ['message' => $this->name . ' сохранен.']);

            $this->closeForm();
            $this->dispatchBrowserEvent('close');

        });
    }

    public function remove($itemId)
    {
        $user = User::with('reviews')->find($itemId);
        if ($user->reviews()->exists()) {

            $this->dispatchBrowserEvent('toaster', ['class' => 'bg-red-500', 'message' => 'У этого пользователя есть отзывы о товарах']);

        } else {

            $user_name = $user->name;
            $user->delete();

            $this->reset();

            $this->dispatchBrowserEvent('toaster', ['class' => 'bg-red-500', 'message' => 'Пользователь "' . $user_name . '" удален.']);

        }
    }

    public function closeForm()
    {
        $this->reset();
    }

    public function render()
    {

        return view('livewire.dashboard.users', [
            'users' => User::when($this->search, function ($query) {
                $query->whereLike(['name', 'id', 'phone', 'email'], $this->search);
            })
                ->with('roles')
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->itemsPerPage),
            'roles' => Role::all(),
        ])
            ->extends('dashboard.app')
            ->section('content');
    }

}
