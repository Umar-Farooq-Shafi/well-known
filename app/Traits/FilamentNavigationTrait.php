<?php

namespace App\Traits;

trait FilamentNavigationTrait
{
    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
