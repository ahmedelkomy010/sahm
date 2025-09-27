<?php

namespace App\Observers;

use App\Models\WorkOrderItem;

class WorkOrderItemObserver
{
    /**
     * Handle the WorkOrderItem "created" event.
     */
    public function created(WorkOrderItem $workOrderItem): void
    {
        //
    }

    /**
     * Handle the WorkOrderItem "updated" event.
     */
    public function updated(WorkOrderItem $workOrderItem): void
    {
        //
    }

    /**
     * Handle the WorkOrderItem "deleted" event.
     */
    public function deleted(WorkOrderItem $workOrderItem): void
    {
        //
    }

    /**
     * Handle the WorkOrderItem "restored" event.
     */
    public function restored(WorkOrderItem $workOrderItem): void
    {
        //
    }

    /**
     * Handle the WorkOrderItem "force deleted" event.
     */
    public function forceDeleted(WorkOrderItem $workOrderItem): void
    {
        //
    }
}
