<?php

declare(strict_types=1);

use Classes\getFieldsFromTable as getFieldsFromTable;
use Classes\PutFieldToTable as PutFieldToTable;
use Classes\Parser as Parser;

spl_autoload_register(
    function ($class) {
        include str_ireplace('\\', '/', $class . '.php');
    }
);


$tiresAssocArray = (new getFieldsFromTable('tires'))->allFields();

$parseTires = (new Parser($tiresAssocArray))->getParserResult();

(new PutFieldToTable('characteristics'))->putFields($parseTires['authorized_data']);

echo "<h1>Нераспознанные позиции:</h1>";
foreach ($parseTires['error_data'] as $id => $name) {
        echo 'позиция с id: ' . $id . " ---> " . $name . "<br>";
}


//$parseTires['error_data']
//$parseTires['authorized_data']

//print_r($parseTires['authorized_data']);