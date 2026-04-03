<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Consignation;

class ConsignationPolicy
{
    public function update(User $user, Consignation $consignation)
    {
        if ($user->role === 'admin') return true;
        if ($user->role === 'rbo') {
            $delegateIds = $user->zonesAsRbo->flatMap->delegates->pluck('id');
            return $delegateIds->contains($consignation->delegate_id);
        }
        if ($user->role === 'delegue') {
            return $user->id === $consignation->delegate_id;
        }
        return false;
    }
}