<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\Rank;
use App\Models\Subdivision;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AddUser extends Component
{
    public $charactername;
    public $character_id;
    public $rank_id;
    public $subdivision_id;
    public $played_minutes = 0;
    public $phone_number;
    public $badge_number;
    public $recommended_by;

    // Generált adatok tárolása
    public $generated_username;
    public $generated_password;
    public $showCredentials = false;

    protected $rules = [
        'charactername' => 'required|string|max:255',
        'character_id' => 'required|integer',
        'rank_id' => 'required|exists:ranks,id',
        'subdivision_id' => 'required|exists:subdivisions,id',
        'played_minutes' => 'required|integer|min:0',
        'phone_number' => 'required|string|max:255',
        'badge_number' => 'required|string|max:255|unique:users,badge_number',
        'recommended_by' => 'required|string|max:255'
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);

        if ($propertyName === 'badge_number') {
            $this->checkBadgeNumber();
        }
    }

    public function checkBadgeNumber()
    {
        if ($this->badge_number) {
            $exists = User::where('badge_number', $this->badge_number)->exists();
            if ($exists) {
                session()->flash('badge_exists', 'Ez a jelvényszám már használatban van!');
            }
        }
    }

    public function store()
    {
        try {
            $this->validate();

            // Felhasználónév és jelszó generálása
            $this->generated_username = Str::lower(Str::replace(' ', '', $this->charactername));
            $this->generated_password = Str::random(10);

            // Felhasználó létrehozása
            $user = User::create([
                'charactername' => $this->charactername,
                'character_id' => $this->character_id,
                'rank_id' => $this->rank_id,
                'subdivision_id' => $this->subdivision_id,
                'played_minutes' => $this->played_minutes,
                'phone_number' => $this->phone_number,
                'badge_number' => $this->badge_number,
                'recommended_by' => $this->recommended_by,
                'username' => $this->generated_username,
                'password' => bcrypt($this->generated_password),
                'status' => 'active',
                'isAdmin' => 0,
                'canGiveAdmin' => 0,
                'is_superadmin' => 0,
                'is_officer' => 0
            ]);

            // Alosztály hozzárendelése
            $user->subdivisions()->attach($this->subdivision_id);

            // Sikeres üzenet és hitelesítő adatok mutatása
            session()->flash('successful-creation', 'A felhasználó sikeresen létrehozva!');
            $this->showCredentials = true;

            // Mezők törlése
            $this->reset(['charactername', 'character_id', 'rank_id', 'subdivision_id', 
                         'played_minutes', 'phone_number', 'badge_number', 'recommended_by']);

        } catch (\Exception $e) {
            session()->flash('error', 'Hiba történt a regisztráció során: ' . $e->getMessage());
            \Log::error('Felhasználó létrehozási hiba: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.add-user', [
            'ranks' => Rank::all(),
            'subdivisions' => Subdivision::all(),
        ]);
    }
}
