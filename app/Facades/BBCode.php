<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class BBCode extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'bbcode';
    }
}
