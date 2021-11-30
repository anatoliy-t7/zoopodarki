<?php

namespace App\Http\Livewire\Dashboard;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Usernotnull\Toast\Concerns\WireToast;

class Permissions extends Component
{
    use WireToast;

    public $name;
    public $permissionId;

    protected $listeners = ['save'];

    public function openForm($permissionId)
    {
        $permission = Permission::where('id', $permissionId)->firstOrFail();
        $this->name = $permission->name;
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

            toast()
                ->success($permission->name . ' сохранены.')
                ->push();

            $this->closeForm();
        });
    }

    public function remove($permissionId)
    {
        $permission = Permission::find($permissionId);

        $permission_name = $permission->name;
        $permission->delete();

        $this->resetFields();

        toast()
            ->success('Права "' . $permission_name . '" удалены.')
            ->push();
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
