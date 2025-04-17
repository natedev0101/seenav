<?php

namespace App\Livewire;

use App\Models\Vehicle;
use App\Models\VehicleType;
use App\Models\User;
use App\Models\Subdivision;
use App\Models\Rank;
use Livewire\Component;
use Livewire\Attributes\On;

class EditVehicleModal extends Component
{
    public $show = false;
    public $vehicle;
    public $plate_number = '';
    public $vehicle_type_id = '';
    public $veh_id = '';
    public $registration_expiry = '';
    public $warnings = [];
    public $notes = '';
    public $owner_ids = [];
    public $subdivision_id = '';
    public $rank_id = '';

    protected function rules()
    {
        return [
            'plate_number' => ['required', 'string', 'max:20', 'unique:vehicles,plate_number,' . $this->vehicle?->id],
            'vehicle_type_id' => ['required', 'exists:vehicle_types,id'],
            'veh_id' => ['required', 'string', 'max:50', 'unique:vehicles,veh_id,' . $this->vehicle?->id],
            'registration_expiry' => ['required', 'date'],
            'warnings' => ['nullable', 'array'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'owner_ids' => ['required', 'array', 'min:1', 'max:2'],
            'owner_ids.*' => ['exists:users,id'],
            'subdivision_id' => ['required', 'exists:subdivisions,id'],
            'rank_id' => ['required', 'exists:ranks,id'],
        ];
    }

    protected function messages()
    {
        return [
            'plate_number.required' => 'A rendszám megadása kötelező.',
            'plate_number.unique' => 'Ez a rendszám már használatban van.',
            'vehicle_type_id.required' => 'A jármű típusának kiválasztása kötelező.',
            'vehicle_type_id.exists' => 'A kiválasztott járműtípus nem létezik.',
            'veh_id.required' => 'A jármű azonosító megadása kötelező.',
            'veh_id.unique' => 'Ez a jármű azonosító már használatban van.',
            'registration_expiry.required' => 'A forgalmi érvényességének megadása kötelező.',
            'registration_expiry.date' => 'Érvénytelen dátum formátum.',
            'owner_ids.required' => 'Legalább egy tulajdonos kiválasztása kötelező.',
            'owner_ids.min' => 'Legalább egy tulajdonos kiválasztása kötelező.',
            'owner_ids.max' => 'Maximum két tulajdonos választható ki.',
            'subdivision_id.required' => 'Az alosztály kiválasztása kötelező.',
            'subdivision_id.exists' => 'A kiválasztott alosztály nem létezik.',
            'rank_id.required' => 'A rang kiválasztása kötelező.',
            'rank_id.exists' => 'A kiválasztott rang nem létezik.',
        ];
    }

    #[On('openEditVehicleModal')]
    public function open($id)
    {
        if (!auth()->user()->isAdmin && !auth()->user()->is_szuperadmin) {
            $this->dispatch('show-notification', [
                'message' => 'Nincs jogosultságod jármű szerkesztéséhez!',
                'type' => 'error'
            ]);
            return;
        }

        $this->vehicle = Vehicle::with(['owners'])->find($id);
        
        if (!$this->vehicle) {
            $this->dispatch('show-notification', [
                'message' => 'A jármű nem található!',
                'type' => 'error'
            ]);
            return;
        }

        $this->plate_number = $this->vehicle->plate_number;
        $this->vehicle_type_id = $this->vehicle->vehicle_type_id;
        $this->veh_id = $this->vehicle->veh_id;
        $this->registration_expiry = $this->vehicle->registration_expiry->format('Y-m-d');
        $this->warnings = $this->vehicle->warnings ?? [];
        $this->notes = $this->vehicle->notes;
        $this->owner_ids = $this->vehicle->owners->pluck('id')->toArray();
        $this->subdivision_id = $this->vehicle->subdivision_id;
        $this->rank_id = $this->vehicle->rank_id;

        $this->show = true;
    }

    public function save()
    {
        if (!auth()->user()->isAdmin && !auth()->user()->is_szuperadmin) {
            $this->dispatch('show-notification', [
                'message' => 'Nincs jogosultságod jármű szerkesztéséhez!',
                'type' => 'error'
            ]);
            return;
        }

        try {
            $this->validate();

            $this->vehicle->update([
                'plate_number' => strtoupper($this->plate_number),
                'vehicle_type_id' => $this->vehicle_type_id,
                'veh_id' => $this->veh_id,
                'registration_expiry' => $this->registration_expiry,
                'warnings' => $this->warnings,
                'notes' => $this->notes,
                'subdivision_id' => $this->subdivision_id,
                'rank_id' => $this->rank_id,
            ]);

            $this->vehicle->owners()->sync($this->owner_ids);

            $this->dispatch('vehicleUpdated');
            $this->dispatch('show-notification', [
                'message' => 'Jármű sikeresen frissítve!',
                'type' => 'success'
            ]);
            $this->closeModal();
        } catch (\Exception $e) {
            $this->dispatch('show-notification', [
                'message' => 'Hiba történt a jármű frissítése közben!',
                'type' => 'error'
            ]);
        }
    }

    public function closeModal()
    {
        $this->show = false;
        $this->reset();
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.edit-vehicle-modal', [
            'vehicleTypes' => VehicleType::orderBy('name')->get(),
            'users' => User::orderBy('charactername')->get(),
            'subdivisions' => Subdivision::orderBy('name')->get(),
            'ranks' => Rank::orderBy('name')->get(),
        ]);
    }
}
