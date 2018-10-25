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

        // $this->client = new ZohoCRMClient($this->moduleCode, '846eb480ccfe45b319c8d5671813626f', 'com', 500);

        //adrian's account
        $this->client = new ZohoCRMClient($this->moduleCode, '2fc3d043812f0370d50aac5570e2201d', 'com', 500);

        
    }

    /**
     * List instance.
     *
     * @return void
     */
    public function list()
    {

        // $fields = $this->client->getFields()
        //   ->request();

        // $fields = $this->client->searchRecords()
        //                         ->withEmptyFields()
        //                         ->where('Claim No', '13242865')
        //                         ->request();

        // print_r($fields);
        // die();


        

        $configuration=[
            "client_id"=>"1000.1PZUFPR3W9U329484SB8MKVFRS7KKP",
            "client_secret"=>"7df9140d0b835a067aaaf84e9501d5c55efef67e67",
            "redirect_uri"=>"http://localhost:8001",
            "currentUserEmail"=>"adrianmunt@tfgroup.co.nz",
            "applicationLogFilePath"=> storage_path("logs/ZCRMClientLibrary.log"), 
            "token_persistence_path"=> storage_path("logs/zcrm_oauthtokens.txt")
        ];

        ZCRMRestClient::initialize($configuration);


        print_r($configuration);
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
