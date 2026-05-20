<?php

namespace App\Observers;

use App\Models\AdministrationOrder;

class AdministrationOrderObserver
{
    /**
     * Handle the AdministrationOrder "created" event.
     */
    public function created(AdministrationOrder $order): void
    {
        // Deactivate any previous active orders for the same employee
        AdministrationOrder::where('employee_id', $order->employee_id)
            ->where('id', '<>', $order->id)
            ->where('active', true)
            ->update(['active' => false]);
    }
}
