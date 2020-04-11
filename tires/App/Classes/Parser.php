<?php
declare(strict_types=1);

namespace Classes;

use Types\Brand;

class Parser
{
    private $brand;
    private $model;
    private $width;
    private $height;
    private $construction;
    private $diameter;
    private $loadIndex;
    private $speedIndex;
    private $abbreviation;
    private $runFlatTire;
    private $tireBox;
    private $season;

    private $authorizedData = array();
    private $errorData = array();

    private $assocArray;


    public function __construct(array $assocArray)
    {
        $this->assocArray = $assocArray;

        if (count($assocArray) == 0) {
            throw new \Exception('Parsing data not contained.');
        }

        foreach ($assocArray as $tire)
        {

            if (!$setOfCharacters = $this->getSetOfCharacteristics($tire->name))
            {
                $this->errorMark($tire);
                continue;
            }



            if (!$this->brand = $this->getBrand($setOfCharacters['name']))
            {
                $this->errorMark($tire);
                continue;
            }

            if (!$this->model = $this->getModel($setOfCharacters['name']))
            {
                $this->errorMark($tire);
                continue;
            }

            if (!$this->width = $this->getWidth($setOfCharacters['width']))
            {
                $this->errorMark($tire);
                continue;
            }

            if (!$this->height = $this->getHeight($setOfCharacters['height']))
            {
                $this->errorMark($tire);
                continue;
            }

            if (!$this->construction = $this->getConstruction($setOfCharacters['construction']))
            {
                $this->errorMark($tire);
                continue;
            }

            if (!$this->diameter = $this->getDiameter($setOfCharacters['diameter']))
            {
                $this->errorMark($tire);
                continue;
            }

            if (!$this->loadIndex = $this->getLoadIndex($setOfCharacters['load_index']))
            {
                $this->errorMark($tire);
                continue;
            }

            if (!$this->speedIndex = $this->getSpeedIndex($setOfCharacters['speed_index']))
            {
                $this->errorMark($tire);
                continue;
            }

            if (!$analyzeRestOfLine = $this->analyzeImplicitCharacteristics($setOfCharacters['rest_of_line']))
            {
                $this->errorMark($tire);
                continue;
            }


            echo $setOfCharacters['rest_of_line'].'<br>';

            $this->authorizedData[$tire->id] = array(
                'brand' => $this->brand,
                'model' => $this->model,
                'width' => $this->width,
                'height' => $this->height,
                'construction' => $this->construction,
                'diameter' => $this->diameter,
                'load_index' => $this->loadIndex,
                'speed_index' => $this->speedIndex,
            );



        }

        //print_r($this->authorizedData);
        echo '<br>';
        print_r($this->errorData);
    }

    public function getSetOfCharacteristics(string $unbrokenString)  : ?array
    {

        $result = preg_match('/(?P<name>^.+)(?P<width>[0-9]{3,4})\/+(?P<height>[0-9]{2})(?P<construction>R)+(?P<diameter>[0-9]{2,3})\s{1}(?P<load_index>[0-9]{2,3})(?P<speed_index>[A-Z]{1})(?P<rest_of_line>.+)/',$unbrokenString,$found);
        if ($result != 1) {
            return null;
        }
        return $found;
    }

    public function analyzeImplicitCharacteristics(string $unbrokenString)
    {

    }

    public function getBrand(string $unbrokenString) : ?string
    {
        $delimited = explode(" ", $unbrokenString);
        return (new Brand($delimited[0]))->getValue();
    }

    public function getModel(string $unbrokenString) : ?string
    {
        return substr(strstr($unbrokenString," "), 1);
    }

    public function getWidth(string $unbrokenString) : ?string
    {
        return $unbrokenString;
    }

    public function getHeight(string $unbrokenString) : ?string
    {
        return $unbrokenString;
    }

    public function getConstruction(string $unbrokenString) : ?string
    {
        return $unbrokenString;
    }

    public function getDiameter(string $unbrokenString) : ?string
    {
        return $unbrokenString;
    }

    public function getLoadIndex(string $unbrokenString) : ?string
    {
        return $unbrokenString;
    }

    public function getSpeedIndex(string $unbrokenString) : ?string
    {
        return $unbrokenString;
    }

    public function errorMark($tire)
    {
        $this->errorData[$tire->id] = $tire->name;
    }

}