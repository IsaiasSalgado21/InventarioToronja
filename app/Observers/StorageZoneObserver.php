<?php

namespace App\Observers;

use App\Models\StorageZone;

class StorageZoneObserver
{
    /**
     * Handle the StorageZone "created" event.
     */
    public function created(StorageZone $storageZone): void
    {
        //
    }

    /**
     * Handle the StorageZone "updated" event.
     */
    public function updated(StorageZone $storageZone): void
    {
        //
    }

    /**
     * Handle the StorageZone "deleted" event.
     */
    public function deleted(StorageZone $storageZone): void
    {
        //
    }

    /**
     * Handle the StorageZone "restored" event.
     */
    public function restored(StorageZone $storageZone): void
    {
        //
    }

    /**
     * Handle the StorageZone "force deleted" event.
     */
    public function forceDeleted(StorageZone $storageZone): void
    {
        //
    }
    public function saving(StorageZone $storageZone): void
    {
        $storageZone->capacity_m2 = ($storageZone->dimension_x ?? 0) * ($storageZone->dimension_y ?? 0);
    }
}
