<?php

namespace App\Http\Livewire\Home;

use App\FeaturedPiece;
use Livewire\Component;

class TrackedFeaturedPieceButton extends Component
{
    public FeaturedPiece $featuredPiece;
    public string $class;

    public function trackClick()
    {
        $this->featuredPiece->increment('click_count');
        return redirect()->to($this->featuredPiece->url);
    }

    public function render()
    {
        return view('livewire.home.tracked-featured-piece-button');
    }
}
