<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;

class UserMedal extends Component
{
    public $user;
    public $showModal = false;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function updateMedal($medal)
    {
        if (auth()->user()->isAdmin || auth()->user()->is_superadmin) {
            $this->user->update(['medal' => $medal]);
            $this->emit('medalUpdated');
        }
    }

    public function render()
    {
        return view('livewire.user-medal');
    }
}
