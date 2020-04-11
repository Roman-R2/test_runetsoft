<?php

declare(strict_types=1);

namespace Classes;


class PutFieldToTable
{
    private $tableName;

    public function __construct(string $tableName)
    {
        $this->tableName = $tableName;
    }

    public function putFields(array $data)
    {


        $queryString = 'INSERT INTO 
                            ' . $this->tableName . ' 
                            (
                                brand,
                                model,
                                width,
                                height,
                                construction,
                                diameter,
                                load_index,
                                speed_index ,
                                abbreviations,
                                run_flat_tire,
                                tire_box,
                                season,
                                tires_id
                            ) 
                            VALUES 
                            (
                                :brand,
                                :model,
                                :width,
                                :height,
                                :construction,
                                :diameter,
                                :load_index,
                                :speed_index,
                                :abbreviation,
                                :runFlatTire,
                                :tireBox,
                                :season,
                                :tires_id
                                )';
        try {
            foreach ($data as $id => $characteristics)
            {
            $preparedArray = array();
                $preparedArray[] =  $characteristics['brand'];
                $preparedArray[] =  $characteristics['model'];
                $preparedArray[] =  $characteristics['width'];
                $preparedArray[] =  $characteristics['height'];
                $preparedArray[] =  $characteristics['construction'];
                $preparedArray[] =  $characteristics['diameter'];
                $preparedArray[] =  $characteristics['load_index'];
                $preparedArray[] =  $characteristics['speed_index'];
                $preparedArray[] =  $characteristics['abbreviation'];
                $preparedArray[] =  $characteristics['runFlatTire'];
                $preparedArray[] =  $characteristics['tireBox'];
                $preparedArray[] =  $characteristics['season'];
                $preparedArray[] =  $id;

            (new Connect())->insertQuery($queryString, $preparedArray);
                unset($preparedArray);
            }


        } catch (\Exception $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
        }
    }


}