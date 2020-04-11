<?php

declare(strict_types=1);

namespace Types;

class Brand
{

    private $value;

    private $options = array(
        "Nokian",
        "BFGoodrich",
        "Pirelli",
        "Toyo",
        "Continental",
        "Hankook",
        "Mitas"
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

}