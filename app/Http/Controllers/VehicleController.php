<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Subdivision;
use App\Models\Rank;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::with(['owners', 'subdivision', 'rank'])->get();
        return view('database.jarmuvek', compact('vehicles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'plate_number' => ['required', 'string', 'unique:vehicles'],
            'type' => ['required', 'string'],
            'veh_id' => ['required', 'string', 'unique:vehicles'],
            'registration_expiry' => ['required', 'date'],
            'warnings' => ['nullable', 'array'],
            'notes' => ['nullable', 'string'],
            'subdivision_id' => ['nullable', 'exists:subdivisions,id'],
            'rank_id' => ['nullable', 'exists:ranks,id'],
            'owner_ids' => ['required', 'array', 'min:1', 'max:2'],
            'owner_ids.*' => ['exists:users,id'],
        ]);

        DB::transaction(function () use ($validated) {
            $vehicle = Vehicle::create($validated);
            $vehicle->owners()->attach($validated['owner_ids']);
        });

        return redirect()->route('vehicles.index')
            ->with('success', 'Jármű sikeresen hozzáadva!');
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'plate_number' => ['required', 'string', Rule::unique('vehicles')->ignore($vehicle)],
            'type' => ['required', 'string'],
            'veh_id' => ['required', 'string', Rule::unique('vehicles')->ignore($vehicle)],
            'registration_expiry' => ['required', 'date'],
            'warnings' => ['nullable', 'array'],
            'notes' => ['nullable', 'string'],
            'subdivision_id' => ['nullable', 'exists:subdivisions,id'],
            'rank_id' => ['nullable', 'exists:ranks,id'],
            'owner_ids' => ['required', 'array', 'min:1', 'max:2'],
            'owner_ids.*' => ['exists:users,id'],
        ]);

        DB::transaction(function () use ($vehicle, $validated) {
            $vehicle->update($validated);
            $vehicle->owners()->sync($validated['owner_ids']);
        });

        return redirect()->route('vehicles.index')
            ->with('success', 'Jármű sikeresen frissítve!');
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        return redirect()->route('vehicles.index')
            ->with('success', 'Jármű sikeresen törölve!');
    }
}
