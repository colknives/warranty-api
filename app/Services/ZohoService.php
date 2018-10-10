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

        // $_SERVER["user_email_id"]="ronmar@weroar.co.nz";

        // $configuration = [
        //     "client_id" => "1000.4J470IB5FUD549210WL08RZM2W87DH",
        //     "client_secret" => "2e3a43c9388fd6634727426565022c248635db9f25",
        //     "redirect_uri" => "http://localhost:8001/auth/callback",
        //     "currentUserEmail" => "ronmar@weroar.co.nz",
        //     "applicationLogFilePath"=>"/var/www/tfgroup/api/zoho/ZCRMClientLibrary.log",
        //     "token_persistence_path"=>"/storage/token/zcrm_oauthtokens.txt"
        // ];

        // ZCRMRestClient::initialize($configuration);

        // $oAuthClient = ZohoOAuth::getClientInstance();
        // $grantToken = "1000.ea8f9d117ce9aa8930d41408a682f195.890abbca4919953d08bcf1facd03d439";
        // $oAuthTokens = $oAuthClient->generateAccessToken($grantToken);

        // try{
        //     $ins=ZCRMRestClient::getInstance();
        //     $apiResponse=$ins->getModule("Leads");//Module API Name
        //     $module=$apiResponse->getData();
        //     echo "ModuleName:".$module->getModuleName();
        //     echo "SingLabel:".$module->getSingularLabel();
        //     echo "PluLabel:".$module->getPluralLabel();
        //     echo "BusinesscardLimit:".$module->getBusinessCardFieldLimit();
        //     echo "ApiName:".$module->getAPIName();
        //     $fields=$module->getFields();


        //     foreach ($fields as $field)
        //     {
        //         echo $field->getApiName().", ";
        //         echo $field->getLength().", ";
        //         echo $field->IsVisible().", ";
        //         echo $field->getFieldLabel().", ";
        //         echo $field->getCreatedSource().", ";
        //         echo $field->isMandatory().", ";
        //         echo $field->getSequenceNumber().", ";
        //         echo $field->isReadOnly().", ";
        //         echo $field->getDataType().", ";
        //         echo $field->getId().", ";
        //         echo $field->isCustomField().", ";
        //         echo $field->isBusinessCardSupported().", ";
        //         echo $field->getDefaultValue().", ";
        //     }
        // }
        // catch (ZCRMException $e)
        // {
        //     echo $e->getCode();
        //     echo $e->getMessage();
        //     echo $e->getExceptionCode();
        // }


        // $client = new ZohoCRMClient($this->moduleCode, '846eb480ccfe45b319c8d5671813626f');
        // $records = $client->getRecords()
        //     ->selectColumns('First Name', 'Last Name', 'Email')
        //     ->sortBy('Last Name')->sortAsc()
        //     ->since(date_create('last week'))
        //     ->request();

        // print_r($records);
        // die();
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
