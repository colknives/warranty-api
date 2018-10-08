<?php

namespace App\Http\Controllers;

use App\Services\WarrantyService;

class CoreController extends Controller
{
    /**
     * Zoho Service Instance
     */
    protected $warranty;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ZohoService $warranty)
    {   
        $this->warranty = $warranty;
    }

    /**
     * Save warranty instance.
     *
     * @return void
     */
    public function save()
    {
        $create = $this->warranty->save();

        return response()->json([
            "message" => $create->message,
            static::OBJECTNAME => $create->model,
        ]);
    }
}
