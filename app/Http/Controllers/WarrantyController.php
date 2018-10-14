<?php

namespace App\Http\Controllers;

use App\Services\WarrantyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;
use Carbon\Carbon;

class WarrantyController extends Controller
{
    const OBJECTNAME = 'warranty';

    const CREATE_RULES = [
        "firstname" => "required",
        "lastname" => "required",
        "contact_number" => "required",
        "email" => "required|email",
        "address" => "required",
        "suburb" => "required",
        "city" => "required",
        "postcode" => "required",
        "dealer_name" => "required",
        "dealer_location" => "required"
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
        "dealer_location",
        "subscribe",
    ];



    /**
     * Zoho Service Instance
     */
    protected $warranty;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(WarrantyService $warranty)
    {   
        $this->warranty = $warranty;
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
     * Save warranty instance.
     *
     * @return response
     */
    public function save(Request $request)
    {

        $this->validate($request, static::CREATE_RULES);

        // die();

        // print_r('hehehe');
        // print_r($request->all());
        // die();

        $productDetails = $request->get('product_details');
        $issues = [];

        // foreach( $productDetails as $key => $productDetail ){

        //     if( $this->serialNumberExist($productDetail['serial_number']) ){
        //         $issues[] = $productDetail['serial_number'];
        //     }

        //     if( !$this->serialNumberFormat($productDetail['product_type'], $productDetail['serial_number'], $productDetail['product_applied']) ){
        //         $issues[] = $productDetail['serial_number'];
        //     }
        // }

        if( count($issues) == 0 ){

            $data = [];
            $details = [];

            $claimNo = rand(100001, 999999);

            foreach( $productDetails as $key => $productDetail ){

                $data[] = [
                    'Claim No' => $claimNo,
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
                    'Country' => 'New Zealand',
                    // 'Serial Number' => $productDetail['serial_number'],
                    'Serial Number' => rand(100001, 999999),
                    'Purchase Date' => Carbon::parse($productDetail['purchase_date'])->format('d/m/Y'),
                    'Product Type' => $productDetail['product_type'],
                    'Product Applied' => implode(',', $productDetail['product_applied']),
                    'Email Opt Out' => $request->get('subscribe'),
                    'Dealer Name' => $request->get('dealer_name'),
                    'Dealer Address' => $request->get('dealer_location')
                ];

                $create = $this->warranty->save($data);
                $details[] = $create->model;

            }

            if( count($details) > 0 ){

                foreach( $details as $key => $detail ){

                    $value = '';
                    $filename = '';

                    if( $productDetails[$key]['proof_purchase_type'] == 'image/jpeg' ){

                        $value = substr($productDetails[$key]['proof_purchase'], 22);

                        $filename = str_random('18') . Carbon::now()->timestamp . ".jpg";
                        Storage::disk('warranty_attachment')->put( $filename, base64_decode($value) );


                    }
                    elseif( $productDetails[$key]['proof_purchase_type'] == 'image/png' ){

                        $value = substr($productDetails[$key]['proof_purchase'], 21);

                        $filename = str_random('18') . Carbon::now()->timestamp . ".png";
                        Storage::disk('warranty_attachment')->put( $filename, base64_decode($value) );

                    }

            //         $file = Storage::disk('warranty_attachment')->path($filename);

            //         // print_r($detail->id.' '.$file);
            //         // die();

            //         $file = Storage::disk('warranty_attachment')->url($filename);
            //         $upload = $this->warranty->upload($detail->id, $file);

                }
            }

            // $upload = $this->warranty->upload('3548539000000330025', '/var/www/tfgroup/api/zoho/storage/attachment/fx1Vvr5a3AEBy6rQTe1539530002.png');

            // print_r($create);
            // die();

            Mail::to('colknives@gmail.com')
                        ->send(new WelcomeMail( $request->get('firstname').' '.$request->get('lastname'), $claimNo ));

            return response()->json([
                "message" => __("messages.warranty.create.200"),
                "data" => $data
            ]);
        }

        return response()->json([
            "message" => __("messages.warranty.serial.issues"),
            "data" => null
        ], 400);        
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
     * Serial number check format instance.
     *
     * @return boolean
     */
    public function serialNumberFormat($type, $serialNumber, $productApplied)
    {
        $valid = true;

        if( in_array($type, ['Soil Guard', 'Leather Guard', 'DURA SEAL Vehicle Protection']) ){
            $typeCode = substr($serialNumber, 0, 2);
            $yearCode = substr($serialNumber, 2, 2);
            $appliedCode = substr($serialNumber, -2);
        }
        elseif( in_array($type, ['Premium Care Leather', 'Premium Care Fabric', 'Premium Care Outdoor']) ){
            $typeCode = substr($serialNumber, 0, 3);
            $yearCode = substr($serialNumber, 3, 2);
            $appliedCode = substr($serialNumber, -2);
        }
        else{
            $typeCode = substr($serialNumber, 0, 4);
            $yearCode = substr($serialNumber, 4, 2);
            $appliedCode = substr($serialNumber, -2);
        }

        switch ($type) {
            case 'Soil Guard':
                if( ( $typeCode != 'SG' ) || ( $yearCode > date('y') ) ){
                    $valid = false;
                }

                if( $productApplied == 'Single' && $appliedCode != 'SL' ){
                    $valid = false;
                }

                if( $productApplied == 'Double' && $appliedCode != 'DB' ){
                    $valid = false;
                }

                if( $productApplied == 'Multi' && $appliedCode != 'MT' ){
                    $valid = false;
                }
                break;
            case 'Leather Guard':
                if( ( $typeCode != 'LG' ) || ( $yearCode > date('y') ) ){
                    $valid = false;
                }

                if( $productApplied == 'Single' && $appliedCode != 'SL' ){
                    $valid = false;
                }

                if( $productApplied == 'Double' && $appliedCode != 'DB' ){
                    $valid = false;
                }

                if( $productApplied == 'Multi' && $appliedCode != 'MT' ){
                    $valid = false;
                }
                break;
            case 'Premium Care Leather':
                if( ( $typeCode != 'PCL' ) || ( $yearCode > date('y') ) ){
                    $valid = false;
                }

                if( $productApplied == 'Mini' && $appliedCode != 'MN' ){
                    $valid = false;
                }

                if( $productApplied == 'Midi' && $appliedCode != 'MD' ){
                    $valid = false;
                }

                if( $productApplied == 'Maxi' && $appliedCode != 'MX' ){
                    $valid = false;
                }
                break;
            case 'Premium Care Fabric':
                if( ( $typeCode != 'PCF' ) || ( $yearCode > date('y') ) ){
                    $valid = false;
                }

                if( $productApplied == 'Mini' && $appliedCode != 'MN' ){
                    $valid = false;
                }

                if( $productApplied == 'Midi' && $appliedCode != 'MD' ){
                    $valid = false;
                }

                if( $productApplied == 'Maxi' && $appliedCode != 'MX' ){
                    $valid = false;
                }
                break;
            case 'Premium Care Synthetic':
                if( ( $typeCode != 'PCSU' ) || ( $yearCode > date('y') ) ){
                    $valid = false;
                }

                if( $productApplied == 'Mini' && $appliedCode != 'MN' ){
                    $valid = false;
                }

                if( $productApplied == 'Midi' && $appliedCode != 'MD' ){
                    $valid = false;
                }

                if( $productApplied == 'Maxi' && $appliedCode != 'MX' ){
                    $valid = false;
                }
                break;
            case 'Premium Care Outdoor':
                if( ( $typeCode != 'PCO' ) || ( $yearCode > date('y') ) ){
                    $valid = false;
                }

                if( $productApplied == 'Small' && $appliedCode != 'SM' ){
                    $valid = false;
                }

                if( $productApplied == 'Medium' && $appliedCode != 'MD' ){
                    $valid = false;
                }
                break;
            case 'DURA SEAL Vehicle Protection':
                if( ( $typeCode != 'DS' ) || ( $yearCode > date('y') ) || ( $appliedCode != 'PK' ) ){
                    $valid = false;
                }
                break;
            case 'DURA SEAL Leather Protection':
                if( ( $typeCode != 'DSL' ) || ( $yearCode > date('y') ) || ( $appliedCode != 'PO' ) ){
                    $valid = false;
                }
                break;
            default:
                $valid = false;
                break;
        }

        return $valid;
    }
}
