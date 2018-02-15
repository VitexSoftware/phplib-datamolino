<?php

namespace Datamolino;

require_once '../vendor/autoload.php';
require_once './config.php';

$agendor = new Agenda();

$result = $agendor->createNew([
    "name" => "DataMolino s. r. o.",
    "address" => [
        "street" => "Zochova",
        "building_no" => "6 - 8",
        "city" => "Bratislava",
        "postal_code" => "811 03",
        "country" => "sk"
    ],
    "company_id" => "47327961",
    "company_tax_id" => "2023832976",
    "company_vat_id" => null,
    "home_currency" => "EUR",
    "email_alias" => "datamolino123",
    "email_whitelist" => "test@yahoo.com, invoices@gmail.com"
    ]
);

print_r($result);

$agendas = $agendor->getListing();
print_r($agendas);
