<?php

namespace App\Http\Livewire;

use Livewire\Component;

class VideoPlayer extends Component
{

    public $player;

    public function render()
    {
        return view('livewire.video-player', ['player' => $this->player]);
    }
}
