<?php

namespace Datamolino;

require_once '../vendor/autoload.php';

require_once './config.php';


$documentor = new Document();



$result = $documentor->getDocuments($agendaId, $pageNo);

print_r($result);

