<?php

namespace App\Observers;

use App\Models\Dial;

class DialObserver
{
    public function creating(Dial $dial)
    {

    }

    /**
     * Handle the Dial "created" event.
     *
     * @param \App\Models\Dial $dial
     * @return void
     */
    public function created(Dial $dial)
    {
        if (!file_exists($dial->images->img_source)) {
            $dial->images->img_source = null;
            $dial->save();
        }
    }

    /**
     * Handle the Dial "updated" event.
     *
     * @param \App\Models\Dial $dial
     * @return void
     */
    public function updated(Dial $dial)
    {
        //
    }

    /**
     * Handle the Dial "deleted" event.
     *
     * @param \App\Models\Dial $dial
     * @return void
     */
    public function deleted(Dial $dial)
    {
        //
    }

    /**
     * Handle the Dial "restored" event.
     *
     * @param \App\Models\Dial $dial
     * @return void
     */
    public function restored(Dial $dial)
    {
        //
    }

    /**
     * Handle the Dial "force deleted" event.
     *
     * @param \App\Models\Dial $dial
     * @return void
     */
    public function forceDeleted(Dial $dial)
    {
        //
    }
}
