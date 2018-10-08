<?php

namespace App\Http\Controllers;

use App\Services\ZohoService;

class AuthController extends CoreController
{


    /**
     * Zoho Service Instance
     */
    protected $zoho;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ZohoService $zoho)
    {
        $this->zoho = $zoho;
    }

    /**
     * Index instance.
     *
     * @return void
     */
    public function index()
    {

        $this->zoho->list();

        echo 'HAHAHHAHAHAH';
    }

    /**
     * Callback instance.
     *
     * @return void
     */
    public function callback()
    {

        echo 'callback';
    }
    
}
