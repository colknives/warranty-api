<?php

namespace App\Http\Controllers;

use App\Repositories\WarrantyRepository;
use App\Services\WarrantyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Jobs\AttachmentJob;
use App\Mail\WelcomeMail;
use Carbon\Carbon;
use Validator;

class WarrantyController extends Controller
{
    const OBJECTNAME = 'warranty';

    const CREATE_RULES = [
        "firstname" => "required",
        "lastname" => "required",
        "contact_number" => "required",
        "email" => "required|email",
        "address" => "required",
        "city" => "required",
        "postcode" => "required",
        "dealer_name" => "required"
    ];

    const CREATE_FIELDS = [
        "firstname",
        "lastname",
        "contact_number",
        "email",
        "address",
        "suburb",
        "city",
        "postcode",
        "serial_number",
        "purchase_date",
        "product_type",
        "product_applied",
        "dealer_name",
        "dealer_location"
    ];

    const SAFE_SERIALS = [
        "PCSUTESTER",
        "PCLTESTER",
        "PCOTESTER",
        "DSLTESTER",
        "PCFTESTER",
        "SGTESTER",
        "LGTESTER",
        "DSFTESTER",
        "DSPTESTER"
    ];



    /**
     * Warranty Repository Instance
     *
     * @var
     */
    protected $warrantyRepository;


    /**
     * Zoho Service Instance
     */
    protected $warranty;

    /**
     * WarrantyController constructor.
     * @param WarrantyService $warranty
     * @param WarrantyRepository $warrantyRepository
     *
     * @return void
     */
    public function __construct(
        WarrantyService $warranty,
        WarrantyRepository $warrantyRepository)
    {   
        $this->warranty = $warranty;
        $this->warrantyRepository = $warrantyRepository;
    }

    /**
     * Save warranty instance.
     *
     * @return void
     */
    public function list()
    {

        $list = $this->warranty->list();

        return response()->json([
            "message" => $create->message,
            static::OBJECTNAME => $list,
        ]);
    }

    /**
     * Get Product Type via Serial Number instance.
     *
     * @return void
     */
    public function getTypeViaSerial(Request $request)
    {

        $serialNumber = $request->get('serial_number');

        $type = $this->identifyType(strtoupper($serialNumber));

        if( $type ){
            return response()->json([
                "message" => __("messages.warranty.type.200"),
                "type" => $type
            ]);
        }

        return response()->json([
            "message" => __("messages.warranty.type.404"),
            "type" => null
        ],404);

    }

    /**
     * Get Product Type via Serial Number instance.
     *
     * @return void
     */
    public function identifyType($serialNumber)
    {

        $type = "";
        $typeCode = substr($serialNumber, 0, 4);

        if( $typeCode == 'PCSU' ){
            return 'Premium Care Synthetic Upholstery';
        }

        $typeCode = substr($serialNumber, 0, 3);

        if( $typeCode == 'PCL' ){
            return 'Premium Care Leather';
        }
        elseif( $typeCode == 'PCO' ){
            return 'Premium Care Outdoor';
        }
        elseif( $typeCode == 'PCF' ){
            return 'Premium Care Fabric';
        }
        elseif( $typeCode == 'DSL' ){
            return 'DURA SEAL Leather Protection';
        }
        elseif( $typeCode == 'DSP' ){
            return 'DURA SEAL Paint Protection';
        }
        elseif( $typeCode == 'DSF' ){
            return 'DURA SEAL Fabric Protection';
        }

        $typeCode = substr($serialNumber, 0, 2);

        if( $typeCode == 'SG' ){
            return 'Soil Guard';
        }
        elseif( $typeCode == 'LG' ){
            return 'Leather Guard';
        }

        return false;
    }

    /**
     * Save warranty instance.
     *
     * @return response
     */
    public function save(Request $request)
    {

        $this->validate($request, static::CREATE_RULES);

        $exist = [];
        $invalid = [];

        if( $this->serialNumberExist($request->get('serial_number')) ){
            if( !in_array($request->get('serial_number'), static::SAFE_SERIALS) ){
                $exist[] = $request->get('serial_number');
            }
        }

        if( !$this->serialNumberFormat($request->get('product_type'), $request->get('serial_number')) ){
            $invalid[] = $request->get('serial_number');
        }

        if( count($exist) == 0 && count($invalid) == 0 ){

            $data = [];
            $localData = [];
            $productType = [];

            $claimNo = rand(100001, 999999);

            $data[] = [
                'Registration Number' => $claimNo,
                'Status' => 'Pending',
                'Name' => $request->get('firstname').' '.$request->get('lastname'),
                'First Name' => $request->get('firstname'),
                'Last Name' => $request->get('lastname'),
                'Email' => $request->get('email'),
                'Secondary Email' => '',
                'Address Line 1' => $request->get('address'),
                'Address Line 2' => '',
                'Contact Number' => $request->get('contact_number'),
                'City' => $request->get('city'),
                'Suburb/Town/Province' => $request->get('suburb'),
                'Zip Code' => $request->get('postcode'),
                'Country' => $request->get('country'),
                'Invoice Number' => $request->get('invoice_number'),
                'Serial Number' => $request->get('serial_number'),
                'Product Type' => $request->get('product_type'),
                'Product Applied' => ( $request->get('product_applied') == 'Yes' )? 'Fabric Protection' : '',
                'Purchase Date' => Carbon::now()->format('d/m/Y'),
                'Date Registered' => Carbon::now()->format('d/m/Y'),
                'Vehicle Registration' => $request->get('vehicle_registration'),
                'Make' => $request->get('vehicle_make'),
                'Model' => $request->get('vehicle_model'),
                'Dealer Name' => $request->get('dealer_name'),
                'Dealer Address' => $request->get('dealer_location')
            ];

            $create = $this->warranty->save($data);

            if( $create->status == 200 ){

                    $productType = $request->get('product_type'); 

                    $localData = [
                        'claim_no' => $claimNo,
                        'firstname' => $request->get('firstname'),
                        'lastname' => $request->get('lastname'),
                        'email' => $request->get('email'),
                        'address' => $request->get('address'),
                        'contact_number' => $request->get('contact_number'),
                        'city' => $request->get('city'),
                        'suburb' => $request->get('suburb'),
                        'postcode' => $request->get('postcode'),
                        'country' => $request->get('country'),
                        'invoice_number' => $request->get('invoice_number'),
                        'vehicle_registration' => $request->get('vehicle_registration'),
                        'vehicle_make' => $request->get('vehicle_make'),
                        'vehicle_model' => $request->get('vehicle_model'),
                        'serial_number' => $request->get('serial_number'),
                        'product_type' => $request->get('product_type'),
                        'product_applied' => ( $request->get('product_applied') == 'Yes' )? 'Fabric Protection' : '',
                        'dealer_name' => $request->get('dealer_name')
                    ];

                    $saveLocal = $this->warrantyRepository->create($localData);

            }


            Mail::to($request->get('email'))
                        ->send(new WelcomeMail( $request->get('firstname').' '.$request->get('lastname'), $claimNo, $productType, $request->get('serial_number') ));

            return response()->json([
                "message" => __("messages.warranty.create.200"),
                "data" => $data
            ]);
        }

        if( count($invalid) > 0  ){

            $data = [
                'serial' => implode(', ', $invalid)
            ];

            return response()->json([
                "message" => __("messages.warranty.serial.invalid", $data),
                "data" => null
            ], 404); 
        }

        $data = [
            'serial' => implode(', ', $exist)
        ];

        return response()->json([
            "message" => __("messages.warranty.serial.exist", $data),
            "data" => null
        ], 404);     
    }

    /**
     * Serial number exist instance.
     *
     * @return boolean
     */
    public function serialNumberExist($serialNumber)
    {
        $data = [
            'Serial Number' => $serialNumber
        ];

        $search = $this->warranty->search($data);

        return ( $search->status == 200 )? true : false;
    }

    /**
     * Email exist instance.
     *
     * @return boolean
     */
    public function emailExist($email)
    {
        $data = [
            'Email' => $email
        ];

        $search = $this->warranty->search($data);

        return ( $search->status == 200 )? false : false;
    }

    /**
     * Search exist instance.
     *
     * @return boolean
     */
    public function searchExist($type, $value)
    {

        $searchType = false;

        switch ($type) {
            case 'email':
                $searchType = 'Email';
                break;
            
            case 'serial_number':
                $searchType = 'Serial Number';
                break;
        }

        if( !$searchType ){
            return false;
        }

        $data[$searchType] = $value;

        $search = $this->warranty->search($data);

        return ( $search->status == 200 )? array_column($search->model, 'data') : false;
    }

    /**
     * Serial number check format instance.
     *
     * @return boolean
     */
    public function serialNumberFormat($type, $serialNumber)
    {
        $valid = true;

        if( in_array($type, ['Soil Guard', 'Leather Guard']) ){
            $typeCode = substr($serialNumber, 0, 2);
        }
        elseif( in_array($type, ['DURA SEAL Leather Protection', 'DURA SEAL Paint Protection', 'DURA SEAL Fabric Protection', 'Premium Care Leather', 'Premium Care Fabric', 'Premium Care Outdoor']) ){
            $typeCode = substr($serialNumber, 0, 3);
        }
        else{
            $typeCode = substr($serialNumber, 0, 4);
        }

        return ( ( $type == 'Soil Guard' && $typeCode == 'SG' ) || ( $type == 'Leather Guard' && $typeCode == 'LG' ) || ( $type == 'Premium Care Leather' && $typeCode == 'PCL' ) || ( $type == 'Premium Care Synthetic Upholstery' && $typeCode == 'PCSU' ) || ( $type == 'Premium Care Outdoor' && $typeCode == 'PCO' ) || ( $type == 'Premium Care Fabric' && $typeCode == 'PCF' ) || ( $type == 'DURA SEAL Paint Protection' && $typeCode == 'DSP' ) || ( $type == 'DURA SEAL Leather Protection' && $typeCode == 'DSL' ) || ( $type == 'DURA SEAL Fabric Protection' && $typeCode == 'DSF' ) );
    }

    /**
     * Check range instance.
     *
     * @return boolean
     */
    public function checkRangeFormat($serialNumber)
    {
        $valid = true;
        $type = $this->identifyType($serialNumber);

        if( in_array($type, ['Soil Guard', 'Leather Guard']) ){
            $typeCode = substr($serialNumber, 0, 2);
        }
        elseif( in_array($type, ['DURA SEAL Leather Protection', 'DURA SEAL Paint Protection', 'DURA SEAL Fabric Protection', 'Premium Care Leather', 'Premium Care Fabric', 'Premium Care Outdoor']) ){
            $typeCode = substr($serialNumber, 0, 3);
        }
        else{
            $typeCode = substr($serialNumber, 0, 4);
        }

        $serialCode = str_replace($typeCode, '', $serialNumber);
        $serialCode = ( float ) $serialCode;

        if( in_array($type, ['DURA SEAL Leather Protection', 'DURA SEAL Paint Protection', 'DURA SEAL Fabric Protection']) ){
            return ( ( $type == 'DURA SEAL Leather Protection' && ( $serialCode > env('DURA_SEAL_LEATHER_START') && $serialCode < env('DURA_SEAL_LEATHER_END')  ) ) || ( $type == 'DURA SEAL Fabric Protection' && ( $serialCode > env('DURA_SEAL_FABRIC_START') && $serialCode < env('DURA_SEAL_FABRIC_END')  ) ) || ( $type == 'DURA SEAL Paint Protection' && ( $serialCode > env('DURA_SEAL_PAINT_START') && $serialCode < env('DURA_SEAL_PAINT_END')  ) ) );
        }
        else{
            return true;
        } 
    }

    /**checkRangeFormat
     * Check serial email.
     *
     * @return boolean
     */
    public function checkSerialEmail(Request $request)
    {
        
        //Check if sent data is email or serial
        $validator = Validator::make($request->all(), [
            'serial_email' => 'email'
        ]);

        $type = false;
        $serial_type = false;

        if( $validator->fails() ){

            $identify = $this->identifyType($request->get('serial_email'));

            if( $identify ){
                $type = 'serial_number';
                $serial_type = $identify;
            }

        }
        else{
            $type = 'email';
        }

        if( !$type ){
            return response()->json([
                "message" => __("messages.warranty.serial_email.404")
            ], 404);   
        }

        // $search = $this->warrantyRepository->searchWarranty($type, $request->get('serial_email'));
        $searchZoho = $this->searchExist($type, $request->get('serial_email'));

        if( ( !in_array($request->get('serial_email'), static::SAFE_SERIALS) ) && ( !$this->checkRangeFormat($request->get('serial_email')) ) ){
            return response()->json([
                "message" => __("messages.warranty.serial_email.serial_number.range_invalid"),
                "type" => $type,
                "serial_type" => $serial_type,
                "count" => 0,
                "data" => null,
                "test_account" => 0,
                "invalid_range" => 1
            ], 200); 
        }

        // if( $count == 0 || $searchZoho == false ){
        if( $searchZoho == false ){

            $testData = 0;

            if( in_array($request->get('serial_email'), static::SAFE_SERIALS) ){
                $testData = 1;
            }

            return response()->json([
                "message" => __("messages.warranty.serial_email.".$type.".404"),
                "type" => $type,
                "serial_type" => $serial_type,
                "count" => 0,
                "data" => null,
                "test_account" => $testData,
                "invalid_range" => 0
            ], 200); 
        }

        if( in_array($request->get('serial_email'), static::SAFE_SERIALS) ){
            return response()->json([
                "message" => __("messages.warranty.serial_email.".$type.".404"),
                "type" => $type,
                "serial_type" => $serial_type,
                "count" => 0,
                "data" => null,
                "test_account" => 1,
                "invalid_range" => 0
            ], 200); 
        }

        $testData = 0;

        foreach( $searchZoho as $key => $zohoInfo ){
            if( in_array($zohoInfo['Serial Number'], static::SAFE_SERIALS) ){
                $testData = 1;
            }
        }

        return response()->json([
            "message" => __("messages.warranty.serial_email.".$type.".200"),
            "type" => $type,
            "serial_type" => $serial_type,
            "count" => count($searchZoho),
            "data" => $searchZoho,
            "test_account" => $testData,
            "invalid_range" => 0
        ], 200);   
    }
}
