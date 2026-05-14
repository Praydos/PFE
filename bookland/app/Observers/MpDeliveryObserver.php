<?php

namespace App\Observers;

use App\Models\MpDelivery;
use App\Services\MpDeliveryCommercialActionCreator;

class MpDeliveryObserver
{
    public function __construct(
        private MpDeliveryCommercialActionCreator $commercialActionCreator
    ) {}

    public function created(MpDelivery $mpDelivery): void
    {
        $this->commercialActionCreator->create($mpDelivery);
    }
}
