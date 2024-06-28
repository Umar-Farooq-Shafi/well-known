<?php

declare(strict_types=1);

namespace App\Livewire;

use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Support\Facades\View;
use Livewire\Component;

class SidePanel extends Component
{


    public function render(): ViewContract
    {
        return View::make(
            view: 'livewire.side-panel',
        );
    }
}
