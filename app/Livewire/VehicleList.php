<?php

namespace App\Livewire;

use App\Models\Vehicle;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class VehicleList extends Component
{
    use WithPagination;

    public $search = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    #[On('vehicleCreated')]
    #[On('vehicleUpdated')]
    public function refreshList()
    {
        // A lista automatikusan frissül
    }

    public function delete($id)
    {
        if (!auth()->user()->isAdmin && !auth()->user()->is_szuperadmin) {
            $this->dispatch('show-notification', [
                'message' => 'Nincs jogosultságod jármű törléséhez!',
                'type' => 'error'
            ]);
            return;
        }

        try {
            $vehicle = Vehicle::find($id);
            if (!$vehicle) {
                $this->dispatch('show-notification', [
                    'message' => 'A jármű nem található!',
                    'type' => 'error'
                ]);
                return;
            }

            $vehicle->owners()->detach();
            $vehicle->delete();

            $this->dispatch('show-notification', [
                'message' => 'Jármű sikeresen törölve!',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-notification', [
                'message' => 'Hiba történt a jármű törlése közben!',
                'type' => 'error'
            ]);
        }
    }

    public function render()
    {
        $vehicles = Vehicle::with(['vehicleType', 'owners', 'subdivision', 'rank'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('plate_number', 'like', '%' . $this->search . '%')
                      ->orWhere('veh_id', 'like', '%' . $this->search . '%')
                      ->orWhereHas('vehicleType', function ($q) {
                          $q->where('name', 'like', '%' . $this->search . '%');
                      })
                      ->orWhereHas('owners', function ($q) {
                          $q->where('charactername', 'like', '%' . $this->search . '%');
                      })
                      ->orWhereHas('subdivision', function ($q) {
                          $q->where('name', 'like', '%' . $this->search . '%');
                      })
                      ->orWhereHas('rank', function ($q) {
                          $q->where('name', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->orderBy('plate_number')
            ->paginate(15);

        return view('livewire.vehicle-list', [
            'vehicles' => $vehicles
        ]);
    }
}
