<?php

namespace App\Observers;

use App\Models\Presentation;

class PresentationObserver
{
    /**
     * Handle the Presentation "created" event.
     */
    public function created(Presentation $presentation): void
    {
        //
    }

    /**
     * Handle the Presentation "updated" event.
     */
    public function updated(Presentation $presentation): void
    {
        //
    }

    /**
     * Handle the Presentation "deleted" event.
     */
    public function deleted(Presentation $presentation): void
    {
        //
    }

    /**
     * Handle the Presentation "restored" event.
     */
    public function restored(Presentation $presentation): void
    {
        //
    }

    /**
     * Handle the Presentation "force deleted" event.
     */
    public function forceDeleted(Presentation $presentation): void
    {
        //
    }
}
