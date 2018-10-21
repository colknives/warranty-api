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
    ],
    "vehicle_info" => [
        "list" => [
            "200" => "Vehicle Info successfully fetched",
            "404" => "Vehicle Info unsuccessfully fetched"
        ]
    ]
];