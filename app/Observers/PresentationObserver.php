<?php

namespace App\Observers;

use App\Models\Presentation;
use App\Models\PriceHistory;
use Illuminate\Support\Facades\Auth;

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
        // Comprueba si el campo 'unit_price' (Precio de Venta) fue el que cambió
        if ($presentation->wasChanged('unit_price')) {

            // Crea un registro en el historial
            PriceHistory::create([
                'presentation_id' => $presentation->id,
                'user_id'         => Auth::id(), // Guarda QUIÉN hizo el cambio
                'old_price'       => $presentation->getOriginal('unit_price'), // Precio antiguo
                'new_price'       => $presentation->unit_price, // Precio nuevo
                'changed_at'      => now(),
            ]);
        }
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
