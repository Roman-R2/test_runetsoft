<?php
declare(strict_types=1);

namespace Classes;


class getFieldsFromTable
{
    private $tableName;

    public function __construct(string $tableName)
    {
        $this->tableName = $tableName;
    }

    public function allFields() {
        return (new Connect())->selectQuery('SELECT * FROM '.$this->tableName);
    }


}