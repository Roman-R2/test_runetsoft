<?php

declare(strict_types=1);

namespace Classes;

use Types\Brand;
use Types\RunFlat;
use Types\Season;
use Types\TireBox;

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

    private $finalArray = array();


    public function __construct(array $assocArray)
    {

        if (count($assocArray) == 0) {
            new \Exception('Parsing data not contained.');
        }

        foreach ($assocArray as $tire) {
            if (!$setOfCharacters = $this->getSetOfCharacteristics($tire->name)) {
                $this->errorMark($tire);
                continue;
            }

            if (!$this->brand = $this->getBrand($setOfCharacters['name'])) {
                $this->errorMark($tire);
                continue;
            }

            if (!$this->model = $this->getModel($setOfCharacters['name'])) {
                $this->errorMark($tire);
                continue;
            }

            if (!$this->width = $this->getWidth($setOfCharacters['width'])) {
                $this->errorMark($tire);
                continue;
            }

            if (!$this->height = $this->getHeight($setOfCharacters['height'])) {
                $this->errorMark($tire);
                continue;
            }

            if (!$this->construction = $this->getConstruction($setOfCharacters['construction'])) {
                $this->errorMark($tire);
                continue;
            }

            if (!$this->diameter = $this->getDiameter($setOfCharacters['diameter'])) {
                $this->errorMark($tire);
                continue;
            }

            if (!$this->loadIndex = $this->getLoadIndex($setOfCharacters['load_index'])) {
                $this->errorMark($tire);
                continue;
            }

            if (!$this->speedIndex = $this->getSpeedIndex($setOfCharacters['speed_index'])) {
                $this->errorMark($tire);
                continue;
            }

            if (!$this->season = $this->getSeason($setOfCharacters['rest_of_line'])) {
                $this->errorMark($tire);
                continue;
            }

            if (!$this->runFlatTire = $this->getRunFlat($setOfCharacters['rest_of_line'])) {
                $this->runFlatTire = null;
            }

            if (!$this->tireBox = $this->getTireBox($setOfCharacters['rest_of_line'])) {
                $this->tireBox = null;
            }

            if (!$this->abbreviation = $this->getAbbreviation($setOfCharacters['rest_of_line'])) {
                echo $this->abbreviation.'<br>';
                //$this->tireBox = null;
            }


            //echo $setOfCharacters['rest_of_line'] . '<br>';


            $this->authorizedData[$tire->id] = array(
                'brand' => $this->brand,
                'model' => $this->model,
                'width' => $this->width,
                'height' => $this->height,
                'construction' => $this->construction,
                'diameter' => $this->diameter,
                'load_index' => $this->loadIndex,
                'speed_index' => $this->speedIndex,
                'abbreviation' => $this->abbreviation,
                'runFlatTire' => $this->runFlatTire,
                'tireBox' => $this->tireBox,
                'season' => $this->season,
            );
        }


    }

    public function getParserResult() : array
    {
        $this->finalArray['authorized_data'] = $this->authorizedData;
        $this->finalArray['error_data'] = $this->errorData;

        return $this->finalArray;
    }

    public function getSetOfCharacteristics(string $unbrokenString): ?array
    {
        $result = preg_match(
            '/(?P<name>^.+)(?P<width>[0-9]{3,4})\/+(?P<height>[0-9]{2})(?P<construction>R)+(?P<diameter>[0-9]{2,3})\s{1}(?P<load_index>[0-9]{2,3})(?P<speed_index>[A-Z]{1})(?P<rest_of_line>.+)/',
            $unbrokenString,
            $found
        );
        if ($result != 1) {
            return null;
        }
        return $found;
    }

    public function getSeason(string $unbrokenString): ?string
    {
        foreach ((new Season(""))->getOptions() as $item) {
            if (stripos($unbrokenString, $item) !== false) {
                return $item;
            }
        }
        return null;
    }

    public function getRunFlat(string $unbrokenString): ?string
    {
        foreach ((new RunFlat(""))->getOptions() as $item) {
            if (stripos($unbrokenString, $item) !== false) {
                return $item;
            }
        }
        return null;
    }

    public function getTireBox(string $unbrokenString): ?string
    {
        foreach ((new TireBox(""))->getOptions() as $item) {
            if (stripos($unbrokenString, $item) !== false) {
                return $item;
            }
        }
        return null;
    }

    public function getAbbreviation(string $unbrokenString): ?string
    {
        $withoutFindString = str_replace($this->tireBox, "", $unbrokenString);
        $withoutFindString = str_replace($this->runFlatTire, "", $withoutFindString);
        $withoutFindString = str_replace($this->season, "", $withoutFindString);
//        $withoutFindString = str_replace(" ", "", $withoutFindString);

        /*if (mb_strlen($withoutFindString,'UTF-8') == 0)
        {
            return null;
        }*/

        return $withoutFindString;
    }

    public function getBrand(string $unbrokenString): ?string
    {
        $delimited = explode(" ", $unbrokenString);
        return (new Brand($delimited[0]))->getValue();
    }

    public function getModel(string $unbrokenString): ?string
    {
        return substr(strstr($unbrokenString, " "), 1);
    }

    public function getWidth(string $unbrokenString): ?string
    {
        return $unbrokenString;
    }

    public function getHeight(string $unbrokenString): ?string
    {
        return $unbrokenString;
    }

    public function getConstruction(string $unbrokenString): ?string
    {
        return $unbrokenString;
    }

    public function getDiameter(string $unbrokenString): ?string
    {
        return $unbrokenString;
    }

    public function getLoadIndex(string $unbrokenString): ?string
    {
        return $unbrokenString;
    }

    public function getSpeedIndex(string $unbrokenString): ?string
    {
        return $unbrokenString;
    }

    public function errorMark($tire)
    {
        $this->errorData[$tire->id] = $tire->name;
    }

}