<?php

namespace App\Livewire;

use App\Models\VehicleType;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class VehicleTypeList extends Component
{
    use WithPagination;

    public $name = '';
    public $editingId = null;
    public $editingName = '';
    public $search = '';

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:50', 'unique:vehicle_types,name'],
            'editingName' => ['required', 'string', 'min:2', 'max:50', 'unique:vehicle_types,name,' . $this->editingId],
        ];
    }

    protected function messages()
    {
        return [
            'name.required' => 'A járműtípus neve kötelező.',
            'name.min' => 'A járműtípus neve legalább 2 karakter legyen.',
            'name.max' => 'A járműtípus neve maximum 50 karakter lehet.',
            'name.unique' => 'Ez a járműtípus már létezik.',
            'editingName.required' => 'A járműtípus neve kötelező.',
            'editingName.min' => 'A járműtípus neve legalább 2 karakter legyen.',
            'editingName.max' => 'A járműtípus neve maximum 50 karakter lehet.',
            'editingName.unique' => 'Ez a járműtípus már létezik.',
        ];
    }

    public function mount()
    {
        $this->name = '';
        $this->editingId = null;
        $this->editingName = '';
        $this->search = '';
    }

    public function save()
    {
        if (!auth()->user()->isAdmin && !auth()->user()->is_szuperadmin) {
            $this->dispatch('show-notification', [
                'message' => 'Nincs jogosultságod járműtípus létrehozásához!',
                'type' => 'error'
            ]);
            return;
        }

        try {
            $this->name = trim($this->name);
            $this->validate(['name' => $this->rules()['name']]);

            VehicleType::create([
                'name' => $this->name
            ]);

            $this->name = '';
            $this->dispatch('show-notification', [
                'message' => 'Járműtípus sikeresen létrehozva!',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-notification', [
                'message' => 'Hiba történt a járműtípus létrehozása közben!',
                'type' => 'error'
            ]);
        }
    }

    public function startEditing($id)
    {
        if (!auth()->user()->isAdmin && !auth()->user()->is_szuperadmin) {
            $this->dispatch('show-notification', [
                'message' => 'Nincs jogosultságod járműtípus szerkesztéséhez!',
                'type' => 'error'
            ]);
            return;
        }

        $this->editingId = $id;
        $this->editingName = VehicleType::find($id)->name;
    }

    public function updateType()
    {
        if (!auth()->user()->isAdmin && !auth()->user()->is_szuperadmin) {
            $this->dispatch('show-notification', [
                'message' => 'Nincs jogosultságod járműtípus szerkesztéséhez!',
                'type' => 'error'
            ]);
            return;
        }

        try {
            $this->editingName = trim($this->editingName);
            $this->validate(['editingName' => $this->rules()['editingName']]);

            VehicleType::find($this->editingId)->update([
                'name' => $this->editingName
            ]);

            $this->editingId = null;
            $this->editingName = '';
            $this->dispatch('show-notification', [
                'message' => 'Járműtípus sikeresen frissítve!',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-notification', [
                'message' => 'Hiba történt a járműtípus frissítése közben!',
                'type' => 'error'
            ]);
        }
    }

    public function delete($id)
    {
        if (!auth()->user()->isAdmin && !auth()->user()->is_szuperadmin) {
            $this->dispatch('show-notification', [
                'message' => 'Nincs jogosultságod járműtípus törléséhez!',
                'type' => 'error'
            ]);
            return;
        }

        try {
            $type = VehicleType::find($id);
            
            if ($type->vehicles()->exists()) {
                $this->dispatch('show-notification', [
                    'message' => 'Nem törölhető a járműtípus, mert már van hozzá rendelve jármű!',
                    'type' => 'error'
                ]);
                return;
            }

            $type->delete();
            $this->dispatch('show-notification', [
                'message' => 'Járműtípus sikeresen törölve!',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-notification', [
                'message' => 'Hiba történt a járműtípus törlése közben!',
                'type' => 'error'
            ]);
        }
    }

    public function render()
    {
        $query = VehicleType::query();
        
        if ($this->search !== '') {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        return view('livewire.vehicle-type-list', [
            'types' => $query->orderBy('name')->paginate(10)
        ]);
    }
}
