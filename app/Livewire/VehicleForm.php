<?php

namespace App\Livewire;

use App\Models\Vehicle;
use App\Models\User;
use App\Models\Subdivision;
use App\Models\Rank;
use LivewireUI\Modal\ModalComponent;
use Illuminate\Validation\Rule;

class VehicleForm extends ModalComponent
{
    public $vehicle;
    public $plate_number;
    public $type;
    public $veh_id;
    public $registration_expiry;
    public $warnings = [];
    public $notes;
    public $subdivision_id;
    public $rank_id;
    public $owner_ids = [];

    public function mount(?Vehicle $vehicle = null)
    {
        if ($vehicle) {
            $this->vehicle = $vehicle;
            $this->plate_number = $vehicle->plate_number;
            $this->type = $vehicle->type;
            $this->veh_id = $vehicle->veh_id;
            $this->registration_expiry = $vehicle->registration_expiry->format('Y-m-d');
            $this->warnings = $vehicle->warnings ?? [];
            $this->notes = $vehicle->notes;
            $this->subdivision_id = $vehicle->subdivision_id;
            $this->rank_id = $vehicle->rank_id;
            $this->owner_ids = $vehicle->owners->pluck('id')->toArray();
        }
    }

    public function save()
    {
        $validated = $this->validate([
            'plate_number' => ['required', 'string', Rule::unique('vehicles')->ignore($this->vehicle)],
            'type' => ['required', 'string'],
            'veh_id' => ['required', 'string', Rule::unique('vehicles')->ignore($this->vehicle)],
            'registration_expiry' => ['required', 'date'],
            'warnings' => ['nullable', 'array'],
            'notes' => ['nullable', 'string'],
            'subdivision_id' => ['nullable', 'exists:subdivisions,id'],
            'rank_id' => ['nullable', 'exists:ranks,id'],
            'owner_ids' => ['required', 'array', 'min:1', 'max:2'],
            'owner_ids.*' => ['exists:users,id'],
        ]);

        if ($this->vehicle) {
            $this->vehicle->update($validated);
            $this->vehicle->owners()->sync($this->owner_ids);
            $message = 'Jármű sikeresen frissítve!';
        } else {
            $vehicle = Vehicle::create($validated);
            $vehicle->owners()->attach($this->owner_ids);
            $message = 'Jármű sikeresen hozzáadva!';
        }

        $this->dispatch('refreshVehicles');
        $this->closeModalWithEvents(['vehicleUpdated']);
        $this->dispatch('show-notification', [
            'message' => $message,
            'type' => 'success'
        ]);
    }

    public function getSubdivisionsProperty()
    {
        return Subdivision::orderBy('name')->get();
    }

    public function getRanksProperty()
    {
        return Rank::orderBy('name')->get();
    }

    public function getUsersProperty()
    {
        return User::orderBy('name')->get();
    }

    public function render()
    {
        return view('livewire.vehicle-form');
    }
}
