<?php

namespace App\Http\Livewire\Dashboard;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Roles extends Component
{

    public $roleId;
    public $name;
    public $rolePermissions = [];

    protected $listeners = ['save'];

    public function openForm($roleId)
    {
        $role = Role::where('id', $roleId)->with('permissions')->firstOrFail();
        $this->roleId = $role->id;
        $this->name = $role->name;
        $this->rolePermissions = $role->getAllPermissions()->pluck('name');
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|unique:roles,name,' . $this->roleId,
            'rolePermissions' => 'required',
        ]);

        DB::transaction(function () {

            $role = Role::updateOrCreate(
                ['id' => $this->roleId],
                [
                    'name' => trim($this->name),
                ]
            );

            $role->syncPermissions($this->rolePermissions);

            $this->dispatchBrowserEvent('toast', ['message' => $this->name . ' сохранена.']);

            $this->closeForm();

            $this->dispatchBrowserEvent('close');

        });
    }

    public function closeForm()
    {
        $this->reset();
    }

    public function remove($id)
    {
        Role::find($id)->delete();

        session()->flash('message', 'Role deleted');

        $this->dispatchBrowserEvent('toast', ['message' => 'Роль удалена']);

    }

    public function render()
    {
        return view('livewire.dashboard.roles', [
            'roles' => Role::with('permissions')->get(),
            'permissions' => Permission::all(),
        ])
            ->extends('dashboard.app')
            ->section('content');
    }
}
