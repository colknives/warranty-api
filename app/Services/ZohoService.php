<?php

namespace App\Services;

require_once realpath(dirname(__FILE__).'/../../vendor/zohocrm/php-sdk/src/com/zoho/crm/library/setup/restclient/ZCRMRestClient.php');
require_once realpath(dirname(__FILE__).'/../../vendor/zohocrm/php-sdk/src/com/zoho/oauth/client/ZohoOAuth.php');

use CristianPontes\ZohoCRMClient\ZohoCRMClient;
use CristianPontes\ZohoCRMClient\Exception;
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

        $this->client = new ZohoCRMClient($this->moduleCode, '846eb480ccfe45b319c8d5671813626f', 'com', 500);
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

        // $fields = $this->client->searchRecords()
        //                         ->withEmptyFields()
        //                         ->where('Claim No', '13242865')
        //                         ->request();

        print_r($fields);
        die();
    }

    /**
     * Save instance.
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

        print_r($records);

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

    /**
     * Upload instance.
     *
     * @return void
     */
    public function upload($id, $file)
    {
        $record = $this->client->uploadFile()
          ->id($id)
          ->uploadFromPath($file)
          ->request();
    }

    /**
     * Search instance.
     *
     * @return void
     */
    public function search($search = [])
    {

        try {
         
            $records = $this->client
                            ->searchRecords()
                            ->withEmptyFields();

            foreach( $search as $field => $value ){
                $records = $records->where($field, $value);
            }

            $records = $records->request();

            return (object)[
                "status" => 200,
                "message" => __("messages.{$this->moduleName}.search.200"),
                "model" => $records
            ];

        } catch (Exception\NoDataException  $e) {
           
           return (object)[
                "status" => 404,
                "message" => __("messages.{$this->moduleName}.search.404"),
                "model" => null
            ]; 
           
        }

        
    }
}
