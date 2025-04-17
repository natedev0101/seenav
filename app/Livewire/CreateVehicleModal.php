<?php

namespace App\Livewire;

use App\Models\Vehicle;
use App\Models\VehicleType;
use App\Models\User;
use App\Models\Subdivision;
use App\Models\Rank;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CreateVehicleModal extends Component
{
    public $show = false;
    public $plate_number = '';
    public $vehicle_type_id;
    public $veh_id = '';
    public $selectedOwners = [];
    public $registration_expiry;
    public $subdivision_id;
    public $rank_id;
    public $users;

    protected $listeners = ['openModal' => 'openModal'];

    public function mount()
    {
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->plate_number = '';
        $this->vehicle_type_id = null;
        $this->veh_id = '';
        $this->selectedOwners = [];
        $this->registration_expiry = null;
        $this->subdivision_id = null;
        $this->rank_id = null;
        $this->refreshUsers();
    }

    protected function refreshUsers()
    {
        $this->users = User::select('id', 'charactername')
            ->orderBy('charactername')
            ->get();
    }

    public function selectFirstOwner($name)
    {
        try {
            $user = User::where('charactername', $name)->first();
            
            if (!$user) {
                throw new \Exception('A kiválasztott felhasználó nem található.');
            }

            $this->selectedOwners[0] = $user->charactername;
            
            $this->dispatch('show-notification', [
                'message' => '1. Tulajdonos sikeresen kiválasztva!',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-notification', [
                'message' => $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function selectSecondOwner($name)
    {
        try {
            $user = User::where('charactername', $name)->first();
            
            if (!$user) {
                throw new \Exception('A kiválasztott felhasználó nem található.');
            }

            if (isset($this->selectedOwners[0]) && $this->selectedOwners[0] === $user->charactername) {
                throw new \Exception('Ez a felhasználó már ki van választva első tulajdonosként.');
            }

            $this->selectedOwners[1] = $user->charactername;
            
            $this->dispatch('show-notification', [
                'message' => '2. Tulajdonos sikeresen kiválasztva!',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-notification', [
                'message' => $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function removeFirstOwner()
    {
        unset($this->selectedOwners[0]);
        $this->selectedOwners = array_values($this->selectedOwners);
    }

    public function removeSecondOwner()
    {
        unset($this->selectedOwners[1]);
    }

    public function save()
    {
        try {
            if (!auth()->user()->isAdmin && !auth()->user()->is_szuperadmin) {
                throw new \Exception('Nincs jogosultságod jármű létrehozásához!');
            }

            $this->plate_number = strtoupper($this->plate_number);
            
            // Validáció
            $validatedData = $this->validate([
                'plate_number' => ['required', 'string', 'regex:/^[A-Z0-9-]+$/', 'unique:vehicles,plate_number'],
                'vehicle_type_id' => ['required', 'exists:vehicle_types,id'],
                'veh_id' => ['required', 'string', 'unique:vehicles,veh_id'],
                'selectedOwners' => ['required', 'array', 'min:1'],
                'selectedOwners.0' => ['required', 'string'],
                'selectedOwners.1' => ['nullable', 'string'],
                'registration_expiry' => ['nullable', 'date'],
                'subdivision_id' => ['nullable', 'exists:subdivisions,id'],
                'rank_id' => ['nullable', 'exists:ranks,id'],
            ], [
                'plate_number.required' => 'A rendszám megadása kötelező.',
                'plate_number.regex' => 'A rendszám csak nagybetűket és számokat tartalmazhat.',
                'plate_number.unique' => 'Ez a rendszám már foglalt.',
                'vehicle_type_id.required' => 'A jármű típusának kiválasztása kötelező.',
                'veh_id.required' => 'A jármű azonosító megadása kötelező.',
                'veh_id.unique' => 'Ez a jármű azonosító már foglalt.',
                'selectedOwners.required' => 'Legalább egy tulajdonos kiválasztása kötelező.',
                'selectedOwners.min' => 'Legalább egy tulajdonost ki kell választani.',
                'selectedOwners.0.required' => 'Az első tulajdonos kiválasztása kötelező.',
            ]);

            DB::beginTransaction();

            // Jármű létrehozása
            $vehicle = Vehicle::create([
                'plate_number' => $validatedData['plate_number'],
                'vehicle_type_id' => $validatedData['vehicle_type_id'],
                'veh_id' => $validatedData['veh_id'],
                'registration_expiry' => $validatedData['registration_expiry'],
                'subdivision_id' => $validatedData['subdivision_id'],
                'rank_id' => $validatedData['rank_id'],
            ]);

            // Tulajdonosok hozzáadása
            $ownerIds = [];
            foreach ($this->selectedOwners as $ownerName) {
                $user = User::where('charactername', $ownerName)->first();
                if ($user) {
                    $ownerIds[] = $user->id;
                }
            }
            $vehicle->owners()->sync($ownerIds);

            DB::commit();

            $this->dispatch('vehicleCreated');
            $this->dispatch('show-notification', [
                'message' => 'Jármű sikeresen létrehozva!',
                'type' => 'success'
            ]);
            
            $this->show = false;
            $this->resetForm();
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Hiba történt a jármű létrehozása közben:', [
                'error' => $e->getMessage(),
                'data' => [
                    'plate_number' => $this->plate_number,
                    'vehicle_type_id' => $this->vehicle_type_id,
                    'veh_id' => $this->veh_id,
                    'selectedOwners' => $this->selectedOwners,
                ]
            ]);
            
            $this->dispatch('show-notification', [
                'message' => 'Hiba történt a jármű létrehozása közben: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function openModal()
    {
        $this->resetForm();
        $this->show = true;
    }

    public function closeModal()
    {
        $this->show = false;
        $this->resetForm();
    }

    public function render()
    {
        return view('livewire.create-vehicle-modal', [
            'vehicleTypes' => VehicleType::orderBy('name')->get(),
            'subdivisions' => Subdivision::orderBy('name')->get(),
            'ranks' => Rank::orderBy('name')->get()
        ]);
    }
}
