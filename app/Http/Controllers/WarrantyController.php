<?php

namespace App\Http\Controllers;

use App\Services\WarrantyService;
use Illuminate\Http\Request;

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
        "product_details" => "required",
        "dealer_name" => "required",
        "dealer_location" => "required",
        "subscribe" => "required",
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
        "product_details",
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
     * @return void
     */
    public function save(Request $request)
    {
        $this->validate($request, static::CREATE_RULES);

        $productDetailList = [];

        foreach ($request->get('product_details') as $key => $productDetail) {

            $productApplied = [];

            if( isset($productDetail['dura_fabric']) ){
                $productApplied[] = $productDetail['dura_fabric'];
            }

            if( isset($productDetail['dura_leather']) ){
                $productApplied[] = $productDetail['dura_leather'];
            }

            if( isset($productDetail['dura_paint']) ){
                $productApplied[] = $productDetail['dura_paint'];
            }

            $productDetailList[] = [
                '@type' => 'Product Registered',
                'Serial Number' => $productDetail['serial_number'],
                'Product Number' => '234234',
                'Product Applied' => implode(',', $productApplied)        
            ];
        }

        $data[] = [
            'Warranty Claim Number' => rand(10000000, 99999999),
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
            'Email Opt Out' => $request->get('subscribe'),
            'Product Registered' => $productDetailList
        ];

        $create = $this->warranty->save($data);

        return response()->json([
            "message" => $create->message,
            "data" => $data
        ]);
    }
}
