<?php

namespace app\Model;

class Product
{
    public string $name;
    public string $imageURL;

    public function __construct(string $name, string $imageURL)
    {
        $this->name = $name;
        $this->imageURL = $imageURL;
    }
}