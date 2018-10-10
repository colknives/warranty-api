<?php

namespace App\Http\Controllers;

use App\Services\WarrantyService;
use Illuminate\Http\Request;
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
        "serial_number" => "required",
        "purchase_date" => "required",
        "product_type" => "required",
        "product_applied" => "required",
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
     * @return void
     */
    public function save(Request $request)
    {
        $this->validate($request, static::CREATE_RULES);

        $productDetailList = [];

        $data[] = [
            'Claim No' => rand(10000000, 99999999),
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
            'Serial Number' => $request->get('serial_number'),
            'Purchase Date' => Carbon::parse($request->get('purchase_date'))->format('d/m/Y'),
            'Product Type' => $request->get('product_type'),
            'Product Applied' => implode(',', $request->get('product_applied')),
            'Email Opt Out' => $request->get('subscribe'),
            'Dealer Name' => $request->get('dealer_name'),
            'Dealer Address' => $request->get('dealer_location')
        ];

        $create = $this->warranty->save($data);

        return response()->json([
            "message" => $create->message,
            "data" => $data
        ]);
    }
}
