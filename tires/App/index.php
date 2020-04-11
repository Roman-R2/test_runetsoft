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

echo "<h1>Распознано ".count($parseTires['authorized_data'])." позиции.</h1>";

(new PutFieldToTable('characteristics'))->putFields($parseTires['authorized_data']);

echo "<h1>Распознанные позиции добавлены в базу данных.</h1>";

echo "<h1>Нераспознанные позиции, требующие ручной корректировки:</h1>";
foreach ($parseTires['error_data'] as $id => $name) {
        echo 'позиция с id: ' . $id . " ---> " . $name . "<br>";
}
