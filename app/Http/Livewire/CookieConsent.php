<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Cookie;

class CookieConsent extends Component
{
    public $show = true;

    public function mount()
    {
        $this->show = !Cookie::has('cookie_consent');
    }

    public function accept()
    {
        Cookie::queue('cookie_consent', 'accepted', 60 * 24 * 365);
        $this->show = false;
        $this->dispatch('show-notification', [
            'type' => 'success',
            'message' => 'Cookie beállítások elfogadva!'
        ]);
    }

    public function decline()
    {
        Cookie::queue('cookie_consent', 'declined', 60 * 24 * 365);
        $this->show = false;
        $this->dispatch('show-notification', [
            'type' => 'info',
            'message' => 'Cookie beállítások elutasítva!'
        ]);
    }

    public function render()
    {
        return view('livewire.cookie-consent');
    }
}
