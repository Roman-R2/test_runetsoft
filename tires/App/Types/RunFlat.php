<?php

declare(strict_types=1);

namespace Types;

class RunFlat
{

    private $value;

    private $options = array(
        "RunFlat",
        "Run Flat",
        "ROF",
        "ZP",
        "SSR",
        "ZPS",
        "HRS",
        "RFT"
    );

    public function __construct(string $value)
    {
        if (!in_array($value, $this->options)) {
            $this->value = null;
        } else {
            $this->value = $value;
        }
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function getOptions(){
        return $this->options;
    }
}