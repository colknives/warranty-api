<?php

namespace App\Services;

class WarrantyService extends ZohoService
{

    const MODULE_NAME = 'warranty';

    const MODULE_CODE = 'CustomModule2';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct(static::MODULE_NAME, static::MODULE_CODE);
    }
}
