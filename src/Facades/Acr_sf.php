<?php

namespace Acr\sf\Facades;

use Illuminate\Support\Facades\Facade;

class Acr_sf extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'AcrSf';
    }

}