<?php

declare(strict_types=1);

use Classes\getFieldsFromTable as getFieldsFromTable;
use Classes\Parser as Parser;

spl_autoload_register(
    function ($class) {
        include str_ireplace('\\', '/', $class . '.php');
    }
);


$tiresAssocArray = (new getFieldsFromTable('tires'))->allFields();

$parseTires = new Parser($tiresAssocArray);


//$parseTires['error_data']
//$parseTires['authorized_data']

//print_r($rez);