<?php
return [
    "warranty" => [
        "create" => [
            "200" => "Warranty successfully created",
            "404" => "Warranty unsuccessfully created"
        ],
        "search" => [
            "200" => "Warranty successfully fetched",
            "404" => "Warranty not found"
        ],
        "serial" => [
        	"exist" => "Serial number(s) :serial already exist",
            "invalid" => "Invalid serial number(s) :serial"
        ],
        "type" => [
            "200" => "Serial successfully idetified",
            "404" => "Serial unsuccessfully idetified"
        ],
        "serial_email" => [
            "serial_number" => [
                "200" => "Warranty successfully fetched",
                "404" => "No warranty under serial number is found"
            ],
            "email" => [
                "200" => "Warranty successfully fetched",
                "404" => "No warranty under email is found"
            ],
            "404" => "The provided info is invalid"
        ],
    ],
    "vehicle_info" => [
        "list" => [
            "200" => "Vehicle Info successfully fetched",
            "404" => "Vehicle Info unsuccessfully fetched"
        ]
    ],
    "dealer_info" => [
        "list" => [
            "200" => "Dealer Info successfully fetched",
            "404" => "Dealer Info unsuccessfully fetched"
        ]
    ]
];