<?php

namespace App\Support;

use Illuminate\Support\Facades\Auth;

class YearLock
{
    public static function check($model)
    {
        // Admin bypass
        if (Auth::user()?->role === 'admin') {
            return;
        }

        // No year relation
        if (!isset($model->anneeScolaire)) {
            return;
        }

        // Locked year
        if ($model->anneeScolaire->isLocked()) {

            abort(403, 'Cette année scolaire est clôturée.');
        }
    }
}