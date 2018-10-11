<?php

namespace App\Services;

require_once realpath(dirname(__FILE__).'/../../vendor/zohocrm/php-sdk/src/com/zoho/crm/library/setup/restclient/ZCRMRestClient.php');
require_once realpath(dirname(__FILE__).'/../../vendor/zohocrm/php-sdk/src/com/zoho/oauth/client/ZohoOAuth.php');

use CristianPontes\ZohoCRMClient\ZohoCRMClient;
use Illuminate\Support\Facades\Config;
use ZCRMRestClient;
use ZohoOAuth;

class ZohoService
{

    /**
     * Client Instance
     * @var String
     */
    protected $client;

    /**
     * Module Name
     * @var String
     */
    protected $moduleName;

    /**
     * Module Code
     * @var String
     */
    protected $moduleCode;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct($moduleName, $moduleCode)
    {
        $this->moduleName = $moduleName;
        $this->moduleCode = $moduleCode;

        $this->client = new ZohoCRMClient($this->moduleCode, '846eb480ccfe45b319c8d5671813626f');
    }

    /**
     * List instance.
     *
     * @return void
     */
    public function list()
    {

        $fields = $this->client->getFields()
          ->request();

        print_r($fields);
        die();
    }

    /**
     * Save warranty instance.
     *
     * @return void
     */
    public function save($data)
    {
        $records = $this->client->insertRecords()
        ->setRecords($data)
        ->onDuplicateError()
        ->triggerWorkflow()
        ->request();

        $success = true;

        foreach ($records as $record) {
            if( !$record->isInserted() ){
                $success = false;
            }
        }

        if( $success ){
            return (object)[
                "status" => 200,
                "message" => __("messages.{$this->moduleName}.create.200"),
                "model" => $records
            ];
        }

        return (object)[
            "status" => 404,
            "message" => __("messages.{$this->moduleName}.create.404"),
            "model" => null
        ];
    }
}
