<?php

namespace App\Http\Livewire\Dashboard;

use App\Mail\UserRegistration;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Usernotnull\Toast\Concerns\WireToast;

class Users extends Component
{
    use WireToast;
    use RegistersUsers;
    use WithPagination;

    public $search;
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $itemsPerPage = 30;

    public $userId;
    public $name;
    public $email;
    public $phone;
    public $password;
    public $discount = 0;
    public $userRoles = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
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
        $this->resetPage();
    }

    public function openForm($userId)
    {
        $user = User::where('id', $userId)->firstOrFail();
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->discount = $user->discount;
        $this->userRoles = collect($user->roles)->pluck('name');
    }

    public function save()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'unique:users,email,' . $this->userId],
            'phone' => ['required', 'digits:10', 'unique:users,phone,' . $this->userId],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        if (!$this->password) {
            $this->password = Str::random(10);
        }

        DB::transaction(function () {
            $user = User::updateOrCreate(
                ['id' => $this->userId],
                [
                    'name' => trim($this->name),
                    'email' => $this->email,
                    'phone' => $this->phone,
                    'discount' => $this->discount,
                    'password' => Hash::make($this->password),
                ]
            );

            $user->syncRoles($this->userRoles);

            toast()
                ->success($user->name . ' сохранен')
                ->push();
            // TODO on prod on
            // \Mail::to($user->email)
            // ->send(new UserRegistration([
            //     'email' => $user->email,
            //     'password' => $this->password,
            // ]));

            $this->closeForm();
            $this->dispatchBrowserEvent('close');
        });
    }

    public function remove($itemId)
    {
        $user = User::with('reviews')->find($itemId);
        if ($user->reviews()->exists()) {
            toast()
                ->warning('У этого пользователя есть отзывы о товарах')
                ->push();
        } else {
            $user_name = $user->name;
            $user->delete();

            $this->reset();

            toast()
                ->success('Пользователь "' . $user_name . '" удален.')
                ->push();
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
