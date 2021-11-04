<?php

namespace App\Http\Livewire\Dashboard;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

class Permissions extends Component
{
    public $name;
    public $permissionId;

    protected $listeners = ['save'];

    public function openForm($permissionId)
    {
        $permission         = Permission::where('id', $permissionId)->firstOrFail();
        $this->name         = $permission->name;
        $this->permissionId = $permission->id;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|unique:permissions,name,' . $this->permissionId,
        ]);

        DB::transaction(function () {

            $permission = Permission::updateOrCreate(
                ['id' => $this->permissionId],
                [
                    'name' => $this->name,
                ]
            );

            $this->dispatchBrowserEvent('toast', ['text' => $permission->name . ' сохранены.']);

            $this->closeForm();
        });
    }

    public function remove($permissionId)
    {
        $permission = Permission::find($permissionId);

        $permission_name = $permission->name;
        $permission->delete();

        $this->resetFields();

        $this->dispatchBrowserEvent('toast', ['type' => 'error', 'text' => 'Права "' . $permission_name . '" удалены.']);

    }

    public function closeForm()
    {
        $this->resetFields();
        $this->dispatchBrowserEvent('close');
    }

    public function resetFields()
    {
        $this->reset();
    }

    public function render()
    {
        return view('livewire.dashboard.permissions', [
            'permissions' => Permission::orderBy('name', 'ASC')->get(),
        ])
            ->extends('dashboard.app')
            ->section('content');
    }

}
