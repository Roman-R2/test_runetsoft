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
        return (new Connect())->query('SELECT * FROM '.$this->tableName);
    }


}