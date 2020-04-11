<?php

declare(strict_types=1);

namespace Types;

class TireBox
{

    private $value;

    private $brands = array(
        "ТТ",
        "TL",
        "TL/TT"
    );

    public function __construct(string $value)
    {
        if (!in_array($value, $this->brands)) {
            $this->value = null;
        } else {
            $this->value = $value;
        }
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

}