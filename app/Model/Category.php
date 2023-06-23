<?php

namespace app\Model;

class Category
{
    /**
     * @var string
     */
    public string $name;

    /**
     * @var array
     */
    public array $products;

    /**
     * @param string $name
     * @param array $products
     */
    public function __construct(string $name, array $products = [])
    {
        $this->name = $name;
        $this->products = $products;
    }

    /**
     * Get summary of category
     *
     * @return array
     */
    public function getSummaryData(): array
    {
        return ["name" => $this->name, "productCount" => count($this->products)];
    }
}